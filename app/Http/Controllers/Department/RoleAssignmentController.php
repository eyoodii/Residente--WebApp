<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

/**
 * RoleAssignmentController
 *
 * HRMO: Assign and revoke department roles for LGU staff accounts.
 * Extends StaffManagementController's account creation with role management.
 */
class RoleAssignmentController extends Controller
{
    private array $departmentRoles;

    public function __construct()
    {
        $this->departmentRoles = config('department_permissions', []);
    }

    /**
     * Show the role assignment matrix — all staff and their current roles.
     */
    public function index()
    {
        $user      = auth()->user();
        $allStaff  = Resident::whereNotNull('department_role')->orderBy('department_role')->get();
        $allRoles  = $this->departmentRoles;
        $filledRoles = $allStaff->pluck('department_role')->unique()->toArray();
        $vacantRoles = array_diff(array_keys($allRoles), $filledRoles);

        // Unassigned admin accounts (role='admin' but no department_role)
        $unassigned = Resident::where('role', 'admin')
            ->whereNull('department_role')
            ->get();

        return view('department.role-assignment.index', [
            'user'      => $user,
            'assigned'  => $allStaff,
            'residents' => $unassigned,
            'roles'     => $allRoles,
        ]);
    }

    /**
     * Assign a department role to a resident/staff account.
     */
    public function assign(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'department_role' => 'required|string|in:' . implode(',', array_keys($this->departmentRoles)),
        ]);

        $resident->update(['department_role' => $validated['department_role']]);

        return back()->with('success', "{$resident->full_name} has been assigned the {$validated['department_role']} role.");
    }

    /**
     * Revoke a department role from a staff account.
     */
    public function revoke(Resident $resident)
    {
        $oldRole = $resident->department_role;
        $resident->update(['department_role' => null]);

        return back()->with('success', "Department role ({$oldRole}) revoked from {$resident->full_name}.");
    }
}
