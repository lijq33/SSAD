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


// });

Route::get('{any}', function () {
    return view('index');
})->where('any', '.*');

Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider');

Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderFacebookCallback');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => [
    'auth'
]], function(){
    Route::get('/user', 'GraphController@retrieveUserProfile');
 
    Route::post('/user', 'GraphController@publishToProfile');
 
    Route::post('/page', 'GraphController@publishToPage');
    Route::post('/page', 'GraphController@updatePost');
});
