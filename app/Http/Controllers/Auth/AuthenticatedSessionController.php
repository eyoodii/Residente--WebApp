<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Check if account is locked
        if ($user->isLocked()) {
            $minutes = $user->locked_until->diffInMinutes(now());
            Auth::logout();
            
            return back()->withErrors([
                'email' => "Your account is temporarily locked due to multiple failed login attempts. Please try again in {$minutes} minutes.",
            ]);
        }

        // Reset login attempts and update last login info
        $user->resetLoginAttempts();

        // Log successful login
        $user->logActivity(
            'login',
            "{$user->full_name} logged in successfully",
            [
                'severity' => 'info',
            ]
        );

        $request->session()->regenerate();

        // Redirect based on user role — department staff check MUST come before isAdmin()
        // because department staff have role='admin' + department_role set, so isAdmin() is also true.
        if ($user->isDepartmentStaff()) {
            return redirect()->intended(route('department.dashboard', absolute: false));
        }

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // If email is verified but PhilSys is not, go straight to PhilSys verification
        if ($user->hasVerifiedEmail() && !$user->philsys_verified_at) {
            return redirect()->route('verification.philsys');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Log logout activity before destroying session
        if ($user) {
            $user->logActivity(
                'logout',
                "{$user->full_name} logged out",
                [
                    'severity' => 'info',
                ]
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
