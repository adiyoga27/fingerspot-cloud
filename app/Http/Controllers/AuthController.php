<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function verify(Request $request)
    {
        $credential = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credential)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return redirect()->back()->withErrors('Username or password invalid !!!');
    }
    public function login(Request $request)
    {
        $credential = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credential)) {
            $user = Auth::user();
            $token = $user->createToken(
                $user->username.'_'.Carbon::now(), // The name of the token
                ['*'],                         // Whatever abilities you want
                Carbon::now()->addDays(360)     // The expiration date
            );
            
            $user['token'] = $token;
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' =>  (new AuthResource($user)),
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Username or password invalid !!!'
        ], 200);
    }
    public function registration(Request $request){
        $request->validate([
            'name' => ['required'],  //name should be required
            'username' => ['required','unique:users'],
            'password' => ['required','min:8'],
            'role' => ['required', "in:admin,user"],  //admin or user
            'email' => ['required','unique:users','email']  //email should be unique and valid
        ]);
        try {
            User::create([
                'name' => $request->name,    //name is required
                'username' => $request->username,   
                'password' => Hash::make($request->password),   
                'role' => $request->role,   
                'email' => $request->email
            ]);

            return response()->json([
               'status' => true,
               'message' => 'User created successfully'
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()  //error message
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
