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


Route::get('crisis/gasLeak', 'CrisisController@gasLeakCrisis');
Route::get('crisis/fire', 'CrisisController@fireCrisis');
Route::get('crisis/dengue', 'CrisisController@dengueCrisis');

Route::post('pubcrisis', 'ReportCrisisController@store');


Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('subscriber', 'SubscriberController@store');
    Route::post('subscriber/delete', 'SubscriberController@destroy');
});

Route::group(['middleware' => 'auth.ccoperator'], function ($router) {
    Route::post('ccopercrisis', 'CrisisController@store');
});

Route::group(['middleware' => 'auth.crisismanager'], function ($router) {
    Route::post('crisis', 'CrisisController@store');
    Route::get('crisis', 'CrisisController@index');
    Route::get('crisis/archive', 'CrisisController@archive');
    Route::post('crisis/{crisis}', 'CrisisController@update');
    Route::delete('crisis/{crisis}', 'CrisisController@destroy');
    Route::post('report/crisis/{crisis}', 'ReportCrisisController@update');
    Route::delete('report/crisis/{crisis}', 'ReportCrisisController@destroy');
    Route::get('/crisis/managePublic', 'ReportCrisisController@index');
});

Route::group(['middleware' => 'auth.accountmanager'], function ($router) {
    Route::resource('register', 'RegisterController', ['except' => ['create', 'edit']]);
});

// 'auth.user'
// 'auth.ccoperator'
// 'auth.cdadmin'
// 'auth.crisismanager'
// 'auth.accountmanager'