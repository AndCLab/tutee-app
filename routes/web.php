<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified', 'check.is_stepper'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'check.is_stepper'])
    ->name('profile');

// // In web.php or routes file
// Route::get('/blocked', function () {
//     return view('blocked'); // Ensure you have a blocked.blade.php view
// })->name('blocked');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
