<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Resident;
use Illuminate\Http\Request;

/**
 * EmergencyAlertController
 *
 * DRRMO: Broadcast emergency alerts and access flood-prone household data.
 */
class EmergencyAlertController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $floodProneCount = Resident::where('flood_prone', true)->count();

        $floodProneByBarangay = Resident::where('flood_prone', true)
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->get();

        $recentAlerts = Announcement::where('category', 'Emergency Alert')
            ->latest('posted_at')
            ->take(5)
            ->get();

        return view('department.emergency.index', compact(
            'user', 'floodProneCount', 'floodProneByBarangay', 'recentAlerts'
        ));
    }

    public function floodProne()
    {
        $user      = auth()->user();
        $residents = Resident::where('flood_prone', true)
            ->select('first_name', 'last_name', 'barangay', 'purok', 'household_number', 'contact_number')
            ->orderBy('barangay')
            ->paginate(30);

        return view('department.emergency.flood-prone', compact('user', 'residents'));
    }

    public function alerts()
    {
        $user   = auth()->user();
        $alerts = Announcement::where('category', 'Emergency Alert')
            ->latest('posted_at')
            ->paginate(20);

        return view('department.emergency.alerts', compact('user', 'alerts'));
    }

    /**
     * Broadcast an emergency alert to the Citizen Dashboard timeline.
     */
    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',
            'target_barangay' => 'nullable|string|max:100',
        ]);

        Announcement::create([
            'title'           => $validated['title'],
            'content'         => $validated['content'],
            'category'        => 'Emergency Alert',
            'target_barangay' => $validated['target_barangay'] ?: null,
            'posted_at'       => now(),
            'is_active'       => true,
        ]);

        return back()->with('success', 'Emergency alert has been broadcast to the citizen dashboard.');
    }
}
