<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')
	->name('api.v1.')
	->namespace('App\Http\Controllers\Api\v1')
	->group(function () {
		Route::name('user.')
			->prefix('user')
			->controller(UserController::class)
			->group(function () {
				Route::get('/', 'index')->name('index');
			});
        });
