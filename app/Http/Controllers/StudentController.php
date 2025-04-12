<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class StudentController extends BaseAPIController
{
    // public function register_student(Request $request)
    // {
    //     // Manually validate the request
    //     try {
    //         $request->validate([
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|string|email|max:255',
    //             'password' => 'required|string|min:6|confirmed',
    //             'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    //         ]);


    //         $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('student', 'public') : null;
    //         $role_student = Role::where('name', 'student')->first();
    //         // dd($role_student);
    //         $student = Student::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             'photo' => $photoPath
    //         ]);

    //         if ($role_student) {
    //             $student->assignRole($role_student);
    //         }


    //         return $this->sendSuccess("Registration successful!",$student);

    //     } catch (Exception $ex) {
    //         return $this->sendError($ex->getMessage());
    //     }
    // }

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
                'token'     => $token,
                'student'   =>  $student->select("name", "email", "password", "photo", "id")->where("email", $request->email)->first(),
                'role' => $student->roles->pluck('name'),
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
            foreach ($request->file('images') as $upload_file) {
                $path   = saveImage($upload_file, 'students');
                $user->images()->create(['path' => $path]);
            }
            return $this->sendSuccess("Uploaded");
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }
}
