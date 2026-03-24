<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentManagementController extends Controller
{
    /**
     * Display list of residents for admin management
     */
    public function index(Request $request)
    {
        $query = Resident::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by verification status (boolean: 1 = verified, 0 = unverified)
        if ($request->has('verified') && $request->verified !== '') {
            $query->where('is_verified', (bool) $request->verified);
        }

        // Filter by profile matched status
        if ($request->has('profile_matched') && $request->profile_matched !== '') {
            $query->where('profile_matched', (bool) $request->profile_matched);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $residents = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.residents.index', compact('residents'));
    }

    /**
     * Show resident details
     */
    public function show(Resident $resident)
    {
        $resident->load([
            'serviceRequests.service',
            'householdProfile',
            'householdMembers',
            'activityLogs' => function($query) {
                $query->latest()->take(20);
            }
        ]);
        
        return view('admin.residents.show', compact('resident'));
    }

    /**
     * Show profile verification form
     */
    public function verifyForm(Resident $resident)
    {
        return view('admin.residents.verify', compact('resident'));
    }

    /**
     * Verify a resident's profile and upgrade role to citizen
     */
    public function verify(Request $request, Resident $resident)
    {
        $admin = Auth::user();
        
        $validated = $request->validate([
            'verification_method' => ['required', 'string', 'in:manual,auto,biometric'],
            'notes' => ['nullable', 'string'],
        ]);
        
        $resident->update([
            'profile_matched' => true,
            'profile_matched_at' => now(),
            'verification_method' => $validated['verification_method'],
            'role' => 'citizen', // Upgrade from visitor to citizen
            'is_verified' => true,
        ]);
        
        // Log the verification activity
        ActivityLog::log([
            'resident_id' => $admin->id,
            'user_email' => $admin->email,
            'user_role' => $admin->role,
            'action' => 'verify_resident',
            'description' => "{$admin->full_name} verified resident: {$resident->full_name}",
            'entity_type' => 'Resident',
            'entity_id' => $resident->id,
            'severity' => 'critical',
            'metadata' => [
                'verification_method' => $validated['verification_method'],
                'notes' => $validated['notes'] ?? null,
            ],
        ]);
        
        // Notify the resident
        $resident->createNotification([
            'title' => 'Profile Verified!',
            'message' => 'Congratulations! Your profile has been verified. You can now access all barangay e-services.',
            'type' => 'account_update',
            'action_url' => route('services.index'),
            'action_label' => 'Browse Services',
            'priority' => 'high',
        ]);
        
        return redirect()
            ->route('admin.residents.show', $resident)
            ->with('success', "Resident {$resident->full_name} has been verified and upgraded to Citizen role.");
    }

    /**
     * Revoke citizen status
     */
    public function revoke(Request $request, Resident $resident)
    {
        $admin = Auth::user();
        
        $validated = $request->validate([
            'reason' => ['required', 'string'],
        ]);
        
        $resident->update([
            'role' => 'visitor',
            'is_verified' => false,
        ]);
        
        // Log the revocation
        ActivityLog::log([
            'resident_id' => $admin->id,
            'user_email' => $admin->email,
            'user_role' => $admin->role,
            'action' => 'revoke_citizen',
            'description' => "{$admin->full_name} revoked citizen status: {$resident->full_name}",
            'entity_type' => 'Resident',
            'entity_id' => $resident->id,
            'severity' => 'critical',
            'metadata' => [
                'reason' => $validated['reason'],
            ],
        ]);
        
        // Notify the resident
        $resident->createNotification([
            'title' => 'Account Status Changed',
            'message' => 'Your citizen status has been revoked. Please contact the Barangay Hall for more information.',
            'type' => 'account_update',
            'priority' => 'urgent',
        ]);
        
        return redirect()
            ->route('admin.residents.show', $resident)
            ->with('success', "Citizen status revoked for {$resident->full_name}.");
    }

    /**
     * Promote to admin role
     */
    public function promoteToAdmin(Resident $resident)
    {
        $admin = Auth::user();
        
        $resident->update([
            'role' => 'admin',
        ]);
        
        // Log the promotion
        ActivityLog::log([
            'resident_id' => $admin->id,
            'user_email' => $admin->email,
            'user_role' => $admin->role,
            'action' => 'promote_admin',
            'description' => "{$admin->full_name} promoted {$resident->full_name} to Admin",
            'entity_type' => 'Resident',
            'entity_id' => $resident->id,
            'severity' => 'critical',
        ]);
        
        // Notify the resident
        $resident->createNotification([
            'title' => 'Promoted to Administrator',
            'message' => 'You have been promoted to Administrator. You now have access to admin features.',
            'type' => 'account_update',
            'priority' => 'high',
        ]);
        
        return redirect()
            ->route('admin.residents.show', $resident)
            ->with('success', "{$resident->full_name} has been promoted to Admin.");
    }

    /**
     * Unlock a locked account
     */
    public function unlock(Resident $resident)
    {
        $admin = Auth::user();
        
        $resident->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
        
        // Log the unlock
        ActivityLog::log([
            'resident_id' => $admin->id,
            'user_email' => $admin->email,
            'user_role' => $admin->role,
            'action' => 'unlock_account',
            'description' => "{$admin->full_name} unlocked account for {$resident->full_name}",
            'entity_type' => 'Resident',
            'entity_id' => $resident->id,
            'severity' => 'warning',
        ]);
        
        return redirect()
            ->route('admin.residents.show', $resident)
            ->with('success', "Account unlocked for {$resident->full_name}.");
    }
    /**
     * Delete a resident account permanently
     */
    public function destroy(Resident $resident)
    {
        $admin = Auth::user();

        // Prevent deleting Super Admins
        if ($resident->role === 'SA') {
            return redirect()
                ->route('admin.residents.index')
                ->with('error', 'Super Admin accounts cannot be deleted.');
        }

        // Prevent self-deletion
        if ($resident->id === $admin->id) {
            return redirect()
                ->route('admin.residents.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $residentName = $resident->full_name;

        // Log before deleting
        ActivityLog::log([
            'resident_id' => $admin->id,
            'user_email'  => $admin->email,
            'user_role'   => $admin->role,
            'action'      => 'delete_resident',
            'description' => "{$admin->full_name} permanently deleted resident account: {$residentName}",
            'entity_type' => 'Resident',
            'entity_id'   => $resident->id,
            'severity'    => 'critical',
        ]);

        $resident->delete();

        return redirect()
            ->route('admin.residents.index')
            ->with('success', "Resident '{$residentName}' has been permanently deleted.");
    }

    /**
     * Securely serve a private PhilSys card image (Super Admin only)
     */
    public function servePhilSysImage(Resident $resident, string $side)
    {
        $admin = Auth::user();

        // Double-check: only SA may access
        if ($admin->role !== 'SA') {
            abort(403, 'Access restricted to Super Admin only.');
        }

        if (!in_array($side, ['front', 'back'])) {
            abort(404);
        }

        $column = $side === 'front' ? 'philsys_id_front' : 'philsys_id_back';
        $path = $resident->$column;

        if (!$path || !\Storage::disk('private')->exists($path)) {
            abort(404, 'PhilSys ID image not found.');
        }

        // Log access
        ActivityLog::log([
            'resident_id' => $admin->id,
            'user_email'  => $admin->email,
            'user_role'   => $admin->role,
            'action'      => 'view_philsys_id',
            'description' => "{$admin->full_name} viewed PhilSys ID ({$side}) for {$resident->full_name}",
            'entity_type' => 'Resident',
            'entity_id'   => $resident->id,
            'severity'    => 'critical',
        ]);

        return \Storage::disk('private')->response($path);
    }
}
