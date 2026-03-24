<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

/**
 * StaffManagementController
 *
 * HRMO-only: Create, assign, and manage LGU department staff accounts.
 */
class StaffManagementController extends Controller
{
    private array $departmentRoles;

    public function __construct()
    {
        $this->departmentRoles = config('department_permissions', []);
    }

    /**
     * List all department staff accounts.
     */
    public function index()
    {
        $staff = Resident::whereNotNull('department_role')
            ->orderBy('department_role')
            ->get();

        $roles = $this->departmentRoles;

        return view('department.staff.index', compact('staff', 'roles'));
    }

    /**
     * Show form to create a new department staff account.
     */
    public function create()
    {
        $roles = $this->departmentRoles;
        return view('department.staff.create', compact('roles'));
    }

    /**
     * Store a new department staff account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email|unique:residents,email',
            'department_role' => 'required|string|in:' . implode(',', array_keys($this->departmentRoles)),
            'password'        => 'required|string|min:8|confirmed',
        ]);

        $resident = Resident::create([
            'first_name'      => $validated['first_name'],
            'last_name'       => $validated['last_name'],
            'middle_name'     => $request->middle_name,
            'email'           => $validated['email'],
            'password'        => $validated['password'],
            'role'            => 'admin',
            'department_role' => $validated['department_role'],
            'is_verified'     => true,
            'date_of_birth'   => $request->date_of_birth ?? '1980-01-01',
            'place_of_birth'  => 'Buguey, Cagayan',
            'gender'          => $request->gender ?? 'Male',
            'civil_status'    => $request->civil_status ?? 'Single',
            'purok'           => '1',
            'barangay'        => 'Centro',
            'municipality'    => 'Buguey',
            'province'        => 'Cagayan',
        ]);

        return redirect()->route('department.staff.index')
            ->with('success', "Staff account created for {$resident->full_name} ({$validated['department_role']}).");
    }

    /**
     * Show form to edit a department staff account's role.
     */
    public function edit(Resident $resident)
    {
        $roles = $this->departmentRoles;
        return view('department.staff.edit', compact('resident', 'roles'));
    }

    /**
     * Update a department staff member's role.
     */
    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'department_role' => 'nullable|string|in:' . implode(',', array_keys($this->departmentRoles)),
        ]);

        $resident->update([
            'department_role' => $validated['department_role'] ?: null,
        ]);

        return redirect()->route('department.staff.index')
            ->with('success', "Role updated for {$resident->full_name}.");
    }
}
