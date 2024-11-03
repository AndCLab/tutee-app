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
            $role = $user->user_type;
            $applyStatus = $user->apply_status;

             $route = $request->route();
            
            //$isStepperRoute = $request->route()->named('stepper.be-a-tutee') || $request->route()->named('stepper.apply-as-tutor');

             // determine if the current route is related to the stepper process
            $isStepperRoute = $route->named('stepper.apply-as-tutor', 'stepper.be-a-tutee');
            
            // if ($user->is_stepper == 0) {
            //     if ($user->apply_status == 'applied' && !$isStepperRoute) {
            //         return redirect()->route('stepper');
            //     }

            //     if ($user->apply_status == 'not_applied' && $isStepperRoute) {
            //         return $user->user_type == 'tutee' ? redirect()->route('tutee.discover') : redirect()->route('tutor.discover');
            //     }
            // }

            // redirect logic for tutee
            if ($role === 'tutee') {
                if ($applyStatus === 'pending' && !$route->named('stepper.apply-as-tutor')) {
                    return redirect()->route('stepper.apply-as-tutor');
                }
                if ($applyStatus === 'not_applied' && $isStepperRoute) {
                    return redirect()->route('tutee.discover');
                }
                if ($route->named('stepper.be-a-tutee')) {
                    return redirect()->route('tutee.discover');
                }
            }

            // redirect logic for tutor
            if ($role === 'tutor') {
                if ($applyStatus === 'pending' && !$route->named('stepper.be-a-tutee')) {
                    return redirect()->route('stepper.be-a-tutee');
                }
                if ($applyStatus === 'not_applied' && $isStepperRoute) {
                    return redirect()->route('tutor.discover');
                }
                if ($route->named('stepper.apply-as-tutor')) {
                    return redirect()->route('tutor.discover');
                }
            }

            // redirect logic for both roles if the apply status is 'applied'
            if ($applyStatus === 'applied' && $isStepperRoute) {
                return redirect()->route($role === 'tutee' ? 'tutee.discover' : 'tutor.discover');
            }
        }
        
        return $next($request);
    }
}


