<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function verify(Request $request)  {
       
        $credential = $request->validate([
                'username' => ['required'],
                'password' => ['required']

        ]);
        if(Auth::attempt($credential)){
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return redirect()->back()->withErrors('Username or password invalid !!!');

    }
    public function logout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
