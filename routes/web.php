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

Route::get('/photos/index', 'PhotoController@index');
Route::get('/photos/index/gray', 'PhotoController@indexGrayscale');
Route::get('/photos/index/{width}/{height}', 'PhotoController@indexByDimension')->where('width', '[0-9]+', 'heigth', '[0-9]+');
Route::get('/photos/add', 'PhotoController@create');
Route::post('/photos/upload', 'PhotoController@store')->name('csv_import_parse');
Route::get('/photos/csv', 'PhotoController@downloadCsv')->name('csv_download');
