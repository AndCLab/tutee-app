<?php

// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use Laravel\Socialite\Facades\Socialite;

// class GoogleController extends Controller
// {
//     // Redirect to Google's OAuth page
//     public function redirectToGoogle()
//     {
//         return Socialite::driver('google')->redirect();
//     }

//     // Handle the Google OAuth callback
//     public function handleGoogleCallback()
//     {
//         try {
//             $googleUser = Socialite::driver('google')->stateless()->user();

//             // Check if the email is provided (if the login was successful)
//             if (!$googleUser->email) {
//                 return redirect()->route('google.cancel');
//                 // Render a view for canceled login
//             }

//             // Check if the user exists
//             $user = User::where('email', $googleUser->email)->first();

//             if ($user) {
//                 // Log in the existing user
//                 Auth::login($user);
//             } else {
//                 // Create a new user
//                 $user = User::create([
//                     'fname' => $googleUser->user['given_name'] ?? null,
//                     'lname' => $googleUser->user['family_name'] ?? null,
//                     'email' => $googleUser->email,
//                     'google_id' => $googleUser->id,
//                     'password' => Hash::make('random_password'),
//                 ]);
//                 Auth::login($user);
//             }

//             // Redirect to the success page to handle closing the modal and redirection
//             return view('layouts.google.google_success');

//         } catch (\Exception $e) {
//             // Handle failure or canceled login
//             return redirect()->route('google.cancel');
//             // Render a view for canceled login
//         }
//     }
// }


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

        try{
            if ($user) {
                // Existing user
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {

                $user = User::create([
                    'fname' => $googleUser->user['given_name'] ?? '',
                    'lname' => $googleUser->user['family_name'] ?? '',
                    'name' => $googleUser->name ?? '',  // Full name if available
                    'email' => $googleUser->email,
                    'address' => $googleUser->user['locale'] ?? '',
                    'zip_code' => $googleUser->user['locale'] ?? '',
                    'phone_prefix' => '', // Google API doesn’t usually return this, can be collected later
                    'phone_number' => '', // Phone number is generally not included in Google OAuth
                    // 'is_stepper' => true, // Defaulting to 1 (true), but you can adjust
                    // 'apply_status' => 'not_applied', // Default status
                    // 'user_type' => 'tutee', // You can adjust based on your logic
                    'avatar' => $googleUser->avatar ?? null, // If available from the Google user
                    'email_verified_at' => now(), // Assuming Google-verified email
                    'password' => Hash::make('random_password'), // Random password since they login via Google
                    'google_id' => $googleUser->id,
                ]);
                
                

                Auth::login($user);
                return redirect()->route('stepper');
            }
        }catch (\Exception $e) {
            // Handle failure or canceled login
            \Log::error('Google OAuth error: ' . $e->getMessage());


            return redirect()->route('google.cancel');
            // Render a view for canceled login
        }

    }
}