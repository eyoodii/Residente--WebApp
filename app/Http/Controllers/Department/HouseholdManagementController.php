<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\Resident;

/**
 * HouseholdManagementController
 *
 * Shared read-only household drill-down for roles:
 * MPDC, ENGR, ASSOR, MSWDO, MHO, DRRMO
 */
class HouseholdManagementController extends Controller
{
    public function index()
    {
        $user       = auth()->user();
        $households = Household::withCount('residents')
            ->orderBy('barangay')
            ->paginate(20);

        $floodProneCount   = Resident::where('flood_prone', true)->count();
        $withoutToilet     = Resident::where('sanitary_toilet', false)->count();
        $totalHouseholds   = Household::count();

        return view('department.households.index', compact(
            'user', 'households', 'floodProneCount', 'withoutToilet', 'totalHouseholds'
        ));
    }

    public function show(Household $household)
    {
        $user      = auth()->user();
        $household->load('residents');

        return view('department.households.show', compact('user', 'household'));
    }
}
