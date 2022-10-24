<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'client_credentials'], function () {
    Route::resource('students', StudentController::class);
    Route::get('/export', [StudentController::class, 'export']);
    Route::post('/import', [StudentController::class, 'import']);
});

// Route::middleware('auth:api')->group(function () {
//     Route::resource('/students', StudentController::class);
//     // Route::get('/students/email/{}', [StudentController::class, 'searchByEmail']);
//     // Route::get('/students/name/{}', [StudentController::class, 'searchByName']);

// });
