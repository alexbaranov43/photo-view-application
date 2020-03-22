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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/photos', 'PhotoController@index');
Route::post('/photos/upload', 'PhotoController@store')->name('csv_import_parse');
Route::get('/photos/csv', 'PhotoController@downloadCsv')->name('csv_download');
