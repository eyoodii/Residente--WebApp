<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Services\PhilsysValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    protected PhilsysValidationService $philsysService;

    public function __construct(PhilsysValidationService $philsysService)
    {
        $this->philsysService = $philsysService;
    }

    /**
     * Show the PhilSys verification page
     */
    public function showPhilSysVerification()
    {
        /** @var Resident $resident */
        $resident = Auth::user();

        // If already verified, redirect to dashboard
        if ($resident->hasPhilSysVerification()) {
            return redirect()->route('dashboard')
                ->with('info', 'Your PhilSys identity has already been verified.');
        }

        return view('verification.philsys');
    }

    /**
     * Process PhilSys QR scan or verification
     * 
     * This integrates with the PhilsysValidationService for comprehensive
     * identity verification including format validation, data matching,
     * PSGC address verification, and audit trail creation.
     */
    public function verifyPhilSys(Request $request)
    {
        /** @var Resident $resident */
        $resident = Auth::user();

        // Validate the verification request
        $validated = $request->validate([
            'verification_type' => 'required|in:qr_scan,manual_verify',
            'national_id' => 'required|string|min:12|max:20',
            'transaction_id' => 'nullable|string',
            'card_front' => 'nullable|image|max:5120', // 5MB max
            'card_back' => 'nullable|image|max:5120',
            'qr_data' => 'nullable|string', // Raw QR data for audit
        ], [
            'national_id.required' => 'Please enter your PhilSys National ID number.',
            'national_id.min' => 'National ID must be at least 12 characters.',
            'verification_type.required' => 'Please select a verification method.',
            'card_front.image' => 'Card front must be a valid image file.',
            'card_back.image' => 'Card back must be a valid image file.',
            'card_front.max' => 'Card front image must be less than 5MB.',
            'card_back.max' => 'Card back image must be less than 5MB.',
        ]);

        $transactionId = null;
        $cardPaths = [];

        try {
            // Perform PhilSys verification using the service
            $verificationResult = $this->philsysService->verifyResident($resident, $validated);
            $transactionId = $verificationResult['transaction_id'];

            if (!$verificationResult['success']) {
                // Create failed verification record for audit trail
                $this->philsysService->createVerificationRecord(
                    $resident,
                    $validated,
                    $transactionId,
                    $verificationResult['result_code'],
                    false,
                    [
                        'qr_data' => $validated['qr_data'] ?? null,
                        'match_results' => json_encode($verificationResult['errors']),
                    ]
                );

                // Log failed verification attempt
                $resident->logActivity(
                    'philsys_verification_failed',
                    $verificationResult['message'],
                    [
                        'result_code' => $verificationResult['result_code'],
                        'transaction_id' => $transactionId,
                        'errors' => $verificationResult['errors'],
                        'ip_address' => $request->ip(),
                    ]
                );

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => $verificationResult['message'],
                        'result_code' => $verificationResult['result_code'],
                        'errors' => $verificationResult['errors'],
                        'transaction_id' => $transactionId,
                    ], 422);
                }

                return back()
                    ->with('error', $verificationResult['message'])
                    ->withInput();
            }

            // Store card images if provided
            if ($request->hasFile('card_front') || $request->hasFile('card_back')) {
                $imageResult = $this->philsysService->storeCardImages($resident, [
                    'front' => $request->file('card_front'),
                    'back' => $request->file('card_back'),
                ]);
                $cardPaths = $imageResult['paths'] ?? [];
            }

            // Complete the verification process
            $this->philsysService->completeVerification(
                $resident,
                $validated,
                $transactionId
            );

            // Create successful verification record for audit trail
            $this->philsysService->createVerificationRecord(
                $resident,
                $validated,
                $transactionId,
                $verificationResult['result_code'],
                true,
                [
                    'qr_data' => $validated['qr_data'] ?? null,
                    'card_front_path' => $cardPaths['front'] ?? null,
                    'card_back_path' => $cardPaths['back'] ?? null,
                ]
            );

            // Log successful verification
            $resident->logActivity(
                'philsys_verification',
                'PhilSys identity verification completed successfully',
                [
                    'verification_method' => $validated['verification_type'],
                    'transaction_id' => $transactionId,
                    'ip_address' => $request->ip(),
                    'checksum_valid' => $verificationResult['checksum_valid'] ?? null,
                ]
            );

            // Create notification
            $resident->createNotification([
                'title' => 'PhilSys Verification Successful',
                'message' => 'Your identity has been verified. Please complete your profile to activate your account.',
                'type' => 'success',
                'action_url' => route('profile.onboarding.show'),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'PhilSys verification successful. Please complete your profile.',
                    'transaction_id' => $transactionId,
                    'redirect_url' => route('profile.onboarding.show'),
                ]);
            }

            return redirect()->route('profile.onboarding.show')
                ->with('toast_success', 'PhilSys verification successful! Please complete your profile to activate your account.');

        } catch (\Illuminate\Database\QueryException $qe) {
            Log::error('PhilSys Verification DB Error: ' . $qe->getMessage(), [
                'resident_id' => $resident->id,
                'transaction_id' => $transactionId,
                'ip_address' => $request->ip(),
            ]);

            // Detect duplicate national_id constraint violation
            $userMessage = 'Verification failed due to a database error. Please try again.';
            if (str_contains($qe->getMessage(), 'Duplicate entry') && str_contains($qe->getMessage(), 'national_id')) {
                $userMessage = 'This National ID is already associated with another account. Please verify the number is correct or contact support.';
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => $userMessage], 422);
            }
            return back()->with('error', $userMessage)->withInput();

        } catch (\Exception $e) {
            Log::error('PhilSys Verification Error: ' . $e->getMessage(), [
                'resident_id' => $resident->id,
                'transaction_id' => $transactionId,
                'ip_address' => $request->ip(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Create error verification record
            if ($transactionId) {
                try {
                    $this->philsysService->createVerificationRecord(
                        $resident,
                        $validated,
                        $transactionId,
                        'SYSTEM_ERROR',
                        false,
                        ['error' => $e->getMessage()]
                    );
                } catch (\Exception $recordError) {
                    Log::error('Failed to create verification record: ' . $recordError->getMessage());
                }
            }

            // Log failed verification attempt
            $resident->logActivity(
                'philsys_verification_failed',
                'PhilSys verification attempt failed: ' . $e->getMessage(),
                [
                    'verification_type' => $validated['verification_type'] ?? 'unknown',
                    'ip_address' => $request->ip(),
                ]
            );

            // Show the actual error, not a generic message
            $userMessage = 'Verification failed: ' . $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $userMessage,
                ], 500);
            }

            return back()->with('error', $userMessage)->withInput();
        }
    }

    /**
     * API endpoint to validate National ID format without full verification
     */
    public function validateNationalIdFormat(Request $request)
    {
        $validated = $request->validate([
            'national_id' => 'required|string',
        ]);

        $result = $this->philsysService->validateIdFormat($validated['national_id']);

        return response()->json([
            'valid' => $result['valid'],
            'formatted' => $result['formatted'],
            'errors' => $result['errors'],
        ]);
    }

    /**
     * API endpoint to parse QR code data
     */
    public function parseQrCode(Request $request)
    {
        $validated = $request->validate([
            'qr_data' => 'required|string',
        ]);

        $result = $this->philsysService->parseQrCodeData($validated['qr_data']);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to parse QR code',
            ], 422);
        }

        // If user is authenticated, check if data matches
        if (Auth::check()) {
            /** @var Resident $resident */
            $resident = Auth::user();
            $matchResult = $this->philsysService->matchQrDataToResident($result, $resident);
            
            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'match_result' => $matchResult,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $result['data'],
        ]);
    }

    /**
     * Admin manual verification of PhilSys (for residents without QR capability)
     */
    public function adminManualVerify(Request $request, $residentId)
    {
        /** @var Resident $admin */
        $admin = Auth::user();
        
        // Only admins can manually verify
        if (!$admin->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'national_id' => 'required|string|min:12|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $resident = Resident::findOrFail($residentId);

        // Validate National ID format
        $formatResult = $this->philsysService->validateIdFormat($validated['national_id']);
        if (!$formatResult['valid']) {
            return back()->with('error', 'Invalid National ID format: ' . implode(', ', $formatResult['errors']));
        }

        // Verify the resident using the service
        $verificationResult = $this->philsysService->verifyResident($resident, [
            'national_id' => $validated['national_id'],
            'verification_type' => 'manual_verify',
        ]);

        if (!$verificationResult['success']) {
            return back()->with('error', $verificationResult['message']);
        }

        // Complete verification
        $this->philsysService->completeVerification($resident, [
            'national_id' => $validated['national_id'],
            'verification_type' => 'admin_manual',
        ], $verificationResult['transaction_id']);

        // Log admin verification
        $resident->logActivity(
            'philsys_manual_verification',
            'PhilSys verification completed manually by admin',
            [
                'verified_by' => $admin->id,
                'admin_name' => $admin->full_name,
                'transaction_id' => $verificationResult['transaction_id'],
                'notes' => $validated['notes'] ?? null,
            ]
        );

        // Notify resident
        $resident->createNotification([
            'title' => 'PhilSys Verification Completed',
            'message' => 'Your identity has been verified. Please complete your profile to activate your account.',
            'type' => 'success',
            'action_url' => route('profile.onboarding.show'),
        ]);

        return back()->with('success', 'Resident PhilSys verification completed successfully.');
    }

    /**
     * Revoke PhilSys verification (admin only, for security reasons)
     */
    public function revokePhilSysVerification(Request $request, $residentId)
    {
        /** @var Resident $admin */
        $admin = Auth::user();
        
        // Only admins can revoke verification
        if (!$admin->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $resident = Resident::findOrFail($residentId);

        // Store revocation data before clearing
        $previousData = [
            'verified_at' => $resident->philsys_verified_at,
            'method' => $resident->philsys_verification_method,
            'transaction_id' => $resident->philsys_transaction_id,
        ];

        // Clear verification
        $resident->update([
            'philsys_verified_at' => null,
            'philsys_verification_method' => null,
            'philsys_transaction_id' => null,
        ]);

        // Log revocation
        $resident->logActivity(
            'philsys_verification_revoked',
            'PhilSys verification revoked by admin',
            [
                'revoked_by' => $admin->id,
                'admin_name' => $admin->full_name,
                'reason' => $validated['reason'],
                'previous_data' => $previousData,
            ]
        );

        // Notify resident
        $resident->createNotification([
            'title' => 'PhilSys Verification Revoked',
            'message' => 'Your PhilSys verification has been revoked. Please contact the administrator.',
            'type' => 'warning',
        ]);

        return back()->with('success', 'PhilSys verification has been revoked.');
    }
}
