<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\DeleteAccount;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function index(){
    }

    public function store(Request $request, User $user){

        try{
            $validated = $request->validate([
            'full_name' => 'required|string',
            'gender' => 'required|in:Male,Female,Other ',
            'date_of_birth' => 'required|date',
            'profile_picture' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        if($request->hasFile('profile_picture')){
            $pfp = $request->file('profile_picture');
            $pfp_name = time() . '_' . $pfp->getClientOriginalName();
            $pfp->storeAs('profile_pictures', $pfp_name, 'public');

           $updated = $user->update([
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'profile_picture' => $pfp_name
           ]);

            return response()->json([
                'status' => 200,
                'message' => 'Profile created successfully',
                'user' => $user
            ]);
        }

            $user->update($validated);

            return response()->json([
                'status' => 200,
                'message' => 'Profile created successfully',
                'user' => $user
            ]);

        }catch(Exception $e){
             return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, User $user){

        try{
            $validated = $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);

        if($request->hasFile('profile_picture')){
            $pfp = $request->file('profile_picture');
            $pfp_name = time() . '_' . $pfp->getClientOriginalName();
            $pfp->storeAs('profile_pictures', $pfp_name, 'public');

            $user = User::update([
                'name' => $request->name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'profile_picture' => $pfp_name
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'User account created successfully',
                'user' => $user
            ]);
        }

            $user = User::update($validated);

            return response()->json([
                'status' => 200,
                'message' => 'User account created successfully',
                'user' => $user
            ]);

        }catch(Exception $e){
             return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function request_delete_account(Request $request, User $user){

        try{
            $request->validate([
                'password' => 'required'
            ]);

            if(!Hash::check($request->password, $user->password)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Incorrect Password'
            ]);

            }

            $otp = rand(1000, 9999);

            $user->update([
                'otp' => $otp
            ]);

            Mail::to($user->email)->send(new DeleteAccount($user));

            return response()->json([
                'status' => 200,
                'message' => 'OTP sent'
            ]);
            
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request, User $user){

        try{

        $request->validate([
            'otp' => 'required'
        ]);

        if($request->otp !== $user->otp){
            return response()->json([
                'status' => 401,
                'message' => 'Incorrect OTP code'
            ]);
        }

        $user->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Account Deleted Successfully'
        ]); 

        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }  
}
