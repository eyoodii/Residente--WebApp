<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\ServiceRequest;
use App\Models\ActivityLog;
use App\Models\HouseholdProfile;
use App\Models\Household;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics
     */
    public function index()
    {
        // Resident Statistics
        $totalResidents = Resident::count();
        $citizenCount = Resident::where('role', 'citizen')->count();
        $visitorCount = Resident::where('role', 'visitor')->count();
        $adminCount = Resident::where('role', 'admin')->count();
        $pendingVerification = Resident::where('role', 'visitor')
            ->whereNotNull('email_verified_at')
            ->count();
        
        // Service Request Statistics
        $totalRequests = ServiceRequest::count();
        $pendingRequests = ServiceRequest::where('status', 'pending')->count();
        $inProgressRequests = ServiceRequest::where('status', 'in-progress')->count();
        $completedRequests = ServiceRequest::where('status', 'completed')->count();
        $readyForPickup = ServiceRequest::where('status', 'ready-for-pickup')->count();
        
        // Activity Statistics
        $todayActivities = ActivityLog::whereDate('created_at', today())->count();
        $suspiciousActivities = ActivityLog::suspicious()->count();
        $criticalActivities = ActivityLog::critical()->count();
        
        // Recent registrations (last 7 days)
        $recentRegistrations = Resident::whereBetween('created_at', [now()->subDays(7), now()])
            ->count();
        
        // Demographics
        $genderDistribution = Resident::select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->pluck('count', 'gender');
        
        $vulnerableSectorDistribution = Resident::select('vulnerable_sector', DB::raw('count(*) as count'))
            ->where('vulnerable_sector', '!=', 'None')
            ->groupBy('vulnerable_sector')
            ->pluck('count', 'vulnerable_sector');
        
        // Household Statistics
        $householdsWithProfiles = HouseholdProfile::count();
        $lowIncomeHouseholds = HouseholdProfile::where('income_classification', 'Below Poverty Threshold')
            ->orWhere('income_classification', 'Low Income')
            ->count();
        
        // Recent activities
        $recentActivities = ActivityLog::with('resident')
            ->latest()
            ->take(10)
            ->get();
        
        // Residents needing verification
        $residentsNeedingVerification = Resident::where('role', 'visitor')
            ->whereNotNull('email_verified_at')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalResidents',
            'citizenCount',
            'visitorCount',
            'adminCount',
            'pendingVerification',
            'totalRequests',
            'pendingRequests',
            'inProgressRequests',
            'completedRequests',
            'readyForPickup',
            'todayActivities',
            'suspiciousActivities',
            'criticalActivities',
            'recentRegistrations',
            'genderDistribution',
            'vulnerableSectorDistribution',
            'householdsWithProfiles',
            'lowIncomeHouseholds',
            'recentActivities',
            'residentsNeedingVerification'
        ));
    }

    /**
     * Master Collections - View all household/family data collections
     */
    public function masterCollections(Request $request)
    {
        $search = $request->get('search');
        $barangay = $request->get('barangay');
        
        // Get all households with families and members
        $households = Household::with(['families.members', 'families.householdHead'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('household_number', 'like', "%{$search}%")
                      ->orWhere('full_address', 'like', "%{$search}%")
                      ->orWhereHas('families', function ($fq) use ($search) {
                          $fq->where('hhn_number', 'like', "%{$search}%")
                             ->orWhere('head_surname', 'like', "%{$search}%");
                      });
                });
            })
            ->when($barangay, function ($query, $barangay) {
                $query->where('barangay', $barangay);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        
        // Statistics
        $stats = [
            'total_households' => Household::count(),
            'total_families' => Family::count(),
            'total_members' => Resident::whereNotNull('family_id')->count(),
            'households_with_families' => Household::has('families')->count(),
        ];
        
        // Barangays list
        $barangays = config('barangays.list', []);
        
        return view('admin.master-collections', compact('households', 'stats', 'barangays', 'search', 'barangay'));
    }

    /**
     * Barangay Overview - Statistics by barangay
     */
    public function barangayOverview(Request $request)
    {
        $selectedBarangay = $request->get('barangay');
        
        // Get barangay codes from config
        $barangayCodes = config('barangays.list', []);
        
        // Get all barangays with statistics
        $barangays = Resident::select('barangay', 
            DB::raw('COUNT(*) as total_residents'),
            DB::raw('SUM(CASE WHEN role = "citizen" THEN 1 ELSE 0 END) as citizens'),
            DB::raw('SUM(CASE WHEN role = "visitor" THEN 1 ELSE 0 END) as visitors'),
            DB::raw('SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) as verified'))
            ->groupBy('barangay')
            ->orderBy('total_residents', 'desc')
            ->get()
            ->map(function($item) use ($barangayCodes) {
                $item->code = $barangayCodes[$item->barangay] ?? 'N/A';
                return $item;
            });
        
        // If a specific barangay is selected, get detailed data
        $barangayDetails = null;
        if ($selectedBarangay) {
            $barangayDetails = [
                'name' => $selectedBarangay,
                'code' => $barangayCodes[$selectedBarangay] ?? 'N/A',
                'total_residents' => Resident::where('barangay', $selectedBarangay)->count(),
                'citizens' => Resident::where('barangay', $selectedBarangay)->where('role', 'citizen')->count(),
                'visitors' => Resident::where('barangay', $selectedBarangay)->where('role', 'visitor')->count(),
                'verified' => Resident::where('barangay', $selectedBarangay)->where('is_verified', true)->count(),
                'households' => Household::where('barangay', $selectedBarangay)->count(),
                'families' => Family::whereHas('household', function($q) use ($selectedBarangay) {
                    $q->where('barangay', $selectedBarangay);
                })->count(),
                'gender_distribution' => Resident::where('barangay', $selectedBarangay)
                    ->select('gender', DB::raw('count(*) as count'))
                    ->groupBy('gender')
                    ->pluck('count', 'gender'),
                'age_distribution' => [
                    'infants' => Resident::where('barangay', $selectedBarangay)->whereBetween('date_of_birth', [now()->subYears(6)->addDay(), now()])->count(),
                    'children' => Resident::where('barangay', $selectedBarangay)->whereBetween('date_of_birth', [now()->subYears(13)->addDay(), now()->subYears(6)])->count(),
                    'teens' => Resident::where('barangay', $selectedBarangay)->whereBetween('date_of_birth', [now()->subYears(18)->addDay(), now()->subYears(13)])->count(),
                    'young_adults' => Resident::where('barangay', $selectedBarangay)->whereBetween('date_of_birth', [now()->subYears(36)->addDay(), now()->subYears(18)])->count(),
                    'middle_aged' => Resident::where('barangay', $selectedBarangay)->whereBetween('date_of_birth', [now()->subYears(60)->addDay(), now()->subYears(36)])->count(),
                    'seniors' => Resident::where('barangay', $selectedBarangay)->whereBetween('date_of_birth', [now()->subYears(80)->addDay(), now()->subYears(60)])->count(),
                    'elderly' => Resident::where('barangay', $selectedBarangay)->where('date_of_birth', '<=', now()->subYears(80))->count(),
                ],
            ];
        }
        
        return view('admin.barangay-overview', compact('barangays', 'barangayDetails', 'selectedBarangay'));
    }

    /**
     * Validation Flags - Auto-linked residents needing validation
     */
    public function validationFlags(Request $request)
    {
        $status = $request->get('status', 'pending'); // pending, all
        
        // Get auto-linked residents
        $residents = Resident::with(['family', 'household'])
            ->where('is_auto_linked', true)
            ->when($status === 'pending', function($query) {
                // Could add additional filtering for unvalidated ones
                return $query;
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        // Statistics
        $stats = [
            'total_auto_linked' => Resident::where('is_auto_linked', true)->count(),
            'needs_review' => Resident::where('is_auto_linked', true)->count(),
            'by_barangay' => Resident::where('is_auto_linked', true)
                ->select('barangay', DB::raw('count(*) as count'))
                ->groupBy('barangay')
                ->pluck('count', 'barangay'),
        ];
        
        return view('admin.validation-flags', compact('residents', 'stats', 'status'));
    }
}
