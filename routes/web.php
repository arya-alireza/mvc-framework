<?php

use Core\Route;

Route::get('', ['MainController@wellcome', 'wellcome']);
Route::get('dashboard', ['MainController@wellcome', 'dashboard']);