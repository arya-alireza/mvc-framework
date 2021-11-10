<?php

use Core\Route;
use App\Helpers\Auth;

Route::get('', ['MainController@home', 'home']);

Auth::routes();