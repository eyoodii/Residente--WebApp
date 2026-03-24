<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;

/**
 * LivelihoodController
 *
 * AGRI: Track farmers, fisherfolk, livestock raisers, and aquaculture practitioners.
 */
class LivelihoodController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $farmersCount     = Resident::whereNotNull('crops')->count();
        $fisheriesCount   = Resident::whereNotNull('fisheries')->count();
        $livestockCount   = Resident::whereNotNull('livestock')->count();
        $aquacultureCount = Resident::whereNotNull('aquaculture')->count();

        $totalSectorCount = Resident::where(function ($q) {
            $q->whereNotNull('crops')
              ->orWhereNotNull('fisheries')
              ->orWhereNotNull('livestock')
              ->orWhereNotNull('aquaculture');
        })->count();

        $byBarangay = Resident::selectRaw(
            'barangay,
             SUM(CASE WHEN crops IS NOT NULL THEN 1 ELSE 0 END) as farmers,
             SUM(CASE WHEN fisheries IS NOT NULL THEN 1 ELSE 0 END) as fisheries,
             SUM(CASE WHEN livestock IS NOT NULL THEN 1 ELSE 0 END) as livestock,
             SUM(CASE WHEN aquaculture IS NOT NULL THEN 1 ELSE 0 END) as aquaculture'
        )
            ->groupBy('barangay')
            ->orderBy('barangay')
            ->get();

        return view('department.livelihood.index', compact(
            'user', 'farmersCount', 'fisheriesCount', 'livestockCount',
            'aquacultureCount', 'totalSectorCount', 'byBarangay'
        ));
    }

    public function farmers()
    {
        $user      = auth()->user();
        $residents = Resident::whereNotNull('crops')
            ->select('first_name', 'last_name', 'barangay', 'purok', 'crops', 'contact_number')
            ->orderBy('barangay')
            ->paginate(30);

        return view('department.livelihood.farmers', compact('user', 'residents'));
    }

    public function fisheries()
    {
        $user      = auth()->user();
        $residents = Resident::whereNotNull('fisheries')
            ->select('first_name', 'last_name', 'barangay', 'purok', 'fisheries', 'contact_number')
            ->orderBy('barangay')
            ->paginate(30);

        return view('department.livelihood.fisheries', compact('user', 'residents'));
    }

    public function livestock()
    {
        $user      = auth()->user();
        $residents = Resident::whereNotNull('livestock')
            ->select('first_name', 'last_name', 'barangay', 'purok', 'livestock', 'contact_number')
            ->orderBy('barangay')
            ->paginate(30);

        return view('department.livelihood.livestock', compact('user', 'residents'));
    }

    public function aquaculture()
    {
        $user      = auth()->user();
        $residents = Resident::whereNotNull('aquaculture')
            ->select('first_name', 'last_name', 'barangay', 'purok', 'aquaculture', 'contact_number')
            ->orderBy('barangay')
            ->paginate(30);

        return view('department.livelihood.aquaculture', compact('user', 'residents'));
    }
}
