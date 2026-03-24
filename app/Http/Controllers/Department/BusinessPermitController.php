<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

/**
 * BusinessPermitController
 *
 * BPLO: Manage full lifecycle of business permit applications.
 * Can route applications to ENGR and MHO for digital clearances.
 */
class BusinessPermitController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $baseQuery = ServiceRequest::with(['resident', 'service'])
            ->whereHas('service', fn($q) =>
                $q->where('name', 'like', '%Business%')
                  ->orWhere('name', 'like', '%Permit%')
                  ->orWhere('name', 'like', '%MTOP%')
                  ->orWhere('name', 'like', '%Tricycle%')
            );

        $stats = [
            'pending'          => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress'      => (clone $baseQuery)->where('status', 'in-progress')->count(),
            'ready_for_pickup' => (clone $baseQuery)->where('status', 'ready-for-pickup')->count(),
            'completed'        => (clone $baseQuery)->where('status', 'completed')->count(),
        ];

        $requests = $baseQuery->latest()->paginate(20);

        return view('department.business-permits.index', compact('user', 'requests', 'stats'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();
        $serviceRequest->load(['resident', 'service', 'scannedDocuments']);

        return view('department.business-permits.show', compact('user', 'serviceRequest'));
    }

    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status'     => 'completed',
            'completed_at' => now(),
            'notes'      => $request->input('notes'),
        ]);

        return back()->with('success', 'Business permit approved and issued.');
    }

    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $serviceRequest->update([
            'status' => 'rejected',
            'notes'  => $request->input('reason'),
        ]);

        return back()->with('success', 'Business permit application rejected.');
    }

    /**
     * Route to Municipal Engineer for structural clearance.
     */
    public function routeToEngineer(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status' => 'in-progress',
            'notes'  => '[Routed to Municipal Engineer for structural clearance] ' . $request->input('notes'),
        ]);

        return back()->with('success', 'Application routed to the Municipal Engineer for clearance.');
    }

    /**
     * Route to Municipal Health Officer for sanitary clearance.
     */
    public function routeToHealth(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status' => 'in-progress',
            'notes'  => '[Routed to Municipal Health Officer for sanitary clearance] ' . $request->input('notes'),
        ]);

        return back()->with('success', 'Application routed to the MHO for sanitary clearance.');
    }
}
