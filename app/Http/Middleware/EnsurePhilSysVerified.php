<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhilSysVerified
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that a resident has completed PhilSys verification
     * before accessing E-Services that require official document requests.
     * 
     * Security Level: Layer 3 - PhilSys Identity Verification
     * (After Auth and Email Verification)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $resident = $request->user();

        // Check if the user is logged in AND if their PhilSys verification timestamp is null
        if ($resident && is_null($resident->philsys_verified_at)) {
            
            // If they try to request a document without PhilSys verification, 
            // abort the request and send them back to the dashboard with an error.
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'PhilSys identity verification required.',
                    'message' => 'You must verify your identity via PhilSys before accessing this service.'
                ], 403);
            }

            // Log the unauthorized access attempt
            if (method_exists($resident, 'logActivity')) {
                $resident->logActivity(
                    'unauthorized_access_attempt',
                    'Attempted to access PhilSys-protected resource without verification',
                    [
                        'requested_url' => $request->fullUrl(),
                        'ip_address' => $request->ip(),
                    ]
                );
            }

            return redirect()->route('dashboard')
                ->with('warning', 'You must verify your identity via PhilSys before accessing E-Services.')
                ->with('philsys_required', true);
        }

        // If verified, allow the request to proceed to the controller
        return $next($request);
    }
}
