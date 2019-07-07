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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/login','UserController@login');

Route::group(['middleware' => 'auth:api'], function(){
	Route::get('/user','UserController@index');
	Route::post('/user','UserController@store');
	Route::post('/vacation/max','VacationController@setMaximum');
	Route::get('/vacation','VacationController@index');
	Route::post('/vacation','VacationController@requestVacation');
	Route::put('/vacation/{id}/approve','VacationController@approveVacation');
});

