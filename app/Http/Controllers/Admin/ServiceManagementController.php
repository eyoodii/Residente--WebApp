<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceRequirement;
use App\Models\ServiceStep;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ServiceManagementController
 * 
 * SuperAdmin E-Services Management Module
 * Allows LGU to dynamically control all eServices:
 * - Add/Edit/Delete services
 * - Toggle availability (master switch)
 * - Manage requirements per service
 * - Update fees and processing times
 */
class ServiceManagementController extends Controller
{
    /**
     * Display all services for management
     */
    public function index()
    {
        $services = Service::withCount(['requirements', 'steps', 'requests'])
            ->orderBy('department')
            ->orderBy('name')
            ->get();

        $servicesByDepartment = $services->groupBy('department');

        return view('admin.services.index', compact('services', 'servicesByDepartment'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        $departments = [
            'Municipal Health Office',
            'Municipal Mayor\'s Office',
            'Municipal Planning Office',
            'Municipal Engineering Office',
            'Municipal Civil Registry',
            'Municipal Social Welfare Office',
            'Municipal Treasurer\'s Office',
            'Business Permits and Licensing Office',
        ];

        $classifications = ['Simple', 'Complex', 'Highly Technical'];
        $types = ['G2C', 'G2B'];

        return view('admin.services.create', compact('departments', 'classifications', 'types'));
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classification' => 'required|in:Simple,Complex,Highly Technical',
            'type' => 'required|in:G2C,G2B',
            'who_may_avail' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
            'fee_description' => 'nullable|string|max:255',
            'processing_time_minutes' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // Auto-generate slug from name
            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = $request->has('is_active');

            $service = Service::create($validated);

            // Add requirements if provided
            if ($request->has('requirements')) {
                foreach ($request->requirements as $req) {
                    if (!empty($req['requirement'])) {
                        ServiceRequirement::create([
                            'service_id' => $service->id,
                            'requirement' => $req['requirement'],
                            'where_to_secure' => $req['where_to_secure'] ?? null,
                            'is_required' => $req['is_required'] ?? true,
                        ]);
                    }
                }
            }

            // Add steps if provided
            if ($request->has('steps')) {
                foreach ($request->steps as $index => $step) {
                    if (!empty($step['title'])) {
                        ServiceStep::create([
                            'service_id' => $service->id,
                            'step_number' => $index + 1,
                            'title' => $step['title'],
                            'description' => $step['description'] ?? null,
                            'assigned_office' => $step['assigned_office'] ?? null,
                            'duration_minutes' => $step['duration_minutes'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('Service created by SuperAdmin', [
                'admin_id' => auth()->user()->id ?? null,
                'service_id' => $service->id,
                'service_name' => $service->name,
            ]);

            return redirect()
                ->route('admin.services.index')
                ->with('toast_success', "Service '{$service->name}' created successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create service', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->user()->id ?? null,
            ]);

            return back()
                ->withInput()
                ->with('toast_error', 'Failed to create service. Please try again.');
        }
    }

    /**
     * Display the specified service
     */
    public function show(Service $service)
    {
        $service->load(['requirements', 'steps' => function($query) {
            $query->orderBy('step_number');
        }]);

        $requestStats = [
            'total' => $service->requests()->count(),
            'pending' => $service->requests()->where('status', 'pending')->count(),
            'processing' => $service->requests()->where('status', 'processing')->count(),
            'completed' => $service->requests()->where('status', 'completed')->count(),
            'cancelled' => $service->requests()->where('status', 'cancelled')->count(),
        ];

        return view('admin.services.show', compact('service', 'requestStats'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(Service $service)
    {
        $service->load(['requirements', 'steps' => function($query) {
            $query->orderBy('step_number');
        }]);

        $departments = [
            'Municipal Health Office',
            'Municipal Mayor\'s Office',
            'Municipal Planning Office',
            'Municipal Engineering Office',
            'Municipal Civil Registry',
            'Municipal Social Welfare Office',
            'Municipal Treasurer\'s Office',
            'Business Permits and Licensing Office',
        ];

        $classifications = ['Simple', 'Complex', 'Highly Technical'];
        $types = ['G2C', 'G2B'];

        return view('admin.services.edit', compact('service', 'departments', 'classifications', 'types'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classification' => 'required|in:Simple,Complex,Highly Technical',
            'type' => 'required|in:G2C,G2B',
            'who_may_avail' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
            'fee_description' => 'nullable|string|max:255',
            'processing_time_minutes' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active');

            // Update slug if name changed
            if ($service->name !== $validated['name']) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $service->update($validated);

            // Update requirements
            if ($request->has('requirements')) {
                // Delete existing requirements
                $service->requirements()->delete();

                // Add new requirements
                foreach ($request->requirements as $req) {
                    if (!empty($req['requirement'])) {
                        ServiceRequirement::create([
                            'service_id' => $service->id,
                            'requirement' => $req['requirement'],
                            'where_to_secure' => $req['where_to_secure'] ?? null,
                            'is_required' => $req['is_required'] ?? true,
                        ]);
                    }
                }
            }

            // Update steps
            if ($request->has('steps')) {
                // Delete existing steps
                $service->steps()->delete();

                // Add new steps
                foreach ($request->steps as $index => $step) {
                    if (!empty($step['title'])) {
                        ServiceStep::create([
                            'service_id' => $service->id,
                            'step_number' => $index + 1,
                            'title' => $step['title'],
                            'description' => $step['description'] ?? null,
                            'assigned_office' => $step['assigned_office'] ?? null,
                            'duration_minutes' => $step['duration_minutes'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('Service updated by SuperAdmin', [
                'admin_id' => auth()->user()->id ?? null,
                'service_id' => $service->id,
                'service_name' => $service->name,
            ]);

            return redirect()
                ->route('admin.services.show', $service)
                ->with('toast_success', "Service '{$service->name}' updated successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update service', [
                'error' => $e->getMessage(),
                'service_id' => $service->id,
                'admin_id' => auth()->user()->id ?? null,
            ]);

            return back()
                ->withInput()
                ->with('toast_error', 'Failed to update service. Please try again.');
        }
    }

    /**
     * Toggle service availability (Master Switch)
     * This is the key feature for temporarily disabling services
     */
    public function toggleStatus(Service $service)
    {
        try {
            $service->is_active = !$service->is_active;
            $service->save();

            $status = $service->is_active ? 'activated' : 'deactivated';

            Log::info('Service status toggled', [
                'admin_id' => auth()->user()->id ?? null,
                'service_id' => $service->id,
                'service_name' => $service->name,
                'new_status' => $status,
            ]);

            return back()->with('toast_success', "Service '{$service->name}' {$status} successfully!");

        } catch (\Exception $e) {
            Log::error('Failed to toggle service status', [
                'error' => $e->getMessage(),
                'service_id' => $service->id,
            ]);

            return back()->with('toast_error', 'Failed to toggle service status. Please try again.');
        }
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        try {
            // Check if service has active requests
            $activeRequests = $service->requests()
                ->whereIn('status', ['pending', 'processing'])
                ->count();

            if ($activeRequests > 0) {
                return back()->with('toast_error', "Cannot delete service. There are {$activeRequests} active request(s).");
            }

            $serviceName = $service->name;

            DB::beginTransaction();

            // Delete related records
            $service->requirements()->delete();
            $service->steps()->delete();
            $service->delete();

            DB::commit();

            Log::info('Service deleted by SuperAdmin', [
                'admin_id' => auth()->user()->id ?? null,
                'service_name' => $serviceName,
            ]);

            return redirect()
                ->route('admin.services.index')
                ->with('toast_success', "Service '{$serviceName}' deleted successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete service', [
                'error' => $e->getMessage(),
                'service_id' => $service->id,
            ]);

            return back()->with('toast_error', 'Failed to delete service. Please try again.');
        }
    }
}
