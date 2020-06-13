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
Route::view("/sandbox",'sandbox');
Route::view("/admin/login",'admin')->name("login");
Route::any('/admin/{all?}', function () {
    return view('admin');
})->where(['all' => '.*']);

Route::view('/', 'welcome');
Route::view('/completed', 'welcome');
Route::view('/check', 'welcome');
Route::view('/book', 'welcome');
Route::get('/dashboard', 'AdminController@dashboard');
Route::get('/dashboard/{resourceName}', 'AdminController@index');
