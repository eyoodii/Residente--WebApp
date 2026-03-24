# Database Relationships Documentation

## Overview
This document provides a comprehensive overview of all database tables and their relationships in the ResidenteWebApp system.

---

## Entity Relationship Diagram (ERD)

```
┌─────────────────┐
│   RESIDENTS     │ ◄────┐
│  (Main Entity)  │      │
└─────────────────┘      │
     │  ↓  ↓  ↓          │
     │  │  │  │          │
     │  │  │  └──────────┼─────────────────────────┐
     │  │  │             │                         │
     │  │  └─────────────┼──────────────┐          │
     │  │                │              │          │
     │  └────────────────┼───┐          │          │
     │                   │   │          │          │
     ↓                   ↓   ↓          ↓          ↓
┌──────────┐    ┌──────────────┐  ┌─────────┐  ┌──────────┐
│HOUSEHOLD │    │SERVICE       │  │ACTIVITY │  │HOUSEHOLD │
│PROFILE   │    │REQUESTS      │  │LOGS     │  │MEMBERS   │
│(1:1)     │    │(1:Many)      │  │(1:Many) │  │(1:Many)  │
└──────────┘    └──────────────┘  └─────────┘  └──────────┘
                      │
                      ↓
                ┌──────────┐
                │SERVICES  │
                │(Master)  │
                └──────────┘
                   │    │
                   ↓    ↓
         ┌─────────┐  ┌─────────────┐
         │SERVICE  │  │SERVICE      │
         │STEPS    │  │REQUIREMENTS │
         └─────────┘  └─────────────┘


┌──────────────────────────────────────────────────────────────┐
│           HIERARCHICAL DATA COLLECTION SYSTEM                 │
│  (HN → HHN → HHM: Household Number → Family → Members)       │
└──────────────────────────────────────────────────────────────┘

         ┌─────────────┐
    ┌───│  HOUSEHOLD  │ (HN - Physical Address)
    │   └─────────────┘
    │         │
    │         │ hasMany
    │         ↓
    │   ┌─────────────┐
    ├───│   FAMILIES  │ (HHN - Family Unit within HN)
    │   └─────────────┘
    │         │
    │         │ hasMany
    │         ↓
    │   ┌─────────────┐
    └──►│  RESIDENTS  │ (HHM - Individual Members)
        └─────────────┘
              │
              │ belongsTo
              ↓
        ┌─────────────┐
        │HOUSEHOLD    │ (Optional: Family Leader)
        │HEAD         │
        └─────────────┘


┌──────────────────────────────────────────────────────────────┐
│                    OTHER RELATIONSHIPS                         │
└──────────────────────────────────────────────────────────────┘

RESIDENTS ───1:Many──→ NOTIFICATIONS
RESIDENTS ───M:M────→ ANNOUNCEMENTS (via barangay matching)
RESIDENTS ───1:1────→ SCANNED_DOCUMENTS
```

---

## Core Tables & Relationships

### 1. **RESIDENTS** (Central Hub)
**Primary Table**: All system users (citizens, visitors, admins, SP)

#### **Relationships FROM Residents:**

| Relationship | Type | Target Table | Foreign Key | Description |
|-------------|------|--------------|-------------|-------------|
| `serviceRequests()` | 1:Many | service_requests | resident_id | All service requests by this resident |
| `householdMembers()` | 1:Many | household_members | resident_id | Legacy household member records |
| `householdProfile()` | 1:1 | household_profiles | resident_id | Socio-economic profiling data |
| `household()` | Many:1 | households | household_id | Physical address (HN level) |
| `family()` | Many:1 | families | family_id | Family unit (HHN level) |
| `householdHeadRelation()` | Many:1 | household_heads | household_head_id | If member belongs to a family leader |
| `asHouseholdHead()` | 1:1 | household_heads | resident_id | If this resident IS a family leader |
| `activityLogs()` | 1:Many | activity_logs | resident_id | All activities by this resident |
| `notifications()` | 1:Many | notifications | resident_id | All notifications for this resident |
| `unreadNotifications()` | 1:Many | notifications | resident_id | Unread notifications only |
| `announcements()` | 1:Many | announcements | target_barangay = barangay | Announcements targeting resident's barangay |

#### **Key Fields:**
```sql
id                  - Primary Key
national_id         - PhilSys Number
first_name          - Given name
last_name           - Surname
barangay            - Full barangay name (e.g., "Centro")
barangay_code       - 3-letter code (e.g., "CEN")
role                - ENUM('SP', 'admin', 'citizen', 'visitor')
household_id        - FK to households (HN level)
family_id           - FK to families (HHN level)
household_head_id   - FK to household_heads (family leader)
is_auto_linked      - Boolean: Auto-matched by system
is_verified         - Boolean: Admin verified
```

---

### 2. **HOUSEHOLDS** (HN - Physical Address Level)
**Purpose**: Represents a physical dwelling/address where multiple families may live

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `householdHeads()` | 1:Many | household_heads | household_id | All family leaders at this address (DEPRECATED) |
| `residents()` | 1:Many | residents | household_id | All residents living at this address |
| `families()` | 1:Many | families | household_id | All family units (HHN) at this address |

#### **Key Fields:**
```sql
id                  - Primary Key
household_number    - HN Code (e.g., "HN-2026-001")
full_address        - Complete address string
purok               - Neighborhood/cluster
barangay            - Barangay name
municipality        - Municipality (Buguey)
province            - Province (Cagayan)
```

#### **Example Structure:**
```
Household HN-2026-001 (Centro, Purok 1)
  ├── Family HHN-001 (Dela Cruz Family) → 5 members
  ├── Family HHN-002 (Santos Family) → 3 members
  └── Family HHN-003 (Reyes Family) → 4 members
Total: 3 families, 12 residents
```

---

### 3. **FAMILIES** (HHN - Family Unit Level)
**Purpose**: Represents a family unit within a household (related by surname/kinship)

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `household()` | Many:1 | households | household_id | Physical address this family lives at |
| `members()` | 1:Many | residents | family_id | All residents in this family |
| `householdHead()` | Many:1 | residents | household_head_id | The designated family leader |
| `autoLinkedMembers()` | 1:Many | residents | family_id + is_auto_linked=true | Members auto-matched, need review |
| `verifiedMembers()` | 1:Many | residents | family_id + is_auto_linked=false | Manually confirmed members |

#### **Key Fields:**
```sql
id                  - Primary Key
household_id        - FK to households (parent HN)
hhn_number          - Family Code (e.g., "HHN-001")
head_surname        - Family surname
household_head_id   - FK to residents (family leader)
member_count        - Cached count of members
is_verified         - Boolean: Admin verified
```

#### **Example:**
```
HHN-001 (Dela Cruz Family) in HN-2026-001
  └── Members (5):
      ├── Juan Dela Cruz (Head, household_head_id)
      ├── Maria Dela Cruz (Spouse)
      ├── Pedro Dela Cruz (Child)
      ├── Ana Dela Cruz (Child)
      └── Rosa Dela Cruz (Parent, auto-linked)
```

---

### 4. **HOUSEHOLD_HEADS** (LEGACY - Being Phased Out)
**Purpose**: Old system for tracking family leaders
**Status**: Superseded by `families.household_head_id` pointing to residents

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `household()` | Many:1 | households | household_id | Physical address |
| `resident()` | Many:1 | residents | resident_id | The actual person |
| `members()` | 1:Many | household_members | household_head_id | Legacy members list |
| `linkedResidents()` | 1:Many | residents | household_head_id | Residents linked to this family leader |

**NOTE**: New system uses `families` table with `household_head_id` pointing directly to `residents`.

---

### 5. **HOUSEHOLD_MEMBERS** (LEGACY - Being Phased Out)
**Purpose**: Old many-to-many relationship structure
**Status**: Replaced by direct `residents.family_id` relationship

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `resident()` | Many:1 | residents | resident_id | The actual resident |
| `householdHead()` | Many:1 | household_heads | household_head_id | Family leader |
| `linkedResident()` | Many:1 | residents | linked_resident_id | Alternative link |

---

### 6. **HOUSEHOLD_PROFILES** (Socio-Economic Data)
**Purpose**: One-to-one socio-economic profiling data for residents

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `resident()` | 1:1 | residents | resident_id | The profiled resident |

#### **Key Fields:**
```sql
id                      - Primary Key
resident_id             - FK to residents (UNIQUE)
income_classification   - Economic status
household_size          - Number of members
monthly_income          - Income range
```

---

### 7. **SERVICES** (E-Services Master Table)
**Purpose**: Available government services (Barangay Clearance, Indigency, etc.)

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `steps()` | 1:Many | service_steps | service_id | Process steps for this service |
| `requirements()` | 1:Many | service_requirements | service_id | Required documents |
| `requests()` | 1:Many | service_requests | service_id | All requests for this service |

#### **Key Fields:**
```sql
id                  - Primary Key
service_code        - Unique code (e.g., "BRGY-CLR")
name                - Service name
description         - Details
is_active           - Boolean: Available or not
processing_days     - Estimated turnaround
```

---

### 8. **SERVICE_REQUESTS** (E-Services Transactions)
**Purpose**: Tracks individual service applications by residents

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `resident()` | Many:1 | residents | resident_id | Who requested |
| `service()` | Many:1 | services | service_id | What service |

#### **Key Fields:**
```sql
id                  - Primary Key
resident_id         - FK to residents
service_id          - FK to services
status              - ENUM('pending', 'in-progress', 'completed', 'ready-for-pickup', 'rejected')
request_date        - When submitted
completion_date     - When finished
tracking_number     - Unique reference
```

#### **Status Flow:**
```
pending → in-progress → completed → ready-for-pickup
                    ↘   rejected
```

---

### 9. **SERVICE_STEPS** (Process Workflow)
**Purpose**: Defines the workflow steps for each service

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `service()` | Many:1 | services | service_id | Parent service |

#### **Key Fields:**
```sql
id              - Primary Key
service_id      - FK to services
step_number     - Order (1, 2, 3...)
step_name       - Step title
description     - What happens
responsible     - Who handles it
estimated_time  - Duration
```

---

### 10. **SERVICE_REQUIREMENTS** (Document Checklist)
**Purpose**: Lists required documents for each service

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `service()` | Many:1 | services | service_id | Parent service |

#### **Key Fields:**
```sql
id              - Primary Key
service_id      - FK to services
requirement     - Document name
is_required     - Boolean: Mandatory or optional
```

---

### 11. **ACTIVITY_LOGS** (Audit Trail)
**Purpose**: Tracks all user actions for security and debugging

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `resident()` | Many:1 | residents | resident_id | Who performed the action |

#### **Key Fields:**
```sql
id              - Primary Key
resident_id     - FK to residents
action          - Action type (login, register, update, etc.)
description     - Human-readable details
ip_address      - User's IP
user_agent      - Browser info
severity        - ENUM('info', 'warning', 'critical')
metadata        - JSON: Additional context
```

---

### 12. **NOTIFICATIONS** (User Alerts)
**Purpose**: System notifications for residents

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `resident()` | Many:1 | residents | resident_id | Who receives this |

#### **Key Fields:**
```sql
id              - Primary Key
resident_id     - FK to residents
type            - Notification category
title           - Subject
message         - Body content
is_read         - Boolean: Seen or not
read_at         - Timestamp
```

---

### 13. **ANNOUNCEMENTS** (Public Information)
**Purpose**: Barangay-wide or municipality-wide announcements

#### **Special Relationship:**
- **No direct FK**, but connected via `barangay` field matching
- Residents can access announcements where `announcements.target_barangay = residents.barangay`
- OR where `announcements.target_barangay = 'All Barangays'`

#### **Key Fields:**
```sql
id                  - Primary Key
title               - Announcement title
content             - Full text
category            - ENUM('Barangay Ordinance', 'Public Information', 'Emergency Alert')
target_barangay     - Which barangay (or "All Barangays")
is_active           - Boolean: Visible or not
posted_by_id        - FK to residents (admin who posted)
```

---

### 14. **SCANNED_DOCUMENTS** (PhilSys Verification)
**Purpose**: Stores uploaded PhilSys ID scans for verification

#### **Relationships:**

| Method | Type | Target | FK | Description |
|--------|------|--------|-----|-------------|
| `resident()` | Many:1 | residents | resident_id | Who uploaded |
| `user()` | Many:1 | users | user_id | DEPRECATED: Use resident_id |

#### **Key Fields:**
```sql
id              - Primary Key
resident_id     - FK to residents
document_type   - Type (PhilSys Front, PhilSys Back)
file_path       - Storage location
upload_date     - When uploaded
```

---

## Hierarchical Data Collection System (HN → HHN → HHM)

### Three-Level Hierarchy:

```
LEVEL 1: HOUSEHOLD (HN)
   └─ Physical Address
   └─ Shared by multiple families
   
LEVEL 2: FAMILY (HHN)
   └─ Family unit within household
   └─ Related by surname/kinship
   └─ Has one designated head
   
LEVEL 3: RESIDENTS (HHM)
   └─ Individual members
   └─ Linked to family and household
```

### Database Structure:

```sql
-- HOUSEHOLDS Table
id: 1
household_number: "HN-2026-001"
full_address: "123 Main St, Purok 1, Centro, Buguey"

-- FAMILIES Table
id: 1
household_id: 1 (FK to HOUSEHOLDS)
hhn_number: "HHN-001"
head_surname: "Dela Cruz"
household_head_id: 5 (FK to RESIDENTS)

-- RESIDENTS Table
id: 5
household_id: 1 (FK to HOUSEHOLDS)
family_id: 1 (FK to FAMILIES)
household_head_id: NULL (because this IS the head)
first_name: "Juan"
last_name: "Dela Cruz"

id: 6
household_id: 1 (same household)
family_id: 1 (same family)
household_head_id: 5 (Juan is the head)
first_name: "Maria"
last_name: "Dela Cruz"
```

### Query Examples:

```php
// Get all families in a household
$household = Household::find(1);
$families = $household->families; // Returns all HHN units

// Get all members of a family
$family = Family::find(1);
$members = $family->members; // Returns all residents in this family

// Get the family leader
$head = $family->householdHead; // Returns the Resident who is the head

// Get all residents at an address
$household = Household::find(1);
$allResidents = $household->residents; // All individuals at this HN

// Get a resident's family
$resident = Resident::find(6);
$family = $resident->family; // Returns the family unit
$household = $resident->household; // Returns the physical address
```

---

## Foreign Key Summary

| Table | Foreign Key | References | Relationship |
|-------|-------------|-----------|--------------|
| residents | household_id | households(id) | Many residents → 1 household |
| residents | family_id | families(id) | Many residents → 1 family |
| residents | household_head_id | household_heads(id) | LEGACY: Many members → 1 head |
| families | household_id | households(id) | Many families → 1 household |
| families | household_head_id | residents(id) | 1 family → 1 head resident |
| household_heads | household_id | households(id) | LEGACY |
| household_heads | resident_id | residents(id) | LEGACY |
| household_members | resident_id | residents(id) | LEGACY |
| household_members | household_head_id | household_heads(id) | LEGACY |
| household_profiles | resident_id | households(id) | 1:1 profiling data |
| service_requests | resident_id | residents(id) | Many requests → 1 resident |
| service_requests | service_id | services(id) | Many requests → 1 service |
| service_steps | service_id | services(id) | Many steps → 1 service |
| service_requirements | service_id | services(id) | Many requirements → 1 service |
| activity_logs | resident_id | residents(id) | Many logs → 1 resident |
| notifications | resident_id | residents(id) | Many notifications → 1 resident |
| scanned_documents | resident_id | residents(id) | Many docs → 1 resident |

---

## Special Relationships

### 1. **Barangay-Based Announcement Matching**
```php
// Residents see announcements targeting their barangay
$resident = Resident::find(1);
$announcements = $resident->announcements;
// Returns announcements where:
// - target_barangay = $resident->barangay
// - OR target_barangay = 'All Barangays'
```

### 2. **Auto-Linking System**
```php
// System automatically suggests family connections
$family = Family::find(1);
$needsReview = $family->autoLinkedMembers;
// Returns residents with is_auto_linked = true
```

### 3. **Circular Role Structure**
```
Resident → asHouseholdHead() → HouseholdHead
                                      ↓
                             resident() ← Back to same Resident
```

### 4. **Legacy vs New System**
**OLD (DEPRECATED):**
```
Household → HouseholdHeads → HouseholdMembers → Residents
```

**NEW (CURRENT):**
```
Household → Families → Residents
                       ↓
                 household_head_id (direct FK to Residents)
```

---

## Index & Performance Notes

### Indexed Columns:
- `residents.household_id` - Frequent household queries
- `residents.family_id` - Family member lookups
- `residents.barangay` - Barangay filtering
- `residents.barangay_code` - Quick code lookups
- `residents.email` - Login authentication
- `residents.national_id` - PhilSys verification
- `service_requests.resident_id` - User's service history
- `service_requests.status` - Status filtering
- `activity_logs.resident_id` - Audit trail queries
- `notifications.resident_id + is_read` - Unread notifications

### Common Queries:
```php
// Residents in a barangay
Resident::where('barangay', 'Centro')->get();
Resident::inBarangay('CEN')->get(); // Using scope

// All members of a family
Family::with('members')->find(1);

// All families at an address
Household::with('families.members')->find(1);

// Pending service requests
ServiceRequest::where('status', 'pending')->get();

// Unread notifications for a user
Notification::where('resident_id', 1)->unread()->get();
```

---

## Migration Order

Execute migrations in this order to respect foreign key constraints:

1. `create_residents_table` - Core users table
2. `create_households_table` - Physical addresses (HN)
3. `create_families_table` - Family units (HHN)
4. `add_household_tracking_to_residents_table` - Links residents to households
5. `add_family_linking_to_residents_table` - Links residents to families
6. `create_services_table` - E-Services master
7. `create_service_requests_table` - Service transactions
8. `create_activity_logs_table` - Audit trail
9. `create_notifications_table` - User alerts
10. `add_barangay_code_to_residents_table` - Abbreviation system

---

## Data Integrity Rules

### Cascading Deletes:
- `residents` deleted → cascade to `service_requests`, `activity_logs`, `notifications`
- `households` deleted → cascade to `families` → cascade to `residents`
- `services` deleted → cascade to `service_requests`, `service_steps`, `service_requirements`

### Soft Deletes:
- `residents` - Can be restored
- NOT implemented on other tables

### Validation Rules:
- A `resident` CANNOT have `family_id` without `household_id`
- A `family` MUST belong to a `household`
- A `family.household_head_id` MUST point to a resident in the same family
- A `service_request.resident_id` MUST exist in residents

---

## Database Diagram Legend

```
┌────────┐
│ TABLE  │  - Entity/Table
└────────┘

─────►    - One-to-Many relationship (hasMany)
◄─────    - Many-to-One relationship (belongsTo)
◄────►    - Many-to-Many relationship (belongsToMany)
═════►    - One-to-One relationship (hasOne)

(1:1)     - One-to-One
(1:Many)  - One-to-Many
(Many:1)  - Many-to-One
(M:M)     - Many-to-Many
```

---

**Last Updated**: March 5, 2026  
**System Version**: Laravel 12.53.0  
**Total Tables**: 20+ (including Laravel system tables)
