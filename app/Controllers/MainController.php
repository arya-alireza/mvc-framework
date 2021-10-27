<?php

namespace App\Controllers;

class MainController
{
    public function index()
    {
        echo "OK!";
    }

    public function test($req)
    {
        print_r($req['id']);
    }
}