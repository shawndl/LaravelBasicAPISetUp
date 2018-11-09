<?php

use Illuminate\Http\Request;

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


Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function(){
    Route::post('register', 'RegisterController@register')
        ->middleware(['guest.api'])
        ->name('register');
    Route::post('login', 'LoginController@login')
        ->middleware(['guest.api', 'user.confirmed', 'user.banned'])
        ->name('login');

    Route::post('/logout', 'LogoutController@logout')->name('logout');

    Route::post('forgot_password', 'ForgetPasswordController@reset')
        ->middleware(['guest.api'])
        ->name('forget');

    Route::post('reset_password/{confirmation_token}', 'ResetPasswordController@reset')
        ->middleware(['confirmation_token.expired', 'guest.api'])
        ->name('reset');

    Route::get('/me', 'MeController@me')
        ->middleware(['auth:api'])
        ->name('me');

    Route::get('/activate/{confirmation_token}', 'ActivationController@activate')
        ->middleware(['confirmation_token.expired', 'guest.api'])
        ->name('activate');

    Route::post('login/oauth', 'SocialLoginController@login')
        ->name('login.oauth');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => 'auth:api'], function(){
    Route::post('', 'Profile\ProfileController@store')->name('update');
    Route::post('password', 'Profile\PasswordController@store')->name('password');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth:api', 'auth.admin']], function(){
    Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
        Route::get('', 'AdminUserController@index')->name('index');
        Route::post('admin-access', 'AdminAccessController@store')
            ->name('access');
        Route::post('banned', 'AdminAccessController@banned')
            ->name('banned');
        Route::get('{user}/show', 'AdminUserController@show')
            ->name('show');
    });
});
