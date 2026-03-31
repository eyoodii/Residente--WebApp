<?php

use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\ChatbotAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CitizenProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\IDScannerController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ResidentProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ResidentManagementController;
use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\HouseholdController;
use App\Http\Controllers\Admin\VerificationDashboardController;
use App\Http\Controllers\Admin\ServiceManagementController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Department\AnalyticsController;
use App\Http\Controllers\Department\MasterCollectionsController;
use App\Http\Controllers\Department\HouseholdManagementController;
use App\Http\Controllers\Department\ActivityLogMonitorController;
use App\Http\Controllers\Department\LocationalClearanceController;
use App\Http\Controllers\Department\BuildingPermitController;
use App\Http\Controllers\Department\FinancialModuleController;
use App\Http\Controllers\Department\ServiceRequestController as DepartmentServiceRequestController;
use App\Http\Controllers\Department\WelfareTargetingController;
use App\Http\Controllers\Department\HealthServicesController;
use App\Http\Controllers\Department\EmergencyAlertController;
use App\Http\Controllers\Department\LivelihoodController;
use App\Http\Controllers\Department\BusinessPermitController;
use App\Http\Controllers\Department\CivilRegistryController;
use App\Http\Controllers\Department\BlotterController;
use App\Http\Controllers\Department\TransparencyBoardController;
use App\Http\Controllers\Department\RoleAssignmentController;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Fetch public announcements (target_barangay is null = for everyone)
    $announcements = Announcement::published()
        ->whereNull('target_barangay')
        ->latest('posted_at')
        ->take(5)
        ->get();
    
    return view('welcome', compact('announcements'));
});

// ── TEMPORARY DEMO PAGE (remove before production) ──────────────────────────
Route::get('/demo', function () {
    return view('demo');
})->name('demo');
// ────────────────────────────────────────────────────────────────────────────

// Terms and Conditions page accessible to everyone
Route::get('/terms-and-conditions', function () {
    return view('auth.termsandcondition');
})->name('terms');

// Privacy Policy page accessible to everyone
Route::get('/privacy-policy', function () {
    return view('auth.privacypolicy');
})->name('privacy');

// Public pages
Route::get('/news-events', function () {
    $announcements = Announcement::published()
        ->latest('posted_at')
        ->paginate(12);
    return view('news-events', compact('announcements'));
})->name('news-events');

Route::get('/memos', function () {
    return view('memos');
})->name('memos');

Route::get('/services-directory', function () {
    return view('public-services');
})->name('public.services');

Route::get('/e-bugueyano', function () {
    return view('e-bugueyano');
})->name('e-bugueyano');

// AI Services Assistant (publicly accessible chat page + throttled API)
Route::get('/ask', [AiAssistantController::class, 'index'])->name('ai-assistant.index');
Route::post('/ask/chat', [AiAssistantController::class, 'chat'])
    ->middleware('throttle:30,1')
    ->name('ai-assistant.chat');

// ── RESIDENTE Chatbot Widget API (public, throttled) ────────────────────────
Route::prefix('chatbot')->name('chatbot.')->middleware('throttle:60,1')->group(function () {
    Route::post('/chat',         [ChatbotController::class, 'chat'])->name('chat');
    Route::post('/handoff',      [ChatbotController::class, 'handoff'])->name('handoff');
    Route::post('/quick-action', [ChatbotController::class, 'quickAction'])->name('quick-action');
});
// ────────────────────────────────────────────────────────────────────────────

// About section routes
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/history', function () {
        return view('about.history');
    })->name('history');
    
    Route::get('/demographic-profile', function () {
        return view('about.demographic');
    })->name('demographic');
    
    Route::get('/barangay-list-map', function () {
        return view('about.barangay-list-map');
    })->name('barangay-list-map');
    
    Route::get('/map', function () {
        return view('about.map');
    })->name('map');
    
    Route::get('/barangay-list', function () {
        return view('about.barangay-list');
    })->name('barangay-list');
    
    Route::get('/subdivision-map', function () {
        return view('about.subdivision-map');
    })->name('subdivision-map');
    
    Route::get('/hymn', function () {
        return view('about.hymn');
    })->name('hymn');
});

// Session keep-alive endpoint (prevents 419 errors on long forms)
Route::post('/keep-alive', function () {
    return response()->json(['status' => 'alive', 'csrf' => csrf_token()]);
})->middleware(['web'])->name('keep-alive');

// The throttle:6,1 middleware restricts users to 6 attempts per minute per IP address
Route::middleware(['guest', 'throttle:6,1'])->group(function () {
    Route::get('/register', [AuthController::class, 'create'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Limits login attempts to 5 per minute per IP address to stop brute-force bots
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');
});

// Logout route - requires authentication
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// The 'verified' middleware restricts access to users with a non-null email_verified_at timestamp
// Dashboard is accessible to all authenticated users (verified or not)
Route::middleware(['auth', 'lockout'])->group(function () {
    // Dashboard - accessible to all authenticated users (shows verification prompts if needed)
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->middleware(['verified', 'philsys.complete'])
        ->name('dashboard');
    
    // AJAX endpoint for loading more announcements
    Route::get('/dashboard/load-more-announcements', [App\Http\Controllers\DashboardController::class, 'loadMoreAnnouncements'])
        ->middleware('verified')
        ->name('dashboard.load-more-announcements');
});

// Routes requiring email verification (without onboarding requirement)
Route::middleware(['auth', 'verified', 'lockout', 'philsys.complete'])->group(function () {
    // Profile Onboarding (Required after PhilSys verification)
    Route::prefix('profile/onboarding')->name('profile.onboarding.')->group(function () {
        Route::get('/', [ProfileController::class, 'showOnboarding'])->name('show');
        Route::post('/', [ProfileController::class, 'storeOnboarding'])->name('store');
    });
    
    // Triple-Key Profile Setup (New step-by-step wizard)
    Route::prefix('profile/setup')->name('profile.setup.')->group(function () {
        Route::get('/', [ResidentProfileController::class, 'showSetup'])->name('index');
        Route::post('/location', [ResidentProfileController::class, 'storeLocation'])->name('location');
        Route::post('/role', [ResidentProfileController::class, 'storeRole'])->name('role');
        Route::post('/identity', [ResidentProfileController::class, 'storeIdentity'])->name('identity');
        Route::post('/details', [ResidentProfileController::class, 'storeDetails'])->name('details');
        
        // AJAX endpoints for smart form
        Route::post('/check-address', [ResidentProfileController::class, 'checkAddress'])->name('check-address');
        Route::post('/search-heads', [ResidentProfileController::class, 'searchMatchingHeads'])->name('search-heads');
        Route::get('/triple-key', [ResidentProfileController::class, 'getTripleKey'])->name('triple-key');
    });
    
    // Legacy route alias
    Route::get('/profile/setup', [ResidentProfileController::class, 'showSetup'])->name('profile.setup');
    
    // PhilSys Verification Routes (Level 2.5 - Bridge to Level 3)
    Route::prefix('verification')->name('verification.')->group(function () {
        Route::get('/philsys', [VerificationController::class, 'showPhilSysVerification'])->name('philsys');
        Route::post('/philsys', [VerificationController::class, 'verifyPhilSys'])->name('philsys.verify');
        
        // PhilSys API endpoints for real-time validation
        Route::post('/philsys/validate-format', [VerificationController::class, 'validateNationalIdFormat'])->name('philsys.validate-format');
        Route::post('/philsys/parse-qr', [VerificationController::class, 'parseQrCode'])->name('philsys.parse-qr');
    });
});

// Routes requiring email verification AND onboarding completion
Route::middleware(['auth', 'verified', 'lockout', 'onboarding.complete'])->group(function () {
    // Citizen Profile Management - accessible to all verified and onboarded users
    Route::prefix('citizen/profile')->name('citizen.profile.')->group(function () {
        Route::get('/', [CitizenProfileController::class, 'index'])->name('index');
        
        // Personal Information
        Route::get('/personal/edit', [CitizenProfileController::class, 'editPersonal'])->name('personal.edit');
        Route::put('/personal', [CitizenProfileController::class, 'updatePersonal'])->name('personal.update');
        
        // Address Information
        Route::get('/address/edit', [CitizenProfileController::class, 'editAddress'])->name('address.edit');
        Route::put('/address', [CitizenProfileController::class, 'updateAddress'])->name('address.update');
        
        // Household Profile
        Route::get('/household/edit', [CitizenProfileController::class, 'editHousehold'])->name('household.edit');
        Route::put('/household', [CitizenProfileController::class, 'updateHousehold'])->name('household.update');
        
        // Household Members
        Route::get('/members/add', [CitizenProfileController::class, 'addMember'])->name('members.add');
        Route::post('/members', [CitizenProfileController::class, 'storeMember'])->name('members.store');
        Route::delete('/members/{member}', [CitizenProfileController::class, 'deleteMember'])->name('members.delete');
    });
    
    // Shortcut route for profile
    Route::get('/profile/citizen', [CitizenProfileController::class, 'index'])->name('citizen.profile');
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
    });
    
    // Activity Logs
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
    });
    
    // Service routes - restricted to citizens and admins only (not visitors)
    // Level 2: Basic service browsing (Email verified citizens can view)
    Route::middleware('citizen')->group(function () {
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
        Route::get('/my-requests', [ServiceController::class, 'myRequests'])->name('services.my-requests');
        Route::get('/service-request/{requestNumber}', [ServiceController::class, 'showRequest'])->name('service-request.show');
        
        // Level 3: RESTRICTED E-SERVICES (Requires PhilSys Verification AND Onboarding Complete)
        // Citizens can only submit service requests if their PhilSys ID was successfully scanned AND profile is complete
        Route::middleware(['philsys.verified', 'onboarding.complete'])->group(function () {
            Route::post('/services/{slug}/request', [ServiceController::class, 'request'])->name('services.request');
            Route::post('/service-request/{requestNumber}/upload', [ServiceController::class, 'uploadDocument'])->name('service-request.upload');
            Route::post('/service-request/{requestNumber}/cancel', [ServiceController::class, 'cancel'])->name('service-request.cancel');
            Route::get('/service-request/{requestNumber}/download/{documentId}', [ServiceController::class, 'downloadDocument'])->name('service-request.download');
        });
    });
    
    // Admin Routes - restricted to admin and SA (Super Admin) roles
    Route::middleware('role:admin,SA')->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // E-Services Management (SuperAdmin Dynamic Service Control)
        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/', [ServiceManagementController::class, 'index'])->name('index');
            Route::get('/create', [ServiceManagementController::class, 'create'])->name('create');
            Route::post('/', [ServiceManagementController::class, 'store'])->name('store');
            Route::get('/{service}', [ServiceManagementController::class, 'show'])->name('show');
            Route::get('/{service}/edit', [ServiceManagementController::class, 'edit'])->name('edit');
            Route::put('/{service}', [ServiceManagementController::class, 'update'])->name('update');
            Route::patch('/{service}/toggle', [ServiceManagementController::class, 'toggleStatus'])->name('toggle');
            Route::delete('/{service}', [ServiceManagementController::class, 'destroy'])->name('destroy');
        });
        
        // Master Collections - View all household/family data collections
        Route::get('/master-collections', [AdminDashboardController::class, 'masterCollections'])->name('master-collections');
        
        // Barangay Overview - Statistics by barangay
        Route::get('/barangay-overview', [AdminDashboardController::class, 'barangayOverview'])->name('barangay-overview');
        
        // Validation Flags - Auto-linked residents needing validation
        Route::get('/validation-flags', [AdminDashboardController::class, 'validationFlags'])->name('validation-flags');
        
        // Resident Management
        Route::prefix('residents')->name('residents.')->group(function () {
            Route::get('/', [ResidentManagementController::class, 'index'])->name('index');
            Route::get('/{resident}', [ResidentManagementController::class, 'show'])->name('show');
            Route::get('/{resident}/verify', [ResidentManagementController::class, 'verifyForm'])->name('verify');
            Route::post('/{resident}/verify', [ResidentManagementController::class, 'verify'])->name('verify.store');
            Route::post('/{resident}/revoke', [ResidentManagementController::class, 'revoke'])->name('revoke');
            Route::post('/{resident}/promote', [ResidentManagementController::class, 'promoteToAdmin'])->name('promote');
            Route::post('/{resident}/unlock', [ResidentManagementController::class, 'unlock'])->name('unlock');
            Route::delete('/{resident}', [ResidentManagementController::class, 'destroy'])->name('destroy');

            // PhilSys Verification Management
            Route::post('/{resident}/philsys-verify', [VerificationController::class, 'adminManualVerify'])->name('philsys-verify');
            Route::post('/{resident}/philsys-revoke', [VerificationController::class, 'revokePhilSysVerification'])->name('philsys-revoke');

            // SA-Only: Serve private PhilSys card image
            Route::get('/{resident}/philsys-id/{side}', [ResidentManagementController::class, 'servePhilSysImage'])
                ->name('philsys-image')
                ->middleware('role:SA');
        });
        
        // Activity Log Monitoring
        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [AdminActivityLogController::class, 'index'])->name('index');
            Route::get('/suspicious', [AdminActivityLogController::class, 'suspicious'])->name('suspicious');
            Route::get('/{activityLog}', [AdminActivityLogController::class, 'show'])->name('show');
        });
        
        // ID Scanner Routes
        Route::prefix('id-scanner')->name('id-scanner.')->group(function () {
            Route::get('/', function () {
                return view('admin.id-scanner.index');
            })->name('index');
            Route::post('/scan', [IDScannerController::class, 'scan'])->name('scan');
            Route::get('/list', [IDScannerController::class, 'index'])->name('list');
            Route::get('/{id}', [IDScannerController::class, 'show'])->name('show');
            Route::patch('/{id}/status', [IDScannerController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{id}', [IDScannerController::class, 'destroy'])->name('destroy');
            Route::post('/auto-fill', [IDScannerController::class, 'autoFill'])->name('auto-fill');
        });

        // Super Admin Data Collection Dashboard (HN → HHN → HHM Hierarchy)
        Route::prefix('data-collection')->name('data-collection.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DataCollectionController::class, 'index'])->name('index');
            Route::post('/approve-link/{resident}', [\App\Http\Controllers\Admin\DataCollectionController::class, 'approveAutoLink'])->name('approve-link');
            Route::post('/reject-link/{resident}', [\App\Http\Controllers\Admin\DataCollectionController::class, 'rejectAutoLink'])->name('reject-link');
        });

        // Household Management Routes (LGU Secretary Drill-Down System)
        Route::prefix('households')->name('households.')->group(function () {
            // Dashboard
            Route::get('/', [HouseholdController::class, 'index'])->name('index');
            Route::get('/stats', [HouseholdController::class, 'getStats'])->name('stats');
            
            // Level 1: Search by Address/HN
            Route::get('/search/address', [HouseholdController::class, 'searchByAddress'])->name('search.address');
            Route::get('/create', [HouseholdController::class, 'createHousehold'])->name('create');
            Route::post('/', [HouseholdController::class, 'storeHousehold'])->name('store');
            Route::get('/{household}', [HouseholdController::class, 'showHousehold'])->name('show');
            
            // Level 2: Search by HHN/Head Name
            Route::get('/search/head', [HouseholdController::class, 'searchByHead'])->name('search.head');
            Route::get('/{household}/head/create', [HouseholdController::class, 'createHead'])->name('head.create');
            Route::post('/{household}/head', [HouseholdController::class, 'storeHead'])->name('head.store');
            Route::get('/head/{householdHead}', [HouseholdController::class, 'showHouseholdHead'])->name('head.show');
            Route::get('/head/{householdHead}/edit', [HouseholdController::class, 'editHead'])->name('head.edit');
            Route::put('/head/{householdHead}', [HouseholdController::class, 'updateHead'])->name('head.update');
            Route::delete('/head/{householdHead}', [HouseholdController::class, 'destroyHead'])->name('head.destroy');
            
            // Level 3: Individual Search
            Route::get('/search/individual', [HouseholdController::class, 'searchIndividual'])->name('search.individual');
            Route::get('/individual/{type}/{id}', [HouseholdController::class, 'showIndividual'])->name('individual.show');
            
            // Member Management
            Route::get('/head/{householdHead}/member/create', [HouseholdController::class, 'createMember'])->name('member.create');
            Route::post('/head/{householdHead}/member', [HouseholdController::class, 'storeMember'])->name('member.store');
            Route::get('/head/{householdHead}/member/{householdMember}/edit', [HouseholdController::class, 'editMember'])->name('member.edit');
            Route::put('/head/{householdHead}/member/{householdMember}', [HouseholdController::class, 'updateMember'])->name('member.update');
            Route::delete('/head/{householdHead}/member/{householdMember}', [HouseholdController::class, 'destroyMember'])->name('member.destroy');
            
            // Auto-linking
            Route::post('/head/{householdHead}/auto-link', [HouseholdController::class, 'autoLink'])->name('auto-link');
            Route::post('/confirm-auto-link', [HouseholdController::class, 'confirmAutoLink'])->name('confirm-auto-link');
        });
        
        // Verification Dashboard (LGU Secretary Cross-Verification)
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', [VerificationDashboardController::class, 'index'])->name('dashboard');
            Route::get('/household/{household}', [VerificationDashboardController::class, 'verifyHousehold'])->name('household');
            Route::get('/family/{householdHead}', [VerificationDashboardController::class, 'verifyFamily'])->name('family');
            Route::get('/search', [VerificationDashboardController::class, 'searchResidents'])->name('search');
            Route::get('/fix-ghost/{resident}', [VerificationDashboardController::class, 'showFixGhost'])->name('fix-ghost');
            
            // Actions
            Route::post('/transfer-member', [VerificationDashboardController::class, 'transferMember'])->name('transfer-member');
            Route::post('/fix-ghost', [VerificationDashboardController::class, 'fixGhostMember'])->name('fix-ghost.store');
            
            // AJAX
            Route::get('/ghost-members', [VerificationDashboardController::class, 'getGhostMembers'])->name('ghost-members');
            Route::get('/resident/{resident}/triple-key', [VerificationDashboardController::class, 'getResidentTripleKey'])->name('resident-triple-key');
        });

        // Role Permissions Management (SA only)
        Route::middleware('role:SA')->prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/',  [RolePermissionController::class, 'index'])->name('index');
            Route::post('/', [RolePermissionController::class, 'store'])->name('store');
            Route::put('/department/{departmentRole}', [RolePermissionController::class, 'updateDepartment'])->name('department.update');
            Route::put('/{role}',        [RolePermissionController::class, 'update'])->name('update');
            Route::patch('/{role}/meta', [RolePermissionController::class, 'updateMeta'])->name('updateMeta');
            Route::delete('/{role}',     [RolePermissionController::class, 'destroy'])->name('destroy');
        });

        // Chatbot Management (Admin + SA)
        Route::prefix('chatbot')->name('chatbot.')->group(function () {
            Route::get('/',                                   [ChatbotAdminController::class, 'index'])->name('index');
            Route::get('/create',                             [ChatbotAdminController::class, 'create'])->name('create');
            Route::post('/',                                  [ChatbotAdminController::class, 'store'])->name('store');
            Route::get('/{chatbot}/edit',                     [ChatbotAdminController::class, 'edit'])->name('edit');
            Route::put('/{chatbot}',                          [ChatbotAdminController::class, 'update'])->name('update');
            Route::delete('/{chatbot}',                       [ChatbotAdminController::class, 'destroy'])->name('destroy');
            Route::patch('/{chatbot}/toggle',                 [ChatbotAdminController::class, 'toggleActive'])->name('toggle');
            // Unanswered queries / AI audit
            Route::get('/unanswered',                         [ChatbotAdminController::class, 'unanswered'])->name('unanswered');
            Route::patch('/unanswered/{query}/reviewed',      [ChatbotAdminController::class, 'markReviewed'])->name('mark-reviewed');
            Route::patch('/unanswered/bulk-reviewed',         [ChatbotAdminController::class, 'bulkMarkReviewed'])->name('bulk-reviewed');
            // Handoff queue
            Route::get('/handoffs',                           [ChatbotAdminController::class, 'handoffs'])->name('handoffs');
            Route::patch('/handoffs/{handoff}',               [ChatbotAdminController::class, 'updateHandoff'])->name('update-handoff');
            // API Keys
            Route::prefix('api-keys')->name('api-keys.')->group(function () {
                Route::get('/',                                   [\App\Http\Controllers\Admin\ChatbotApiKeyController::class, 'index'])->name('index');
                Route::post('/',                                  [\App\Http\Controllers\Admin\ChatbotApiKeyController::class, 'store'])->name('store');
                Route::patch('/{apiKey}/revoke',                  [\App\Http\Controllers\Admin\ChatbotApiKeyController::class, 'revoke'])->name('revoke');
            });
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Department Staff Portal Routes
|--------------------------------------------------------------------------
| These routes are for LGU department staff (e.g. Mayor, Treasurer, HRMO).
| All require auth + a valid department_role on the user.
| Super Admin also has access to these routes (bypasses middleware check).
*/
Route::prefix('department')
    ->middleware(['auth', 'department'])
    ->name('department.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Department\DepartmentDashboardController::class, 'index'])
            ->name('dashboard');

        // ──────────────────────────────────────────────────────
        // SHARED: Population Analytics (multi-role, read-only)
        // ──────────────────────────────────────────────────────
        Route::prefix('analytics')->name('analytics.')
            ->middleware('department:MAYOR,VMYOR,MPDC,ENGR,BUDGT,MSWDO,DRRMO,AGRI,SEPD')
            ->group(function () {
                Route::get('/', [AnalyticsController::class, 'index'])->name('index');
                Route::get('/barangay', [AnalyticsController::class, 'barangay'])->name('barangay');
                Route::get('/demographics', [AnalyticsController::class, 'demographics'])->name('demographics');
                Route::get('/services', [AnalyticsController::class, 'services'])->name('services');
            });

        // ──────────────────────────────────────────────────────
        // SHARED: Master Collections (multi-role, read-only)
        // ──────────────────────────────────────────────────────
        Route::prefix('master-collections')->name('master-collections.')
            ->middleware('department:MAYOR,VMYOR,MPDC,ASSOR,BUDGT,MSWDO,MHO,DRRMO,AGRI,REGST')
            ->group(function () {
                Route::get('/', [MasterCollectionsController::class, 'index'])->name('index');
                Route::get('/households', [MasterCollectionsController::class, 'households'])->name('households');
                Route::get('/demographics', [MasterCollectionsController::class, 'demographics'])->name('demographics');
                Route::get('/export', [MasterCollectionsController::class, 'export'])->name('export');
            });

        // ──────────────────────────────────────────────────────
        // SHARED: Household Management (multi-role)
        // ──────────────────────────────────────────────────────
        Route::prefix('households')->name('households.')
            ->middleware('department:MPDC,ENGR,ASSOR,MSWDO,MHO,DRRMO')
            ->group(function () {
                Route::get('/', [HouseholdManagementController::class, 'index'])->name('index');
                Route::get('/{household}', [HouseholdManagementController::class, 'show'])->name('show');
            });

        // ──────────────────────────────────────────────────────
        // SHARED: Activity Log Monitor (multi-role, read-only)
        // ──────────────────────────────────────────────────────
        Route::prefix('activity-logs')->name('activity-logs.')
            ->middleware('department:MAYOR,TRESR,ACCT,BPLO,HRMO,SEPD')
            ->group(function () {
                Route::get('/', [ActivityLogMonitorController::class, 'index'])->name('index');
                Route::get('/{activityLog}', [ActivityLogMonitorController::class, 'show'])->name('show');
            });

        // ──────────────────────────────────────────────────────
        // PLANNING: Locational Clearance (MPDC only)
        // ──────────────────────────────────────────────────────
        Route::prefix('locational-clearance')->name('locational-clearance.')
            ->middleware('department:MPDC')
            ->group(function () {
                Route::get('/', [LocationalClearanceController::class, 'index'])->name('index');
                Route::get('/{serviceRequest}', [LocationalClearanceController::class, 'show'])->name('show');
                Route::patch('/{serviceRequest}/approve', [LocationalClearanceController::class, 'approve'])->name('approve');
                Route::patch('/{serviceRequest}/reject', [LocationalClearanceController::class, 'reject'])->name('reject');
            });

        // ──────────────────────────────────────────────────────
        // ENGINEERING: Building Permits (ENGR only)
        // ──────────────────────────────────────────────────────
        Route::prefix('building-permits')->name('building-permits.')
            ->middleware('department:ENGR')
            ->group(function () {
                Route::get('/', [BuildingPermitController::class, 'index'])->name('index');
                Route::get('/{serviceRequest}', [BuildingPermitController::class, 'show'])->name('show');
                Route::patch('/{serviceRequest}/approve', [BuildingPermitController::class, 'approve'])->name('approve');
                Route::patch('/{serviceRequest}/reject', [BuildingPermitController::class, 'reject'])->name('reject');
            });

        // ──────────────────────────────────────────────────────
        // FINANCIAL MODULE (TRESR, ACCT, BUDGT)
        // ──────────────────────────────────────────────────────
        Route::prefix('finance')->name('finance.')
            ->middleware('department:TRESR,ACCT,BUDGT')
            ->group(function () {
                Route::get('/', [FinancialModuleController::class, 'index'])->name('index');
                Route::get('/transactions', [FinancialModuleController::class, 'transactions'])->name('transactions');
                // Treasurer only: reconcile payment records
                Route::patch('/transactions/{serviceRequest}/reconcile', [FinancialModuleController::class, 'reconcile'])
                    ->name('transactions.reconcile')
                    ->middleware('department:TRESR');
                // Accountant only: audit log
                Route::get('/audit-log', [FinancialModuleController::class, 'auditLog'])
                    ->name('audit-log')
                    ->middleware('department:ACCT');
                // Budget Officer only: financial forecast
                Route::get('/forecast', [FinancialModuleController::class, 'forecast'])
                    ->name('forecast')
                    ->middleware('department:BUDGT');
            });

        // ──────────────────────────────────────────────────────
        // SHARED: Service Requests (TRESR, MHO, BPLO, REGST)
        // ──────────────────────────────────────────────────────
        Route::prefix('service-requests')->name('service-requests.')
            ->middleware('department:TRESR,MHO,BPLO,REGST')
            ->group(function () {
                Route::get('/', [DepartmentServiceRequestController::class, 'index'])->name('index');
                Route::get('/{serviceRequest}', [DepartmentServiceRequestController::class, 'show'])->name('show');
                Route::patch('/{serviceRequest}/approve', [DepartmentServiceRequestController::class, 'approve'])->name('approve');
                Route::patch('/{serviceRequest}/reject', [DepartmentServiceRequestController::class, 'reject'])->name('reject');
                Route::patch('/{serviceRequest}/ready', [DepartmentServiceRequestController::class, 'markReady'])->name('ready');
            });

        // ──────────────────────────────────────────────────────
        // SOCIAL WELFARE: Targeting & Profiling (MSWDO)
        // ──────────────────────────────────────────────────────
        Route::prefix('welfare')->name('welfare.')
            ->middleware('department:MSWDO')
            ->group(function () {
                Route::get('/', [WelfareTargetingController::class, 'index'])->name('index');
                Route::get('/vulnerable', [WelfareTargetingController::class, 'vulnerable'])->name('vulnerable');
                Route::get('/sectors/{sector}', [WelfareTargetingController::class, 'bySector'])->name('by-sector');
                Route::get('/export', [WelfareTargetingController::class, 'export'])->name('export');
            });

        // ──────────────────────────────────────────────────────
        // HEALTH SERVICES (MHO)
        // ──────────────────────────────────────────────────────
        Route::prefix('health')->name('health.')
            ->middleware('department:MHO')
            ->group(function () {
                Route::get('/', [HealthServicesController::class, 'index'])->name('index');
                Route::get('/sanitation', [HealthServicesController::class, 'sanitation'])->name('sanitation');
                Route::get('/water-sources', [HealthServicesController::class, 'waterSources'])->name('water-sources');
            });

        // ──────────────────────────────────────────────────────
        // DISASTER RISK: Emergency Alerts (DRRMO)
        // ──────────────────────────────────────────────────────
        Route::prefix('emergency')->name('emergency.')
            ->middleware('department:DRRMO')
            ->group(function () {
                Route::get('/', [EmergencyAlertController::class, 'index'])->name('index');
                Route::get('/flood-prone', [EmergencyAlertController::class, 'floodProne'])->name('flood-prone');
                Route::get('/alerts', [EmergencyAlertController::class, 'alerts'])->name('alerts');
                Route::post('/broadcast', [EmergencyAlertController::class, 'broadcast'])->name('broadcast');
            });

        // ──────────────────────────────────────────────────────
        // AGRICULTURE & LIVELIHOOD (AGRI)
        // ──────────────────────────────────────────────────────
        Route::prefix('livelihood')->name('livelihood.')
            ->middleware('department:AGRI')
            ->group(function () {
                Route::get('/', [LivelihoodController::class, 'index'])->name('index');
                Route::get('/farmers', [LivelihoodController::class, 'farmers'])->name('farmers');
                Route::get('/fisheries', [LivelihoodController::class, 'fisheries'])->name('fisheries');
                Route::get('/livestock', [LivelihoodController::class, 'livestock'])->name('livestock');
                Route::get('/aquaculture', [LivelihoodController::class, 'aquaculture'])->name('aquaculture');
            });

        // ──────────────────────────────────────────────────────
        // BUSINESS PERMITS (BPLO)
        // ──────────────────────────────────────────────────────
        Route::prefix('business-permits')->name('business-permits.')
            ->middleware('department:BPLO')
            ->group(function () {
                Route::get('/', [BusinessPermitController::class, 'index'])->name('index');
                Route::get('/{serviceRequest}', [BusinessPermitController::class, 'show'])->name('show');
                Route::patch('/{serviceRequest}/approve', [BusinessPermitController::class, 'approve'])->name('approve');
                Route::patch('/{serviceRequest}/reject', [BusinessPermitController::class, 'reject'])->name('reject');
                Route::patch('/{serviceRequest}/route-to-engineer', [BusinessPermitController::class, 'routeToEngineer'])->name('route-to-engineer');
                Route::patch('/{serviceRequest}/route-to-health', [BusinessPermitController::class, 'routeToHealth'])->name('route-to-health');
            });

        // ──────────────────────────────────────────────────────
        // CIVIL REGISTRY (REGST)
        // ──────────────────────────────────────────────────────
        Route::prefix('civil-registry')->name('civil-registry.')
            ->middleware('department:REGST')
            ->group(function () {
                Route::get('/', [CivilRegistryController::class, 'index'])->name('index');
                Route::get('/verification', [CivilRegistryController::class, 'verificationDashboard'])->name('verification');
                Route::get('/{serviceRequest}', [CivilRegistryController::class, 'show'])->name('show');
                Route::patch('/{serviceRequest}/approve', [CivilRegistryController::class, 'approve'])->name('approve');
                Route::patch('/{serviceRequest}/reject', [CivilRegistryController::class, 'reject'])->name('reject');
            });

        // ──────────────────────────────────────────────────────
        // SECURITY & ENFORCEMENT: Blotter (SEPD)
        // ──────────────────────────────────────────────────────
        Route::prefix('blotter')->name('blotter.')
            ->middleware('department:SEPD')
            ->group(function () {
                Route::get('/', [BlotterController::class, 'index'])->name('index');
                Route::get('/create', [BlotterController::class, 'create'])->name('create');
                Route::post('/', [BlotterController::class, 'store'])->name('store');
                Route::get('/{blotter}', [BlotterController::class, 'show'])->name('show');
                Route::patch('/{blotter}/resolve', [BlotterController::class, 'resolve'])->name('resolve');
            });

        // ──────────────────────────────────────────────────────
        // TRANSPARENCY BOARD (SBSEC)
        // ──────────────────────────────────────────────────────
        Route::prefix('transparency-board')->name('transparency-board.')
            ->middleware('department:SBSEC')
            ->group(function () {
                Route::get('/', [TransparencyBoardController::class, 'index'])->name('index');
                Route::get('/create', [TransparencyBoardController::class, 'create'])->name('create');
                Route::post('/', [TransparencyBoardController::class, 'store'])->name('store');
                Route::get('/{announcement}/edit', [TransparencyBoardController::class, 'edit'])->name('edit');
                Route::put('/{announcement}', [TransparencyBoardController::class, 'update'])->name('update');
                Route::delete('/{announcement}', [TransparencyBoardController::class, 'destroy'])->name('destroy');
                Route::patch('/{announcement}/publish', [TransparencyBoardController::class, 'publish'])->name('publish');
                Route::patch('/{announcement}/unpublish', [TransparencyBoardController::class, 'unpublish'])->name('unpublish');
            });

        // ──────────────────────────────────────────────────────
        // HRMO: Role Assignment
        // ──────────────────────────────────────────────────────
        Route::prefix('role-assignment')->name('role-assignment.')
            ->middleware('department:HRMO')
            ->group(function () {
                Route::get('/', [RoleAssignmentController::class, 'index'])->name('index');
                Route::post('/assign/{resident}', [RoleAssignmentController::class, 'assign'])->name('assign');
                Route::delete('/revoke/{resident}', [RoleAssignmentController::class, 'revoke'])->name('revoke');
            });

        // HRMO: Staff Management
        Route::prefix('staff')->name('staff.')->middleware('department:HRMO')->group(function () {
            Route::get('/', [\App\Http\Controllers\Department\StaffManagementController::class, 'index'])
                ->name('index');
            Route::get('/create', [\App\Http\Controllers\Department\StaffManagementController::class, 'create'])
                ->name('create');
            Route::post('/', [\App\Http\Controllers\Department\StaffManagementController::class, 'store'])
                ->name('store');
            Route::get('/{resident}/edit', [\App\Http\Controllers\Department\StaffManagementController::class, 'edit'])
                ->name('edit');
            Route::patch('/{resident}', [\App\Http\Controllers\Department\StaffManagementController::class, 'update'])
                ->name('update');
        });
    });

require __DIR__.'/auth.php';

