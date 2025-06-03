<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PicsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StoryController;

Route::get('/', function () {
    return view('welcome');
});

// Jetstream dashboard route
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route::resource('categories', CategoryController::class);
    // Route::resource('events', EventController::class);
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // swagger routes
    Route::get('/api/doucumentation', function () {
        return view('swagger');
    })->name('swagger');


    // PicsController routes
    Route::get('/test', function () {
        return app(PicsController::class)->index();
    })->name('test');
    Route::get('/test/create', function () {
        return app(PicsController::class)->create();
    })->name('test.create');
    // Route::post('/test', function () {
    //     return app(PicsController::class)->store(request());
    // })->name('test.store');
    Route::resource('users', UserController::class);
});

// Comments management routes
Route::middleware(['auth'])->group(function () {
    Route::resource('comments', CommentController::class);
    Route::resource('stories', StoryController::class);
});
