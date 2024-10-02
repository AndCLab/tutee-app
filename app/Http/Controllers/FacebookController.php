<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {

        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Exception $e) {
            // Handle the error when user cancels the login or there's a problem
            return redirect()->route('login')->withErrors('Facebook login was canceled or failed.');
        }

        $user = User::where('email', $facebookUser->email)->first();

        if ($user) {
            // Existing user
            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            // New user
            $user = User::create([
                'fname' => $facebookUser->user['first_name'] ?? null,
                'lname' => $facebookUser->user['last_name'] ?? null,
                'email' => $facebookUser->email,
                'facebook_id' => $facebookUser->id,
                'password' => Hash::make('random_password'),
            ]);

            Auth::login($user);
            return redirect()->route('stepper');
        }
    }
}
