<?php

use App\Framework\Route;

Route::get('/', function() {
    return view('welcome');
});

Route::get('test', "UserController@test");
