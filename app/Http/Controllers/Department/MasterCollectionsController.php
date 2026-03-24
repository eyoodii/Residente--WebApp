<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Household;

/**
 * MasterCollectionsController
 *
 * Shared read-only access to household and demographic master data for roles:
 * MAYOR, VMYOR, MPDC, ASSOR, BUDGT, MSWDO, MHO, DRRMO, AGRI, REGST
 */
class MasterCollectionsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalResidents  = Resident::where('role', 'citizen')->count();
        $totalHouseholds = Household::count();
        $verifiedCount   = Resident::where('is_verified', true)->where('role', 'citizen')->count();
        $withPhilsys     = Resident::whereNotNull('philsys_verified_at')->count();

        $barangayBreakdown = Resident::where('role', 'citizen')
            ->selectRaw('barangay, COUNT(*) as residents')
            ->groupBy('barangay')
            ->orderByDesc('residents')
            ->get();

        $houseMaterialStats = Resident::whereNotNull('house_materials')
            ->selectRaw('house_materials, COUNT(*) as count')
            ->groupBy('house_materials')
            ->orderByDesc('count')
            ->get();

        $waterSourceStats = Resident::whereNotNull('water_source')
            ->selectRaw('water_source, COUNT(*) as count')
            ->groupBy('water_source')
            ->orderByDesc('count')
            ->get();

        $sanitationStats = [
            'with_toilet'    => Resident::where('sanitary_toilet', true)->count(),
            'without_toilet' => Resident::where('sanitary_toilet', false)->count(),
        ];

        $floodProneCount = Resident::where('flood_prone', true)->count();

        return view('department.master-collections.index', compact(
            'user', 'totalResidents', 'totalHouseholds', 'verifiedCount', 'withPhilsys',
            'barangayBreakdown', 'houseMaterialStats', 'waterSourceStats',
            'sanitationStats', 'floodProneCount'
        ));
    }

    public function households()
    {
        $user       = auth()->user();
        $households = Household::withCount('residents')->paginate(25);

        return view('department.master-collections.households', compact('user', 'households'));
    }

    public function demographics()
    {
        $user = auth()->user();

        $residents = Resident::where('role', 'citizen')
            ->select('first_name', 'last_name', 'barangay', 'purok', 'gender', 'date_of_birth', 'civil_status', 'vulnerable_sector')
            ->paginate(30);

        return view('department.master-collections.demographics', compact('user', 'residents'));
    }

    public function export()
    {
        // Returns JSON for download — expandable to CSV/Excel via maatwebsite/excel
        $residents = Resident::where('role', 'citizen')
            ->select('first_name', 'last_name', 'barangay', 'purok', 'gender', 'date_of_birth', 'civil_status', 'occupation', 'vulnerable_sector')
            ->get();

        return response()->json($residents)
            ->header('Content-Disposition', 'attachment; filename="master_collection_' . now()->format('Ymd') . '.json"');
    }
}
