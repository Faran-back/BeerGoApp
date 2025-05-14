<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request){

        try{
            $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $otp = rand(1000, 8888);

        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'otp' => $otp
        ]);

        $token = $user->createToken('BeerGoApp')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $token
        ]);
        }catch(Exception $e){
            return response()->json([
            'status' => 500,
            'message' => $e->getMessage()
        ]);
        }
    }

    public function login(Request $request){
         try{
            $validated = $request->validate([
                'email' => 'required',
                'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('BeerGoApp')->plainTextToken;

        if(!$user && 
           !Hash::check('password', $request->password)){

                return response()->json([
                    'status' => 404,
                    'message' => 'The given credentials does not match our records'
                ]);
                
           }

        return response()->json([
            'status' => 200,
            'message' => 'User Logged in',
            'token' => $token
        ]);

        }catch(Exception $e){

            return response()->json([
            'status' => 500,
            'message' => $e->getMessage()
        ]);

        }
    }

    public function login_with_google(Request $request){
        
        try{

        $request->validate([
            'email' => 'required'
        ]);

        $user = User::create([
            'email' => $request->email
        ]);

        $token = $user->createToken('BeerGoApp')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Logged in successfully',
            'token' => $token,
        ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function login_with_apple(Request $request){
        try{

        $request->validate([
            'email' => 'required'
        ]);

        $user = User::create([
            'email' => $request->email
        ]);

        $token = $user->createToken('BeerGoApp')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Logged in successfully',
            'token' => $token,
        ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage(),
            ]);
        }
    }

    
}
