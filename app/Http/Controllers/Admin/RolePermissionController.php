<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartmentRoleModule;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /** All portal modules with their labels and icons (for the UI). */
    private const MODULES = [
        'executive_dashboard' => ['label' => 'Executive Dashboard', 'icon' => '📊'],
        'analytics'           => ['label' => 'Analytics',           'icon' => '📈'],
        'master_collections'  => ['label' => 'Master Collections',  'icon' => '📋'],
        'activity_logs'       => ['label' => 'Activity Logs',       'icon' => '📝'],
        'household_management'=> ['label' => 'Household Management','icon' => '🏠'],
        'locational_clearance'=> ['label' => 'Locational Clearance','icon' => '📍'],
        'building_permits'    => ['label' => 'Building Permits',    'icon' => '🏗️'],
        'business_permits'    => ['label' => 'Business Permits',    'icon' => '🏪'],
        'financial_module'    => ['label' => 'Financial Module',    'icon' => '💰'],
        'civil_registry'      => ['label' => 'Civil Registry',      'icon' => '📜'],
        'emergency_alerts'    => ['label' => 'Emergency Alerts',    'icon' => '🚨'],
        'welfare_targeting'   => ['label' => 'Welfare Targeting',   'icon' => '🤝'],
        'health_services'     => ['label' => 'Health Services',     'icon' => '🏥'],
        'livelihood_programs' => ['label' => 'Livelihood Programs', 'icon' => '🌾'],
        'service_management'  => ['label' => 'Service Management',  'icon' => '⚙️'],
        'staff_management'    => ['label' => 'Staff Management',    'icon' => '👤'],
        'role_assignment'     => ['label' => 'Role Assignment',     'icon' => '🔑'],
        'transparency_board'  => ['label' => 'Transparency Board',  'icon' => '🔍'],
        'announcements'       => ['label' => 'Announcements',       'icon' => '📢'],
        'blotter'             => ['label' => 'Blotter',             'icon' => '📁'],
        'verification_dashboard' => ['label' => 'Verification Dashboard', 'icon' => '✅'],
    ];

    /** Office role groups for sidebar organisation. */
    private const DEPT_GROUPS = [
        ['label' => 'Executive & Legislative', 'icon' => '🏛️', 'roles' => ['MAYOR', 'VMYOR']],
        ['label' => 'Planning & Engineering',  'icon' => '📐', 'roles' => ['MPDC', 'ENGR', 'ASSOR']],
        ['label' => 'Financial Management',    'icon' => '💰', 'roles' => ['TRESR', 'ACCT', 'BUDGT']],
        ['label' => 'Social & Health',         'icon' => '🏥', 'roles' => ['MSWDO', 'MHO', 'DRRMO']],
        ['label' => 'Sector Services',         'icon' => '🏢', 'roles' => ['AGRI', 'BPLO', 'REGST', 'SEPD', 'SBSEC', 'HRMO']],
        ['label' => 'Sangguniang Bayan',       'icon' => '📜', 'roles' => ['SBFIN', 'SBHLT', 'SBWMN', 'SBRLS', 'SBPIC', 'SBTSP', 'SBPWK', 'SBAGR', 'SBBGA']],
        ['label' => 'SK Federation',           'icon' => '🌟', 'roles' => ['SKPRS']],
    ];

    public function index()
    {
        $roles = Role::withCount('residents')->with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');

        $deptConfig = config('department_permissions', []);
        $deptModules = DepartmentRoleModule::all()->groupBy('department_role');
        $allModules  = self::MODULES;
        $deptGroups  = self::DEPT_GROUPS;

        return view('admin.permissions', compact(
            'roles', 'permissions',
            'deptConfig', 'deptModules', 'allModules', 'deptGroups'
        ));
    }

    /** Update system role permissions (admin, citizen, visitor, etc.). */
    public function update(Request $request, Role $role)
    {
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

    /** Update which portal modules a department office role can access. */
    public function updateDepartment(Request $request, string $departmentRole)
    {
        if (!config('department_permissions.' . $departmentRole)) {
            abort(404);
        }

        $validated = $request->validate([
            'modules'        => ['present', 'array'],
            'modules.*'      => ['string', 'in:' . implode(',', array_keys(self::MODULES))],
            'access_level'   => ['required', 'in:read_only,write,full'],
        ]);

        // Wipe existing entries then re-insert selected ones
        DepartmentRoleModule::where('department_role', $departmentRole)->delete();

        foreach ($validated['modules'] as $module) {
            DepartmentRoleModule::create([
                'department_role' => $departmentRole,
                'module'          => $module,
                'access_level'    => $validated['access_level'],
            ]);
        }

        $label = config("department_permissions.{$departmentRole}.label", $departmentRole);

        return back()->with('success', "{$label} portal access updated.");
    }

    /** Create a new custom system role. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:32', 'unique:roles,name', 'regex:/^[a-z0-9_]+$/'],
            'display_name' => ['required', 'string', 'max:100'],
            'color'        => ['required', 'string', 'max:20'],
        ]);

        Role::create($validated);

        return back()->with('success', "Role \"{$validated['display_name']}\" created.");
    }

    /** Update a role's display name and color (slug is immutable). */
    public function updateMeta(Request $request, Role $role)
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'color'        => ['required', 'string', 'max:20'],
        ]);

        $role->update($validated);

        return back()->with('success', "\"" . $role->display_name . "\" updated.");
    }

    /** Delete a custom role (built-in roles are protected). */
    public function destroy(Role $role)
    {
        $protected = ['SA', 'admin', 'citizen', 'visitor'];

        if (in_array($role->name, $protected)) {
            return back()->with('error', "Cannot delete built-in role \"{$role->display_name}\".");
        }

        if ($role->residents()->exists()) {
            return back()->with('error', "Cannot delete \"{$role->display_name}\" — it still has active users assigned.");
        }

        $roleName = $role->display_name;
        $role->permissions()->detach();
        $role->delete();

        return back()->with('success', "Role \"{$roleName}\" deleted.");
    }
}
