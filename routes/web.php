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

Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();

Route::get('/home', 'UserController@index')->name('home');

Route::get('/karyawan/tambah','UserController@create');
Route::get('/karyawan/liburan','UserController@createLiburan');
Route::post('/karyawan/liburan','UserController@requestVacation');
Route::post('/karyawan/store','UserController@store');
