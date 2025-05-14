<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

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
    
    public function destroy(User $user){
        try{
            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => 'User Deleted Successfully',
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
