<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified', 'check.is_stepper'])
//     ->name('dashboard');
use App\Http\Controllers\ApiSelect;
use Laravel\Socialite\Facades\Socialite;
// use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\FacebookController;
// Route for the homepage
Route::view('/', 'welcome');

Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/call-back', [FacebookController::class, 'handleFacebookCallback']);
Route::view('auth/facebook/cancel', 'layouts.facebook.facebook_cancel')->name('facebook.cancel');

// Route for handling date selection
Route::get('/dates', [ApiSelect::class, 'getDate'])->name('dates');


// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/call-back', [GoogleController::class, 'handleGoogleCallback']);
Route::view('auth/google/cancel', 'layouts.google.google_cancel')->name('google.cancel');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'check.is_stepper'])
    ->name('dashboard');

// Middleware group for routes that require authentication and stepper check
Route::middleware(['auth', 'verified', 'check.is_stepper'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
});

// Include the default authentication routes provided by Laravel
require __DIR__.'/auth.php';
