<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/movies', 'MovieController@list')->name('movies.list');
Route::get('/movies/data', 'MovieController@data')->name('movies.data');
Route::get('/movies/search/{item}', 'MovieController@search')->name('movies.search');
Route::post('/movies', 'MovieController@add')->name('movies.add');;
Route::put('/movies/{movie}', 'MovieController@update')->name('movies.update');
Route::delete('/movies/{movie}', 'MovieController@delete')->name('movies.delete');

