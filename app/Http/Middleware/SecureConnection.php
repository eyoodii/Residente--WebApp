<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Secure Connection Middleware
 * 
 * Enforces HTTPS connections and adds comprehensive security headers
 * to protect against common web vulnerabilities:
 * - Forces HTTPS in production
 * - Strict Transport Security (HSTS)
 * - Content Security Policy (CSP)
 * - XSS Protection
 * - Frame Protection (Clickjacking)
 * - Content Type Sniffing Protection
 * - Referrer Policy
 */
class SecureConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS in production environment
        if (app()->environment('production') && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        $response = $next($request);

        // Add comprehensive security headers
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        
        // Content Security Policy - Allow only trusted sources
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com data:; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self';"
        );
        
        // Prevent XSS attacks
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Referrer Policy - Control information sent with referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy - Control browser features
        $response->headers->set('Permissions-Policy', 
            'camera=(), microphone=(), geolocation=(self), payment=()'
        );

        return $response;
    }
}
