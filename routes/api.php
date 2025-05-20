<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Event routes
Route::apiResource('events', EventController::class);
Route::apiResource('categories', CategoryController::class);


//api/token Auth , to be used to see any api (sanctom token)


// Route::post('/token', function (Request $request) {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     if (Auth::attempt($request->only('email', 'password'))) {
//         $user = Auth::user();
//         $token = $user->createToken('API Token')->plainTextToken;

//         return response()->json(['token' => $token]);
//     }

//     return response()->json(['error' => 'Unauthorized'], 401);  

// });



