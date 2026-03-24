# Socio-Economic Profiling & Onboarding System

## Overview

This system implements a **5-step onboarding wizard** that residents must complete after PhilSys identity verification. The wizard collects socio-economic data including housing, agriculture, aquaculture, livestock, and fisheries information.

---

## User Flow

```
Registration → Email Verification → PhilSys Verification → Onboarding Wizard → Full Account Activation
```

### Account Status Progression

| Status | Description | Access Level |
|--------|-------------|--------------|
| **Unverified** | Just registered, email not verified | Cannot access dashboard |
| **Email Verified** | Email confirmed, no PhilSys verification | Limited access, can see verification pages |
| **PhilSys Verified** | Identity verified via PhilSys QR scan | Can access onboarding wizard |
| **In Progress** | PhilSys verified but onboarding incomplete | Locked to onboarding wizard only |
| **Active** | Onboarding completed | Full system access |

---

## Database Schema

### New Columns in `residents` Table

```php
// Housing & Sanitation
'residential_type'      // Owned, Rented, Shared, Informal Settler
'house_materials'       // Type A/B/C
'water_source'         // Poso, Balon, Tap, Others
'flood_prone'          // boolean
'sanitary_toilet'      // boolean

// Livelihood (JSON arrays)
'crops'                // ["Vegetables", "Ginger", "Banana", ...]
'aquaculture'          // ["Fishpond", "Fishcage", ...]
'livestock'            // ["Carabao", "Swine", "Poultry", ...]
'fisheries'            // ["Municipal Inland Fishing", ...]

// Status Tracking
'is_onboarding_complete'   // boolean
'onboarding_completed_at'  // timestamp
```

### JSON Column Usage

Instead of creating 30+ boolean columns, livelihood data is stored efficiently as JSON arrays:

```php
// Database storage
$resident->crops = ["Vegetables", "Ginger", "Coconut"];

// Automatic casting (Laravel handles this)
$crops = $resident->crops; // Returns array, not JSON string

// Checking values
if (in_array("Coconut", $resident->crops)) {
    // User farms coconut
}
```

---

## Implementation Details

### 1. Migration

**File:** `database/migrations/2026_03_02_011844_add_socioeconomic_profiling_to_residents_table.php`

Adds 11 new columns to the `residents` table:
- 5 housing/sanitation fields
- 4 JSON livelihood fields
- 2 status tracking fields

**Run migration:**
```bash
php artisan migrate
```

### 2. Model Updates

**File:** `app/Models/Resident.php`

**Added to `$fillable` array:**
```php
'residential_type', 'house_materials', 'water_source',
'flood_prone', 'sanitary_toilet',
'crops', 'aquaculture', 'livestock', 'fisheries',
'is_onboarding_complete', 'onboarding_completed_at',
```

**Added to `casts()` method:**
```php
'flood_prone' => 'boolean',
'sanitary_toilet' => 'boolean',
'crops' => 'array',          // Auto JSON encoding/decoding
'aquaculture' => 'array',
'livestock' => 'array',
'fisheries' => 'array',
'is_onboarding_complete' => 'boolean',
'onboarding_completed_at' => 'datetime',
```

### 3. Controller Methods

**File:** `app/Http/Controllers/ProfileController.php`

#### `showOnboarding()` Method
- Displays the 5-step wizard view
- Redirects to dashboard if already completed
- Prevents duplicate onboarding

#### `storeOnboarding()` Method
- Validates all incoming data
- Stores housing, crops, aquaculture, livestock, fisheries
- Sets `is_onboarding_complete = true`
- Records `onboarding_completed_at` timestamp
- Logs completion event
- Redirects to dashboard with success toast

**Validation Rules:**
```php
'residential_type' => 'nullable|string|max:255',
'house_materials' => 'nullable|string|max:255',
'water_source' => 'nullable|string|max:255',
'flood_prone' => 'nullable|boolean',
'sanitary_toilet' => 'nullable|boolean',
'crops' => 'nullable|array',
'crops.*' => 'string',
'aquaculture' => 'nullable|array',
'aquaculture.*' => 'string',
'livestock' => 'nullable|array',
'livestock.*' => 'string',
'fisheries' => 'nullable|array',
'fisheries.*' => 'string',
```

### 4. Middleware

**File:** `app/Http/Middleware/EnsureOnboardingComplete.php`

**Purpose:** Enforces onboarding completion before accessing protected features.

**Logic:**
```php
if (!$resident->is_onboarding_complete) {
    // Allow access to:
    // - profile.onboarding.* routes
    // - verification.* routes
    // - logout route
    
    // Block everything else, redirect to onboarding
    return redirect()->route('profile.onboarding.show')
        ->with('toast_warning', 'Please complete your profile...');
}
```

**Registered as:** `onboarding.complete` in `bootstrap/app.php`

### 5. Routes

**File:** `routes/web.php`

#### Onboarding Routes (No completion requirement)
```php
Route::middleware(['auth', 'verified', 'lockout'])->group(function () {
    Route::prefix('profile/onboarding')->name('profile.onboarding.')->group(function () {
        Route::get('/', [ProfileController::class, 'showOnboarding'])->name('show');
        Route::post('/', [ProfileController::class, 'storeOnboarding'])->name('store');
    });
    
    Route::prefix('verification')->name('verification.')->group(function () {
        Route::get('/philsys', [VerificationController::class, 'showPhilSysVerification'])->name('philsys');
        Route::post('/philsys', [VerificationController::class, 'verifyPhilSys'])->name('philsys.verify');
    });
});
```

#### Protected Routes (Require onboarding completion)
```php
Route::middleware(['auth', 'verified', 'lockout', 'onboarding.complete'])->group(function () {
    // Citizen profiles, services, notifications, activity logs, etc.
});
```

**Dashboard Route:**
```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['verified', 'onboarding.complete'])
    ->name('dashboard');
```

### 6. Blade View

**File:** `resources/views/profile/onboarding.blade.php`

**Features:**
- ✅ 5-step wizard with Alpine.js state management
- ✅ Progress bar showing completion percentage
- ✅ "Skip this step" option for optional fields
- ✅ Back button (except on Step 1)
- ✅ Next/Complete button based on current step
- ✅ Form validation
- ✅ Tailwind CSS styling with brand colors
- ✅ Responsive design (mobile-friendly)
- ✅ Toast notification integration

**Alpine.js State:**
```javascript
x-data="{ step: 1, maxSteps: 5 }"
```

**Step Navigation:**
```javascript
// Next Step
@click="step++"

// Previous Step
@click="step--"

// Show/Hide Steps
x-show="step === 1"
x-show="step === 2"
// etc.
```

**Progress Bar:**
```javascript
:style="'width: ' + ((step / maxSteps) * 100) + '%'"
```

---

## Wizard Steps

### Step 1: Housing & Sanitation

**Fields:**
- Residential Status (dropdown)
- House Materials (dropdown)
- Water Source (dropdown)
- Flood Prone Area (checkbox)
- Sanitary Toilet Access (checkbox)

### Step 2: Agriculture (Crops)

**Checkboxes:**
Vegetables, Ginger, Banana, Mango, Pineapple, Citrus, Mungbean, Peanut, Coconut, Coffee, Cacao

### Step 3: Aquaculture

**Checkboxes:**
Fishpond, Fishcage, Oyster (Raft/Broadcast), Seaweed

### Step 4: Livestock & Poultry

**Categories:**
- **Large Ruminants:** Carabao, Cattle, Horse
- **Small Ruminants:** Goat, Sheep
- **Others:** Swine, Poultry, Companions (Pets)

### Step 5: Capture Fisheries

**Checkboxes:**
- Municipal Inland Fishing
- Municipal Marine Fishing
- Various Fishing Gear Types

---

## Security Features

### 1. Middleware Protection Layer

```
Auth → Email Verified → Onboarding Complete → Protected Features
```

### 2. Prevented Bypasses

❌ Cannot access `/dashboard` without onboarding  
❌ Cannot access `/services` without onboarding  
❌ Cannot type URLs manually to skip wizard  
✅ Middleware enforces strict completion flow

### 3. Logging

Every onboarding completion is logged:
```php
Log::info('Resident completed onboarding', [
    'resident_id' => $resident->id,
    'national_id' => $resident->national_id,
]);
```

Every redirect attempt is logged:
```php
Log::info('Resident redirected to onboarding', [
    'resident_id' => $resident->id,
    'attempted_route' => $request->route()->getName(),
]);
```

---

## Testing the System

### Manual Test Flow

1. **Register a new account**
   ```
   Navigate to: /register
   Fill out form and submit
   ```

2. **Verify email**
   ```
   Check email inbox
   Click verification link
   ```

3. **Verify PhilSys**
   ```
   Navigate to: /verification/philsys
   Scan QR code or enter PSN
   Click "Verify Identity"
   ```

4. **Complete Onboarding**
   ```
   Should be automatically redirected to: /profile/onboarding
   Fill out 5 steps
   Click "Complete Onboarding"
   ```

5. **Access Dashboard**
   ```
   Should be redirected to: /dashboard
   Full system access granted
   ```

### Testing Skip Functionality

Users can skip any step (all fields are optional):
```
Step 1 → Click "Skip this step" → Step 2
Step 2 → Click "Skip this step" → Step 3
...
Step 5 → Click "Skip this step" → Submit form → Dashboard
```

### Testing Bypass Prevention

Try accessing dashboard before completing onboarding:
```bash
# After PhilSys verification but before onboarding
# Manually type: http://localhost/dashboard
# Expected: Redirect to /profile/onboarding with warning toast
```

---

## Data Retrieval & Usage

### Accessing Socio-Economic Data

```php
$resident = Resident::find(1);

// Housing
$resident->residential_type; // "Owned"
$resident->house_materials;   // "Type A"
$resident->flood_prone;       // true/false

// Livelihood (automatically returns arrays)
$resident->crops;        // ["Vegetables", "Coconut", "Ginger"]
$resident->aquaculture;  // ["Fishpond", "Seaweed"]
$resident->livestock;    // ["Carabao", "Poultry"]
$resident->fisheries;    // ["Municipal Marine Fishing"]

// Status
$resident->is_onboarding_complete;  // true
$resident->onboarding_completed_at; // Carbon instance
```

### Checking Onboarding Status in Blade

```blade
@if($resident->is_onboarding_complete)
    <span class="text-green-600">Active</span>
@else
    <span class="text-orange-600">In Progress</span>
@endif
```

### Analyzing Livelihood Data

```php
// Count residents who farm coconut
$coconutFarmers = Resident::whereJsonContains('crops', 'Coconut')->count();

// Find residents with fishponds
$fishpondOwners = Resident::whereJsonContains('aquaculture', 'Fishpond')->get();

// Find residents with any livestock
$livestockOwners = Resident::whereNotNull('livestock')
    ->whereJsonLength('livestock', '>', 0)
    ->get();
```

---

## Admin Features

### Viewing Onboarding Data

Admins can view resident socio-economic profiles:

**Example:** In `admin/residents/{id}` view:
```blade
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="font-bold text-lg mb-4">Socio-Economic Profile</h3>
    
    <div class="grid grid-cols-2 gap-4">
        <div>
            <span class="font-medium">Housing Type:</span>
            {{ $resident->residential_type }}
        </div>
        <div>
            <span class="font-medium">Water Source:</span>
            {{ $resident->water_source }}
        </div>
    </div>
    
    @if($resident->crops)
        <div class="mt-4">
            <span class="font-medium">Crops:</span>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($resident->crops as $crop)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        {{ $crop }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif
</div>
```

### Generating Reports

```php
// Example: Livelihood Distribution Report
$cropsData = DB::table('residents')
    ->select(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(crops, "$[*]")) as crop'))
    ->whereNotNull('crops')
    ->get()
    ->flatten()
    ->countBy()
    ->sortDesc();

// Result:
// [
//     "Coconut" => 156,
//     "Vegetables" => 143,
//     "Banana" => 98,
//     ...
// ]
```

---

## Customization

### Adding New Wizard Steps

1. **Update the view:**
   ```blade
   <!-- Add Step 6 -->
   <div x-show="step === 6" x-transition.opacity class="p-8 space-y-6" style="display: none;">
       <h3 class="text-xl font-bold text-deep-forest border-b pb-2">Step 6: Your New Section</h3>
       <!-- Add fields here -->
   </div>
   ```

2. **Update maxSteps:**
   ```javascript
   x-data="{ step: 1, maxSteps: 6 }" // Change from 5 to 6
   ```

3. **Update progress labels:**
   ```blade
   <span :class="step >= 6 ? 'text-sea-green' : ''">Your Step</span>
   ```

4. **Add migration:**
   ```bash
   php artisan make:migration add_new_fields_to_residents_table --table=residents
   ```

5. **Update model casts:**
   ```php
   'new_field' => 'array',
   ```

6. **Update validation:**
   ```php
   'new_field' => 'nullable|array',
   'new_field.*' => 'string',
   ```

### Changing Skip Behavior

To **remove** the skip option:
```blade
<!-- Delete this button -->
<button type="button" @click="step < maxSteps ? step++ : $el.closest('form').submit()" class="text-gray-500 hover:text-tiger-orange font-bold text-sm px-4 py-2 transition underline">
    Skip this step
</button>
```

To **make fields required:**
```php
// Remove 'nullable' from validation rules
'residential_type' => 'required|string|max:255',
```

---

## Troubleshooting

### Issue: Stuck on Onboarding Page

**Cause:** `is_onboarding_complete` is still `false`

**Solution:**
```bash
php artisan tinker
```
```php
$resident = Resident::find(1);
$resident->is_onboarding_complete = true;
$resident->onboarding_completed_at = now();
$resident->save();
```

### Issue: Toast Not Showing

**Cause:** Missing `<x-toast />` component in layout

**Solution:** Add to `resources/views/profile/onboarding.blade.php`:
```blade
<body>
    <!-- Your content -->
    <x-toast />
</body>
```

### Issue: JSON Data Not Saving

**Cause:** Form fields not named as arrays

**Solution:** Ensure checkboxes use array syntax:
```blade
<!-- Correct -->
<input type="checkbox" name="crops[]" value="Coconut">

<!-- Wrong -->
<input type="checkbox" name="crops" value="Coconut">
```

### Issue: Alpine.js Not Working

**Cause:** CDN not loaded or conflicts

**Solution:** Check Alpine.js is loaded:
```blade
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

---

## Best Practices

### 1. Data Validation
Always validate JSON arrays to prevent malformed data:
```php
'crops' => 'nullable|array|max:20',
'crops.*' => 'string|max:100',
```

### 2. Database Indexing
For frequent searches, add JSON indexes:
```php
$table->index('is_onboarding_complete');
DB::statement('CREATE INDEX idx_crops ON residents((CAST(crops AS CHAR(255))))');
```

### 3. Privacy & Security
- Never expose raw JSON in public views
- Sanitize data before displaying
- Use Laravel's `{{ }}` syntax for XSS protection

### 4. User Experience
- Keep wizard steps short (5-7 fields max per step)
- Always show progress indicator
- Provide "Skip" for optional fields
- Show helpful tooltips/examples
- Auto-save draft progress (future enhancement)

---

## Future Enhancements

### Potential Improvements

1. **Auto-save Draft Progress**
   ```javascript
   @change="saveDraft()"
   ```
   Store incomplete data in session/localStorage

2. **Conditional Logic**
   Show Step 2 only if "farming" is selected in Step 1

3. **Pre-fill from PhilSys Data**
   If PhilSys API provides livelihood data, auto-populate

4. **Edit Mode**
   Allow residents to update their socio-economic data later:
   ```php
   Route::get('/profile/onboarding/edit', [ProfileController::class, 'editOnboarding']);
   Route::put('/profile/onboarding', [ProfileController::class, 'updateOnboarding']);
   ```

5. **Reporting Dashboard**
   Admin analytics showing:
   - Completion rates
   - Most common crops/livestock
   - Housing conditions distribution
   - Geographic livelihood patterns

---

## API Endpoints Summary

| Method | Route | Purpose | Middleware |
|--------|-------|---------|------------|
| GET | `/profile/onboarding` | Show onboarding wizard | auth, verified |
| POST | `/profile/onboarding` | Store onboarding data | auth, verified |
| GET | `/dashboard` | Dashboard (requires completion) | auth, verified, onboarding.complete |
| GET | `/services` | Services (requires completion) | auth, verified, citizen, onboarding.complete |

---

## Conclusion

This onboarding system ensures **complete resident profiling** for government data accuracy while maintaining **user-friendly design** and **enterprise-grade security**. Residents cannot bypass the wizard, ensuring data completeness for LGU analytics and service delivery optimization.

**Key Benefits:**
✅ Zero-bypass security  
✅ Flexible skip options  
✅ JSON storage efficiency  
✅ Professional UX with Alpine.js  
✅ Complete audit trail  
✅ Scalable architecture  

For questions or modifications, refer to this guide and the related files.
