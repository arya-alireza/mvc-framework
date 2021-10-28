<?php

namespace App\Middlewares;

use Core\Route;

class Authentication
{
    public function handle()
    {
        if (isset($_SESSION['userLogin'])) return true;
    }

    public function error()
    {
        Route::redirect(Route::url('login'));
    }
}