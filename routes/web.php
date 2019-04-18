<?php

Auth::routes(['verify' => true]);
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/', function () { return view('home'); });
Route::get('/home', function () { return view('home'); });
Route::get('/pages/{path}', 'PagesController@index')->where('path', '(.*)');

// middleware:profile にひっかからないように別で定義しておく
Route::middleware('auth')->group(function () {
    Route::get('/user/account/verify/{email}', 'User\AccountController@verify')->name('user.account.verify');
    Route::get('/user/account', 'User\AccountController@index');
    Route::put('/user/account', 'User\AccountController@update');
});
