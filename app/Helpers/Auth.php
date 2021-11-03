<?php

namespace App\Helpers;

use App\Models\User;

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
}