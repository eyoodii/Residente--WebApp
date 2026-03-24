<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Apply secure connection middleware globally
        $middleware->append(\App\Http\Middleware\SecureConnection::class);
        
        // Register middleware aliases
        $middleware->alias([
            'role'             => \App\Http\Middleware\CheckRole::class,
            'citizen'          => \App\Http\Middleware\CheckCitizenAccess::class,
            'lockout'          => \App\Http\Middleware\PreventAccountLockout::class,
            'log.activity'     => \App\Http\Middleware\LogActivity::class,
            'philsys.verified' => \App\Http\Middleware\EnsurePhilSysVerified::class,
            'onboarding.complete' => \App\Http\Middleware\EnsureOnboardingComplete::class,
            'department'       => \App\Http\Middleware\DepartmentRole::class,
            'permission'       => \App\Http\Middleware\CheckPermission::class,
        ]);

        // Secure redirects for authenticated users hitting guest pages (like login/register)
        $middleware->redirectUsersTo(function (Request $request) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && $user->isSuperAdmin()) {
                return route('admin.dashboard');
            }
            if ($user && $user->isDepartmentStaff()) {
                return route('department.dashboard');
            }
            if ($user && $user->isAdmin()) {
                return route('admin.dashboard');
            }
            return route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF Token Mismatch (419 Page Expired)
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session has expired. Please refresh the page.',
                    'error' => 'token_mismatch',
                    'redirect' => route('login'),
                ], 419);
            }

            return redirect()
                ->back()
                ->withInput($request->except('_token', 'password', 'password_confirmation'))
                ->with('error', 'Your session expired. Please try again.');
        });

        // Handle 403 Forbidden with custom message
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 403) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'You do not have permission to access this resource.',
                        'error' => 'forbidden',
                    ], 403);
                }
            }
            
            return null; // Let Laravel handle other HTTP exceptions
        });
    })->create();
