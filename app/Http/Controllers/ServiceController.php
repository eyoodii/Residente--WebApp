<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Actions\ProcessServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * ServiceController
 * 
 * Handles E-Services for residents with enterprise-grade security
 * Features:
 * - IDOR protection via policies
 * - Secure file upload handling
 * - Action-based architecture
 * - Professional toast notifications
 */
class ServiceController extends Controller
{
    /**
     * Display all available services
     */
    public function index()
    {
        $resident = Auth::user();
        
        // Group services by department
        $servicesByDepartment = Service::active()
            ->with('steps', 'requirements')
            ->get()
            ->groupBy('department');

        return view('services', compact('resident', 'servicesByDepartment'));
    }

    /**
     * Show detailed information about a specific service
     */
    public function show($slug)
    {
        $resident = Auth::user();
        
        $service = Service::where('slug', $slug)
            ->with(['steps' => function($query) {
                $query->orderBy('step_number');
            }, 'requirements'])
            ->firstOrFail();

        return view('service-detail', compact('resident', 'service'));
    }

    /**
     * Submit a new service request with enterprise security
     * Uses Action Class for clean architecture
     */
    public function request($slug, ProcessServiceRequest $processRequest)
    {
        $resident = Auth::user();
        
        // SECURITY: Check if user can create service requests (PhilSys verified)
        $this->authorize('create', ServiceRequest::class);
        
        $service = Service::where('slug', $slug)->firstOrFail();

        try {
            // Use Action Class for business logic
            $serviceRequest = $processRequest->execute($resident, [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'status' => 'pending',
                'current_step' => 1,
                'requested_at' => now(),
            ]);

            // PROFESSIONAL UX: Toast notification instead of flash message
            return redirect()->route('service-request.show', $serviceRequest->request_number)
                ->with('toast_success', "Your {$service->name} request has been submitted successfully! Tracking: {$serviceRequest->request_number}");

        } catch (\Exception $e) {
            Log::error('Service request submission failed', [
                'resident_id' => $resident->id,
                'service_slug' => $slug,
                'error' => $e->getMessage(),
            ]);

            return back()->with('toast_error', 'Failed to submit service request. Please try again or contact support.');
        }
    }

    /**
     * Show a specific service request with IDOR protection
     */
    public function showRequest($requestNumber)
    {
        $resident = Auth::user();
        
        $serviceRequest = ServiceRequest::where('request_number', $requestNumber)
            ->with(['service.steps', 'service.requirements', 'resident'])
            ->firstOrFail();

        // SECURITY: IDOR Protection - Check if user can view this request
        $this->authorize('view', $serviceRequest);

        return view('service-request', compact('resident', 'serviceRequest'));
    }

    /**
     * Display all service requests for the current user
     */
    public function myRequests()
    {
        $resident = Auth::user();
        
        // SECURITY: Policy check for viewing service requests
        $this->authorize('viewAny', ServiceRequest::class);
        
        $requests = ServiceRequest::where('resident_id', $resident->id)
            ->with('service')
            ->latest()
            ->paginate(10);

        return view('my-requests', compact('resident', 'requests'));
    }

    /**
     * Upload supporting documents for a service request
     * SECURITY: Files stored in private disk, not publicly accessible
     */
    public function uploadDocument(Request $request, $requestNumber)
    {
        $resident = Auth::user();
        
        $serviceRequest = ServiceRequest::where('request_number', $requestNumber)
            ->firstOrFail();

        // SECURITY: Check authorization
        $this->authorize('update', $serviceRequest);

        // SECURITY: Strict validation for file uploads
        $validated = $request->validate([
            'document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120', // 5MB maximum
            ],
            'document_type' => 'required|string|in:id_copy,proof_of_residency,supporting_document',
        ]);

        try {
            // SECURE UPLOAD: Store in private directory
            $path = $request->file('document')->store(
                "service_requests/{$serviceRequest->id}",
                'private'
            );

            // Store file reference
            $serviceRequest->documents()->create([
                'file_path' => $path,
                'file_name' => $request->file('document')->getClientOriginalName(),
                'file_type' => $validated['document_type'],
                'file_size' => $request->file('document')->getSize(),
                'uploaded_by' => $resident->id,
                'uploaded_at' => now(),
            ]);

            // Log upload activity
            $resident->logActivity(
                'document_uploaded',
                "Uploaded {$validated['document_type']} for service request {$requestNumber}",
                [
                    'request_number' => $requestNumber,
                    'document_type' => $validated['document_type'],
                ]
            );

            return back()->with('toast_success', 'Document uploaded successfully and stored securely.');

        } catch (\Exception $e) {
            Log::error('Document upload failed', [
                'resident_id' => $resident->id,
                'request_number' => $requestNumber,
                'error' => $e->getMessage(),
            ]);

            return back()->with('toast_error', 'Failed to upload document. Please try again.');
        }
    }

    /**
     * Download a document securely
     * SECURITY: Checks authorization before serving file
     */
    public function downloadDocument($requestNumber, $documentId)
    {
        $resident = Auth::user();
        
        $serviceRequest = ServiceRequest::where('request_number', $requestNumber)
            ->firstOrFail();

        // SECURITY: Check if user can download this document
        $this->authorize('download', $serviceRequest);

        $document = $serviceRequest->documents()->findOrFail($documentId);

        // SECURITY: Verify file exists in private storage
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        // Log download activity
        $resident->logActivity(
            'document_downloaded',
            "Downloaded document from service request {$requestNumber}",
            [
                'request_number' => $requestNumber,
                'document_id' => $documentId,
            ]
        );

        // Serve file securely
        return Storage::disk('private')->download(
            $document->file_path,
            $document->file_name
        );
    }

    /**
     * Cancel a pending service request
     */
    public function cancel($requestNumber)
    {
        $resident = Auth::user();
        
        $serviceRequest = ServiceRequest::where('request_number', $requestNumber)
            ->firstOrFail();

        // SECURITY: Check authorization
        $this->authorize('update', $serviceRequest);

        // Only allow cancelling pending requests
        if (!in_array($serviceRequest->status, ['pending', 'Pending'])) {
            return back()->with('toast_error', 'Only pending requests can be cancelled.');
        }

        try {
            $serviceRequest->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => $resident->id,
            ]);

            $resident->logActivity(
                'service_request_cancelled',
                "Cancelled service request {$requestNumber}",
                ['request_number' => $requestNumber]
            );

            return back()->with('toast_success', 'Service request has been cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Service request cancellation failed', [
                'resident_id' => $resident->id,
                'request_number' => $requestNumber,
                'error' => $e->getMessage(),
            ]);

            return back()->with('toast_error', 'Failed to cancel request. Please contact support.');
        }
    }
}
