<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ------------------------------------------------------------------------

Route::get('/dashboard', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->middleware('auth');

// ------------------------------------------------------------------------

/* Authenticaton Login, Logout & Signup */

// Redirect empty page index auth to auth/login
Route::redirect('/auth', '/auth/login');

// Redirect url default laravel to new custom url
Route::redirect('/login', '/auth/login')->name('login');

Route::get('auth/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('auth.login');
Route::post('auth/login', [App\Http\Controllers\Auth\AuthController::class, 'login_validate'])->name('auth.login.validate');
Route::get('auth/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('auth.logout');

Route::get('auth/asd', [App\Http\Controllers\Auth\AuthController::class, 'asd'])->name('auth.login');

// ------------------------------------------------------------------------

Route::get('/test', [App\Http\Controllers\Test\TestController::class, 'test']);
