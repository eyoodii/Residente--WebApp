<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Resident;
use Illuminate\Http\Request;

/**
 * BuildingPermitController
 *
 * ENGR: Review and approve building permit applications.
 * Access to flood-prone household data for infrastructure prioritisation.
 */
class BuildingPermitController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $requests = ServiceRequest::with(['resident', 'service'])
            ->whereHas('service', fn($q) => $q->where('slug', 'like', '%building%')->orWhere('name', 'like', '%Building%'))
            ->latest()
            ->paginate(20);

        $floodProneByBarangay = Resident::where('flood_prone', true)
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->get();

        $stats = [
            'pending'   => ServiceRequest::whereHas('service', fn($q) => $q->where('name', 'like', '%Building%'))->where('status', 'pending')->count(),
            'approved'  => ServiceRequest::whereHas('service', fn($q) => $q->where('name', 'like', '%Building%'))->where('status', 'completed')->count(),
            'rejected'  => ServiceRequest::whereHas('service', fn($q) => $q->where('name', 'like', '%Building%'))->where('status', 'rejected')->count(),
        ];

        return view('department.building-permits.index', compact(
            'user', 'requests', 'floodProneByBarangay', 'stats'
        ));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();
        $serviceRequest->load(['resident', 'service', 'scannedDocuments']);

        return view('department.building-permits.show', compact('user', 'serviceRequest'));
    }

    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status' => 'completed',
            'notes'  => $request->input('notes'),
        ]);

        return back()->with('success', 'Building permit approved.');
    }

    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $serviceRequest->update([
            'status' => 'rejected',
            'notes'  => $request->input('reason'),
        ]);

        return back()->with('success', 'Building permit rejected.');
    }
}
