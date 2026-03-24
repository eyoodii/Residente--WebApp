<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request and log the activity
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users
        if ($request->user()) {
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Log the activity
     */
    protected function logActivity(Request $request): void
    {
        $user = $request->user();
        $action = $this->determineAction($request);

        // Skip logging for certain routes to avoid clutter
        if ($this->shouldSkipLogging($request, $action)) {
            return;
        }

        ActivityLog::log([
            'resident_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'action' => $action,
            'description' => $this->generateDescription($request, $action),
            'severity' => $this->determineSeverity($action),
            'metadata' => [
                'route' => $request->route()?->getName(),
                'parameters' => $request->route()?->parameters(),
            ],
        ]);
    }

    /**
     * Determine the action from the request
     */
    protected function determineAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()?->getName() ?? 'unknown';

        if (str_contains($routeName, 'login')) return 'login';
        if (str_contains($routeName, 'logout')) return 'logout';
        if (str_contains($routeName, 'register')) return 'register';
        if (str_contains($routeName, 'service')) return 'service_access';
        if (str_contains($routeName, 'profile')) return 'profile_access';

        return match($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'view',
        };
    }

    /**
     * Generate a description for the activity
     */
    protected function generateDescription(Request $request, string $action): string
    {
        $routeName = $request->route()?->getName() ?? 'unknown page';
        $user = $request->user();

        return match($action) {
            'login' => "{$user->full_name} logged in",
            'logout' => "{$user->full_name} logged out",
            'register' => "{$user->full_name} registered a new account",
            'service_access' => "{$user->full_name} accessed service: {$routeName}",
            'profile_access' => "{$user->full_name} accessed profile page",
            default => "{$user->full_name} performed {$action} on {$routeName}",
        };
    }

    /**
     * Determine the severity level
     */
    protected function determineSeverity(string $action): string
    {
        return match($action) {
            'delete' => 'critical',
            'update', 'create' => 'warning',
            default => 'info',
        };
    }

    /**
     * Check if logging should be skipped
     */
    protected function shouldSkipLogging(Request $request, string $action): bool
    {
        $routeName = $request->route()?->getName() ?? '';

        // Skip logging for dashboard views and other high-frequency actions
        $skipRoutes = [
            'dashboard',
            'api.',
            'sanctum.',
            '_ignition',
        ];

        foreach ($skipRoutes as $skip) {
            if (str_contains($routeName, $skip)) {
                return true;
            }
        }

        return false;
    }
}
