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

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::resource('/tickets', 'TicketController');
// Route::get('/tickets/{ticket}', 'TicketController@show');
Route::post('/tickets/{ticket}/message', 'TicketMessageController@store');
Route::delete('/tickets/{ticket}/message/{ticketmessage}', 'TicketMessageController@destroy');
