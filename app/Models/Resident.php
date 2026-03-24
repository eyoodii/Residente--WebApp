<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Schoolees\Psgc\Models\Region;
use Schoolees\Psgc\Models\Province;
use Schoolees\Psgc\Models\City;
use Schoolees\Psgc\Models\Barangay;
use App\Models\DepartmentRoleModule;

class Resident extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, SoftDeletes, Notifiable;

    /**
     * Override email verification check.
     * Only CITIZENS are required to verify their email address.
     * Admins, Super Admins, and Department Staff bypass this requirement.
     */
    public function hasVerifiedEmail(): bool
    {
        // Non-citizen roles are always treated as verified
        if ($this->role !== 'citizen') {
            return true;
        }

        return $this->email_verified_at !== null;
    }

    /**
     * The attributes that are mass assignable.
     * Only these fields can be filled via mass assignment to prevent vulnerabilities.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'national_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'civil_status',
        'blood_type',
        'purok',
        'house_number',
        'street',
        'family_registration_type',
        'barangay',
        'barangay_code',
        'municipality',
        'province',
        'postal_code',
        
        // PSGC (Philippine Standard Geographic Code) fields for Philsys integration
        'region_psgc_code',
        'province_psgc_code',
        'city_psgc_code',
        'barangay_psgc_code',
        'contact_number',
        'email',
        'password',
        'occupation',
        'vulnerable_sector',
        'household_relationship',
        'household_number',
        'household_member_number',
        'household_id',
        'family_id',
        'is_auto_linked',
        'household_head_id',
        'is_household_head',
        'is_verified',
        'role',
        'profile_matched',
        'profile_matched_at',
        'verification_method',
        'philsys_verified_at',
        'philsys_verification_method',
        'philsys_transaction_id',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'locked_until',
        
        // Socio-Economic Profiling Fields
        'residential_type',
        'house_materials',
        'water_source',
        'flood_prone',
        'sanitary_toilet',
        'crops',
        'aquaculture',
        'livestock',
        'fisheries',
        'is_onboarding_complete',
        'onboarding_completed_at',

        // Department RBAC Fields
        'department_role',
        'department_permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Ensures passwords and tokens are never exposed in API responses or arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_verified' => 'boolean',
            'email_verified_at' => 'datetime',
            'philsys_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_matched' => 'boolean',
            'profile_matched_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'failed_login_attempts' => 'integer',
            
            // Socio-Economic Profiling Casts
            'flood_prone' => 'boolean',
            'sanitary_toilet' => 'boolean',
            'crops' => 'array',
            'aquaculture' => 'array',
            'livestock' => 'array',
            'fisheries' => 'array',
            'is_onboarding_complete' => 'boolean',
            'onboarding_completed_at' => 'datetime',
            'is_household_head' => 'boolean',
            'department_permissions' => 'array',
        ];
    }

    /**
     * Get the service requests for the resident.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get household members
     */
    public function householdMembers()
    {
        return $this->hasMany(HouseholdMember::class);
    }

    /**
     * Get household profile
     */
    public function householdProfile()
    {
        return $this->hasOne(HouseholdProfile::class);
    }

    /**
     * Get the physical household (address) this resident belongs to
     */
    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * Get the family unit (HHN level) this resident belongs to
     * NEW: For Super Admin hierarchical data collection
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the household head (family unit) this resident belongs to
     */
    public function householdHeadRelation()
    {
        return $this->belongsTo(HouseholdHead::class, 'household_head_id');
    }

    /**
     * Get the HouseholdHead record if this resident is a household head
     */
    public function asHouseholdHead()
    {
        return $this->hasOne(HouseholdHead::class);
    }

    /**
     * Get activity logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get PhilSys verification records
     */
    public function philsysVerifications()
    {
        return $this->hasMany(PhilsysVerification::class)->latest();
    }

    /**
     * Get the latest successful PhilSys verification
     */
    public function latestPhilsysVerification()
    {
        return $this->hasOne(PhilsysVerification::class)
            ->where('is_successful', true)
            ->latest();
    }

    /**
     * Get notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Get unread notifications
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread()->latest();
    }

    /**
     * CONNECTION: A resident can receive many targeted announcements.
     * Links the resident's barangay to the announcement's target_barangay
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'target_barangay', 'barangay');
    }

    /*
    |--------------------------------------------------------------------------
    | PSGC (Philippine Standard Geographic Code) Relationships
    |--------------------------------------------------------------------------
    |
    | These relationships connect the resident to official PSA geographic data
    | for accurate address validation and Philsys integration.
    |
    */

    /**
     * Get the PSGC region for this resident.
     */
    public function psgcRegion()
    {
        return $this->belongsTo(Region::class, 'region_psgc_code', 'code');
    }

    /**
     * Get the PSGC province for this resident.
     */
    public function psgcProvince()
    {
        return $this->belongsTo(Province::class, 'province_psgc_code', 'code');
    }

    /**
     * Get the PSGC city/municipality for this resident.
     */
    public function psgcCity()
    {
        return $this->belongsTo(City::class, 'city_psgc_code', 'code');
    }

    /**
     * Get the PSGC barangay for this resident.
     */
    public function psgcBarangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_psgc_code', 'code');
    }

    /**
     * Get the full PSGC address hierarchy.
     * Returns an array with region, province, city, and barangay names from PSGC.
     */
    public function getPsgcAddressAttribute(): ?array
    {
        if (!$this->barangay_psgc_code) {
            return null;
        }

        return [
            'region' => $this->psgcRegion?->name,
            'province' => $this->psgcProvince?->name,
            'city' => $this->psgcCity?->name,
            'barangay' => $this->psgcBarangay?->name,
            'codes' => [
                'region' => $this->region_psgc_code,
                'province' => $this->province_psgc_code,
                'city' => $this->city_psgc_code,
                'barangay' => $this->barangay_psgc_code,
            ],
        ];
    }

    /**
     * Check if the resident has complete PSGC address data.
     * Required for Philsys integration.
     */
    public function hasPsgcAddress(): bool
    {
        return !empty($this->region_psgc_code) &&
               !empty($this->province_psgc_code) &&
               !empty($this->city_psgc_code) &&
               !empty($this->barangay_psgc_code);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . substr($this->middle_name, 0, 1) . '.';
        }
        $name .= ' ' . $this->last_name;
        if ($this->extension_name) {
            $name .= ' ' . $this->extension_name;
        }
        return $name;
    }

    /**
     * Get age
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    /**
     * Check if resident is super admin (SA role)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'SA';
    }

    /**
     * Check if resident is admin (includes SA for backward compatibility)
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'SA';
    }

    /**
     * Check if resident is strictly admin (not superadmin)
     */
    public function isRegularAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if resident is citizen (verified resident)
     */
    public function isCitizen(): bool
    {
        return $this->role === 'citizen';
    }

    /**
     * Check if resident is visitor (unverified)
     */
    public function isVisitor(): bool
    {
        return $this->role === 'visitor';
    }

    /**
     * Check if resident is a department staff member (LGU office)
     */
    public function isDepartmentStaff(): bool
    {
        return !is_null($this->department_role);
    }

    /**
     * Check if resident has access to a specific module based on their department role.
     * Checks the database first (UI-configurable); falls back to config if no DB rows exist.
     * Super Admin bypasses all checks.
     */
    public function hasDepartmentAccess(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->department_role) {
            return false;
        }

        // If DB has entries for this role, use them (overrides config)
        $dbModules = DepartmentRoleModule::where('department_role', $this->department_role)
            ->pluck('module');

        if ($dbModules->isNotEmpty()) {
            return $dbModules->contains($module);
        }

        // Fallback: config-based access
        $config = config('department_permissions.' . $this->department_role);

        return $config && in_array($module, $config['modules'] ?? []);
    }

    /**
     * Get the human-readable label for this resident's department role.
     * Example: 'MAYOR' => 'Municipal Mayor'
     */
    public function getDepartmentLabelAttribute(): ?string
    {
        if (!$this->department_role) {
            return null;
        }

        return config('department_permissions.' . $this->department_role . '.label');
    }

    /**
     * Get the full department config for this resident.
     */
    public function getDepartmentConfig(): ?array
    {
        if (!$this->department_role) {
            return null;
        }

        // Allow per-user overrides via department_permissions column
        $config = config('department_permissions.' . $this->department_role, []);

        if ($this->department_permissions) {
            $config = array_merge($config, $this->department_permissions);
        }

        return $config;
    }

    /**
     * Check if this department role is restricted to read-only access.
     * Checks DB access_level first; falls back to config.
     */
    public function isDepartmentReadOnly(): bool
    {
        if ($this->department_role) {
            $dbRow = DepartmentRoleModule::where('department_role', $this->department_role)->first();
            if ($dbRow) {
                return $dbRow->access_level === 'read_only';
            }
        }

        $config = $this->getDepartmentConfig();
        return ($config['access'] ?? 'read_only') === 'read_only';
    }

    /**
     * Check if resident can access e-services
     */
    public function canAccessServices(): bool
    {
        return $this->is_verified && 
               $this->hasVerifiedEmail() && 
               ($this->isSuperAdmin() || $this->isAdmin() || $this->isCitizen());
    }

    /**
     * Check if resident has PhilSys verification
     */
    public function hasPhilSysVerification(): bool
    {
        return !is_null($this->philsys_verified_at);
    }

    /**
     * Check if resident can request official documents
     * Requires PhilSys verification in addition to standard access
     */
    public function canRequestDocuments(): bool
    {
        return $this->canAccessServices() && $this->hasPhilSysVerification();
    }

    /**
     * Check if account is locked due to failed login attempts
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Increment failed login attempts
     */
    public function incrementLoginAttempts(): void
    {
        $this->increment('failed_login_attempts');
        
        // Lock account after 5 failed attempts for 15 minutes
        if ($this->failed_login_attempts >= 5) {
            $this->update([
                'locked_until' => now()->addMinutes(15),
            ]);
        }
    }

    /**
     * Reset failed login attempts on successful login
     */
    public function resetLoginAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    /**
     * Log an activity for this resident
     */
    public function logActivity(string $action, string $description, array $additionalData = []): ActivityLog
    {
        return ActivityLog::log(array_merge([
            'resident_id' => $this->id,
            'user_email' => $this->email,
            'user_role' => $this->role,
            'action' => $action,
            'description' => $description,
        ], $additionalData));
    }

    /**
     * Create a notification for this resident
     */
    public function createNotification(array $data): Notification
    {
        return Notification::notify($this->id, $data);
    }

    /**
     * Get pending service requests count
     */
    public function getPendingRequestsCountAttribute(): int
    {
        return $this->serviceRequests()
            ->whereIn('status', ['pending', 'in-progress'])
            ->count();
    }

    /**
     * Get completed service requests count
     */
    public function getCompletedRequestsCountAttribute(): int
    {
        return $this->serviceRequests()
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Get ready for pickup documents count
     */
    public function getReadyForPickupCountAttribute(): int
    {
        return $this->serviceRequests()
            ->where('status', 'ready-for-pickup')
            ->count();
    }

    /**
     * Get barangay code for this resident
     */
    public function getBarangayCodeAttribute($value): ?string
    {
        // If barangay_code is already set, return it
        if ($value) {
            return $value;
        }

        // Otherwise, derive from barangay name
        if ($this->barangay) {
            return config('barangays.list')[$this->barangay] ?? null;
        }

        return null;
    }

    /**
     * Get formatted barangay display with code
     * Example: "Centro (CEN)"
     */
    public function getFormattedBarangayAttribute(): string
    {
        if ($this->barangay && $this->barangay_code) {
            return $this->barangay . ' (' . $this->barangay_code . ')';
        }

        return $this->barangay ?? 'N/A';
    }

    /**
     * Get barangay tracking prefix
     * Example: "BGY-CEN-"
     */
    public function getBarangayPrefixAttribute(): string
    {
        $code = $this->barangay_code ?? 'XXX';
        return 'BGY-' . $code . '-';
    }

    /**
     * Scope query to specific barangay
     */
    public function scopeInBarangay($query, $barangayNameOrCode)
    {
        // Check if it's a 3-letter code
        if (strlen($barangayNameOrCode) === 3 && ctype_upper($barangayNameOrCode)) {
            return $query->where('barangay_code', $barangayNameOrCode);
        }

        // Otherwise treat as barangay name
        return $query->where('barangay', $barangayNameOrCode);
    }

    /**
     * Get all barangays list from config
     */
    public static function getBarangaysList(): array
    {
        return config('barangays.list', []);
    }

    /**
     * Get barangay names only
     */
    public static function getBarangayNames(): array
    {
        return array_keys(config('barangays.list', []));
    }

    /**
     * Get barangay codes only
     */
    public static function getBarangayCodes(): array
    {
        return array_values(config('barangays.list', []));
    }

    /*
    |--------------------------------------------------------------------------
    | RBAC — Role-Based Access Control
    |--------------------------------------------------------------------------
    |
    | Maps the string `role` column (SA, admin, citizen, visitor) to the
    | `roles` table, then checks permissions through the role_permission pivot.
    |
    */

    /**
     * Get the Role model that matches this resident's role string.
     */
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * Check if the resident has a specific permission via their role.
     * Super Admin always has all permissions.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roleModel?->permissions
            ->pluck('name')
            ->contains($permission) ?? false;
    }
}
