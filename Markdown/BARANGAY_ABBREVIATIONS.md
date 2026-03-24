# Barangay Abbreviation System

## Overview
This document provides a comprehensive guide to the standardized 3-letter barangay abbreviation system implemented across the ResidenteWebApp. These codes are used for database IDs, dropdown values, tracking records, and system-wide identification.

---

## Complete Barangay List

| # | Barangay Name | Code | Usage Example |
|---|--------------|------|---------------|
| 1 | Alucao Weste | ALW | BGY-ALW-001 |
| 2 | Antiporda | ANT | BGY-ANT-042 |
| 3 | Ballang | BAL | BGY-BAL-103 |
| 4 | Balza | BLZ | BGY-BLZ-025 |
| 5 | Cabaritan | CAB | BGY-CAB-067 |
| 6 | Calamegatanan | CAL | BGY-CAL-089 |
| 7 | Centro | CEN | BGY-CEN-156 |
| 8 | Centro West | CEW | BGY-CEW-012 |
| 9 | Dalaya | DAL | BGY-DAL-034 |
| 10 | Fula | FUL | BGY-FUL-078 |
| 11 | Leron | LER | BGY-LER-091 |
| 12 | Maddalero | MAD | BGY-MAD-045 |
| 13 | Mala Este | MAE | BGY-MAE-123 |
| 14 | Mala Weste | MAW | BGY-MAW-098 |
| 15 | Minanga Este | MIE | BGY-MIE-067 |
| 16 | Minanga Weste | MIW | BGY-MIW-145 |
| 17 | Paddaya Este | PAE | BGY-PAE-089 |
| 18 | Paddaya Weste | PAW | BGY-PAW-201 |
| 19 | Pattao | PAT | BGY-PAT-056 |
| 20 | Quinawegan | QUI | BGY-QUI-134 |
| 21 | Remebella | REM | BGY-REM-078 |
| 22 | San Isidro | SAI | BGY-SAI-167 |
| 23 | San Juan | SAJ | BGY-SAJ-092 |
| 24 | San Vicente | SAV | BGY-SAV-045 |
| 25 | Santa Isabel | STI | BGY-STI-123 |
| 26 | Santa Maria | STM | BGY-STM-089 |
| 27 | Tabbac | TAB | BGY-TAB-156 |
| 28 | Villa Cielo | VIC | BGY-VIC-034 |
| 29 | Villa Gracia | VIG | BGY-VIG-078 |
| 30 | Villa Leonora | VIL | BGY-VIL-201 |

---

## How to Use

### In Dropdowns
All barangay selection fields now display:
```
Barangay Name (CODE)
```
Example: `Centro (CEN)`

### In Database
The `residents` table now includes a `barangay_code` field:
- **barangay**: Full name (e.g., "Centro")
- **barangay_code**: 3-letter code (e.g., "CEN")

### In Code

#### Get Barangay List
```php
// Get all barangays with codes
$barangays = config('barangays.list');
// Returns: ['Alucao Weste' => 'ALW', 'Antiporda' => 'ANT', ...]

// Get only names
$names = array_keys(config('barangays.list'));

// Get only codes
$codes = array_values(config('barangays.list'));
```

#### Get Code from Name
```php
$code = config('barangays.list')['Centro']; // Returns: 'CEN'
```

#### Get Name from Code
```php
$barangayList = config('barangays.list');
$flipped = array_flip($barangayList);
$name = $flipped['CEN']; // Returns: 'Centro'
```

#### Using Resident Model Methods
```php
// Get barangay code for a resident
$resident->barangay_code; // Returns: 'CEN'

// Get formatted display
$resident->formatted_barangay; // Returns: 'Centro (CEN)'

// Get tracking prefix
$resident->barangay_prefix; // Returns: 'BGY-CEN-'

// Query residents in a specific barangay
Resident::inBarangay('Centro')->get();
Resident::inBarangay('CEN')->get(); // Also works with code
```

---

## Implementation Details

### Files Updated

#### Configuration
- **`config/barangays.php`** - Central configuration file with all 30 barangay mappings

#### Database
- **Migration**: `2026_03_05_160000_add_barangay_code_to_residents_table.php`
  - Adds `barangay_code` column to `residents` table
  - Auto-populates codes for existing residents
  - Handles legacy name variations (M. Antiporda → Antiporda, Sta. Isabel → Santa Isabel, etc.)

#### Models
- **`app/Models/Resident.php`**
  - Added `barangay_code` to `$fillable` array
  - New methods:
    - `getBarangayCodeAttribute()` - Get code
    - `getFormattedBarangayAttribute()` - Get name with code
    - `getBarangayPrefixAttribute()` - Get tracking prefix
    - `scopeInBarangay()` - Query scope
    - `getBarangaysList()` - Static method to get all barangays
    - `getBarangayNames()` - Static method to get names only
    - `getBarangayCodes()` - Static method to get codes only

#### Controllers
- **`app/Http/Controllers/AuthController.php`**
  - Sets `barangay_code` during registration

- **`app/Http/Controllers/ResidentProfileController.php`**
  - Uses new barangays config in location step

- **`app/Http/Controllers/Admin/DataCollectionController.php`**
  - `getBugueyBarangays()` updated to use config

- **`app/Http/Controllers/Admin/AdminDashboardController.php`**
  - `masterCollections()` - Passes barangay list with codes
  - `barangayOverview()` - Maps codes to barangay statistics

- **`app/Http/Controllers/Admin/HouseholdController.php`**
  - Uses new barangays config in household creation

#### Views
- **`resources/views/auth/register.blade.php`**
  - Displays barangays with codes in dropdown

- **`resources/views/profile/setup/step1-location.blade.php`**
  - Updated barangay selector with codes

- **`resources/views/admin/master-collections.blade.php`**
  - Filter dropdown shows codes

- **`resources/views/admin/barangay-overview.blade.php`**
  - Selector and comparison table show codes
  - Detailed view displays barangay code in header

- **`resources/views/admin/data-collection.blade.php`**
  - Filter dropdown shows codes

- **`resources/views/admin/households/create-household.blade.php`**
  - Changed from text input to dropdown with codes

#### Validation
- **`app/Http/Requests/RegisterResidentRequest.php`**
  - Updated barangay validation rule to use `config('barangays.list')`

---

## Tracking Record Format

The system uses the following format for barangay-based tracking:

```
BGY-{CODE}-{NUMBER}
```

### Examples
- Household in Centro: `BGY-CEN-001`
- Family in Ballang: `BGY-BAL-042`
- Individual in Villa Leonora: `BGY-VIL-167`

### Usage
```php
// Get tracking prefix for a resident
$prefix = $resident->barangay_prefix; // 'BGY-CEN-'

// Create tracking number
$trackingNumber = $prefix . str_pad($resident->id, 3, '0', STR_PAD_LEFT);
// Result: 'BGY-CEN-001'
```

---

## Migration Details

### Automatic Code Population
The migration automatically populates `barangay_code` for existing residents based on their `barangay` field. It also handles legacy name variations:

| Old Name | New Name | Code |
|----------|----------|------|
| M. Antiporda | Antiporda | ANT |
| Calamegatan | Calamegatanan | CAL |
| Sta. Isabel | Santa Isabel | STI |
| Sta. Maria | Santa Maria | STM |

### Running the Migration
```bash
php artisan migrate
```

This will:
1. Add `barangay_code` column to `residents` table
2. Index the column for faster queries
3. Populate codes for all existing residents
4. Update legacy barangay names

---

## Benefits

### For Administrators
- **Quick Identification**: Instantly recognize barangay from 3-letter code
- **Data Filtering**: Easier filtering and sorting by barangay
- **Report Generation**: Simplified barangay-based reporting
- **Space Efficiency**: Compact codes save display space

### For Developers
- **Consistent Reference**: Single source of truth in `config/barangays.php`
- **Type-Safe Queries**: Use codes instead of full names
- **Backwards Compatible**: System still works with full names
- **Easy Updates**: Modify config file to update all references

### For Users
- **Clear Display**: See both full name and code
- **Easier Selection**: Alphabetically sorted with codes
- **Better Understanding**: Learn official barangay codes

---

## Maintenance

### Adding a New Barangay
If Buguey adds a new barangay (unlikely but possible):

1. **Update config**:
```php
// config/barangays.php
'list' => [
    // ...existing barangays...
    'New Barangay Name' => 'NBN',
],
```

2. **Clear config cache**:
```bash
php artisan config:clear
```

3. **Update validation** (if needed):
The validation automatically uses the config, so no code changes needed.

### Changing a Code
If an abbreviation needs to be changed:

1. Update `config/barangays.php`
2. Run a migration to update existing `barangay_code` values:
```php
DB::table('residents')
    ->where('barangay', 'Barangay Name')
    ->update(['barangay_code' => 'NEW']);
```
3. Clear config cache: `php artisan config:clear`

---

## Testing Checklist

After implementing the barangay abbreviation system, verify:

- ✅ Registration form shows codes: `Barangay Name (CODE)`
- ✅ Profile location step shows codes
- ✅ Admin data collection filter shows codes
- ✅ Master collections filter shows codes
- ✅ Barangay overview selector shows codes
- ✅ Barangay overview comparison table shows codes
- ✅ Household creation form uses dropdown with codes
- ✅ New registrations save `barangay_code` automatically
- ✅ Existing residents have `barangay_code` populated
- ✅ `$resident->formatted_barangay` returns formatted string
- ✅ `Resident::inBarangay()` scope works with names and codes

---

## Support

For questions or issues related to the barangay abbreviation system:
1. Check this documentation first
2. Review `config/barangays.php` for the master list
3. Check `app/Models/Resident.php` for helper methods
4. Review the migration file for data structure details

---

**Last Updated**: March 5, 2026  
**System Version**: Laravel 12.53.0  
**Total Barangays**: 30
