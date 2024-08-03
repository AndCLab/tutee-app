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
            $isStepperRoute = $request->route()->named('stepper')
                || $request->route()->named('stepper.tutee')
                || $request->route()->named('stepper.tutor');

            if ($user->is_stepper == 1 && !$isStepperRoute) {
                return redirect()->route('stepper');
            }

            if ($user->is_stepper == 0 && $isStepperRoute) {
                return $user->user_type == 'tutee' ? redirect()->route('tutee.discover') : redirect()->route('tutor.discover');
            }
        }

        return $next($request);
    }
}
