<?php

use Core\Route;

Route::get('', ['MainController@wellcome', 'wellcome']);

// Auth Routes
Route::get('login', ['Auth\LoginController@show', 'login']);
Route::post('login', ['Auth\LoginController@login', 'login.do']);
Route::get('dashboard', ['MainController@wellcome', 'dashboard', 'Authentication']);