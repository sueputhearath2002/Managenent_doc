<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentController extends BaseAPIController
{
    public function register_student(Request $request)
    {
        // Manually validate the request
        try {
            $request->validate([
                'name' => 'required|string|max:255', // Ensure correct table name (plural or singular)
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('student', 'public') : null;

            Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'photo' => $photoPath
            ]);

            return $this->sendSuccess("Registration successful!");
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
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
                'student'   => $student
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
