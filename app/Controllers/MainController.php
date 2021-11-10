<?php

namespace App\Controllers;

use Core\Controller;

class MainController extends Controller
{
    public function home()
    {
        return view('home');
    }
}