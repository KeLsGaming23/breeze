<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function Login(Request $request){
        try{
            if(Auth::attempt($request->only('email', 'password'))){
                $user = Auth::user();
                // $token = Str::random(60);
                $token = $user->createToken('app')->plainTextToken;
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
    public function Register(Request $request){
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = $user->createToken('app')->plainTextToken;
        return response()->json([
            'message' => 'Successfully registered',
            'token' => $token
        ], 200);
    }
}
