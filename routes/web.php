<?php

declare(strict_types=1);

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ideas');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    Route::post('/logout', [SessionsController::class, 'destroy']);

    // Idea CRUD Routes
    Route::controller(IdeaController::class)->group(function () {
        Route::get('/ideas', 'index')->name('idea.index');
        Route::post('/ideas', 'store')->name('idea.store');
        Route::get('/ideas/{idea}', 'show')->name('idea.show');
        Route::patch('/ideas/{idea}', 'update')->name('idea.update');
        Route::delete('/ideas/{idea}', 'destroy')->name('idea.destroy');
    });

    Route::delete('/ideas/{idea}/image', [IdeaImageController::class, 'destroy'])
        ->name('idea.image.destroy');

    Route::patch('/steps/{step}', [StepController::class, 'update'])
        ->name('step.update');

    // Profile Management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
    });
});
