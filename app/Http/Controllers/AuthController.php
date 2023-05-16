<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function Login(Request $request){
        try{
            if(Auth::attempt($request->only('email', 'password'))){
                $user = Auth::user();
                // $token = Str::random(60);
                $token = $user->createToken('app')->accessToken;

                return response([
                    'message' => 'Successfully Login',
                    'token' => $token,
                    'user' => $user
                ], 200)->header('Authorization', 'Bearer ' . $token); // Add this line to include the Bearer token in the response header
            }
        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ],400);
        }
        return response([
            'message' => 'Invalide email or password'
        ], 401);
    }
}
