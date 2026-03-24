# RESIDENTE — Roles & Accounts Reference

> **Source of truth:** `config/department_permissions.php` · `app/Models/Resident.php`

---

## Account Role Types

Every user record in the `residents` table has a `role` field and an optional `department_role` field.

| `role` value | Who it represents | Can log in? | Notes |
|---|---|---|---|
| `SA` | Super Administrator | ✅ | Bypasses all RBAC checks. Full access to every module. |
| `admin` | Regular administrator / Department staff | ✅ | Must also carry a `department_role` to access department portal. |
| `citizen` | Verified resident | ✅ | Can file e-service requests. Must be verified + email confirmed. |
| `visitor` | Unverified registrant | ✅ (limited) | Cannot file services until verified. |

### Helper methods on `Resident` model

| Method | Returns `true` when… |
|---|---|
| `isSuperAdmin()` | `role === 'SA'` |
| `isAdmin()` | `role === 'admin'` or `role === 'SA'` |
| `isRegularAdmin()` | `role === 'admin'` only |
| `isCitizen()` | `role === 'citizen'` |
| `isVisitor()` | `role === 'visitor'` |
| `isDepartmentStaff()` | `department_role` is not null |
| `isDepartmentReadOnly()` | `access` in their config is `'read_only'` |
| `hasDepartmentAccess($module)` | Module is in their config + SA bypass |
| `getDepartmentConfig()` | Returns merged config array for their role |

---

## Department Role Codes

Department staff are `role = 'admin'` users with an additional `department_role` code. These are the 17 recognised codes:

### Executive & Legislative Oversight

| Code | Full Title | Office | Access Level |
|---|---|---|---|
| `MAYOR` | Municipal Mayor | Office of the Mayor | `read_only` |
| `VMYOR` | Vice Mayor | Office of the Vice Mayor | `read_only` |

**Authorised modules:**
- `MAYOR` — `executive_dashboard`, `analytics`, `master_collections`, `activity_logs`
- `VMYOR` — `analytics`, `master_collections`, `activity_logs`

---

### Planning, Engineering & Development

| Code | Full Title | Office | Access Level |
|---|---|---|---|
| `MPDC` | Municipal Planning & Development Coordinator | MPDO | `write` |
| `ENGR` | Municipal Engineer | Municipal Engineering Office | `write` |
| `ASSOR` | Municipal Assessor | Municipal Assessor's Office | `write` |

**Authorised modules:**
- `MPDC` — `master_collections`, `household_management`, `analytics`, `locational_clearance`
- `ENGR` — `building_permits`, `household_management`, `analytics`
- `ASSOR` — `household_management`, `master_collections`

---

### Financial Management

| Code | Full Title | Office | Access Level |
|---|---|---|---|
| `TRESR` | Municipal Treasurer | Municipal Treasurer's Office | `full` |
| `ACCT` | Municipal Accountant | Municipal Accounting Office | `read_only` |
| `BUDGT` | Municipal Budget Officer | Municipal Budget Office | `read_only` |

**Authorised modules:**
- `TRESR` — `financial_module`, `service_management`, `activity_logs`
- `ACCT` — `financial_module`, `activity_logs`
- `BUDGT` — `financial_module`, `analytics`, `master_collections`

---

### Social Services, Health & Emergency

| Code | Full Title | Office | Access Level |
|---|---|---|---|
| `MSWDO` | Social Welfare & Development Officer | MSWDO | `write` |
| `MHO` | Municipal Health Officer | Municipal Health Office | `full` |
| `DRRMO` | Disaster Risk Reduction & Management Officer | DRRMO | `full` |

**Authorised modules:**
- `MSWDO` — `master_collections`, `household_management`, `analytics`, `welfare_targeting`
- `MHO` — `health_services`, `household_management`, `master_collections`, `service_management`
- `DRRMO` — `emergency_alerts`, `household_management`, `analytics`, `master_collections`

---

### Sector-Specific & Administrative Services

| Code | Full Title | Office | Access Level |
|---|---|---|---|
| `AGRI` | Municipal Agriculturist | Municipal Agriculture Office | `write` |
| `BPLO` | Business Permits & Licensing Officer | BPLO | `full` |
| `REGST` | Municipal Civil Registrar | Office of the Civil Registrar | `full` |
| `SEPD` | Security Enforcement & Prosecution Division OIC | SEPD | `write` |
| `SBSEC` | Sangguniang Bayan Secretary | Office of the Sangguniang Bayan | `full` |

**Authorised modules:**
- `AGRI` — `master_collections`, `analytics`, `livelihood_programs`
- `BPLO` — `business_permits`, `service_management`, `activity_logs`
- `REGST` — `civil_registry`, `service_management`, `master_collections`, `verification_dashboard`
- `SEPD` — `blotter`, `analytics`, `activity_logs`
- `SBSEC` — `transparency_board`, `announcements`

---

### Human Resources

| Code | Full Title | Office | Access Level |
|---|---|---|---|
| `HRMO` | Human Resource Management Officer | HRMO | `full` |

**Authorised modules:** `staff_management`, `role_assignment`, `activity_logs`

---

## Role Cluster → Dashboard View Mapping

The `DepartmentDashboardController` routes each code to a shared cluster view:

| Cluster | Codes | View |
|---|---|---|
| Executive | `MAYOR`, `VMYOR` | `department/roles/executive.blade.php` |
| Planning | `MPDC`, `ENGR`, `ASSOR` | `department/roles/planning.blade.php` |
| Financial | `TRESR`, `ACCT`, `BUDGT` | `department/roles/financial.blade.php` |
| Social | `MSWDO`, `MHO`, `DRRMO` | `department/roles/social.blade.php` |
| Sector | `AGRI`, `BPLO`, `REGST`, `SEPD`, `SBSEC` | `department/roles/sector.blade.php` |
| HRMO | `HRMO` | `department/roles/hrmo.blade.php` |
| _(fallback)_ | No matching code | `department/dashboard.blade.php` |

---

## Access Level Summary

| Level | Create | Read | Update | Delete |
|---|---|---|---|---|
| `read_only` | ❌ | ✅ | ❌ | ❌ |
| `write` | ✅ | ✅ | ✅ | ❌ |
| `full` | ✅ | ✅ | ✅ | ✅ |

> Super Admin (`SA`) always has full access regardless of config.

---

## Middleware

Routes are protected by the `department` middleware alias:

```php
// Allow any department staff (any valid department_role)
->middleware('auth', 'department')

// Restrict to specific roles
->middleware('auth', 'department:MAYOR,VMYOR')

// HRMO-only route
->middleware('auth', 'department:HRMO')
```

The middleware (`app/Http/Middleware/DepartmentRole.php`) enforces:
1. User must be authenticated.
2. Super Admin (`SA`) bypasses all role checks.
3. User must have a `department_role` assigned.
4. If specific roles are listed, user's code must be in that list.

---

## How to Create a Department Staff Account

Department staff accounts are created through the HRMO module:

1. Log in as `HRMO` or `SA`.
2. Navigate to **Staff Management → Create New Staff**.
3. Fill in personal details and assign the appropriate `department_role` code.
4. The account is created with `role = 'admin'`.
5. The staff member logs in at the same URL as other users — the department portal sidebar and dashboard load automatically based on their `department_role`.

---

## Module → Route Mapping

| Module key | Route name | Primary controller |
|---|---|---|
| `executive_dashboard` | `department.analytics.index` | `AnalyticsController` |
| `analytics` | `department.analytics.index` | `AnalyticsController` |
| `master_collections` | `department.master-collections.index` | `MasterCollectionsController` |
| `household_management` | `department.households.index` | `HouseholdManagementController` |
| `activity_logs` | `department.activity-logs.index` | `ActivityLogMonitorController` |
| `financial_module` | `department.finance.index` | `FinancialModuleController` |
| `service_management` | `department.service-requests.index` | `ServiceRequestController` |
| `welfare_targeting` | `department.welfare.index` | `WelfareTargetingController` |
| `health_services` | `department.health.index` | `HealthServicesController` |
| `emergency_alerts` | `department.emergency.index` | `EmergencyAlertController` |
| `locational_clearance` | `department.locational-clearance.index` | `LocationalClearanceController` |
| `building_permits` | `department.building-permits.index` | `BuildingPermitController` |
| `business_permits` | `department.business-permits.index` | `BusinessPermitController` |
| `civil_registry` | `department.civil-registry.index` | `CivilRegistryController` |
| `verification_dashboard` | `department.civil-registry.verification` | `CivilRegistryController` |
| `blotter` | `department.blotter.index` | `BlotterController` |
| `transparency_board` | `department.transparency-board.index` | `TransparencyBoardController` |
| `announcements` | `department.transparency-board.index` | `TransparencyBoardController` |
| `livelihood_programs` | `department.livelihood.index` | `LivelihoodController` |
| `role_assignment` | `department.role-assignment.index` | `RoleAssignmentController` |
| `staff_management` | `department.staff.index` | `StaffManagementController` |
