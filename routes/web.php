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

# TODO: Somewhere in the code there is a redirect to /home when logging in.
Route::get('/home', function () {
    return redirect()->route('home');
});

Route::group(['prefix' => 'client-area'], function () {
    Route::get('/', 'HomeController@index')
        ->name('home');

    Route::resource('/tickets', 'TicketController');
    
    Route::put('/tickets/{ticket}', 'TicketController@update')
        ->name('tickets.update');

    Route::post('/tickets/{ticket}/message', 'TicketMessageController@store')
        ->name('ticket_message.store');

    Route::delete('/tickets/{ticket}/message/{ticketmessage}', 'TicketMessageController@destroy')
        ->name('ticket_message.destroy');

    Route::delete('/tickets/delete_file_upload/{query}', 'TicketFileController@destroy')
        ->name('tickets.delete_file_upload');
});

Route::post('/tickets/file_upload', 'TicketFileController@upload')
    ->name('tickets.file_upload');

Route::group(['prefix' => 'client-area/management'], function () {
    Route::get('/', 'ServerManagementController@index')
        ->name('server.index');

    Route::get('/email/{domain}', 'ServerManagementController@show')
        ->name('server.show');

    Route::post('/email-password-strength', 'ServerManagementController@emailPasswordStrength')
        ->name('server.emailPasswordStrength');

    Route::post('/email-password-change', 'ServerManagementController@emailPasswordChange')
        ->name('server.emailChangePassword');

    Route::post('/email-password-check', 'ServerManagementController@emailPasswordCheck')
        ->name('server.emailPasswordCheck');

    Route::post('/email-new-account', 'ServerManagementController@storeNewEmail')
        ->name('server.storeNewEmail');

    Route::delete('/email-delete-account', 'ServerManagementController@deleteEmail')
        ->name('server.deleteEmail');
});

Route::get('/user/resend-verification', 'UserVerificationController@sendVerificationEmail')
    ->name('user.sendVerification');

Route::get('/user/verify/{token}', 'UserVerificationController@verifyUserByToken')
    ->name('user.verify');

Route::get('/{page?}', 'PagesController@serve');
