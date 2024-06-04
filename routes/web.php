<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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

Route::controller(App\Http\Controllers\Web\Auth\AuthController::class)->group(function()
{
	Route::get('auth/login', 'login')->name('auth.login');
	Route::post('auth/login', 'authenticate')->name('auth.login.authenticate');
	Route::get('auth/logout', 'logout')->name('auth.logout');
	Route::get('auth/logout_sso', 'logoutSSO')->name('auth.logout.sso');
});

// Route::get('auth/login', [App\Http\Controllers\Web\Auth\AuthController::class, 'login'])->name('auth.login');
// Route::post('auth/login', [App\Http\Controllers\Web\Auth\AuthController::class, 'authenticate'])->name('auth.login.authenticate');
// Route::get('auth/logout', [App\Http\Controllers\Web\Auth\AuthController::class, 'logout'])->name('auth.logout');

// Route::get('auth/asd', [App\Http\Controllers\Web\Auth\AuthController::class, 'asd'])->name('auth.login');

// ------------------------------------------------------------------------

// Route::get('/awesome_admin', [App\Http\Controllers\Web\Awesome_Admin\Awesome_AdminController::class, 'index'])->middleware('auth');

// ------------------------------------------------------------------------

Route::get('/dashboard/test', [App\Http\Controllers\Web\Dashboard\DashboardController::class, 'test']);

Route::name('admin.')
	->prefix('awesome_admin')
	->namespace('App\Http\Controllers\Web\Awesome_Admin')
	->group(function() 
	{
		Route::controller(\Awesome_Admin_Controller::class)->group(function()
		{
			Route::get('/', 'index')->name('awesome_admin')->middleware('auth');
		});

		Route::controller(\Awesome_Admin_ConfigController::class)->group(function()
		{
			Route::get('/config', 'index')->name('awesome_admin.config')->middleware('auth');
		});

		Route::controller(\Awesome_Admin_RoleController::class)->group(function()
		{
			// List and Add
			Route::get('/role', 'index')->name('awesome_admin.role')->middleware('auth');
			Route::post('/role', 'store')->name('awesome_admin.role.store')->middleware('auth');

			// Edit
			Route::post('/role/edit/{idOrSlug}', 'update')->name('awesome_admin.role.update')->middleware('auth');

			// Delete
			Route::post('/role/delete/{idOrSlug}', 'destroy')->name('awesome_admin.role.delete')->middleware('auth');

			// Get List
			Route::get('/role/listdata', 'listData')->name('awesome_admin.role.list')->middleware('auth');

			// Get List Permission
			Route::get('/role/listdatapermission', 'listDataPermission')->name('awesome_admin.role.listpermission')->middleware('auth');

			// Get Detail Role
			Route::get('/role/detaildata/{idOrSlug}', 'detailData')->name('awesome_admin.role.detail')->middleware('auth');
		
			Route::get('/role/test', 'test')->name('awesome_admin.role.test')->middleware('auth');
		});

		Route::controller(\Awesome_Admin_PermissionController::class)->group(function()
		{
			// List and Add
			Route::get('/permission', 'index')->name('awesome_admin.permission')->middleware('auth');
			Route::post('/permission', 'store')->name('awesome_admin.permission.store')->middleware('auth');
			
			// Edit
			Route::post('/permission/edit/{idOrSlug}', 'update')->name('awesome_admin.permission.update')->middleware('auth');

			// Delete
			Route::post('/permission/delete/{idOrSlug}', 'destroy')->name('awesome_admin.permission.delete')->middleware('auth');

			// Get List Data
			Route::get('/permission/listdata', 'listdata')->name('awesome_admin.permission.list')->middleware('auth');

			// Get Detail Permission
			Route::get('/permission/detaildata/{idOrSlug}', 'detailData')->name('awesome_admin.permission.detail')->middleware('auth');
		});

		Route::name('user.')
			->prefix('user')
			->controller(\Awesome_Admin_UserController::class)
			->group(function() 
			{
				Route::get('/', 'index')->name('index');
				Route::get('/profile/{idOrSlug}', 'show')->name('show');
				Route::get('/{idOrSlug}', 'edit')->name('edit');
				Route::post('/store', 'store')->name('store');
				Route::match(['post', 'put'], '/update/{idOrSlug}', 'update')->name('update');
				Route::delete('/destroy/{idOrSlug}', 'destroy')->name('destroy');
			});
		Route::name('tools.')
			->prefix('tools')
			->controller(\Awesome_Admin_FormBuilderController::class)
			->group(function() 
			{
				Route::get('/form', 'index')->name('index');
				Route::get('/model/columns', 'getModelColumns')->name('model.columns');
			});
		});

Route::get('/oauth/azure', function () 
{
	return Socialite::driver('azure')->redirect();
});
 
Route::get('/oauth/azure/callback', function () 
{
	dd($user = Socialite::driver('azure')->stateless()->user());
});