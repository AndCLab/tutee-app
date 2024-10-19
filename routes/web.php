<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified', 'check.is_stepper'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'check.is_stepper'])
    ->name('profile');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
