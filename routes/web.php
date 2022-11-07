<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;

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
Route::get('dashboard', [CustomAuthController::class, 'dashboard']);

Route::get('login', [CustomAuthController::class, 'index']);
Route::post('custom-login', [CustomAuthController::class, 'customLogin']);

Route::get('registration', [CustomAuthController::class, 'registration']);
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration']);
Route::get('logout', [CustomAuthController::class, 'logOut']);

Route::get('/', [CustomAuthController::class, 'index']);