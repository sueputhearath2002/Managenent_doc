<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register-student', [StudentController::class, 'register_student']);
Route::post('/login-student', [StudentController::class, 'login_student']);
Route::get('/', function () {
    return "Hi from api";
});


// Protected routes (Require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [StudentController::class, 'logout']);
    Route::group(['prefix' => 'student', 'as' => 'student.'], function () {
        Route::post('/upload-images', [StudentController::class, 'uploadImages'])->name('upload.images');
        Route::post('/get-students', [StudentController::class, 'getStudent'])->name('get.student');
        Route::post('/check-attendance', [StudentController::class, 'checkAttendance'])->name('check.attendance');
        Route::post('/upload-model', [FileController::class, 'uploadModel']);
    });
});
