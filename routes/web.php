<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiSelect;

Route::view('/', 'welcome');

Route::get('/dates', [ApiSelect::class, 'getDate'])->name('dates');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'check.is_stepper'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'check.is_stepper'])
    ->name('profile');

require __DIR__.'/auth.php';
