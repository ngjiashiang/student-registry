<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = new User;
        $user->password = Hash::make($request['password']);
        $user->email = $request['email'];
        $user->name = $request['name'];
        $user->save();

        return response(['msg' => 'success'], 200);
    }

    public function login(Request $request) {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if( !Auth::attempt($login) ){
            return response(['msg' => 'invalid']);
        }

        $token = Auth::user()->createToken('authToken')->accessToken;

        return response(['user' => Auth::user(), 'token', $token]);
    }
}
