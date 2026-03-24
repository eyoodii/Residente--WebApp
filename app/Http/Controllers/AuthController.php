<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Http\Requests\RegisterResidentRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display the registration form.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function store(RegisterResidentRequest $request)
    {
        // The request is already validated securely by the time execution reaches here
        $validated = $request->validated();

        $resident = Resident::create([
            // Using strip_tags adds a final layer of defense against cross site scripting
            'first_name' => strip_tags($validated['first_name']),
            'middle_name' => strip_tags($validated['middle_name'] ?? null),
            'last_name' => strip_tags($validated['last_name']),
            'extension_name' => $validated['extension_name'] ?? null,
            'date_of_birth' => $validated['date_of_birth'],
            'barangay' => $validated['barangay'], // User's selected barangay from dropdown
            'barangay_code' => config('barangays.list')[$validated['barangay']] ?? null,
            'household_relationship' => $validated['household_relationship'],
            'postal_code' => $validated['postal_code'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            
            // Physical address details provided at registration
            'purok' => !empty($validated['purok']) ? strip_tags($validated['purok']) : 'Pending Update',
            'house_number' => strip_tags($validated['house_number'] ?? null),
            'street' => strip_tags($validated['street'] ?? null),
            'family_registration_type' => $validated['family_registration_type'] ?? 'new_family',
            
            // Required placeholders from the migration until the user updates their full profile
            'place_of_birth' => 'Pending Update',
            'gender' => 'Other',
            'civil_status' => 'Single',
            
            // Assign 'visitor' role by default until profile is matched with barangay database
            'role' => 'visitor',
            'profile_matched' => false,
        ]);

        // Triggers the email verification process
        event(new Registered($resident));

        // Log the registration activity
        $resident->logActivity(
            'register',
            "New resident account registered: {$resident->full_name}",
            [
                'severity' => 'info',
                'entity_type' => 'Resident',
                'entity_id' => $resident->id,
            ]
        );

        // Send welcome notification
        $resident->createNotification([
            'title' => 'Welcome to Barangay E-Services!',
            'message' => 'Thank you for registering. Please verify your email address to continue. Visit the Barangay Hall with a valid ID to verify your residency and unlock full e-service access.',
            'type' => 'account_update',
            'priority' => 'high',
        ]);

        Auth::login($resident);

        return redirect()->route('dashboard')
            ->with('success', 'Registration successful! Please check your email to verify your account. Visit the Barangay Hall with a valid ID to unlock full e-service access.');
    }

    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication attempt with security measures.
     */
    public function authenticate(Request $request)
    {
        // 1. Validate the incoming request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Attempt authentication and check the "Remember Me" boolean
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // 3. Prevent Session Fixation Attacks by regenerating the session ID
            $request->session()->regenerate();

            // Log successful login
            $resident = Auth::user();
            $resident->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'failed_login_attempts' => 0,
            ]);

            $resident->logActivity(
                'login',
                "User logged in successfully",
                [
                    'severity' => 'info',
                    'ip_address' => $request->ip(),
                ]
            );

            // Redirect based on user role — department staff check MUST come before isAdmin()
            // because department staff have role='admin' + department_role set, so isAdmin() is also true.
            if ($resident->isDepartmentStaff()) {
                return redirect()->intended(route('department.dashboard'));
            }

            if ($resident->isSuperAdmin() || $resident->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            // Redirect to dashboard (or wherever they were trying to go)
            return redirect()->intended('dashboard');
        }

        // Track failed login attempts
        $resident = Resident::where('email', $credentials['email'])->first();
        if ($resident) {
            $resident->increment('failed_login_attempts');
            
            // Lock account after 5 failed attempts for 15 minutes
            if ($resident->failed_login_attempts >= 5) {
                $resident->update(['locked_until' => now()->addMinutes(15)]);
            }
        }

        // 4. Generic Error Message (Prevents Username Enumeration)
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout with complete session cleanup.
     */
    public function logout(Request $request)
    {
        // Log the logout activity
        if (Auth::check()) {
            Auth::user()->logActivity(
                'logout',
                "User logged out",
                [
                    'severity' => 'info',
                    'ip_address' => $request->ip(),
                ]
            );
        }

        Auth::logout();

        // Invalidate the session and regenerate the CSRF token for complete detachment
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
