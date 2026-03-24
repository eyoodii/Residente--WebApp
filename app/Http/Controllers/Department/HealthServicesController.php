<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\ServiceRequest;

/**
 * HealthServicesController
 *
 * MHO: Manage health service requests, monitor sanitation, and water source data.
 */
class HealthServicesController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $baseQuery = ServiceRequest::with(['resident', 'service'])
            ->whereHas('service', fn($q) =>
                $q->where('name', 'like', '%Health%')
                  ->orWhere('name', 'like', '%Medical%')
                  ->orWhere('name', 'like', '%Sanitary%')
                  ->orWhere('name', 'like', '%Certificate%')
            );

        $stats = [
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
        ];

        $healthRequests = $baseQuery->latest()->paginate(20);

        $sanitationSummary = [
            'with_toilet'    => Resident::where('sanitary_toilet', true)->count(),
            'without_toilet' => Resident::where('sanitary_toilet', false)->count(),
        ];

        $waterSources = Resident::whereNotNull('water_source')
            ->selectRaw('water_source, COUNT(*) as count')
            ->groupBy('water_source')
            ->orderByDesc('count')
            ->get();

        return view('department.health.index', compact(
            'user', 'healthRequests', 'stats', 'sanitationSummary', 'waterSources'
        ));
    }

    public function sanitation()
    {
        $user = auth()->user();

        $byBarangay = Resident::selectRaw(
            'barangay,
             SUM(CASE WHEN sanitary_toilet = 1 THEN 1 ELSE 0 END) as with_toilet,
             SUM(CASE WHEN sanitary_toilet = 0 THEN 1 ELSE 0 END) as without_toilet,
             COUNT(*) as total'
        )
            ->groupBy('barangay')
            ->orderBy('barangay')
            ->get();

        return view('department.health.sanitation', compact('user', 'byBarangay'));
    }

    public function waterSources()
    {
        $user = auth()->user();

        $byBarangay = Resident::whereNotNull('water_source')
            ->selectRaw('barangay, water_source, COUNT(*) as count')
            ->groupBy('barangay', 'water_source')
            ->orderBy('barangay')
            ->get()
            ->groupBy('barangay');

        return view('department.health.water-sources', compact('user', 'byBarangay'));
    }
}
