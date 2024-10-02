<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirect to Google's OAuth page
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle the Google OAuth callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if the email is provided (if the login was successful)
            if (!$googleUser->email) {
                return redirect()->route('google.cancel');
                // Render a view for canceled login
            }

            // Check if the user exists
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Log in the existing user
                Auth::login($user);
            } else {
                // Create a new user
                $user = User::create([
                    'fname' => $googleUser->user['given_name'] ?? null,
                    'lname' => $googleUser->user['family_name'] ?? null,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make('random_password'),
                ]);
                Auth::login($user);
            }

            // Redirect to the success page to handle closing the modal and redirection
            return view('layouts.google.google_success');

        } catch (\Exception $e) {
            // Handle failure or canceled login
            return redirect()->route('google.cancel');
            // Render a view for canceled login
        }
    }
}


