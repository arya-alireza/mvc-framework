<?php

namespace App\Middlewares;

class CheckAuth
{
    public function handle()
    {
        if (isset($_GET['test'])) return true;
    }

    public function error()
    {
        echo "Error from middleware";
    }
}