<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    /**
     * Display all activity logs for admin monitoring
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('resident')->latest();
        
        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter by severity
        if ($request->has('severity') && $request->severity) {
            $query->where('severity', $request->severity);
        }
        
        // Filter by suspicious activity
        if ($request->has('suspicious') && $request->suspicious) {
            $query->where('is_suspicious', true);
        }
        
        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Filter by resident
        if ($request->has('resident_id') && $request->resident_id) {
            $query->where('resident_id', $request->resident_id);
        }
        
        $activityLogs = $query->paginate(50);
        
        // Get unique actions for filter dropdown
        $actions = ActivityLog::select('action')->distinct()->pluck('action');
        
        // Get suspicious activity count
        $suspiciousCount = ActivityLog::suspicious()->count();
        
        // Get critical activity count
        $criticalCount = ActivityLog::critical()->count();
        
        return view('admin.activity-logs.index', compact(
            'activityLogs',
            'actions',
            'suspiciousCount',
            'criticalCount'
        ));
    }

    /**
     * Show detailed view of activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('resident');
        
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Display suspicious activities
     */
    public function suspicious()
    {
        $activityLogs = ActivityLog::suspicious()
            ->with('resident')
            ->latest()
            ->paginate(50);
        
        return view('admin.activity-logs.suspicious', compact('activityLogs'));
    }
}
