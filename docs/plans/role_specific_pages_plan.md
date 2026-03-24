# Role-Specific Department Pages — Implementation Plan

## Goal

Build a dedicated, data-driven dashboard page for each LGU department role. Since 17 individual views would be excessive, roles are grouped into **6 functional clusters** sharing a view template but with dynamically-rendered sections per role.

## Role → View Mapping

| Cluster | Roles | View |
|---------|-------|------|
| **Executive** | MAYOR, VMYOR | `department/roles/executive.blade.php` |
| **Planning & Engineering** | MPDC, ENGR, ASSOR | `department/roles/planning.blade.php` |
| **Financial** | TRESR, ACCT, BUDGT | `department/roles/financial.blade.php` |
| **Social & Emergency** | MSWDO, MHO, DRRMO | `department/roles/social.blade.php` |
| **Sector & Licensing** | AGRI, BPLO, REGST, SEPD, SBSEC | `department/roles/sector.blade.php` |
| **HRMO** | HRMO | `department/roles/hrmo.blade.php` |

---

## Proposed Changes

### Controller Updates

#### [MODIFY] `DepartmentDashboardController.php`

Update `index()` to route each `department_role` to the correct cluster view and pass the right data.

---

### New Views (6 blade files)

#### [NEW] `department/roles/executive.blade.php`
- KPI summary cards: total residents, verified count, household count, services filed
- Municipality health bar (verified %)
- Read-only activity log (last 10 entries)
- Barangay-level population breakdown chart (HTML/CSS bar chart)
- ⚠️ MAYOR: full view. VMYOR: same but labeled "Legislative Analytics"

#### [NEW] `department/roles/planning.blade.php`
- Household profiling stats: house materials breakdown (Type A/B/C), water source distribution
- Flood-prone household count + affected barangays list
- MPDC sees: demographic projections + locational clearance placeholder
- ENGR sees: flood-prone households table with address/purok filter
- ASSOR sees: house type cross-reference table (residential vs. material type)

#### [NEW] `department/roles/financial.blade.php`
- Service request stats: pending, completed, total fees collected (from `service_requests` table)
- TRESR: revenue by service type breakdown table
- ACCT: audit log view (last 20 activity log entries related to payments)
- BUDGT: service utilization graph + resident count per barangay for budget forecasting

#### [NEW] `department/roles/social.blade.php`
- MSWDO: vulnerable sector filter table — shows Senior Citizens, PWDs, Solo Parents, IP by barangay
- MHO: `sanitary_toilet` and `water_source` health risk table + service requests for health certificates
- DRRMO: flood-prone households table + emergency alert broadcast form (writes to `announcements` table)

#### [NEW] `department/roles/sector.blade.php`
- AGRI: crops, aquaculture, livestock, fisheries breakdown per resident (from profiling fields)
- BPLO: business permit service requests (filtered by type)
- REGST: civil service requests table + verification status dashboard
- SEPD: activity logs filtered by `action = 'blotter'` or security-related events
- SBSEC: announcements management (list + quick publish form)

#### [NEW] `department/roles/hrmo.blade.php`
- Full staff roster: all residents with `department_role IS NOT NULL`
- Assign/Edit Role inline form
- Quick create new staff account form
- Role coverage matrix: shows which roles are filled and which are vacant

---

## Verification Plan

### Manual Verification
- Log in as each role and confirm correct cluster view loads
- Verify data is role-appropriate (e.g., DRRMO sees flood-prone data, AGRI sees farming data)
- Confirm read-only roles (MAYOR, VMYOR, ACCT, BUDGT) cannot submit any forms

### Automated Route Check
```bash
php artisan route:list --path=department
```
