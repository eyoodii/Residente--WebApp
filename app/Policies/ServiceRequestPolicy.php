<?php

namespace App\Policies;

use App\Models\Resident;
use App\Models\ServiceRequest;
use Illuminate\Auth\Access\Response;

/**
 * ServiceRequestPolicy
 * 
 * CRITICAL SECURITY: Prevents IDOR (Insecure Direct Object Reference) attacks
 * Ensures residents can only access their own service requests
 * 
 * Engineering Note: This is the first line of defense against unauthorized data access.
 * Even if a malicious user guesses another resident's request ID, this policy will 
 * automatically block the attempt before any data is exposed.
 */
class ServiceRequestPolicy
{
    /**
     * Determine whether the resident can view any service requests.
     * This controls access to the service request list page.
     */
    public function viewAny(Resident $resident): bool
    {
        // Any verified citizen can view their own service request list
        return $resident->is_verified && ($resident->isCitizen() || $resident->isAdmin());
    }

    /**
     * Determine whether the resident can view a specific service request.
     * 
     * IDOR PROTECTION: The resident ID must match the request's resident_id
     * This prevents URL manipulation attacks like:
     * /service-request/1 -> /service-request/2 (trying to view someone else's request)
     */
    public function view(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        // Admins can view any request (for processing)
        if ($resident->isAdmin()) {
            return true;
        }

        // Citizens can only view their own requests
        return $resident->id === $serviceRequest->resident_id;
    }

    /**
     * Determine whether the resident can create service requests.
     */
    public function create(Resident $resident): bool
    {
        // Must be verified citizen with PhilSys verification
        return $resident->is_verified && 
               $resident->isCitizen() && 
               $resident->hasPhilSysVerification();
    }

    /**
     * Determine whether the resident can update a service request.
     * Typically, only admins can update status, but residents can cancel pending requests.
     */
    public function update(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        // Admins can update any request
        if ($resident->isAdmin()) {
            return true;
        }

        // Residents can only cancel their own pending requests
        return $resident->id === $serviceRequest->resident_id && 
               in_array($serviceRequest->status, ['pending', 'Pending']);
    }

    /**
     * Determine whether the resident can delete a service request.
     * In government systems, deletion is typically restricted.
     */
    public function delete(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        // Only admins can delete requests (for data integrity)
        return $resident->isAdmin();
    }

    /**
     * Determine whether the resident can download the generated certificate.
     * 
     * SECURITY: Can only download if:
     * 1. They own the request
     * 2. The request has been completed/approved by the barangay
     */
    public function download(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        // Admins can download any completed document
        if ($resident->isAdmin()) {
            return in_array($serviceRequest->status, ['completed', 'Completed', 'ready-for-pickup']);
        }

        // Residents can only download their own completed documents
        return $resident->id === $serviceRequest->resident_id && 
               in_array($serviceRequest->status, ['completed', 'Completed', 'ready-for-pickup']);
    }

    /**
     * Determine whether the resident can view supporting documents.
     * Protects uploaded files from unauthorized access.
     */
    public function viewDocument(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        // Admins can view any supporting document
        if ($resident->isAdmin()) {
            return true;
        }

        // Residents can only view documents from their own requests
        return $resident->id === $serviceRequest->resident_id;
    }

    /**
     * Determine whether the resident can approve/process a request.
     * Admin-only action for changing request status.
     */
    public function process(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        return $resident->isAdmin();
    }

    /**
     * Determine whether the resident can restore a soft-deleted request.
     */
    public function restore(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        return $resident->isAdmin();
    }

    /**
     * Determine whether the resident can permanently delete the request.
     */
    public function forceDelete(Resident $resident, ServiceRequest $serviceRequest): bool
    {
        return $resident->isAdmin();
    }
}
