<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * Handle an incoming request.
     * 
     * Ensures residents complete their socio-economic profiling
     * before accessing protected features.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $resident = $request->user();
        
        // Skip if not authenticated or not a resident
        if (!$resident) {
            return $next($request);
        }
        
        // If PhilSys is verified but onboarding is not complete
        // This is the "In Progress" state - must complete onboarding first
        if ($resident->philsys_verified_at && !$resident->is_onboarding_complete) {
            
            // Allow access to onboarding routes
            if ($request->routeIs('profile.onboarding.*')) {
                return $next($request);
            }
            
            // Allow access to verification routes
            if ($request->routeIs('verification.*')) {
                return $next($request);
            }
            
            // Allow access to logout
            if ($request->routeIs('logout')) {
                return $next($request);
            }
            
            // Log the redirect attempt
            Log::info('Resident redirected to onboarding', [
                'resident_id' => $resident->id,
                'attempted_route' => $request->route()->getName(),
            ]);
            
            // Otherwise, lock them into the "In Progress" state
            return redirect()->route('profile.onboarding.show')
                ->with('toast_warning', 'Please complete your profile to access this feature.');
        }
        
        return $next($request);
    }
}
