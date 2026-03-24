<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

/**
 * BlotterController
 *
 * SEPD: Manage the Barangay Blotter module — incident reports,
 * peace and order tracking across puroks and barangays.
 */
class BlotterController extends Controller
{
    /**
     * List all blotter (incident) entries.
     * Re-uses ActivityLog filtered to 'violation' / security-related events.
     */
    public function index()
    {
        $user = auth()->user();

        $incidents = ActivityLog::whereIn('action', ['violation', 'incident', 'blotter', 'complaint'])
            ->latest()
            ->paginate(25);

        $incidentsByBarangay = Resident::join('activity_logs', 'residents.id', '=', 'activity_logs.resident_id')
            ->whereIn('activity_logs.action', ['violation', 'incident', 'blotter', 'complaint'])
            ->selectRaw('residents.barangay, COUNT(*) as count')
            ->groupBy('residents.barangay')
            ->orderByDesc('count')
            ->get();

        $stats = [
            'total'    => ActivityLog::whereIn('action', ['violation', 'incident', 'blotter', 'complaint'])->count(),
            'resolved' => ActivityLog::where('action', 'blotter')->where('description', 'like', '%resolved%')->count(),
        ];

        return view('department.blotter.index', compact('user', 'incidents', 'incidentsByBarangay', 'stats'));
    }

    public function create()
    {
        $user = auth()->user();
        return view('department.blotter.create', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'complainant_name' => 'required|string|max:200',
            'respondent_name'  => 'required|string|max:200',
            'barangay'         => 'required|string|max:100',
            'purok'            => 'nullable|string|max:50',
            'incident_date'    => 'required|date',
            'description'      => 'required|string|max:2000',
        ]);

        // Store blotter as an ActivityLog entry
        ActivityLog::create([
            'resident_id' => auth()->id(),
            'user_role'   => auth()->user()->role,
            'action'      => 'blotter',
            'description' => "Incident filed by {$validated['complainant_name']} against {$validated['respondent_name']} at {$validated['barangay']}, Purok {$validated['purok']}. Date: {$validated['incident_date']}. Details: {$validated['description']}",
            'ip_address'  => $request->ip(),
            'severity'    => 'warning',
        ]);

        return redirect()->route('department.blotter.index')
            ->with('success', 'Blotter entry recorded successfully.');
    }

    public function show(ActivityLog $blotter)
    {
        $user = auth()->user();
        return view('department.blotter.show', compact('user', 'blotter'));
    }

    public function resolve(Request $request, ActivityLog $blotter)
    {
        $request->validate(['resolution' => 'required|string|max:1000']);

        $blotter->update([
            'description' => $blotter->description . ' [RESOLVED: ' . $request->input('resolution') . ']',
        ]);

        return back()->with('success', 'Incident marked as resolved.');
    }
}
