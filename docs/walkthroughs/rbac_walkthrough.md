# RBAC Implementation — Walkthrough

## What Was Built

The RESIDENTE platform now has a complete **Role-Based Access Control (RBAC)** system for all 17 LGU municipal departments.

---

## Phase 1: Foundation ✅

| File | What It Does |
|------|-------------|
| `database/migrations/2026_03_09_051025_add_department_role_to_residents_table.php` | Adds `department_role` (VARCHAR 10) and `department_permissions` (JSON) to the `residents` table |
| `config/department_permissions.php` | Maps all 17 role codes to their modules, access level, label, and department |
| `app/Http/Middleware/DepartmentRole.php` | Enforces per-department access. SuperAdmin bypasses all checks |
| `bootstrap/app.php` | Registers the `department` middleware alias; updates redirect logic for department staff |
| `app/Models/Resident.php` | Added `isDepartmentStaff()`, `hasDepartmentAccess()`, `getDepartmentLabelAttribute()`, `getDepartmentConfig()`, `isDepartmentReadOnly()` |

---

## Phase 2: Department Portal ✅

| File | What It Does |
|------|-------------|
| `resources/views/layouts/department.blade.php` | Dynamic sidebar — shows only the modules the user is permitted to access |
| `app/Http/Controllers/Department/DepartmentDashboardController.php` | Loads role-specific stats and passes them to the view |
| `resources/views/department/dashboard.blade.php` | Role-aware dashboard with welcome banner, stats cards, and module quick links |
| `routes/web.php` | `/department/dashboard` + `/department/staff/*` routes with middleware |

---

## Phase 3: HRMO Staff Management ✅

| File | What It Does |
|------|-------------|
| `app/Http/Controllers/Department/StaffManagementController.php` | HRMO-only: create, assign, and update department roles for LGU staff |
| `database/seeders/DepartmentStaffSeeder.php` | Creates sample accounts for all 17 department roles |

---

## Department Staff Credentials

All 17 accounts seeded. **Password for all: `Dept@2026`**

| Role Code | Office | Email |
|-----------|--------|-------|
| `MAYOR`  | Municipal Mayor | `mayor@buguey.gov.ph` |
| `VMYOR`  | Vice Mayor | `vmyor@buguey.gov.ph` |
| `MPDC`   | MPDC | `mpdc@buguey.gov.ph` |
| `ENGR`   | Municipal Engineer | `engr@buguey.gov.ph` |
| `ASSOR`  | Municipal Assessor | `assessor@buguey.gov.ph` |
| `TRESR`  | Municipal Treasurer | `treasurer@buguey.gov.ph` |
| `ACCT`   | Municipal Accountant | `accountant@buguey.gov.ph` |
| `BUDGT`  | Municipal Budget Officer | `budget@buguey.gov.ph` |
| `MSWDO`  | MSWDO | `mswdo@buguey.gov.ph` |
| `MHO`    | Municipal Health Officer | `mho@buguey.gov.ph` |
| `DRRMO`  | DRRMO | `drrmo@buguey.gov.ph` |
| `AGRI`   | Municipal Agriculturist | `agri@buguey.gov.ph` |
| `BPLO`   | BPLO | `bplo@buguey.gov.ph` |
| `REGST`  | Civil Registrar | `registrar@buguey.gov.ph` |
| `SEPD`   | SEPD OIC | `sepd@buguey.gov.ph` |
| `SBSEC`  | SB Secretary | `sbsec@buguey.gov.ph` |
| `HRMO`   | HRMO | `hrmo@buguey.gov.ph` |

---

## How It Works

1. User logs in → middleware checks `department_role` on `residents` table
2. If `department_role` is set → redirected to `/department/dashboard`
3. Dashboard dynamically shows only their allowed modules
4. Sidebar only renders links the user is permitted to access
5. Routes are protected by `->middleware('department:ROLE1,ROLE2')`

> [!TIP]
> SuperAdmin (`SA` role) bypasses all department middleware checks and can access all routes.
