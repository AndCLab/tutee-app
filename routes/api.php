<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiSelect;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('phone-prefix', [ApiSelect::class, 'country_details'])->name('phone-prefix');

// DON'T MIND THIS. FUTURE REFERENCE LANG :)
// Route::get('dates', [ApiSelect::class, '__invoke'])->name('dates');
