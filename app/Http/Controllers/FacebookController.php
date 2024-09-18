<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

// class FacebookController extends Controller
// {
//     public function redirectToFacebook()
//     {
//         return Socialite::driver('facebook')->redirect();
//     }

//     public function handleFacebookCallback()
//     {

//         try {
//             $facebookUser = Socialite::driver('facebook')->stateless()->user();
//         } catch (\Exception $e) {
//             // Handle the error when user cancels the login or there's a problem
//             return redirect()->route('login')->withErrors('Facebook login was canceled or failed.');
//         }

//         $user = User::where('email', $facebookUser->email)->first();

//         if ($user) {
//             // Existing user
//             Auth::login($user);
//             return redirect()->route('dashboard');
//         } else {
//             // New user
//             $user = User::create([
//                 'fname' => $facebookUser->user['first_name'] ?? null,
//                 'lname' => $facebookUser->user['last_name'] ?? null,
//                 'email' => $facebookUser->email,
//                 'facebook_id' => $facebookUser->id,
//                 'password' => Hash::make('random_password'),
//             ]);

//             Auth::login($user);
//             return redirect()->route('stepper');
//         }
//     }
// }


class FacebookController extends Controller
{
    // Redirect to Facebook's OAuth page (opens in popup)
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Handle the Facebook OAuth callback (called when the login is successful or canceled)
    public function handleFacebookCallback()
    {
        try {
            // Get user data from Facebook
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            // Check if the email is provided (if the login was successful)
            if (!$facebookUser->email) {
                return redirect()->route('facebook.cancel');
            }

            // Check if the user exists in the database
            $user = User::where('email', $facebookUser->email)->first();

            if ($user) {
                // Log in the existing user
                Auth::login($user);
            } else {
                // Create a new user with the Facebook data
                $user = User::create([
                    'fname' => $facebookUser->user['first_name'] ?? null,
                    'lname' => $facebookUser->user['last_name'] ?? null,
                    'email' => $facebookUser->email,
                    'facebook_id' => $facebookUser->id,
                    'password' => Hash::make('random_password'),
                ]);
                Auth::login($user);
            }

            // Close the popup and redirect the user to the dashboard or appropriate page
            return view('layouts.facebook.facebook_success');
        } catch (\Exception $e) {
            // If there's an error (or the user cancels), redirect to cancel view
            return redirect()->route('facebook.cancel');
        }
    }
}
