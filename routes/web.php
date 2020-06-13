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
Route::view("/admin/login",'admin')->name("login");
Route::any('/admin/{all?}', function () {
    return view('admin');
})->where(['all' => '.*']);

Route::view('/', 'customer');
Route::view('/completed', 'customer');
Route::view('/check', 'customer');
Route::view('/book', 'customer');
Route::get('/dashboard', 'AdminController@dashboard');
Route::get('/dashboard/{resourceName}', 'AdminController@index');
