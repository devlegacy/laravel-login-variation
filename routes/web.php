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
    return view('welcome');
});

Auth::routes();
Route::prefix('cliente')->group(function () {
    Route::get('ingresar', 'Auth\LoginController@showLoginForm')->name('cliente.ingresar');
});
Route::prefix('admin')->group(function () {
    Route::get('ingresar', 'Auth\LoginController@showLoginForm')->name('admin.ingresar');
});




Route::get('/home', 'HomeController@index')->name('home');

