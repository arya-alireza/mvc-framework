<?php

namespace App\Helpers;

use App\Models\User;
use Core\Route;

class Auth
{
    static function check()
    {
        if (isset($_SESSION['userLogin'])) {
            return User::findOrFail($_SESSION['userLogin']) ? true : false;
        } else {
            return false;
        }
    }

    static function user()
    {
        return self::check() ? User::find($_SESSION['userLogin']) : false;
    }

    static function routes()
    {
        Route::get('login', ['Auth\LoginController@show', 'login']);
        Route::post('login', ['Auth\LoginController@login', 'login.do']);
        Route::get('register', ['Auth\RegisterController@show', 'register']);
        Route::post('register', ['Auth\RegisterController@register', 'register.do']);
        Route::post('logout', ['Auth\LoginController@logout', 'logout']);
    }
}