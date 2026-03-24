<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs for the authenticated resident
     */
    public function index(Request $request)
    {
        $resident = Auth::user();
        
        $query = $resident->activityLogs()->latest();
        
        // Filter by action if provided
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter by date range if provided
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $activityLogs = $query->paginate(20);
        
        // Get unique actions for filter dropdown
        $actions = $resident->activityLogs()
            ->select('action')
            ->distinct()
            ->pluck('action');
        
        return view('citizen.activity-logs.index', compact('activityLogs', 'actions'));
    }

    /**
     * Show detailed view of a specific activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $resident = Auth::user();
        
        // Ensure activity log belongs to the authenticated resident
        if ($activityLog->resident_id !== $resident->id) {
            abort(403);
        }
        
        return view('citizen.activity-logs.show', compact('activityLog'));
    }
}
