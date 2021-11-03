<?php

namespace App\Helpers;

class Hash
{
    public static function make($str)
    {
        return password_hash($str, 1);
    }

    public static function verify($pass, $hash)
    {
        return password_verify($pass, $hash);
    }
}