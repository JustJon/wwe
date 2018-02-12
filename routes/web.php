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

Route::get('create', 'HomeController@create');

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::post('/upload', 'HomeController@upload');

Route::get('/video/{video_id}', 'HomeController@video');

Route::post('/add_keywords', 'HomeController@addKeywords');

Route::post('/add_location', 'HomeController@addLocation');
