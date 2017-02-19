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

Route::group(['prefix' => 'client-area'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('/tickets', 'TicketController');
    Route::post('/tickets/{ticket}/message', 'TicketMessageController@store');
    Route::delete('/tickets/{ticket}/message/{ticketmessage}', 'TicketMessageController@destroy');
});

Route::post('/file_upload', 'FileUploadController@store');

Route::get('/user/resend-verification', 'UserVerificationController@sendVerificationEmail')->name('user.sendVerification');
Route::get('/user/verify/{token}', 'UserVerificationController@verifyUserByToken')->name('user.verify');
Route::get('/{page?}', 'PagesController@serve');
