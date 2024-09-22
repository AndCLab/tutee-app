<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckIsApplied
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
            $isStepperRoute = $request->route()->named('stepper.be-a-tutee') || $request->route()->named('stepper.apply-as-tutor');

            if ($user->is_stepper == 0) {
                if ($user->apply_status == 'applied' && !$isStepperRoute) {
                    return redirect()->route('stepper');
                }

                if ($user->apply_status == 'not_applied' && $isStepperRoute) {
                    return $user->user_type == 'tutee' ? redirect()->route('tutee.discover') : redirect()->route('tutor.discover');
                }
            }
        }
        return $next($request);
    }
}


