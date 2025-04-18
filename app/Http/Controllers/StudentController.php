<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use function PHPUnit\Framework\isEmpty;
use function Sodium\add;

class StudentController extends BaseAPIController
{
    public function register_student(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Store photo if provided
            $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('student', 'public') : null;

            // Find student role
            $role_student = Role::where('name', 'student')->first();

            // Create student
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'photo' => $photoPath
            ]);

            // Assign role if exists
            if ($role_student) {
                $student->assignRole($role_student);
            }

            // Load the assigned role and permissions
            // $student->load('roles.permissions');

            return response()->json([
                'success' => true,
                'message' => "Registration successful!",
                'data' => [
                    'user' => $student->only(['name', 'email', 'password', 'photo', 'id']),
                    // ->pluck("name","email","password","photo","id"),
                    'roles' => $student->roles->pluck('name'),
                    'permissions' => $student->getAllPermissions()->pluck('name') // Get permission names
                ]
            ], 201);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }


    // Login student
    public function login_student(Request $request)
    {

        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $student = Student::where('email', $request->email)->first();

            if (!$student || !Hash::check($request->password, $student->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.']
                ]);
            }

            // Generate token
            $token = $student->createToken('student-token')->plainTextToken;
            return $this->sendSuccess("Login successful!", [
                'token' => $token,
                'student' => $student->select("name", "email", "password", "photo", "id")->where("email", $request->email)->first(),
                'role' => $student->roles->pluck('name'),
                'permissions' => $student->getAllPermissions()->pluck('name')
            ]);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(), 400);
        }
    }


    // Logout student
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function uploadImages(Request $request)
    {
        try {
            $request->validate([
                'images' => 'required|array',
                'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = Student::find(Auth()->user()->id);
            $paths = [];
            foreach ($request->file('images') as $upload_file) {
                $path = saveImage($upload_file, 'students');
                $user->images()->create(['path' => $path]);
                $paths[] = $path;
            }
            $uploadedImages = $user->images()->whereIn('path', $paths)->get();
            return $this->sendSuccess("Uploaded", $uploadedImages);
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    public function getStudent()
    {
        $students = Student::all();
        if (empty($students)) {
            return $this->sendError("No students found");
        }
        return $this->sendSuccess("Uploaded", $students);
    }

    public function checkAttendance(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'students' => 'required|array',
            ]);

            $date = Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();

            $studentNames = collect($request->input('students'))->map(fn($n) => strtolower(trim($n)));

            // Get all students from DB
            $allStudents = Student::all();

            foreach ($allStudents as $student) {
                $isPresent = $studentNames->contains(strtolower($student->name));

                // Check if already has attendance today
                $attendance = Attendance::where('studentId', $student->id)
                    ->where('attendanceDate', $date)
                    ->first();

                if ($attendance) {
                    // Update status
                    $attendance->update([
                        'status' => $isPresent ? 'Present' : 'Absent',
                        'attendanceTime' => $time,
                        'reason' => '',
                    ]);
                } else {
                    // Create new record
                    Attendance::create([
                        'studentId' => $student->id,
                        'status' => $isPresent ? 'Present' : 'Absent',
                        'attendanceDate' => $date,
                        'attendanceTime' => $time,
                        'reason' => '',
                    ]);
                }
            }

            return $this->sendSuccess("Attendance recorded successfully.");
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage());
        }


    }

    public function updateRole(Request $request)
    {
        try{
            $request->validate([
                "id"=>'required',
                'role' => 'required|exists:roles,name', // role must exist in roles table
            ]);

            $student = Student::find($request->id);
            $role = Role::where('name', $request->role)->first();

            // Sync role (removes old roles and attaches new one)
            $student->roles()->sync([$role->id]);

            return response()->json(['message' => 'Role updated successfully']);
        }catch (Exception $ex){
            return $this->sendError($ex->getMessage());
        }
    }



}
