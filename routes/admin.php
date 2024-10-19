<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// admin routes
Route::middleware('guest:admin')->group(function () {
    Volt::route('admin/login', 'pages.admin.login')
        ->name('login.admin');
});

Route::middleware('auth:admin')->group(function () {
    Volt::route('admin/verification-requests', 'pages.admin.contents.verification_requests')->name('verify-request.admin');
    Volt::route('admin/user-management', 'pages.admin.contents.user_management')->name('user-manage.admin');
    Volt::route('admin/content-moderation', 'pages.admin.contents.content_moderation')->name('content-moderate.admin');

    Volt::route('admin/profile', 'pages.admin.profile')->name('profile.admin');
});
