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
                return redirect()->route('blocked');
            }
        }

        return $next($request);
    }
}
