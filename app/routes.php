<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@show');

Route::get('dayWork', 'DayWorkController@show');
Route::get('storableDayWork', 'DayWorkController@showStorable');
Route::post('dayWork/store', 'DayWorkController@store');
Route::post('dayWork/load', 'DayWorkController@load');

Route::post('order/createMission/{id}', 'OrderController@doCreateMission');
Route::get('order/createMission/{id}', 'OrderController@createMission');
Route::get('order/deleteMission/{id}', 'OrderController@deleteMission');
Route::get('order/createStore', 'OrderController@createStore');
Route::post('order/createStore', 'OrderController@doCreateStore');
Route::get('order/editStore/{id}', 'OrderController@editStore');
Route::post('order/editStore/{id}', 'OrderController@doEditStore');
Route::get('order/deleteStore/{id}', 'OrderController@deleteStore');
Route::get('order/{id}', 'OrderController@showMission');
Route::get('order', 'OrderController@show');

Route::get('login', 'UserController@showLogin');
Route::post('login', 'UserController@doLogin');
Route::get('logout', 'UserController@doLogout');


Route::post('api/order/add', 'OrderController@addOrder');
Route::post('api/order/decrease', 'OrderController@decrementOrder');
Route::post('api/order/paid', 'OrderController@paid');
Route::post('api/order/remark', 'OrderController@remark');

if (file_exists(__DIR__.'/controllers/Server.php')) {
    Route::get('/server', 'Server@show');
    Route::get('/deploy', 'Server@deploy');
    Route::get('/db/refresh', 'Server@dbRefresh');
    Route::get('/db/reset', 'Server@dbReset');
    Route::get('/db/migrate', 'Server@dbMigrate');
    Route::get('/db/seed', 'Server@dbSeed');
}

View::composer(['order.*'], function($view) {
	$view->with(['users' => User::all()]);
});