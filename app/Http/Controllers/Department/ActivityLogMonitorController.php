<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

/**
 * ActivityLogMonitorController
 *
 * Shared read-only activity log viewer for roles:
 * MAYOR, TRESR, ACCT, BPLO, HRMO, SEPD
 */
class ActivityLogMonitorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $logs = ActivityLog::with('resident')
            ->latest()
            ->paginate(30);

        return view('department.activity-logs.index', compact('user', 'logs'));
    }

    public function show(ActivityLog $activityLog)
    {
        $user = auth()->user();

        return view('department.activity-logs.show', compact('user', 'activityLog'));
    }
}
