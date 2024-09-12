<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            // Existing user
            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            // New user
            $user = User::create([
                'fname' => $googleUser->user['given_name'] ?? null,
                'lname' => $googleUser->user['family_name'] ?? null,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make('random_password'),
            ]);

            Auth::login($user);
            return redirect()->route('stepper');
        }
    }
}
