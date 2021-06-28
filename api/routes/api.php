<?php

use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function() {
    return response()->json([
        'message' => 'DesignHouse API...'
    ], 200);
});

// Routes for auth users
Route::group(['middleware' => ['auth:api']], function () {

});

// Routes for guest users
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register');
});
