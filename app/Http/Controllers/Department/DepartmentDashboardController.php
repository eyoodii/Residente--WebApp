<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Household;
use App\Models\HouseholdProfile;
use App\Models\HouseholdMember;
use App\Models\ActivityLog;
use App\Models\ServiceRequest;
use App\Models\Announcement;
use Carbon\Carbon;

class DepartmentDashboardController extends Controller
{
    // Maps department_role codes to their cluster view
    private const CLUSTER_MAP = [
        'MAYOR'  => 'executive',
        'VMYOR'  => 'executive',
        'MPDC'   => 'planning',
        'ENGR'   => 'planning',
        'ASSOR'  => 'planning',
        'TRESR'  => 'financial',
        'ACCT'   => 'financial',
        'BUDGT'  => 'financial',
        'MSWDO'  => 'social',
        'MHO'    => 'social',
        'DRRMO'  => 'social',
        'AGRI'   => 'sector',
        'BPLO'   => 'sector',
        'REGST'  => 'sector',
        'SEPD'   => 'sector',
        'SBSEC'  => 'sector',
        'HRMO'   => 'hrmo',
        // Sangguniang Bayan Committee Chairs
        'SBFIN'  => 'legislative',
        'SBHLT'  => 'legislative',
        'SBWMN'  => 'legislative',
        'SBRLS'  => 'legislative',
        'SBPIC'  => 'legislative',
        'SBTSP'  => 'legislative',
        'SBPWK'  => 'legislative',
        'SBAGR'  => 'legislative',
        'SBBGA'  => 'legislative',
        // SK Federation
        'SKPRS'  => 'legislative',
    ];

    public function index()
    {
        $user = auth()->user();
        $config = $user->getDepartmentConfig() ?? [];
        $cluster = self::CLUSTER_MAP[$user->department_role] ?? null;

        if (!$cluster) {
            // Fallback to the generic dashboard
            return view('department.dashboard', [
                'user'    => $user,
                'config'  => $config,
                'modules' => $config['modules'] ?? [],
                'stats'   => [],
            ]);
        }

        $data = $this->{'load' . ucfirst($cluster) . 'Data'}($user);

        return view("department.roles.{$cluster}", array_merge([
            'user'   => $user,
            'config' => $config,
        ], $data));
    }

    // -----------------------------------------------------------------------
    // EXECUTIVE (MAYOR, VMYOR)
    // -----------------------------------------------------------------------
    private function loadExecutiveData($user): array
    {
        $totalResidents  = Resident::where('role', 'citizen')->count();
        $verifiedCount   = Resident::where('is_verified', true)->where('role', 'citizen')->count();
        $totalHouseholds = Household::count();
        $recentLogs      = ActivityLog::latest()->take(10)->get();

        // Barangay population breakdown
        $barangayStats = Resident::where('role', 'citizen')
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // Service request summary
        $serviceStats = [
            'total'     => ServiceRequest::count(),
            'pending'   => ServiceRequest::where('status', 'pending')->count(),
            'completed' => ServiceRequest::where('status', 'completed')->count(),
        ];

        return compact('totalResidents', 'verifiedCount', 'totalHouseholds', 'recentLogs', 'barangayStats', 'serviceStats');
    }

    // -----------------------------------------------------------------------
    // PLANNING & ENGINEERING (MPDC, ENGR, ASSOR)
    // -----------------------------------------------------------------------
    private function loadPlanningData($user): array
    {
        $floodProneCount     = Resident::where('flood_prone', true)->count();
        $floodProneByBarangay = Resident::where('flood_prone', true)
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->get();

        $houseMaterials = Resident::whereNotNull('house_materials')
            ->selectRaw('house_materials, COUNT(*) as count')
            ->groupBy('house_materials')
            ->orderByDesc('count')
            ->get();

        $waterSources = Resident::whereNotNull('water_source')
            ->selectRaw('water_source, COUNT(*) as count')
            ->groupBy('water_source')
            ->orderByDesc('count')
            ->get();

        $totalResidents  = Resident::where('role', 'citizen')->count();
        $totalHouseholds = Household::count();

        return compact('floodProneCount', 'floodProneByBarangay', 'houseMaterials', 'waterSources', 'totalResidents', 'totalHouseholds');
    }

    // -----------------------------------------------------------------------
    // FINANCIAL (TRESR, ACCT, BUDGT)
    // -----------------------------------------------------------------------
    private function loadFinancialData($user): array
    {
        $serviceStats = [
            'total'     => ServiceRequest::count(),
            'pending'   => ServiceRequest::where('status', 'pending')->count(),
            'completed' => ServiceRequest::where('status', 'completed')->count(),
            'rejected'  => ServiceRequest::where('status', 'rejected')->count(),
        ];

        $auditLogs = ActivityLog::latest()->take(20)->get();

        $barangayPopulation = Resident::where('role', 'citizen')
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->get();

        $totalResidents = Resident::where('role', 'citizen')->count();

        return compact('serviceStats', 'auditLogs', 'barangayPopulation', 'totalResidents');
    }

    // -----------------------------------------------------------------------
    // SOCIAL & EMERGENCY (MSWDO, MHO, DRRMO)
    // -----------------------------------------------------------------------
    private function loadSocialData($user): array
    {
        $vulnerableSectors = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->selectRaw('vulnerable_sector, barangay, COUNT(*) as count')
            ->groupBy('vulnerable_sector', 'barangay')
            ->orderBy('vulnerable_sector')
            ->get();

        $vulnerableSummary = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->selectRaw('vulnerable_sector, COUNT(*) as count')
            ->groupBy('vulnerable_sector')
            ->get();

        $floodProneHouseholds = Resident::where('flood_prone', true)
            ->select('first_name', 'last_name', 'barangay', 'purok', 'household_number')
            ->take(50)
            ->get();

        $sanitaryData = [
            'with_toilet'    => Resident::where('sanitary_toilet', true)->count(),
            'without_toilet' => Resident::where('sanitary_toilet', false)->count(),
        ];

        $waterSources = Resident::whereNotNull('water_source')
            ->selectRaw('water_source, COUNT(*) as count')
            ->groupBy('water_source')
            ->get();

        $announcements = Announcement::latest()->take(5)->get();

        return compact('vulnerableSectors', 'vulnerableSummary', 'floodProneHouseholds', 'sanitaryData', 'waterSources', 'announcements');
    }

    // -----------------------------------------------------------------------
    // SECTOR & LICENSING (AGRI, BPLO, REGST, SEPD, SBSEC)
    // -----------------------------------------------------------------------
    private function loadSectorData($user): array
    {
        // Agriculture data
        $agricultureFarmers = Resident::whereNotNull('crops')
            ->orWhereNotNull('aquaculture')
            ->orWhereNotNull('livestock')
            ->orWhereNotNull('fisheries')
            ->count();

        // Civil registry: service requests
        $serviceRequests = ServiceRequest::with('resident')
            ->latest()
            ->take(20)
            ->get();

        $serviceStats = [
            'total'     => ServiceRequest::count(),
            'pending'   => ServiceRequest::where('status', 'pending')->count(),
            'completed' => ServiceRequest::where('status', 'completed')->count(),
        ];

        // Activity logs for SEPD (security events)
        $securityLogs = ActivityLog::latest()->take(15)->get();

        // Announcements for SBSEC
        $announcements = Announcement::orderByDesc('posted_at')->take(10)->get();

        // Residents for REGST
        $unverifiedResidents = Resident::where('is_verified', false)
            ->where('role', 'citizen')
            ->take(20)
            ->get();

        return compact('agricultureFarmers', 'serviceRequests', 'serviceStats', 'securityLogs', 'announcements', 'unverifiedResidents');
    }

    // -----------------------------------------------------------------------
    // HRMO
    // -----------------------------------------------------------------------
    private function loadHrmoData($user): array
    {
        $allStaff = Resident::whereNotNull('department_role')
            ->orderBy('department_role')
            ->get();

        $allRoles = config('department_permissions', []);
        $filledRoles = $allStaff->pluck('department_role')->unique()->toArray();
        $vacantRoles = array_diff(array_keys($allRoles), $filledRoles);

        $adminCount   = Resident::where('role', 'admin')->count();
        $totalStaff   = $allStaff->count();
        $recentLogs   = ActivityLog::where('user_role', 'admin')->latest()->take(10)->get();

        return compact('allStaff', 'allRoles', 'filledRoles', 'vacantRoles', 'adminCount', 'totalStaff', 'recentLogs');
    }

    // -----------------------------------------------------------------------
    // LEGISLATIVE — Sangguniang Bayan Committee Chairs & SK Federation
    // -----------------------------------------------------------------------
    private function loadLegislativeData($user): array
    {
        $role = $user->department_role;
        $totalCitizens = Resident::where('role', 'citizen')->count();

        return match ($role) {

            // ── SB Finance, Budget & Comprehensive Affairs ──────────────────
            'SBFIN' => [
                'kpi' => [
                    ['label' => 'Total Citizens',       'value' => number_format($totalCitizens),                                                                              'icon' => '👥', 'color' => 'blue'],
                    ['label' => 'Vulnerable Sectors',   'value' => number_format(Resident::where('role','citizen')->where('vulnerable_sector','!=','None')->count()),           'icon' => '🛡️', 'color' => 'amber'],
                    ['label' => 'Service Transactions', 'value' => number_format(ServiceRequest::count()),                                                                     'icon' => '📄', 'color' => 'emerald'],
                ],
                'insights' => [
                    ['icon' => '📊', 'title' => 'Sector Budget Allocation Model',   'desc' => 'Cross-reference vulnerable sector counts (PWD, Senior, 4Ps) to model equitable budget priorities.',        'action' => 'View Report'],
                    ['icon' => '💰', 'title' => 'E-Service Revenue Tracker',        'desc' => 'Aggregate all completed service request transactions to estimate digitally-collected municipal fees.',      'action' => 'Extract Data'],
                    ['icon' => '🌍', 'title' => 'Per-Barangay Allocation Index',    'desc' => 'Generate a per-barangay population-weighted spending proposal for the Annual Investment Plan.',            'action' => 'Generate AIP'],
                    ['icon' => '📋', 'title' => '4Ps & Indigent Program Targeting', 'desc' => 'Identify 4Ps beneficiary households for subsidy renewal and cross-program referrals.',                    'action' => 'View Beneficiaries'],
                ],
            ],

            // ── SB Health, Sanitation & Ecology ─────────────────────────────
            'SBHLT' => [
                'kpi' => [
                    ['label' => 'No Sanitary Toilet',   'value' => number_format(Resident::where('role','citizen')->where('sanitary_toilet', false)->count()),                 'icon' => '🚽', 'color' => 'red'],
                    ['label' => 'Flood-Prone Residents', 'value' => number_format(Resident::where('role','citizen')->where('flood_prone', true)->count()),                     'icon' => '🌊', 'color' => 'blue'],
                    ['label' => 'Total Citizens',        'value' => number_format($totalCitizens),                                                                             'icon' => '👥', 'color' => 'emerald'],
                ],
                'insights' => [
                    ['icon' => '🚰', 'title' => 'Water Source Distribution',          'desc' => 'Map households by water source type (piped, deep well, spring) to identify sanitation intervention zones.',  'action' => 'View Map'],
                    ['icon' => '🚽', 'title' => 'Sanitation Priority Households',     'desc' => 'Extract a list of households without sanitary toilets for the Sanitation Improvement Ordinance targeting.',   'action' => 'Extract List'],
                    ['icon' => '🌿', 'title' => 'Ecology-at-Risk Barangays',          'desc' => 'Identify barangays with highest flood-prone household density for environmental protection resolutions.',     'action' => 'Analyze Zones'],
                    ['icon' => '🏥', 'title' => 'PWD & Senior Health Coverage',       'desc' => 'Cross-reference PWD and Senior Citizen residents against available health service delivery points.',         'action' => 'View Coverage'],
                ],
            ],

            // ── SB Women, Family, Trade Commerce & Industry ──────────────────
            'SBWMN' => [
                'kpi' => [
                    ['label' => 'Female Household Heads', 'value' => number_format(Resident::where('role','citizen')->where('is_household_head', true)->where('gender', 'Female')->count()), 'icon' => '👩', 'color' => 'pink'],
                    ['label' => 'Solo Parents',           'value' => number_format(Resident::where('role','citizen')->where('vulnerable_sector', 'Solo Parent')->count()),                  'icon' => '👨‍👧', 'color' => 'purple'],
                    ['label' => 'Business Permit Filings', 'value' => number_format(ServiceRequest::where('type', 'like', '%Business%')->count()),                                          'icon' => '🏢', 'color' => 'blue'],
                ],
                'insights' => [
                    ['icon' => '👩‍💼', 'title' => 'Female-Led Household Registry',       'desc' => 'Extract female household heads by barangay for women empowerment and livelihood program targeting.',         'action' => 'View Registry'],
                    ['icon' => '👨‍👧', 'title' => 'Solo Parent Assistance List',          'desc' => 'Generate a complete list of solo parents eligible for the Solo Parents\' Welfare Act benefits.',             'action' => 'Generate List'],
                    ['icon' => '🏪', 'title' => 'Micro-Enterprise Profiling',           'desc' => 'Map registered business permittees by barangay for trade zone planning and MSME support programs.',          'action' => 'View Map'],
                    ['icon' => '📈', 'title' => 'Women in Agriculture Report',          'desc' => 'Identify female residents engaged in farming, aquaculture, or livestock for targeted DA/DOLE programs.',      'action' => 'Run Analysis'],
                ],
            ],

            // ── SB Rules, Privileges, Investigations & Legislative Oversight ─
            'SBRLS' => [
                'kpi' => [
                    ['label' => 'Total Audit Events',   'value' => number_format(ActivityLog::count()),                                                                        'icon' => '📝', 'color' => 'slate'],
                    ['label' => 'Active Staff Accounts', 'value' => number_format(Resident::whereNotNull('department_role')->count()),                                         'icon' => '👤', 'color' => 'blue'],
                    ['label' => 'Pending Requests',     'value' => number_format(ServiceRequest::where('status', 'pending')->count()),                                         'icon' => '⏳', 'color' => 'amber'],
                ],
                'insights' => [
                    ['icon' => '🔍', 'title' => 'System Compliance Audit Log',         'desc' => 'Review all staff activity logs to verify adherence to digital governance ordinances and data privacy rules.',  'action' => 'Open Audit Log'],
                    ['icon' => '⚖️', 'title' => 'Ordinance Enforcement Tracker',       'desc' => 'Track service requests that are overdue or stalled, flagging potential non-compliance by LGU offices.',       'action' => 'View Tracker'],
                    ['icon' => '🔑', 'title' => 'Role & Privilege Review',             'desc' => 'Audit all assigned department roles to ensure no unauthorized access privileges exist in the system.',        'action' => 'Run Privilege Audit'],
                    ['icon' => '📋', 'title' => 'Legislative Session Data Pack',       'desc' => 'Generate a data summary package for use in privilege speeches and committee investigations.',                  'action' => 'Prepare Pack'],
                ],
            ],

            // ── SB Public Information & Communication ────────────────────────
            'SBPIC' => [
                'kpi' => [
                    ['label' => 'Published Announcements', 'value' => number_format(Announcement::count()),                                                                    'icon' => '📢', 'color' => 'indigo'],
                    ['label' => 'Citizen Reach (Total)',   'value' => number_format($totalCitizens),                                                                           'icon' => '🌐', 'color' => 'blue'],
                    ['label' => 'LGU Memorandums',         'value' => number_format(Announcement::where('category', 'LGU Memorandum')->count()),                              'icon' => '📄', 'color' => 'emerald'],
                ],
                'insights' => [
                    ['icon' => '📰', 'title' => 'Transparency Board Performance',      'desc' => 'Review which ordinances, memos, and news items have been published and their visibility to citizens.',         'action' => 'Open Board'],
                    ['icon' => '📡', 'title' => 'Barangay-Targeted Announcements',     'desc' => 'Audit announcements targeted to specific barangays vs. municipality-wide broadcasts for equity analysis.',      'action' => 'Analyze Reach'],
                    ['icon' => '🏛️', 'title' => 'Ordinance Publication Tracker',      'desc' => 'Ensure all newly passed ordinances are published promptly on the citizen-facing dashboard.',                    'action' => 'View Tracker'],
                    ['icon' => '📊', 'title' => 'Digital Literacy by Barangay',        'desc' => 'Compare citizen registration rates per barangay as a proxy for platform adoption and digital awareness.',      'action' => 'Run Report'],
                ],
            ],

            // ── SB Transportation ────────────────────────────────────────────
            'SBTSP' => [
                'kpi' => [
                    ['label' => 'Vehicle-Owning Households', 'value' => number_format(HouseholdProfile::where('owns_vehicle', true)->count()),                                'icon' => '🚗', 'color' => 'blue'],
                    ['label' => 'MTOP Applications',         'value' => number_format(ServiceRequest::where('type', 'like', '%Tricycle%')->count()),                          'icon' => '🛺', 'color' => 'yellow'],
                    ['label' => 'Total Households',          'value' => number_format(Household::count()),                                                                    'icon' => '🏠', 'color' => 'slate'],
                ],
                'insights' => [
                    ['icon' => '🗺️', 'title' => 'TODA Route Density Map',             'desc' => 'Identify barangays with highest MTOP concentration to propose optimal tricycle routes and TODA zones.',        'action' => 'View Map'],
                    ['icon' => '🚗', 'title' => 'Household Vehicle Census',           'desc' => 'Extract vehicle ownership data by barangay for traffic volume estimates and road infrastructure planning.',     'action' => 'Extract Data'],
                    ['icon' => '🛺', 'title' => 'MTOP Renewal Status',                'desc' => 'Track all Motorized Tricycle Operators\' Permits: active, expired, and pending renewal for franchise auditing.', 'action' => 'View MTOP List'],
                    ['icon' => '🏗️', 'title' => 'Infrastructure Needs Assessment',   'desc' => 'Cross-reference vehicle density with road type data to prioritize road widening and improvement projects.',    'action' => 'Run Assessment'],
                ],
            ],

            // ── SB Public Works, Infrastructure, Housing & Land ─────────────
            'SBPWK' => [
                'kpi' => [
                    ['label' => 'Flood-Prone Households',  'value' => number_format(Household::where('barangay', '!=', '')->whereHas('resident', fn($q) => $q->where('flood_prone', true))->count()), 'icon' => '🌊', 'color' => 'blue'],
                    ['label' => 'Informal Settlers',        'value' => number_format(Household::where('housing_type', 'Informal Settler')->count()),                                                    'icon' => '🏚️', 'color' => 'red'],
                    ['label' => 'Total Households',         'value' => number_format(Household::count()),                                                                                               'icon' => '🏠', 'color' => 'emerald'],
                ],
                'insights' => [
                    ['icon' => '🏚️', 'title' => 'Informal Settlement Registry',       'desc' => 'List all households classified as Informal Settlers for socialized housing and resettlement planning.',        'action' => 'View Registry'],
                    ['icon' => '🌊', 'title' => 'Flood-Prone Housing Report',         'desc' => 'Extract flood-prone households with house material type (A/B/C) to prioritize DRRM housing resolutions.',     'action' => 'Generate Report'],
                    ['icon' => '🏗️', 'title' => 'House Materials Classification',    'desc' => 'Aggregate households by house type (Strong/Mixed/Light materials) for the CLUP housing chapter.',            'action' => 'View Breakdown'],
                    ['icon' => '📍', 'title' => 'Public Works Priority Barangays',    'desc' => 'Rank barangays by combined flood risk and settlement density to guide infrastructure budget allocation.',      'action' => 'Rank Barangays'],
                ],
            ],

            // ── SB Agriculture & Farmers Association ─────────────────────────
            'SBAGR' => [
                'kpi' => [
                    ['label' => 'Registered Farmers',     'value' => number_format(Resident::where('role','citizen')->whereNotNull('crops')->count()),                         'icon' => '🌾', 'color' => 'green'],
                    ['label' => 'Aquaculture Operators',  'value' => number_format(Resident::where('role','citizen')->whereNotNull('aquaculture')->count()),                   'icon' => '🐟', 'color' => 'blue'],
                    ['label' => 'Livestock Owners',       'value' => number_format(Resident::where('role','citizen')->whereNotNull('livestock')->count()),                     'icon' => '🐄', 'color' => 'amber'],
                ],
                'insights' => [
                    ['icon' => '🌾', 'title' => 'Crop Farming Registry',               'desc' => 'Enumerate all crop farming residents per barangay for DA seed subsidy and crop insurance program targeting.',  'action' => 'View Registry'],
                    ['icon' => '🦐', 'title' => 'Oyster & Fish Cage Operators',        'desc' => 'List Buguey lagoon aquaculture operators for BFAR assistance, licensing, and zoning compliance.',            'action' => 'View Registry'],
                    ['icon' => '🌊', 'title' => 'Crop Damage Vulnerability Index',     'desc' => 'Cross-reference farming residents in flood-prone barangays to pre-position calamity fund assistance.',        'action' => 'Analyze Risk'],
                    ['icon' => '🐄', 'title' => 'Livestock & Poultry Census',          'desc' => 'Generate a livestock/poultry count per barangay for veterinary outreach and DA animal dispersal programs.',  'action' => 'View Census'],
                ],
            ],

            // ── SB Barangay Affairs ──────────────────────────────────────────
            'SBBGA' => [
                'kpi' => [
                    ['label' => 'Total Barangays',          'value' => '30',                                                                                                  'icon' => '🗺️', 'color' => 'purple'],
                    ['label' => 'Registered Citizens',      'value' => number_format($totalCitizens),                                                                         'icon' => '👥', 'color' => 'blue'],
                    ['label' => 'E-Service Transactions',   'value' => number_format(ServiceRequest::count()),                                                                 'icon' => '📱', 'color' => 'emerald'],
                ],
                'insights' => [
                    ['icon' => '📊', 'title' => 'Per-Barangay Population Breakdown',   'desc' => 'View citizen registration counts for all 30 barangays to identify underserved and high-density zones.',        'action' => 'View Breakdown'],
                    ['icon' => '📱', 'title' => 'E-Service Adoption Rate by Barangay', 'desc' => 'Measure digital service utilization per barangay to coordinate digital literacy drives with Barangay Captains.','action' => 'View Adoption Report'],
                    ['icon' => '🏠', 'title' => 'Barangay Household Density Map',      'desc' => 'Compare household density across barangays to identify areas needing infrastructure and program investments.',   'action' => 'View Map'],
                    ['icon' => '🤝', 'title' => 'Barangay Coordination Dashboard',     'desc' => 'Monitor service delivery compliance per barangay captain for SB coordination and oversight meetings.',          'action' => 'Open Dashboard'],
                ],
            ],

            // ── SK Federation President ──────────────────────────────────────
            'SKPRS' => [
                'kpi' => [
                    ['label' => 'Registered Youth (15–30)', 'value' => number_format(Resident::where('role','citizen')->whereBetween('date_of_birth', [Carbon::now()->subYears(30), Carbon::now()->subYears(15)])->count()), 'icon' => '🎓', 'color' => 'orange'],
                    ['label' => 'Solo Parent Youth',        'value' => number_format(Resident::where('role','citizen')->where('vulnerable_sector','Solo Parent')->whereBetween('date_of_birth', [Carbon::now()->subYears(30), Carbon::now()->subYears(15)])->count()), 'icon' => '👨‍👧', 'color' => 'red'],
                    ['label' => 'First-Time Voter Eligible', 'value' => number_format(Resident::where('role','citizen')->whereBetween('date_of_birth', [Carbon::now()->subYears(22), Carbon::now()->subYears(18)])->count()), 'icon' => '🗳️', 'color' => 'blue'],
                ],
                'insights' => [
                    ['icon' => '📊', 'title' => 'Youth Demographics by Barangay',      'desc' => 'Generate a breakdown of the 15–30 youth population across all 30 barangays for SK program targeting.',          'action' => 'View Report'],
                    ['icon' => '🎓', 'title' => 'Out-of-School Youth Profiling',        'desc' => 'Identify youth household members with incomplete education for ALS referral and scholarship programs.',          'action' => 'Extract Data'],
                    ['icon' => '🗳️', 'title' => 'First-Time Voters Extraction',        'desc' => 'Pull the list of residents turning 18 within the next election cycle for voter registration drives.',           'action' => 'Extract List'],
                    ['icon' => '🏀', 'title' => 'Youth Sports & Development Index',    'desc' => 'Map youth population density per barangay to propose venue allocation for SK sports and cultural programs.',    'action' => 'View Map'],
                ],
            ],

            // ── Fallback ─────────────────────────────────────────────────────
            default => [
                'kpi' => [
                    ['label' => 'Total Citizens', 'value' => number_format($totalCitizens), 'icon' => '👥', 'color' => 'blue'],
                ],
                'insights' => [],
            ],
        };
    }
}
