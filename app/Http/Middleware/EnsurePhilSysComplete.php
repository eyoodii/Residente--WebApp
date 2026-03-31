<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhilSysComplete
{
    /**
     * Ensure that email-verified visitors complete PhilSys verification
     * before accessing any other page. Once they reach this step,
     * they cannot navigate away (except logout).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $resident = $request->user();

        if (!$resident) {
            return $next($request);
        }

        // Only applies to visitors/citizens who have verified email but NOT PhilSys
        // Admins, SA, and department staff are exempt
        if ($resident->isSuperAdmin() || $resident->isAdmin() || $resident->isDepartmentStaff()) {
            return $next($request);
        }

        // If email is verified but PhilSys is NOT verified, lock them in
        if ($resident->hasVerifiedEmail() && !$resident->philsys_verified_at) {
            // Allow access to PhilSys verification routes
            if ($request->routeIs('verification.*')) {
                return $next($request);
            }

            // Allow logout
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // Allow keep-alive (CSRF refresh)
            if ($request->routeIs('keep-alive')) {
                return $next($request);
            }

            // Block everything else — redirect to PhilSys verification
            return redirect()->route('verification.philsys');
        }

        return $next($request);
    }
}
