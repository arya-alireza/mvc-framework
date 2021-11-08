<?php

namespace App\Controllers;

use Core\Controller;

class MainController extends Controller
{
    public function wellcome()
    {
        return view('wellcome', [
            'hello' => 'Hello World',
        ]);
    }
}