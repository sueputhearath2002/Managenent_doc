<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('login_path');
});
Route::get("/register_section",[AuthController::class, 'register']);
Route::get("/login_section",[AuthController::class, 'login'])->name("login_section");

Route::post('/register', [AuthController::class, 'registerPostAdmin'])->name('registerPostAdmin');
Route::post('/login', [AuthController::class, 'loginPostAdmin'])->name('loginPostAdmin');
Route::post('/logout', [AuthController::class, 'logoutAdmin'])->name('logoutAdmin');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, "layout"])->name('layout');
    Route::get('/home-page', [HomeController::class, "homePage"])->name('homePage');
    Route::get('/list-images', [HomeController::class, "listStudent"])->name('listImages');
    Route::get('/download-folder/{id}', function ($id) {
        // Find the student by ID
        $student = Student::find($id);

        // Check if the student exists
        if (!$student) {
            return abort(404, "Student not found.");
        }

        // Retrieve the images associated with this student
        $images = $student->images;

        // Prepare a folder path where the images will be temporarily stored
        $folderPath = storage_path('app/public/students/' . $id);
        // $zipFileName = 'student_' . $id . '-images.zip';
        $zipFileName = $student->name.'.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        // Create the folder if it doesn't exist
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true); // Create folder recursively
        }

        // Check if images exist
        if ($images->isEmpty()) {
            return abort(404, "No images found for this student.");
        }

        foreach ($images as $image) {
            $imagePath = storage_path('app/public/' . $image->path);
            $destinationPath = $folderPath . '/' . basename($image->path);

            copy($imagePath, $destinationPath);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $zip->addFile($file->getRealPath(), substr($file->getRealPath(), strlen($folderPath) + 1));
                }
            }
            $zip->close();
        }

        // Return the zip file as a download
        return response()->download($zipPath)->deleteFileAfterSend(true);
    });
    // Route::get('/download-folder/{id}', function ($id) {

    //     dd($id);
    //     $folderPath = Storage::path('public/students'); // Updated
    //     $zipFileName = 'my-folder.zip';
    //     $zipPath = storage_path('app/public/' . $zipFileName);

    //     if (!file_exists($folderPath)) {
    //         return abort(404, "Folder not found.");
    //     }

    //     $zip = new ZipArchive;
    //     if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    //         $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));
    //         foreach ($files as $file) {
    //             if (!$file->isDir()) {
    //                 $zip->addFile($file->getRealPath(), substr($file->getRealPath(), strlen($folderPath) + 1));
    //             }
    //         }
    //         $zip->close();
    //     }

    //     return response()->download($zipPath)->deleteFileAfterSend(true);
    // });


});

