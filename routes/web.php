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

Route::get('/client-area/', 'HomeController@index');

Route::resource('/client-area/tickets', 'TicketController');

Route::post('/client-area/tickets/{ticket}/message', 'TicketMessageController@store');
Route::delete('/client-area/tickets/{ticket}/message/{ticketmessage}', 'TicketMessageController@destroy');

Route::get('/client-area/user/resend-verification', 'UserVerificationController@sendVerificationEmail')->name('user.sendVerification');
Route::get('/client-area/user/verify/{token}', 'UserVerificationController@verifyUserByToken')->name('user.verify');

Route::post('/file_upload', 'FileUploadController@store');

Route::get('/{page?}', 'PagesController@serve');
