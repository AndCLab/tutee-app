<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStepper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect to the stepper route if the user is a stepper and not already on the stepper route
            if ($user->is_stepper == 1 && !$request->route()->named('stepper')) {
                return redirect()->route('stepper');
            }

            // If the user is not a stepper and tries to access the stepper route, redirect to the dashboard
            if ($user->is_stepper == 0 && $request->route()->named('stepper')) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
