<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Already verified — send to PhilSys if needed, else dashboard
            if (!$request->user()->philsys_verified_at) {
                return redirect()->route('verification.philsys');
            }
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // After verifying email, send directly to PhilSys verification
        if (!$request->user()->philsys_verified_at) {
            return redirect()->route('verification.philsys')
                ->with('success', 'Email verified successfully! Now complete your residency verification.');
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
