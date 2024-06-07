<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::prefix('v1')
	->name('api.v1.')
	->namespace('App\Http\Controllers\Api\v1')
	->group(function () {
		Route::name('auth.')
			->middleware(['guest'])
			->prefix('auth')
			->controller(AuthController::class)
			->group(function () {
				Route::get('/', function () {
					return "tst";
				});
				Route::post('/login', 'login')->name('login');
				Route::match(['get', 'post'], '/logout', 'logout')->name('logout')
					->middleware(['auth:api'])
					->withoutMiddleware(['guest']);
				Route::match(['get', 'post'], '/check', 'validateToken')->name('check')
					->withoutMiddleware(['guest']);
				Route::post('/register', 'register')->name('register');
			});
		Route::name('user.')
			->prefix('user')
			->controller(UserController::class)
			->group(function () {
				Route::get('/', 'index')->name('index');
				Route::get('/{idOrSlug}', 'show')->name('show');
				Route::post('/store', 'store')->name('store');
				Route::match(['post', 'put'], '/update', 'update')->name('update');
				Route::delete('/destroy', 'destroy')->name('destroy');
			});
    });
