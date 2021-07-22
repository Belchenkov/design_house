<?php

use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function() {
    return response()->json([
        'message' => 'DesignHouse API...'
    ], 200);
});
Route::get('me', 'User\MeController@me');


// Get Users
Route::get('users', 'User\UserController@index');

// Get Team
Route::get('teams/slug/{slug}', 'Teams\TeamsController@findBySlug');

// Routes for auth users
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    // Settings
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@updatePassword');

    // Designs
    Route::get('designs', 'Designs\DesignController@index');
    Route::get('designs/{id}', 'Designs\DesignController@findDesign');
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');

    // Comments
    Route::post('designs/{id}/comments', 'Designs\CommentController@store');
    Route::put('comments/{id}', 'Designs\CommentController@update');
    Route::delete('comments/{id}', 'Designs\CommentController@destroy');

    // Likes
    Route::post('designs/{id}/like', 'Designs\DesignController@like');
    Route::get('designs/{id}/liked', 'Designs\DesignController@checkIfUserHasLiked');

    // Teams
    Route::get('teams', 'Teams\TeamsController@index');
    Route::get('teams/{id}', 'Teams\TeamsController@findById');
    Route::get('users/teams', 'Teams\TeamsController@fetchUserTeams');
    Route::post('teams', 'Teams\TeamsController@store');
    Route::put('teams/{id}', 'Teams\TeamsController@update');
    Route::delete('teams/{id}', 'Teams\TeamsController@destroy');
});

// Routes for guest users
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});
