<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockedUser
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

            // check if the user is in the blacklist
            $isBlocked = $user->blacklist()->blocked()->exists();

            // if the user is blocked, redirect them to the blocked route
            if ($isBlocked) {
                // Only redirect if the user is not already on the blocked route
                if (!$request->routeIs('blocked')) {
                    return redirect()->route('blocked');
                }
            } else {
                if ($request->routeIs('blocked')) {
                    $targetRoute = $user->user_type === 'tutee' ? 'tutee.discover' : 'tutor.discover';
                    return redirect()->route($targetRoute);
                }
            }
        }

        return $next($request);
    }
}
