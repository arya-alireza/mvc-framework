<?php

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use App\Helpers\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth/register');
    }

    public function register()
    {
        $req = new Request;
        $data = $req->all();
        $data['password'] = Hash::make($data['password']);
        $userId = User::create($data);
        $_SESSION['userLogin'] = $userId;
        return redirect('dashboard');
    }
}