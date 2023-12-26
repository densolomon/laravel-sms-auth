<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('auth.register');
    Route::view('/login', 'auth.login')->name('auth.login');
    Route::view('/verify', 'auth.verify')->name('auth.verify');
    Route::post('/register/create', [RegisterController::class, 'create'])->name('auth.register.create');
    Route::post('/register/verify-code', [VerifyController::class, 'verifyCode'])->name('auth.verify.code');
    Route::post('/login/user', [LoginController::class, 'loginUser'])->name('auth.login.user');
});

Route::middleware('auth')->group(function () {
    Route::view('/', 'front.index')->name('front.index');
    Route::get('/auth/logout', [LogoutController::class, 'logout'])->name('auth.logout');
});
