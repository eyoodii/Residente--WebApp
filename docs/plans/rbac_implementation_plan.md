# RBAC Architecture — Implementation Plan

## Overview

Comprehensive Role-Based Access Control (RBAC) for 17 LGU municipal departments in the RESIDENTE platform.

## Design Approach

Department staff use `role = 'admin'` (existing) + a new `department_role` column for RBAC. This avoids breaking existing middleware while enabling fine-grained per-department access control.

## Department Role Codes

| Code | Office | Access |
|------|--------|--------|
| `MAYOR`  | Municipal Mayor | Read-Only |
| `VMYOR`  | Vice Mayor | Read-Only |
| `MPDC`   | Municipal Planning & Development Coordinator | Write |
| `ENGR`   | Municipal Engineer | Write |
| `ASSOR`  | Municipal Assessor | Write |
| `TRESR`  | Municipal Treasurer | Full |
| `ACCT`   | Municipal Accountant | Read-Only |
| `BUDGT`  | Municipal Budget Officer | Read-Only |
| `MSWDO`  | Social Welfare & Development Officer | Write |
| `MHO`    | Municipal Health Officer | Full |
| `DRRMO`  | Disaster Risk Reduction & Management Officer | Full |
| `AGRI`   | Municipal Agriculturist | Write |
| `BPLO`   | Business Permits & Licensing Officer | Full |
| `REGST`  | Municipal Civil Registrar | Full |
| `SEPD`   | Security Enforcement & Prosecution Division OIC | Write |
| `SBSEC`  | Sangguniang Bayan Secretary | Full |
| `HRMO`   | Human Resource Management Officer | Full |

## Files Created

### Database
- `database/migrations/2026_03_09_051025_add_department_role_to_residents_table.php`
- `database/seeders/DepartmentStaffSeeder.php`

### Config
- `config/department_permissions.php` — Maps all 17 roles to modules and access levels

### Middleware
- `app/Http/Middleware/DepartmentRole.php` — Registered as `department` alias

### Models
- `app/Models/Resident.php` — Added helpers: `isDepartmentStaff()`, `hasDepartmentAccess()`, `getDepartmentLabel`, etc.

### Controllers
- `app/Http/Controllers/Department/DepartmentDashboardController.php`
- `app/Http/Controllers/Department/StaffManagementController.php`

### Views
- `resources/views/layouts/department.blade.php` — Dynamic sidebar layout
- `resources/views/department/roles/executive.blade.php` — MAYOR, VMYOR
- `resources/views/department/roles/planning.blade.php` — MPDC, ENGR, ASSOR
- `resources/views/department/roles/financial.blade.php` — TRESR, ACCT, BUDGT
- `resources/views/department/roles/social.blade.php` — MSWDO, MHO, DRRMO
- `resources/views/department/roles/sector.blade.php` — AGRI, BPLO, REGST, SEPD, SBSEC
- `resources/views/department/roles/hrmo.blade.php` — HRMO
- `resources/views/department/staff/index.blade.php`
- `resources/views/department/staff/create.blade.php`
- `resources/views/department/staff/edit.blade.php`

### Routes
```
GET  /department/dashboard    → DepartmentDashboardController@index
GET  /department/staff        → StaffManagementController@index (HRMO only)
GET  /department/staff/create → StaffManagementController@create
POST /department/staff        → StaffManagementController@store
GET  /department/staff/{id}/edit → StaffManagementController@edit
PATCH /department/staff/{id}  → StaffManagementController@update
```
