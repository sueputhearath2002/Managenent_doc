<?php

namespace App\Http\Controllers;

use App\Models\StudentsData;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Expr\FuncCall;

class AuthController extends Controller
{

    //======================================== new
    public function register()
    {
        $title = "Register";
        return View("register_path", compact('title'));
    }

    public function login()
    {
        $title = "Login";
        $userEmail = "";
        return View('login_path', compact('title', "userEmail"));
    }


    // ====================================================


    public function registerPostAdmin(Request $request)
    {

        $user = new User();

        $user->name = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return back()->with('success', 'Register successfully');
    }


    public function loginPostAdmin(Request $request)
    {

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {

            return redirect()->route('layout')->with('success', 'Login Success');
        }

        return back()->with('error', 'Error Email or Password');
    }

    public function logoutAdmin(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login_section');
    }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:students,name',
    //         'email' => 'required|email|unique:students,email',
    //         'password' => 'required|min:6',
    //     ]);

    //     $student = Student::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     $token = $student->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'Student registered successfully',
    //         'student' => $student,
    //         'token' => $token
    //     ], 201);
    // }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $student = Student::where('email', $request->email)->first();

    //     if (!$student || !Hash::check($request->password, $student->password)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['The provided credentials are incorrect.'],
    //         ]);
    //     }

    //     $token = $student->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'Login successful',
    //         'token' => $token
    //     ]);
    // }

    // public function logout(Request $request)
    // {
    //     $request->user()->tokens()->delete();

    //     return response()->json([
    //         'message' => 'Logged out successfully'
    //     ]);
    // }
}
