# Triple-Key Resident Profile System

## Overview

This document describes the implementation of the Triple-Key Resident Profile System for the LGU (Local Government Unit) Barangay E-Services platform. The system treats each resident profile as a "node" within a hierarchical network structure, enabling multidimensional data fetching and intelligent family-based organization.

## Architecture: The Three Keys

### 1. Geographic Key (HN - Household Number)

**Purpose**: Identifies the physical address/house structure.

**Data Point**: Physical Address (house number, street, purok, barangay)

**System Action**: When a resident enters their address, the system either:
- Finds an existing HN if the address is already registered
- Creates a new HN (format: `HN-YYYY-NNNN`, e.g., `HN-2026-0001`)

**Use Case**: Clusters all residents living at the same physical structure, regardless of family ties.

**Database Model**: `App\Models\Household`

### 2. Administrative Key (HHN - Household Head Number)

**Purpose**: Defines the "Family Unit" for resource distribution and census reporting.

**Data Point**: Family Role (Head or Member)

**System Actions**:
- **If Head**: Creates a new HHN linked to the HN (format: `HHN-NN`, e.g., `HHN-01`)
- **If Member**: Searches for surname within that HN to find an existing Head

**Use Case**: Multiple families can live at the same HN (physical address) but each maintains their own HHN (family unit).

**Database Model**: `App\Models\HouseholdHead`

### 3. Identity Key (HHM - Household Member)

**Purpose**: Ensures the resident is searchable as an individual while remaining anchored to their family.

**Data Point**: Surname + Individual ID

**System Action**: Uses Surname Recognition Logic to auto-fill the "Relationship to Head" field.

**Format**: `HHM-NNN` (e.g., `HHM-001`)

**Database Model**: `App\Models\HouseholdMember` (for unregistered members) or `App\Models\Resident` (for registered users)

---

## Profile Creation Workflow

### Step-by-Step Registration Flow

| Step | Section | Field Name | Logic Connection |
|------|---------|------------|------------------|
| 1 | Location | Home Address | Generates/Assigns the HN |
| 2 | Role | Are you the Head? | If 'Yes', creates HHN. If 'No', triggers Surname search |
| 3 | Identity | Last Name | Automatically matches resident to existing HHN in that HN |
| 4 | Details | Age, Work, Health | Personal data stored at the HHM level |

### Auto-Recognition Workflow Example

**Resident Input**: "My name is Maria Santos, living at 456 Bonifacio St."

1. **System Check (HN)**: Finds that 456 Bonifacio St. is `HN-202`
2. **System Check (HHN)**: Scans `HN-202` for any Head with surname "Santos"
3. **Auto-Match**: Finds Roberto Santos (`HHN-05`)
4. **Profile Finalization**: Maria's profile is saved as:
   - Resident ID: `HHM-Maria-01`
   - Linked To: `HHN-05` (Roberto)
   - Location: `HN-202` (456 Bonifacio St.)

---

## Conflict Resolution Logic

### Validation Flowchart

When a resident creates their profile, the system navigates:

1. **Input Phase**: User provides Address and Surname
2. **Location Check**: System identifies the HN for that address
3. **Surname Scan**: `SELECT * FROM household_heads WHERE household_id = [HN_ID] AND surname = [User_Surname]`
4. **Branching Logic**:

#### Case A: No Match Found
- System asks if user is the Household Head
- If yes, creates new HHN

#### Case B: One Match Found
- System asks: "We found a household headed by [Head Name]. Are you a member of this household?"
- User confirms or declines

#### Case C: Multiple Matches Found
- System displays list of all Heads with that surname at that address
- User selects: "Which of these individuals is your Household Head?"

### Practical Example (Multiple Same-Surname Families)

**Address**: 789 Quezon St. (`HN-789`)

**Existing Family**: Head: Ramon Cruz (`HHN-A`)

**New Family Moves In**: Head: Santi Cruz (`HHN-B`)

**New Member Registers**: Liza Cruz at 789 Quezon St.

**System Action**: Shows both Ramon and Santi Cruz as options

**Result**: Liza selects Santi, registered as HHM under `HHN-B`

### The "Strict Link" Rule

- **Rule**: Once a member confirms their Head, their profile is permanently tagged with that specific `HHN_ID`
- **Outcome**: Even if another family with the same surname moves to the same address later, existing members remain correctly grouped because their `HHN_ID` is unique

---

## LGU Secretary Features

### Multidimensional Fetch Commands

| Fetch Type | Shows |
|------------|-------|
| By Address (HN) | Map/list of all households at an address |
| By HN | All families (HHNs) under one roof |
| By HHN | Complete "Family Tree" (HHMs) of that household |
| By HHM | Individual's history and current living situation |

### Verification Dashboard

Located at: `/admin/verification`

**Features**:

1. **Ghost Member Detection**: Identifies residents with verified accounts but incomplete household linkage
2. **Multi-Family Address Monitoring**: Highlights addresses with multiple families (potential for confusion)
3. **Cross-Verification Table**: Compare multiple families with same surname at same address
4. **Manual Override**: Transfer members between HHNs while keeping HN constant

### Manual Override Workflow

| Scenario | Secretary Action | System Requirement |
|----------|-----------------|-------------------|
| Two "Santos" Families | Secretary fetches HN-101 | Shows HHN-01 (Jose Santos) and HHN-02 (Pedro Santos) separately |
| Verification of Members | Secretary clicks HHN-01 | Only members linked to Jose (via his unique HHN) are shown |
| Manual Override | Secretary identifies mistake | Can manually move an HHM from one HHN to another while keeping HN constant |

---

## Implementation Files

### Controllers

- `App\Http\Controllers\ResidentProfileController` - Step-by-step profile setup wizard
- `App\Http\Controllers\Admin\HouseholdController` - Drill-down search system
- `App\Http\Controllers\Admin\VerificationDashboardController` - Secretary verification tools

### Service

- `App\Services\HouseholdLinkingService` - Core linking logic and conflict resolution

### Models

- `App\Models\Household` - Physical address (HN)
- `App\Models\HouseholdHead` - Family unit (HHN)
- `App\Models\HouseholdMember` - Individual member (HHM)
- `App\Models\Resident` - Registered user with Triple-Key links

### Views

- `resources/views/profile/setup/step1-location.blade.php` - HN assignment
- `resources/views/profile/setup/step2-role.blade.php` - HHN selection with conflict resolution
- `resources/views/profile/setup/step3-identity.blade.php` - Relationship confirmation
- `resources/views/profile/setup/step4-details.blade.php` - Personal data entry
- `resources/views/admin/households/verification/dashboard.blade.php` - Secretary dashboard

### Routes

```php
// Profile Setup Routes
Route::prefix('profile/setup')->name('profile.setup.')->group(function () {
    Route::get('/', [ResidentProfileController::class, 'showSetup'])->name('index');
    Route::post('/location', [ResidentProfileController::class, 'storeLocation'])->name('location');
    Route::post('/role', [ResidentProfileController::class, 'storeRole'])->name('role');
    Route::post('/identity', [ResidentProfileController::class, 'storeIdentity'])->name('identity');
    Route::post('/details', [ResidentProfileController::class, 'storeDetails'])->name('details');
    Route::post('/check-address', [ResidentProfileController::class, 'checkAddress'])->name('check-address');
    Route::post('/search-heads', [ResidentProfileController::class, 'searchMatchingHeads'])->name('search-heads');
});

// Admin Verification Routes
Route::prefix('verification')->name('verification.')->group(function () {
    Route::get('/', [VerificationDashboardController::class, 'index'])->name('dashboard');
    Route::get('/household/{household}', [VerificationDashboardController::class, 'verifyHousehold'])->name('household');
    Route::get('/family/{householdHead}', [VerificationDashboardController::class, 'verifyFamily'])->name('family');
    Route::post('/transfer-member', [VerificationDashboardController::class, 'transferMember'])->name('transfer-member');
});
```

---

## Database Schema

### households (HN)
```sql
- id
- household_number (unique, format: HN-YYYY-NNNN)
- house_number
- street
- purok
- barangay
- municipality
- province
- full_address
- is_active
- timestamps
```

### household_heads (HHN)
```sql
- id
- household_id (FK -> households)
- resident_id (FK -> residents)
- household_head_number (unique within household)
- surname
- family_size
- is_primary_family
- is_active
- timestamps
```

### residents (with Triple-Key links)
```sql
- id
- household_id (FK -> households, nullable)
- household_head_id (FK -> household_heads, nullable)
- is_household_head (boolean)
- household_relationship (string)
- ... other fields
```

---

## Benefits of This Architecture

1. **Multidimensional Data Access**: Secretary can fetch data at any level of the hierarchy
2. **Conflict Prevention**: Unique HHN_IDs prevent accidental associations between same-surname families
3. **Data Integrity**: Strict Link rule ensures family groupings persist over time
4. **Smart Registration**: Auto-recognition reduces data entry and errors
5. **Scalable Census**: Easy aggregation at household, family, or individual level
6. **Aid Distribution**: Clear family unit tracking for benefits and programs
