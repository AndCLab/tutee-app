<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth', 'check.is_stepper')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    Volt::route('stepper', 'pages.stepper.initial')->name('stepper');
    Volt::route('stepper/tutee', 'pages.stepper.tutee.main')->name('stepper.tutee');
    Volt::route('stepper/tutor', 'pages.stepper.tutor.main')->name('stepper.tutor');

    Route::middleware('checkUserType:tutee')->group(function () {
        // Add tutee routes here
    });

    Route::middleware('checkUserType:tutor')->group(function () {
        // Add tutor routes here
    });

    Route::view('/forbidden-access', 'forbidden-page');
});
