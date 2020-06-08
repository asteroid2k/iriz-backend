<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    public function login(Request $request)
    {

        #Validate DATA
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Login Failed",
                "body" => $validator->errors()->first(),
                "status" => 400
            ]);
        }

        $credentials = $request->only('email', 'password');

        #Attempt Login with DATA
        if (Auth::attempt($credentials)) {
            #Login User
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json([
                "message" => "You're Logged in",
                "token" => $success['token'],
                "status" => 202
            ]);
        } else {
            #Login Failed
            return response()->json([
                "message" => "Credentials do not match",
                "status" => 400
            ]);
        }
    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            "message" => 'You\'re logged out',
            "status" => 200
        ]);
    }
}
