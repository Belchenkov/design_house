<?php

use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function() {
    return response()->json([
        'message' => 'DesignHouse API...'
    ], 200);
});

Route::get('me', 'User\MeController@me');

// Routes for auth users
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'Auth\LoginController@logout');
});

// Routes for guest users
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
});
