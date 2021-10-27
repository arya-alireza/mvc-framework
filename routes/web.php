<?php

use Core\Route;

Route::get('', ['MainController@index', 'index']);
Route::get('products/{id}', ['MainController@test', 'test']);