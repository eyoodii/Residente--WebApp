<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCitizenAccess
{
    /**
     * Handle an incoming request.
     * Ensures only verified citizens can access e-services
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user can access services
        if (!$user->canAccessServices()) {
            if ($user->isVisitor()) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'You need to verify your residency to access e-services. Please visit the Barangay Hall for verification.');
            }

            if (!$user->hasVerifiedEmail()) {
                return redirect()
                    ->route('verification.notice')
                    ->with('error', 'Please verify your email address first.');
            }

            return redirect()
                ->route('dashboard')
                ->with('error', 'Your account is not yet verified. Please contact the Barangay Hall.');
        }

        return $next($request);
    }
}
