<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Resident;
use Illuminate\Http\Request;

/**
 * CivilRegistryController
 *
 * REGST: Process civil registry document requests and manage identity verification.
 */
class CivilRegistryController extends Controller
{
    private const CIVIL_KEYWORDS = ['Birth', 'Marriage', 'Death', 'Certificate', 'CENOMAR', 'Registry'];

    public function index()
    {
        $user = auth()->user();

        $query = ServiceRequest::with(['resident', 'service'])
            ->whereHas('service', function ($q) {
                foreach (self::CIVIL_KEYWORDS as $i => $kw) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    $q->$method('name', 'like', "%{$kw}%");
                }
            });

        $requests = $query->latest()->paginate(20);

        $stats = [
            'pending'   => (clone $query)->where('status', 'pending')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'rejected'  => (clone $query)->where('status', 'rejected')->count(),
        ];

        $unverified = Resident::where('is_verified', false)
            ->where('role', 'citizen')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('department.civil-registry.index', compact(
            'user', 'requests', 'stats', 'unverified'
        ));
    }

    public function verificationDashboard()
    {
        $user = auth()->user();

        $unverifiedCount = Resident::where('is_verified', false)->where('role', 'citizen')->count();
        $verifiedCount   = Resident::where('is_verified', true)->where('role', 'citizen')->count();

        $verifications = Resident::whereNotNull('philsys_verified_at')
            ->where('role', 'citizen')
            ->orderBy('philsys_verified_at', 'desc')
            ->paginate(20);

        return view('department.civil-registry.verification', compact(
            'user', 'unverifiedCount', 'verifiedCount', 'verifications'
        ));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();
        $serviceRequest->load(['resident', 'service', 'scannedDocuments']);

        return view('department.civil-registry.show', compact('user', 'serviceRequest'));
    }

    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status'       => 'ready-for-pickup',
            'completed_at' => now(),
            'notes'        => $request->input('notes'),
        ]);

        return back()->with('success', 'Civil registry document approved and ready for pick-up.');
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
}
