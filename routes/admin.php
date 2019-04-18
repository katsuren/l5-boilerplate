<?php

Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/logout', 'Auth\LoginController@logout');

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/', function () { return view('admin.home'); });
    Route::get('/home', function () { return view('admin.home'); });

    Route::get('/account', 'AccountController@index');
    Route::put('/account', 'AccountController@update');

    Route::resource('/admins', 'AdminsController', ['only' => ['index', 'create', 'store', 'destroy']]);
    Route::resource('/users', 'UsersController', ['only' => ['index', 'edit', 'update', 'destroy']]);
});
