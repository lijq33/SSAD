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

Route::get('address/postal_code/{id}.json', ['as' => 'json.postal_code', 'uses' => 'MapsController@getAddress']);
Route::get('address/autocomplete/{id}.json', ['as' => 'json.autocomplete.address', 'uses' => 'MapsController@autocomplete']);
Route::get('address/geocode/{query}.json', ['as' => 'json.geocode.address', 'uses' => 'MapsController@geocode']);

Route::get('crisis/all', 'CrisisController@crisis');
Route::get('crisis', 'CrisisController@index');

Route::get('weather/all', 'CrisisController@weather');
Route::post('report/crisis', 'ReportCrisisController@store');
 
Route::post('sendemail', 'SendEmailController@send');

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
    Route::get('crisis/archive', 'CrisisController@archive');
    Route::post('crisis/{crisis}', 'CrisisController@update');
    Route::delete('crisis/{crisis}', 'CrisisController@destroy');

    Route::patch('report/crisis/{id}', 'ReportCrisisController@update');
    Route::delete('report/crisis/{crisis}', 'ReportCrisisController@destroy');
    Route::get('report/crisis', 'ReportCrisisController@index');
});

Route::group(['middleware' => 'auth.accountmanager'], function ($router) {
    Route::patch('register/{user}', 'RegisterController@update');
    Route::delete('register/{user}', 'RegisterController@destroy');
    Route::resource('register', 'RegisterController', ['except' => ['create', 'destroy', 'edit']]);
});