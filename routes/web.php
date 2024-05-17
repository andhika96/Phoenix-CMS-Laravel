<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ------------------------------------------------------------------------

Route::get('/dashboard', [App\Http\Controllers\Web\Dashboard\DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard/testcreaterole', [App\Http\Controllers\Web\Dashboard\DashboardController::class, 'testCreateRole'])->middleware('auth');
Route::get('/dashboard/listdata', [App\Http\Controllers\Web\Dashboard\DashboardController::class, 'listdata']);

// ------------------------------------------------------------------------

/* Authenticaton Login, Logout & Signup */

// Redirect empty page index auth to auth/login
Route::redirect('/auth', '/auth/login');

// Redirect url default laravel to new custom url
Route::redirect('/login', '/auth/login')->name('login');

Route::get('auth/login', [App\Http\Controllers\Web\Auth\AuthController::class, 'login'])->name('auth.login');
Route::post('auth/login', [App\Http\Controllers\Web\Auth\AuthController::class, 'authenticate'])->name('auth.login.authenticate');
Route::get('auth/logout', [App\Http\Controllers\Web\Auth\AuthController::class, 'logout'])->name('auth.logout');

// Route::get('auth/asd', [App\Http\Controllers\Web\Auth\AuthController::class, 'asd'])->name('auth.login');

// ------------------------------------------------------------------------

Route::get('/test', [App\Http\Controllers\Web\Test\TestController::class, 'test']);


Route::name('app.')
    ->prefix('app')
	->namespace('App\Http\Controllers\Web')
	->group(function () {
		Route::name('user.')
            ->prefix('user')
			->controller(\User\UserController::class)
			->group(function () {
				Route::get('/', 'index')->name('index');
			});
        });