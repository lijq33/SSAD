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


Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('subscriber', 'SubscriberController@store');
    Route::post('subscriber/delete', 'SubscriberController@destroy');
});


Route::group(['middleware' => 'auth.ccoperator'], function ($router) {
    Route::post('crisis', 'CrisisController@store');
});

Route::group(['middleware' => 'auth.crisismanager'], function ($router) {
    Route::get('crisis', 'CrisisController@index');
    Route::post('crisis/{crisis}', 'CrisisController@update');
    Route::delete('crisis/{crisis}', 'CrisisController@destroy');
    Route::post('crisis/{crisis}', 'CrisisController@update');
});

Route::group(['middleware' => 'auth.accountmanager'], function ($router) {
    Route::resource('register', 'RegisterController', ['except' => ['create', 'edit']]);
});




// 'auth.user'
// 'auth.ccoperator'
// 'auth.cdadmin'
// 'auth.crisismanager'
// 'auth.accountmanager'