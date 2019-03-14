<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//edit, create, store, update, destory, show, index


Route::group([ 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


Route::group(['middleware' => 'auth.ccoperator'], function ($router) {
    Route::resource('crisis', 'CrisisController', ['except' => ['create', 'edit']]);
});

Route::group(['middleware' => 'auth.ccoperator'], function ($router) {
    Route::resource('register', 'RegisterController', ['except' => ['create', 'edit']]);
});




// 'auth.user'
// 'auth.ccoperator'
// 'auth.cdadmin'
// 'auth.crisismanager'
// 'auth.accountmanager'