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
        Route::post('/update-role', [StudentController::class, 'updateRole']);
    });
});





//ssh root@103.253.146.193
//su deployer
//ROOT PASSWORD //RATH@042002R

/*
 * 1. sudo systemctl status nginx // check nginx status
 * 2. sudo systemctl start nginx // check nginx start
 * 1. sudo systemctl status php{version}-fpm // check php status
 * 2. sudo systemctl start php{version}-fpm // check php start
 * 1. sudo systemctl status mysql // check mysql status
 * 2. sudo systemctl start mysql // check mysql start
 * sudo nano /etc/nginx/sites-available/{file_name} // make new server block
 * sudo ln -s /etc/nginx/sites-available/face-detection /etc/nginx/{file_name} // need to enable nginx server block
 * sudo git clone https://github.com/sueputhearath2002/Managenent_doc.git // clone project into server
 * sudo chown -R deployer:www-data /var/www/html/{project_name}/ // change owner
 * sudo chmod -R 775 /var/www/html/{project_name}/storage/ /var/www/html/{project_name}/bootstrap/cache/ // change file mode
 */
