<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('residents')->with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');

        return view('admin.permissions', compact('roles', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        // SA role is protected — cannot be modified
        if ($role->name === 'SA') {
            return back()->with('error', 'Super Admin permissions cannot be modified.');
        }

        $validated = $request->validate([
            'permissions'   => ['present', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role->permissions()->sync($validated['permissions']);

        return back()->with('success', "Permissions for {$role->display_name} updated.");
    }
}
