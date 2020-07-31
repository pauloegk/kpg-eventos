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

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return redirect('events');
    });

    Route::resource('events', 'EventController');

    Route::get('/events/{id}/guests', 'EventController@getGuests');

    Route::post('/events/{id}/cancel', 'EventController@cancelEvent')->name('cancel-event');

    Route::post('/events/{id}/send-invite', 'EventController@sendInvite')->name('send-invite');

    Route::get('/events/{id}/confirm-invite', 'EventController@confirmInvite')->name('confirm-invite');

    Route::get('/my-events', 'GuestController@myEvents')->name('my-events');

    Route::post('/guests/event/{id}/request-participation', 'GuestController@requestParticipation');

    Route::post('/guests/event/{id}/cancel-participation', 'GuestController@cancelParticipation');

    Route::post('/notifications', 'MessageGuestsController@send');


});




