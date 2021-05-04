<?php

use App\Framework\Route;

Route::get('/', function() {
    return view('welcome');
});

Route::get('test', "UserController@test")->name('contoh')->middleware('Admin');

Route::group(['prefix' => "admin", 'middleware' => "Admin"], function() {
    Route::get('login', "AdminController@login")->name('admin.login');
    Route::get('register', "AdminController@register")->name('admin.register');
});

Route::group(['prefix' => "user"], function() {
    Route::get('login', "UserController@login")->name('user.login');
    Route::get('register', "UserController@register")->name('user.register');
    Route::get('dashboard', "UserController@dashboard")->name('user.dashboard');
    Route::get('{id}', "UserController@register")->name('user');
});

Route::get('logout', "UserController@logout")->name('logout');
