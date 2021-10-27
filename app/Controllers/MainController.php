<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class MainController extends Controller
{
    public function wellcome()
    {
        return View::render('wellcome', [
            'hello' => 'Hello World',
        ]);
    }
}