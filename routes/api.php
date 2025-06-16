<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\UserController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Event routes


Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // User management routes
    Route::apiResource('users', UserController::class);

    Route::apiResource('events', EventController::class);
    Route::apiResource('categories', CategoryController::class);

    // Stories routes
    Route::apiResource('stories', StoryController::class);
    Route::get('users/{user}/stories', [StoryController::class, 'getStoriesByUser']);

    // Comments routes
    Route::apiResource('comments', CommentController::class)->only(['index', 'show']);
    Route::get('users/{user}/comments', [CommentController::class, 'getCommentsByUser']);
    Route::post('stories/{story}/comments', [CommentController::class, 'store']);


    // Story edit/delete endpoints by owner or admin
    Route::put('/stories/{story}', [StoryController::class, 'update']);
    Route::delete('/stories/{story}', [StoryController::class, 'destroy']);

    // Update and delete a comment (owner or admin)
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Update user info (user or admin)
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::post('/update-profile-photo', [UserController::class, 'updateProfilePhoto']);
});


