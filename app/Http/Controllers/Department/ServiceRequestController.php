<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

/**
 * ServiceRequestController (Department)
 *
 * Shared service request queue for roles that process citizen requests:
 * TRESR, MHO, BPLO, REGST
 *
 * Each role only sees requests relevant to their service category
 * (enforced via the department middleware on the route level).
 */
class ServiceRequestController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $query = ServiceRequest::with(['resident', 'service']);

        // Filter by service category based on the user's department role
        $query = match ($user->department_role) {
            'MHO'   => $query->whereHas('service', fn($q) => $q->where('name', 'like', '%Health%')
                                ->orWhere('name', 'like', '%Medical%')
                                ->orWhere('name', 'like', '%Sanitary%')),
            'BPLO'  => $query->whereHas('service', fn($q) => $q->where('name', 'like', '%Business%')
                                ->orWhere('name', 'like', '%Permit%')),
            'REGST' => $query->whereHas('service', fn($q) => $q->where('name', 'like', '%Birth%')
                                ->orWhere('name', 'like', '%Marriage%')
                                ->orWhere('name', 'like', '%Death%')
                                ->orWhere('name', 'like', '%Certificate%')),
            default => $query, // TRESR sees all
        };

        // Stats scoped to the same filter as the listing
        $stats = [
            'pending'          => (clone $query)->where('status', 'pending')->count(),
            'in_progress'      => (clone $query)->where('status', 'in-progress')->count(),
            'ready_for_pickup' => (clone $query)->where('status', 'ready-for-pickup')->count(),
            'completed'        => (clone $query)->where('status', 'completed')->count(),
        ];

        $requests = $query->latest()->paginate(25);

        return view('department.service-requests.index', compact('user', 'requests', 'stats'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();
        $serviceRequest->load(['resident', 'service', 'scannedDocuments']);

        return view('department.service-requests.show', compact('user', 'serviceRequest'));
    }

    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status'     => 'in-progress',
            'started_at' => now(),
            'notes'      => $request->input('notes'),
        ]);

        return back()->with('success', 'Request approved and now in-progress.');
    }

    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $serviceRequest->update([
            'status' => 'rejected',
            'notes'  => $request->input('reason'),
        ]);

        return back()->with('success', 'Request rejected.');
    }

    public function markReady(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status' => 'ready-for-pickup',
            'notes'  => $request->input('notes'),
        ]);

        return back()->with('success', 'Request marked as ready for pick-up.');
    }
}
