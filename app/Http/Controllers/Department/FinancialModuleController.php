<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\ActivityLog;
use App\Models\Resident;
use Illuminate\Http\Request;

/**
 * FinancialModuleController
 *
 * Roles:
 *   TRESR  — Revenue & Collections Manager (full: reconcile)
 *   ACCT   — Internal Auditor (read-only: audit-log)
 *   BUDGT  — Financial Forecaster (read-only: forecast)
 */
class FinancialModuleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $serviceStats = [
            'total'     => ServiceRequest::count(),
            'pending'   => ServiceRequest::where('status', 'pending')->count(),
            'completed' => ServiceRequest::where('status', 'completed')->count(),
            'rejected'  => ServiceRequest::where('status', 'rejected')->count(),
        ];

        $recentTransactions = ServiceRequest::with(['resident', 'service'])
            ->whereIn('status', ['completed', 'ready-for-pickup'])
            ->latest()
            ->take(15)
            ->get();

        return view('department.finance.index', compact('user', 'serviceStats', 'recentTransactions'));
    }

    public function transactions()
    {
        $user         = auth()->user();
        $transactions = ServiceRequest::with(['resident', 'service'])
            ->whereIn('status', ['completed', 'ready-for-pickup', 'in-progress'])
            ->latest()
            ->paginate(25);

        return view('department.finance.transactions', compact('user', 'transactions'));
    }

    /**
     * TRESR only — Mark a completed request as reconciled (payment confirmed).
     */
    public function reconcile(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        $serviceRequest->update([
            'notes' => '[RECONCILED by Treasurer] ' . $request->input('notes'),
        ]);

        return back()->with('success', 'Transaction marked as reconciled.');
    }

    /**
     * ACCT only — Full audit log of all financial activity.
     */
    public function auditLog()
    {
        $user = auth()->user();
        $logs = ActivityLog::latest()->paginate(30);

        return view('department.finance.audit-log', compact('user', 'logs'));
    }

    /**
     * BUDGT only — Service utilisation forecast from demographic data.
     */
    public function forecast()
    {
        $user = auth()->user();

        $servicesByType = ServiceRequest::with('service')
            ->selectRaw('service_id, COUNT(*) as count')
            ->groupBy('service_id')
            ->orderByDesc('count')
            ->get();

        $barangayPopulation = Resident::where('role', 'citizen')
            ->selectRaw('barangay, COUNT(*) as count')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->get();

        $monthlyTrend = ServiceRequest::selectRaw(
            'YEAR(requested_at) as year, MONTH(requested_at) as month, COUNT(*) as count'
        )
            ->whereYear('requested_at', now()->year)
            ->groupByRaw('YEAR(requested_at), MONTH(requested_at)')
            ->orderBy('year')->orderBy('month')
            ->get();

        return view('department.finance.forecast', compact(
            'user', 'servicesByType', 'barangayPopulation', 'monthlyTrend'
        ));
    }
}
