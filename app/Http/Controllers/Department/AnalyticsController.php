<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Household;
use App\Models\ServiceRequest;

/**
 * AnalyticsController
 *
 * Shared read-only analytics dashboard for roles:
 * MAYOR, VMYOR, MPDC, ENGR, BUDGT, MSWDO, DRRMO, AGRI, SEPD
 */
class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalResidents  = Resident::where('role', 'citizen')->count();
        $verifiedCount   = Resident::where('is_verified', true)->where('role', 'citizen')->count();
        $totalHouseholds = Household::count();

        $serviceStats = [
            'total'     => ServiceRequest::count(),
            'pending'   => ServiceRequest::where('status', 'pending')->count(),
            'completed' => ServiceRequest::where('status', 'completed')->count(),
        ];

        $barangayStats = Resident::where('role', 'citizen')
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->get();

        $genderBreakdown = Resident::where('role', 'citizen')
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->get();

        $civilStatusBreakdown = Resident::where('role', 'citizen')
            ->selectRaw('civil_status, COUNT(*) as count')
            ->groupBy('civil_status')
            ->orderByDesc('count')
            ->get();

        $vulnerableSectors = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->selectRaw('vulnerable_sector, COUNT(*) as count')
            ->groupBy('vulnerable_sector')
            ->orderByDesc('count')
            ->get();

        return view('department.analytics.index', compact(
            'user', 'totalResidents', 'verifiedCount', 'totalHouseholds',
            'serviceStats', 'barangayStats', 'genderBreakdown',
            'civilStatusBreakdown', 'vulnerableSectors'
        ));
    }

    public function barangay()
    {
        $user = auth()->user();

        $barangayStats = Resident::where('role', 'citizen')
            ->selectRaw('barangay, COUNT(*) as total, SUM(CASE WHEN is_verified=1 THEN 1 ELSE 0 END) as verified')
            ->groupBy('barangay')
            ->orderByDesc('total')
            ->get();

        return view('department.analytics.barangay', compact('user', 'barangayStats'));
    }

    public function demographics()
    {
        $user = auth()->user();

        $ageGroups = [
            'Children (0–12)'   => Resident::where('role', 'citizen')->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 12')->count(),
            'Youth (13–30)'     => Resident::where('role', 'citizen')->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 13 AND 30')->count(),
            'Adult (31–59)'     => Resident::where('role', 'citizen')->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 31 AND 59')->count(),
            'Senior (60+)'      => Resident::where('role', 'citizen')->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60')->count(),
        ];

        $genderBreakdown = Resident::where('role', 'citizen')
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->get();

        $civilStatusBreakdown = Resident::where('role', 'citizen')
            ->selectRaw('civil_status, COUNT(*) as count')
            ->groupBy('civil_status')
            ->orderByDesc('count')
            ->get();

        return view('department.analytics.demographics', compact(
            'user', 'ageGroups', 'genderBreakdown', 'civilStatusBreakdown'
        ));
    }

    public function services()
    {
        $user = auth()->user();

        $servicesByStatus = ServiceRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $servicesByType = ServiceRequest::with('service')
            ->selectRaw('service_id, COUNT(*) as count')
            ->groupBy('service_id')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        $monthlyRequests = ServiceRequest::selectRaw('MONTH(requested_at) as month, YEAR(requested_at) as year, COUNT(*) as count')
            ->whereYear('requested_at', now()->year)
            ->groupByRaw('YEAR(requested_at), MONTH(requested_at)')
            ->orderBy('year')->orderBy('month')
            ->get();

        return view('department.analytics.services', compact(
            'user', 'servicesByStatus', 'servicesByType', 'monthlyRequests'
        ));
    }
}
