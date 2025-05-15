<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

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
            'email' => 'required',
            'access_token' => 'required'
        ]);

        $user = User::create([
            'email' => $request->email,
            'access_token' => $request->access_token,
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

    public function request_otp(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'status' => 404,
                'message' => 'No records found',
            ]);
        }

        $otp = rand(1000, 9999);

        $user->update([
            'otp' => $otp
        ]);

        Mail::to($user)->send(new OTPMail($user));

        return response()->json([
            'status' => 200,
            'message' => 'OTP sent successfully'
        ]);
    }

    public function reset_password(Request $request){
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::where('otp', $request->otp)->first();

        if(!$user){
            return response()->json([
                'status' => 401,
                'message' => 'Incorrect OTP code',
            ]);
        }

        $request->validate([
            'password' => 'required|confirmed'
        ]);

        $user->update([
            'password' => $request->password
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Password reset succesfully'
        ]);
    }

    public function change_password(Request $request, User $user){
        $request->validate([
            'current_password' => 'required',
        ]);

        if(!Hash::check($request->current_password, $user->password)){
            return response()->json([
                'status' => 401,
                'message' => 'Incorrect password'
            ]);
        }

        $request->validate([
            'new_password' => 'required|confirmed'
        ]);

        $user->update([
            'password' => $request->new_password
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Password changed successfully'
        ]);
    }
    

        // public function login_with_google(Request $request){
    //     try{

    //     $request->validate([
    //         'access_token' => 'required'
    //     ]);

    //     $client = new Client(['verify' => false]);

    //     $google_user = Socialite::driver('google')
    //     ->stateless()
    //     ->setHttpClient($client)
    //     ->userFromToken($request->access_token);

    //     $user = User::updateOrCreate(
    //         [ 'email' => $google_user->getEmail() ],

    //         [
    //             'full_name' => $google_user->getName(),
    //             'google_id' => $google_user->getId(),
    //             'email_verified_at' => now(),
    //             'profile_picture' => $google_user->getAvatar()
    //         ]
    // );

    //     $token = $user->createToken('BeerGoApp')->plainTextToken;

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Logged in successfully',
    //         'token' => $token,
    //         'user' => $google_user 
    //     ]);

    //     }catch(Exception $e){
    //         return response()->json([
    //             'status' => 200,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }

}
