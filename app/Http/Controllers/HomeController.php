<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Student;
use App\Models\Students;
use App\Models\StudentsImages;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function layout()
    {
        $data["userEmail"]=Auth::user()->email;
        $data["students"] = Student::all();
        $userEmail = Auth::user()->email;
        return View("layout", $data);
    }

    public function homePage(){
        $data["student_id"] = "";
        return View("home.homepage",$data);
    }

    public function listStudent(Request $request){
        $studentId = $request->input('student_id');
        $data["userEmail"]=Auth::user()->email;
        $data["imageStudent"] = Image::where('user_id', $studentId)->get();
        $student = Student::find($studentId);
        $data["name"] = $student->name;
        // dd( $image);
        // // $data["students"] = Student::all();

        // $data["imageStudent"] = Student::where('user_id', $studentId)->get();
        // dd($data["imageStudent"]);

        return View("home.list_image",$data);
    }


}
