<?php

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

Route::group(['prefix' => 'admin'], function () {

	Route::get('/', function () {
	    return View::make('admin.index');
	});

	Route::get('/login', function () {
	    return View::make('admin.login');
	});

	Route::get('/dashboard', function () {
	    return View::make('admin.dashboard');
	});

	Route::get('{main?}/{main_id?}/{child?}/{child_id?}', function () {
		return View::make('admin.dashboard');
	});

	Route::get('/{main?}/{main_id?}/{child?}', function () {
		return View::make('admin.dashboard');
	});

	Route::get('/{main?}/{main_id?}', function () {
		return View::make('admin.dashboard');
	});

	Route::get('/{name}', function () {
		return View::make('admin.dashboard');
	});

});

Route::get('/', function () {
    return '';
});
