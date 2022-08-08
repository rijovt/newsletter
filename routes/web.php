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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::post('/additem', 'HomeController@additem')->name('additem');
Route::delete('destroyitem/{item}', 'HomeController@destroyitem')->name('destroyitem');
Route::post('/compose', 'HomeController@compose')->name('compose');
Route::post('/sendMail', 'HomeController@sendMail')->name('sendMail');
