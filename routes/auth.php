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

Route::middleware('auth', 'check.is_stepper', 'verified')->group(function () {
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

    // Add tutee routes here
    Route::middleware('checkUserType:tutee')->group(function () {
        Volt::route('tutee/discover', 'pages.tutee.discover')->name('tutee.discover');
        Volt::route('tutors', 'pages.tutee.tutors')->name('tutors');
        Volt::route('tutee/schedule', 'pages.tutee.schedule')->name('tutee.schedule');
        Volt::route('tutee/edit-post/{id}', 'pages.tutee.post_components.edit_post_form')->name('edit-post');
    });

    // Add tutor routes here
    Route::middleware('checkUserType:tutor')->group(function () {
        Volt::route('tutor/discover', 'pages.tutor.discover')->name('tutor.discover');
        Volt::route('classes', 'pages.tutor.classes')->name('classes');
        Volt::route('tutor/schedule', 'pages.tutor.schedule.schedule')->name('tutor.schedule');
        Volt::route('tutor/view-students/{id}', 'pages.tutor.schedule.view-students')->name('view-students');
        Volt::route('tutor/edit-class/{id}', 'pages.tutor.classes_components.edit_class_form')->name('edit-class');
    });

    Route::middleware('checkIsApplied')->group(function () {
        Volt::route('stepper/be-a-tutee', 'pages.stepper.tutee.main')->name('stepper.be-a-tutee');
        Volt::route('stepper/apply-as-tutor', 'pages.stepper.tutor.main')->name('stepper.apply-as-tutor');
    });

    Route::view('/forbidden-access', 'forbidden-page');
});


