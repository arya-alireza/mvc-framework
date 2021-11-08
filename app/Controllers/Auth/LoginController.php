<?php

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use Core\Route;
use Core\View;
use App\Helpers\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth/login');
    }

    public function login()
    {
        $req = new Request;
        $email = $req->email;
        $pass = $req->password;
        $user = User::findWhere("`email`='$email'");
        if ($user) {
            $hash = $user->password;
            if (Hash::verify($pass, $hash)) {
                $_SESSION['userLogin'] = $user->id;
                Route::redirect('dashboard');
            } else {
                Route::redirect('login', ['error', 'Password is wrong!']);
            }
        } else {
            Route::redirect('login', ['error', 'User not found!']);
        }
    }
}