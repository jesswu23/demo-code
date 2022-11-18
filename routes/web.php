<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\LeaveController;

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
	Route::controller(CustomAuthController::class)->group(function () {
		Route::get('logout', 'logOut');
	});

	Route::prefix('upload')->controller(UploadController::class)->group(function () {
		Route::get('/', 'index');
		Route::post('upload_file', 'uploadFile');
	});

	Route::prefix('import')->controller(ImportController::class)->group(function () {
		Route::get('/', 'index');
		Route::post('import_file', 'importFile');
	});

	Route::controller(UserController::class)->group(function () {
		Route::get('dashboard', 'dashboard');
	});

	Route::prefix('leave')->controller(LeaveController::class)->group(function () {
		Route::get('/', 'index');
		Route::get('events', 'events');
		Route::get('create', 'create');
		Route::post('store', 'store');
		Route::get('edit/{id}', 'edit')->where('id', '[0-9]+');
		Route::put('update/{id}', 'update')->where('id', '[0-9]+');
	});

	Route::prefix('calendar')->controller(CalendarController::class)->group(function () {
		Route::get('edit/{date}', 'edit');
		Route::put('update/{date}', 'update');
	});
});

Route::controller(CustomAuthController::class)->group(function () {
	Route::get('login', 'index')->name('login');
	Route::post('custom_login', 'customLogin');

	Route::get('registration', 'registration');
	Route::post('custom_registration', 'customRegistration');

	Route::get('/', 'index');
});