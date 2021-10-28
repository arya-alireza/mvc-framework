<?php

namespace App\Middlewares;

use Core\Route;

class CheckAuth
{
    public function handle()
    {
        if (isset($_SESSION['test'])) return true;
    }

    public function error()
    {
        Route::redirect(Route::url('login'));
    }
}