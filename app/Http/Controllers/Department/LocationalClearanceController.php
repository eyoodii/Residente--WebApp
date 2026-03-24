<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

/**
 * LocationalClearanceController
 *
 * MPDC: Manage locational clearance and zoning certification requests.
 */
class LocationalClearanceController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $baseQuery = ServiceRequest::with(['resident', 'service'])
            ->whereHas('service', fn($q) => $q->where('slug', 'like', '%locational%')->orWhere('name', 'like', '%Locational%'));

        $stats = [
            'pending'  => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved' => (clone $baseQuery)->where('status', 'completed')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        $requests = $baseQuery->latest()->paginate(20);

        return view('department.locational-clearance.index', compact('user', 'requests', 'stats'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();
        $serviceRequest->load(['resident', 'service', 'scannedDocuments']);

        return view('department.locational-clearance.show', compact('user', 'serviceRequest'));
    }

    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status' => 'completed',
            'notes'  => $request->input('notes'),
        ]);

        return back()->with('success', 'Locational clearance approved.');
    }

    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $serviceRequest->update([
            'status' => 'rejected',
            'notes'  => $request->input('reason'),
        ]);

        return back()->with('success', 'Locational clearance rejected.');
    }
}
