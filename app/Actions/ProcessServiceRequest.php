<?php

namespace App\Actions;

use App\Models\Resident;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * ProcessServiceRequest Action Class
 * 
 * PROFESSIONAL CODE ARCHITECTURE: Separates complex business logic from controllers
 * This makes code reusable, testable, and maintainable
 * 
 * Engineering Note: Action classes follow the Single Responsibility Principle
 * Each action handles ONE specific business operation
 */
class ProcessServiceRequest
{
    /**
     * Handles the complex logic of initiating a new LGU service transaction.
     * 
     * This method:
     * 1. Creates the service request record
     * 2. Generates an immutable tracking code
     * 3. Logs the transaction for audit
     * 4. Creates notification for the resident
     * 5. Notifies barangay staff (optional)
     * 
     * @param Resident $resident The requesting resident
     * @param array $data Service request data
     * @return ServiceRequest The created service request
     * @throws \Exception If transaction fails
     */
    public function execute(Resident $resident, array $data): ServiceRequest
    {
        return DB::transaction(function () use ($resident, $data) {
            // 1. Create the database record
            $request = $resident->serviceRequests()->create(array_merge($data, [
                'status' => $data['status'] ?? 'pending',
                'submitted_at' => now(),
            ]));

            // 2. Generate an immutable tracking code (e.g., REQ-2026-AB12CD)
            $request->request_number = $this->generateTrackingCode($request);
            $request->save();

            // 3. Log the transaction securely for the LGU audit trail
            Log::channel('daily')->info("New {$request->service_name} requested", [
                'resident_id' => $resident->id,
                'resident_name' => $resident->full_name,
                'tracking_code' => $request->request_number,
                'service_type' => $request->service_name,
                'ip_address' => request()->ip(),
            ]);

            // 4. Create activity log entry
            if (method_exists($resident, 'logActivity')) {
                $resident->logActivity(
                    'service_request_created',
                    "Requested {$request->service_name}",
                    [
                        'request_number' => $request->request_number,
                        'service_id' => $request->service_id ?? null,
                        'status' => $request->status,
                    ]
                );
            }

            // 5. Create notification for the resident
            if (method_exists($resident, 'createNotification')) {
                $resident->createNotification([
                    'title' => 'Service Request Submitted',
                    'message' => "Your {$request->service_name} request has been submitted successfully. Tracking: {$request->request_number}",
                    'type' => 'success',
                    'action_url' => route('service-request.show', $request->request_number),
                ]);
            }

            // 6. (Optional) Notify barangay admin of new request
            $this->notifyBarangayStaff($request);

            return $request;
        });
    }

    /**
     * Generate a unique tracking code for the service request
     * Format: REQ-YYYY-XXXXXX (REQ-2026-AB12CD)
     * 
     * @param ServiceRequest $request
     * @return string
     */
    protected function generateTrackingCode(ServiceRequest $request): string
    {
        $year = date('Y');
        $uniqueId = strtoupper(substr(uniqid(), -6));
        
        // Ensure uniqueness by checking database
        $trackingCode = "REQ-{$year}-{$uniqueId}";
        
        while (ServiceRequest::where('request_number', $trackingCode)->exists()) {
            $uniqueId = strtoupper(substr(uniqid(), -6));
            $trackingCode = "REQ-{$year}-{$uniqueId}";
        }
        
        return $trackingCode;
    }

    /**
     * Notify barangay staff of new service request
     * Can be extended to send SMS/Email notifications
     * 
     * @param ServiceRequest $request
     * @return void
     */
    protected function notifyBarangayStaff(ServiceRequest $request): void
    {
        // Get admin users for notification
        $admins = Resident::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            if (method_exists($admin, 'createNotification')) {
                $admin->createNotification([
                    'title' => 'New Service Request',
                    'message' => "New {$request->service_name} request from {$request->resident->full_name}. Tracking: {$request->request_number}",
                    'type' => 'info',
                    'action_url' => route('admin.service-requests.show', $request->id),
                ]);
            }
        }

        // Log for staff dashboard
        Log::channel('daily')->info('Barangay staff notified of new service request', [
            'request_number' => $request->request_number,
            'service_type' => $request->service_name,
            'notified_admins' => $admins->count(),
        ]);
    }

    /**
     * Update service request status with audit trail
     * 
     * @param ServiceRequest $request
     * @param string $status
     * @param string|null $notes
     * @param Resident|null $processedBy
     * @return ServiceRequest
     */
    public function updateStatus(
        ServiceRequest $request, 
        string $status, 
        ?string $notes = null,
        ?Resident $processedBy = null
    ): ServiceRequest {
        return DB::transaction(function () use ($request, $status, $notes, $processedBy) {
            $oldStatus = $request->status;
            
            $request->update([
                'status' => $status,
                'notes' => $notes,
                'processed_at' => now(),
                'processed_by' => $processedBy?->id,
            ]);

            // Log status change
            Log::channel('daily')->info("Service request status updated", [
                'request_number' => $request->request_number,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'processed_by' => $processedBy?->full_name ?? 'System',
            ]);

            // Notify resident of status change
            if ($request->resident && method_exists($request->resident, 'createNotification')) {
                $request->resident->createNotification([
                    'title' => 'Service Request Updated',
                    'message' => "Your {$request->service_name} request status changed to: {$status}",
                    'type' => $this->getNotificationType($status),
                    'action_url' => route('service-request.show', $request->request_number),
                ]);
            }

            return $request->fresh();
        });
    }

    /**
     * Get notification type based on status
     * 
     * @param string $status
     * @return string
     */
    protected function getNotificationType(string $status): string
    {
        return match(strtolower($status)) {
            'completed', 'approved', 'ready-for-pickup' => 'success',
            'rejected', 'cancelled' => 'error',
            'in-progress', 'processing' => 'info',
            default => 'info',
        };
    }
}
