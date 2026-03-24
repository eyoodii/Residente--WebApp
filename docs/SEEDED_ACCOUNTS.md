# RESIDENTE — Seeded Test Accounts

> ⚠️ **For development and testing only. Change all passwords before deploying to production.**

---

## How to Seed

```bash
# Seed all at once
php artisan db:seed

# Seed individual groups
php artisan db:seed --class=SuperAdminSeeder
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=DepartmentStaffSeeder
```

---

## Super Admin

**Seeder:** `database/seeders/SuperAdminSeeder.php`

| Field | Value |
|---|---|
| Email | `superadmin@buguey.gov.ph` |
| Password | `SuperAdmin@2026` |
| Role | `SA` |
| Bypasses all RBAC | ✅ Yes |

---

## General Admin

**Seeder:** `database/seeders/AdminUserSeeder.php`

| Name | Email | Password | Role | Verified |
|---|---|---|---|---|
| Admin User | `admin@buguey.gov.ph` | `Admin@2026` | `admin` | ✅ |

---

## Department Staff Accounts

**Seeder:** `database/seeders/DepartmentStaffSeeder.php`  
All accounts: `role = admin` · password: **`Dept@2026`**

### Executive & Legislative

| Code | Name | Email | Access |
|---|---|---|---|
| `MAYOR` | Eduardo G. Reyes | `mayor@buguey.gov.ph` | read_only |
| `VMYOR` | Roberto G. Santos | `vmyor@buguey.gov.ph` | read_only |

### Planning, Engineering & Development

| Code | Name | Email | Access |
|---|---|---|---|
| `MPDC` | Lourdes G. Dela Cruz | `mpdc@buguey.gov.ph` | write |
| `ENGR` | Manuel G. Garcia | `engr@buguey.gov.ph` | write |
| `ASSOR` | Cynthia G. Lopez | `assessor@buguey.gov.ph` | write |

### Financial Management

| Code | Name | Email | Access |
|---|---|---|---|
| `TRESR` | Maricel G. Tanaka | `treasurer@buguey.gov.ph` | full |
| `ACCT` | Antonio G. Villanueva | `accountant@buguey.gov.ph` | read_only |
| `BUDGT` | Gloria G. Macaraeg | `budget@buguey.gov.ph` | read_only |

### Social Services, Health & Emergency

| Code | Name | Email | Access |
|---|---|---|---|
| `MSWDO` | Teresita G. Bautista | `mswdo@buguey.gov.ph` | write |
| `MHO` | Rodrigo G. Aquino | `mho@buguey.gov.ph` | full |
| `DRRMO` | Vicente G. Magsaysay | `drrmo@buguey.gov.ph` | full |

### Sector & Administrative Services

| Code | Name | Email | Access |
|---|---|---|----- |
| `AGRI` | Celestino G. Pascual | `agri@buguey.gov.ph` | write |
| `BPLO` | Josefina G. Hernandez | `bplo@buguey.gov.ph` | full |
| `REGST` | Amado G. Ocampo | `registrar@buguey.gov.ph` | full |
| `SEPD` | Ernesto G. Policarpio | `sepd@buguey.gov.ph` | write |
| `SBSEC` | Milagros G. Evangelista | `sbsec@buguey.gov.ph` | full |
| `HRMO` | Remedios G. Castillo | `hrmo@buguey.gov.ph` | full |

### Sangguniang Bayan (SB) Committee Chairs

| Code | Name | Email | Access |
|---|---|---|---|
| `SBFIN` | Alfredo G. Domingo | `sbfin@buguey.gov.ph` | read_only |
| `SBHLT` | Corazon G. Mercado | `sbhlt@buguey.gov.ph` | read_only |
| `SBWMN` | Estrella G. Ramos | `sbwmn@buguey.gov.ph` | read_only |
| `SBRLS` | Bonifacio G. Aguilar | `sbrls@buguey.gov.ph` | read_only |
| `SBPIC` | Rosario G. Navarro | `sbpic@buguey.gov.ph` | read_only |
| `SBTSP` | Victorino G. Salazar | `sbtsp@buguey.gov.ph` | read_only |
| `SBPWK` | Renato G. Medina | `sbpwk@buguey.gov.ph` | read_only |
| `SBAGR` | Conrado G. Magno | `sbagr@buguey.gov.ph` | read_only |
| `SBBGA` | Felicitas G. Valdez | `sbbga@buguey.gov.ph` | read_only |

### SK Federation

| Code | Name | Email | Access |
|---|---|---|---|
| `SKPRS` | Joshua G. Dela Torre | `skpres@buguey.gov.ph` | read_only |

### Sector-Specific & Administrative Services

| Code | Name | Email | Access |
|---|---|---|---|
| `AGRI` | Celestino G. Pascual | `agri@buguey.gov.ph` | write |
| `BPLO` | Josefina G. Hernandez | `bplo@buguey.gov.ph` | full |
| `REGST` | Amado G. Ocampo | `registrar@buguey.gov.ph` | full |
| `SEPD` | Ernesto G. Policarpio | `sepd@buguey.gov.ph` | write |
| `SBSEC` | Milagros G. Evangelista | `sbsec@buguey.gov.ph` | full |

### Human Resources

| Code | Name | Email | Access |
|---|---|---|---|
| `HRMO` | Remedios G. Castillo | `hrmo@buguey.gov.ph` | full |

---

## Login URL

All accounts log in at the same URL:

```
http://localhost/login
```

The system automatically routes each user to the correct portal based on their `role` and `department_role`.

| Role | Redirects to |
|---|---|
| `SA` / `admin` (no dept role) | Admin dashboard |
| `admin` + `department_role` | Department portal (`/department/dashboard`) |
| `citizen` | Citizen dashboard |
| `visitor` | Onboarding / profile completion |

---

## Password Policy Reminder

> Before going live, run the following for each account or use the HRMO **Role Assignment** module to reset passwords individually.

```bash
php artisan tinker
# Example: change MAYOR password
App\Models\Resident::where('email','mayor@buguey.gov.ph')->first()->update(['password' => 'NewSecurePassword!']);
```
