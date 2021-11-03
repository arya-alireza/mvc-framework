<?php

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use Core\View;
use Core\Route;
use App\Models\User;
use App\Helpers\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        View::render('auth/register');
    }

    public function register()
    {
        $req = new Request();
        $data = $req->all();
        $data['password'] = Hash::make($data['password']);
        $userId = User::create($data);
        $_SESSION['userLogin'] = $userId;
        Route::redirect('dashboard');
    }
}