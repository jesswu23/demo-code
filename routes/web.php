<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
	Route::get('logout', [CustomAuthController::class, 'logOut']);

	Route::get('dashboard', [UserController::class, 'dashboard']);
	Route::get('leave', [UserController::class, 'leave']);
	Route::get('upload', [UserController::class, 'upload']);
	Route::post('upload_file', [UploadController::class, 'uploadFile']);

});

Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom_login', [CustomAuthController::class, 'customLogin']);

Route::get('registration', [CustomAuthController::class, 'registration']);
Route::post('custom_registration', [CustomAuthController::class, 'customRegistration']);

Route::get('/', [CustomAuthController::class, 'index']);