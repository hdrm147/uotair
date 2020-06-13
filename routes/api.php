<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/admin/login', 'AdminController@login');

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::get('/admin/flights/{flight}/classes', 'AdminController@classes');
    Route::get('/admin/{resourceName}', 'AdminController@index');
    Route::get('/admin/{resourceName}/{resourceId}/edit', 'AdminController@form');
    Route::get('/admin/{resourceName}/new', 'AdminController@form');
    Route::get('/admin/{resourceName}/{resourceId}', 'AdminController@detail');
    Route::patch('/admin/{resourceName}/{resourceId}', 'AdminController@update');
    Route::post('/admin/{resourceName}', 'AdminController@create');
    Route::delete('/admin/{resourceName}/{resourceId}', 'AdminController@dropResource');
});

Route::get('/airports', 'APIController@airports');
Route::get('/tickets', 'APIController@getTickets');
Route::get('/flights/{flight}/seats', 'APIController@seats');
Route::post('/flights/{flight}/book', 'APIController@book');
Route::get('/flights/{departureAirport}/{arrivalAirport}', 'APIController@getFlights');
