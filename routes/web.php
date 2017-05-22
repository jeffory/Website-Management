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
    Route::get('/', 'ServerController@index')
        ->name('server.index');
});

Route::bind('remoteserver', function ($value) {
    return App\RemoteServer::where('domain', $value)->firstOrFail();
});

Route::group(['prefix' => 'client-area/management/email/{remoteserver}'], function () {
    Route::get('/', 'ServerEmailController@index')
         ->name('server_email.index');

    Route::post('/', 'ServerEmailController@store')
         ->name('server_email.store');

    Route::delete('/{email?}', 'ServerEmailController@destroy')
           ->name('server_email.destroy');

    Route::put('/{email?}', 'ServerEmailController@update')
         ->name('server_email.update');

    Route::post('/password-check', 'ServerEmailController@password_strength')
         ->name('server_email.password_check');

    Route::post('/account-check/{email?}', 'ServerEmailController@confirm')
        ->name('server_email.account_check');
});

Route::get('/user/resend-verification', 'UserController@sendVerificationEmail')
    ->name('user.sendVerification');

Route::get('/user/verify/{token}', 'UserController@verifyUserByToken')
    ->name('user.verify');

Route::get('/user/edit', 'UserController@edit')
    ->name('user.edit');

Route::post('/user/edit', 'UserController@update')
    ->name('user.update');

Route::group(['prefix' => 'client-area/admin/'], function () {
    Route::get('/', 'AdminController@index')
         ->name('admin.index');

    Route::get('/users', 'AdminController@userIndex')
         ->name('admin.user_index');

    Route::delete('/users/{user?}', 'AdminController@userDestroy')
         ->name('admin.user_destroy');
});

Route::get('/{page?}', 'PagesController@serve');
