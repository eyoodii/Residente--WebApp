<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;

/**
 * WelfareTargetingController
 *
 * MSWDO: Filter and export vulnerable populations for aid distribution.
 */
class WelfareTargetingController extends Controller
{
    private const SECTORS = [
        'Senior Citizen', 'PWD', 'Indigent', 'Informal Settler',
        '4Ps Beneficiary', 'Solo Parent', 'Indigenous People',
    ];

    public function index()
    {
        $user = auth()->user();

        $summary = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->selectRaw('vulnerable_sector, COUNT(*) as count')
            ->groupBy('vulnerable_sector')
            ->orderByDesc('count')
            ->get();

        $totalVulnerable = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->count();

        $byBarangay = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->get();

        $informalSettlers = Resident::where('residential_type', 'Informal Settler')->count();
        $floodProne       = Resident::where('flood_prone', true)->count();
        $withoutToilet    = Resident::where('sanitary_toilet', false)->count();

        return view('department.welfare.index', compact(
            'user', 'summary', 'totalVulnerable', 'byBarangay',
            'informalSettlers', 'floodProne', 'withoutToilet'
        ));
    }

    public function vulnerable()
    {
        $user      = auth()->user();
        $residents = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->with('household')
            ->orderBy('barangay')
            ->paginate(30);

        return view('department.welfare.vulnerable', compact('user', 'residents'));
    }

    public function bySector(string $sector)
    {
        $user      = auth()->user();
        $residents = Resident::where('vulnerable_sector', $sector)
            ->orderBy('barangay')->orderBy('last_name')
            ->paginate(30);

        return view('department.welfare.by-sector', compact('user', 'residents', 'sector'));
    }

    public function export()
    {
        $residents = Resident::whereNotNull('vulnerable_sector')
            ->where('vulnerable_sector', '!=', 'None')
            ->select('first_name', 'last_name', 'barangay', 'purok', 'vulnerable_sector', 'contact_number')
            ->get();

        return response()->json($residents)
            ->header('Content-Disposition', 'attachment; filename="vulnerable_sectors_' . now()->format('Ymd') . '.json"');
    }
}
