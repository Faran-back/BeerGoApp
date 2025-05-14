<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth APIs
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-with-google', [AuthController::class, 'login_with_google']);
Route::post('/login-with-apple', [AuthController::class, 'login_with_apple']);

// User APIs
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/store-profile/{user}', [UserController::class, 'store']);
    Route::post('/update-profile/{user}', [UserController::class, 'update']);
    Route::delete('/delete-profile/{user}', [UserController::class, 'destroy']);
});