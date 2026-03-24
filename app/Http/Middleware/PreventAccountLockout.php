<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAccountLockout
{
    /**
     * Handle an incoming request.
     * Prevents locked accounts from accessing the system
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isLocked()) {
            $minutes = $user->locked_until->diffInMinutes(now());
            
            auth()->logout();
            
            return redirect()
                ->route('login')
                ->with('error', "Your account is temporarily locked due to multiple failed login attempts. Please try again in {$minutes} minutes.");
        }

        return $next($request);
    }
}
