<?php

use Core\Route;

Route::get('', ['MainController@wellcome', 'wellcome']);

// Auth Routes
Route::get('login', ['Auth\LoginController@show', 'login']);
Route::post('login', ['Auth\LoginController@login', 'login.do']);
Route::get('register', ['Auth\RegisterController@show', 'register']);
Route::post('register', ['Auth\RegisterController@register', 'register.do']);
Route::get('dashboard', ['MainController@wellcome', 'dashboard', 'Authentication']);