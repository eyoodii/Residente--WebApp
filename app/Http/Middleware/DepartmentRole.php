<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * DepartmentRole Middleware
 *
 * Enforces access control for LGU department staff.
 * Usage in routes:
 *   ->middleware('department:MAYOR,VMYOR')
 *   ->middleware('department:HRMO')
 */
class DepartmentRole
{
    /**
     * Handle an incoming request.
     *
     * @param  string[]  $allowedRoles  One or more department_role codes allowed (e.g. 'MAYOR', 'HRMO')
     */
    public function handle(Request $request, Closure $next, string ...$allowedRoles): Response
    {
        $user = $request->user();

        // Must be authenticated
        if (!$user) {
            abort(401);
        }

        // Super Admin bypasses all department restrictions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Must have a department role assigned
        if (!$user->department_role) {
            abort(403, 'No department role assigned to your account.');
        }

        // Check if user's department_role is in the allowed list
        if (!empty($allowedRoles) && !in_array($user->department_role, $allowedRoles)) {
            abort(403, 'Access restricted. Your department does not have permission to view this module.');
        }

        return $next($request);
    }
}
