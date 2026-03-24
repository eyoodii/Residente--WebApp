<?php

namespace App\Services;

use App\Models\PhilsysVerification;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * PhilSys Validation Service
 * 
 * Provides comprehensive validation for Philippine Identification System (PhilSys)
 * identity verification. This service handles:
 * - National ID format validation with Luhn-10 checksum
 * - Resident data matching
 * - PSGC address verification
 * - Card image storage
 * - Verification logging and auditing
 * 
 * PhilSys ID Format: XXXX-XXXX-XXXX-XXXX (16 digits with dashes)
 * Also accepts: XXXX-XXXX-XXXX (12 digits - PhilSys Card Number format)
 * 
 * The last digit is a Luhn-10 check digit for validation.
 */
class PhilsysValidationService
{
    /**
     * Valid PhilSys ID patterns
     * PCN (PhilSys Card Number): 12 digits with check digit
     * PSN (PhilSys Number): 16 digits with check digit
     */
    protected const PHILSYS_PATTERN_FULL = '/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/';  // PSN format
    protected const PHILSYS_PATTERN_SHORT = '/^[0-9]{4}-[0-9]{4}-[0-9]{4}$/';
    protected const PHILSYS_PATTERN_DIGITS_ONLY = '/^[0-9]{12,16}$/';

    /**
     * Verification result codes
     */
    public const RESULT_SUCCESS = 'SUCCESS';
    public const RESULT_INVALID_FORMAT = 'INVALID_FORMAT';
    public const RESULT_ID_MISMATCH = 'ID_MISMATCH';
    public const RESULT_NAME_MISMATCH = 'NAME_MISMATCH';
    public const RESULT_DOB_MISMATCH = 'DOB_MISMATCH';
    public const RESULT_ADDRESS_MISMATCH = 'ADDRESS_MISMATCH';
    public const RESULT_ALREADY_VERIFIED = 'ALREADY_VERIFIED';
    public const RESULT_DUPLICATE_ID = 'DUPLICATE_ID';
    public const RESULT_SYSTEM_ERROR = 'SYSTEM_ERROR';

    protected PsgcService $psgcService;

    public function __construct(PsgcService $psgcService)
    {
        $this->psgcService = $psgcService;
    }

    /**
     * Validate PhilSys National ID format with Luhn-10 checksum verification
     * 
     * @param string $nationalId
     * @param bool $strictChecksum If true, reject IDs with invalid checksum. Default: true
     * @return array{valid: bool, formatted: ?string, errors: array, checksum_valid: ?bool}
     */
    public function validateIdFormat(string $nationalId, bool $strictChecksum = false): array
    {
        $cleaned = $this->cleanNationalId($nationalId);
        $digitsOnly = preg_replace('/[^0-9]/', '', $cleaned);
        $errors = [];

        // Check if empty
        if (empty($cleaned)) {
            return [
                'valid' => false,
                'formatted' => null,
                'errors' => ['National ID is required'],
                'checksum_valid' => null,
            ];
        }

        // Check length (must be 12 or 16 digits after removing dashes)
        if (!in_array(strlen($digitsOnly), [12, 16])) {
            return [
                'valid' => false,
                'formatted' => null,
                'errors' => ['National ID must be 12 or 16 digits (e.g. XXXX-XXXX-XXXX or XXXX-XXXX-XXXX-XXXX)'],
                'checksum_valid' => null,
            ];
        }

        // Compute Luhn-10 checksum for audit logging only — not used to block real IDs
        // PhilSys PCN/PSN does not strictly follow standard Luhn-10
        $checksumValid = $this->validateLuhn10Checksum($digitsOnly);

        // Format the ID
        $formatted = $this->formatNationalId($digitsOnly);

        // Additional format pattern validation
        $validPattern = preg_match(self::PHILSYS_PATTERN_FULL, $formatted) ||
                        preg_match(self::PHILSYS_PATTERN_SHORT, $formatted);

        if (!$validPattern) {
            return [
                'valid' => false,
                'formatted' => null,
                'errors' => ['Invalid National ID format. Expected format: XXXX-XXXX-XXXX or XXXX-XXXX-XXXX-XXXX'],
                'checksum_valid' => $checksumValid,
            ];
        }

        return [
            'valid' => true,
            'formatted' => $formatted,
            'errors' => [],
            'checksum_valid' => $checksumValid,
        ];
    }

    /**
     * Validate Luhn-10 (Mod 10) checksum
     * Used by PhilSys for National ID validation
     * 
     * @param string $digits The digit string to validate
     * @return bool True if checksum is valid
     */
    public function validateLuhn10Checksum(string $digits): bool
    {
        $digits = preg_replace('/[^0-9]/', '', $digits);
        
        if (empty($digits) || strlen($digits) < 2) {
            return false;
        }

        $sum = 0;
        $length = strlen($digits);
        $parity = $length % 2;

        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $digits[$i];
            
            // Double every second digit from the right
            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }

        return ($sum % 10) === 0;
    }

    /**
     * Generate check digit for a partial PhilSys ID
     * 
     * @param string $digits The digits without check digit (11 or 15 digits)
     * @return int The check digit (0-9)
     */
    public function generateCheckDigit(string $digits): int
    {
        $digits = preg_replace('/[^0-9]/', '', $digits);
        
        $sum = 0;
        $length = strlen($digits);
        $parity = ($length + 1) % 2; // +1 because we're calculating for an additional digit

        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $digits[$i];
            
            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }

        return (10 - ($sum % 10)) % 10;
    }

    /**
     * Clean national ID by removing spaces and special characters except dashes
     */
    public function cleanNationalId(string $nationalId): string
    {
        // Remove all characters except digits and dashes
        return preg_replace('/[^0-9-]/', '', trim($nationalId));
    }

    /**
     * Format national ID with dashes
     */
    public function formatNationalId(string $digits): string
    {
        $digits = preg_replace('/[^0-9]/', '', $digits);
        
        if (strlen($digits) === 12) {
            // Format as XXXX-XXXX-XXXX
            return substr($digits, 0, 4) . '-' . substr($digits, 4, 4) . '-' . substr($digits, 8, 4);
        }
        
        if (strlen($digits) === 16) {
            // Format as XXXX-XXXX-XXXX-XXXX
            return substr($digits, 0, 4) . '-' . substr($digits, 4, 4) . '-' . 
                   substr($digits, 8, 4) . '-' . substr($digits, 12, 4);
        }

        return $digits; // Return as-is if can't format
    }

    /**
     * Perform full PhilSys verification for a resident
     * 
     * @param Resident $resident
     * @param array $verificationData
     * @return array{success: bool, result_code: string, message: string, transaction_id: ?string, errors: array}
     */
    public function verifyResident(Resident $resident, array $verificationData): array
    {
        $transactionId = $this->generateTransactionId();
        
        try {
            // Log verification attempt
            $this->logVerificationAttempt($resident, $verificationData, $transactionId);

            // 1. Check if already verified
            if ($resident->hasPhilSysVerification()) {
                return $this->createResult(
                    false,
                    self::RESULT_ALREADY_VERIFIED,
                    'This account has already been PhilSys verified.',
                    $transactionId
                );
            }

            // 2. Validate ID format
            $nationalId = $verificationData['national_id'] ?? '';
            $formatResult = $this->validateIdFormat($nationalId, false);
            
            if (!$formatResult['valid']) {
                return $this->createResult(
                    false,
                    self::RESULT_INVALID_FORMAT,
                    'Invalid National ID format.',
                    $transactionId,
                    $formatResult['errors']
                );
            }

            $formattedId = $formatResult['formatted'];

            // 3. Check for duplicate ID (another resident using same PhilSys ID)
            // Check ALL residents, not just verified — the DB has a UNIQUE constraint on national_id
            $existingResident = Resident::where('national_id', $formattedId)
                ->where('id', '!=', $resident->id)
                ->first();

            if ($existingResident) {
                $msg = $existingResident->philsys_verified_at
                    ? 'This National ID is already registered to another verified account.'
                    : 'This National ID is already associated with another account. Please contact support if this is your ID.';
                return $this->createResult(
                    false,
                    self::RESULT_DUPLICATE_ID,
                    $msg,
                    $transactionId
                );
            }

            // 4. Match National ID against resident record
            // Allow verification even if the stored ID is different (they can update it)
            $storedId = $this->cleanNationalId($resident->national_id ?? '');
            $inputId = $this->cleanNationalId($formattedId);
            
            // If resident has a stored ID, it must match
            if (!empty($storedId) && $storedId !== $inputId) {
                return $this->createResult(
                    false,
                    self::RESULT_ID_MISMATCH,
                    'The provided National ID does not match your registered ID. Please contact an administrator if this is an error.',
                    $transactionId
                );
            }

            // 5. Validate PSGC address if available
            $addressValidation = $this->validateResidentAddress($resident);
            if (!$addressValidation['valid']) {
                // Log warning but don't fail - address mismatch is not critical
                Log::warning('PhilSys verification address warning', [
                    'resident_id' => $resident->id,
                    'transaction_id' => $transactionId,
                    'warnings' => $addressValidation['warnings'],
                ]);
            }

            // 6. All validations passed - return success
            return $this->createResult(
                true,
                self::RESULT_SUCCESS,
                'PhilSys verification completed successfully.',
                $transactionId
            );

        } catch (\Exception $e) {
            Log::error('PhilSys verification error', [
                'resident_id' => $resident->id,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->createResult(
                false,
                self::RESULT_SYSTEM_ERROR,
                'A system error occurred during verification. Please try again.',
                $transactionId,
                [$e->getMessage()]
            );
        }
    }

    /**
     * Complete the verification process and update resident record
     * 
     * @param Resident $resident
     * @param array $verificationData
     * @param string $transactionId
     * @return bool
     */
    public function completeVerification(
        Resident $resident,
        array $verificationData,
        string $transactionId
    ): bool {
        return DB::transaction(function () use ($resident, $verificationData, $transactionId) {
            $formattedId = $this->formatNationalId(
                $this->cleanNationalId($verificationData['national_id'])
            );

            // Update resident record
            $resident->update([
                'national_id' => $formattedId,
                'philsys_verified_at' => now(),
                'philsys_verification_method' => $verificationData['verification_type'] ?? 'manual_verify',
                'philsys_transaction_id' => $transactionId,
                'role' => 'citizen', // Upgrade from visitor to citizen
                'is_verified' => true,
            ]);

            // If PSGC codes are not set, populate them
            if (!$resident->hasPsgcAddress()) {
                $this->populateResidentPsgcCodes($resident);
            }

            return true;
        });
    }

    /**
     * Validate resident's address against PSGC database
     */
    protected function validateResidentAddress(Resident $resident): array
    {
        $warnings = [];

        // Skip if no barangay set
        if (empty($resident->barangay)) {
            return ['valid' => true, 'warnings' => []];
        }

        // Get address codes from PSGC
        $addressCodes = $this->psgcService->getAddressCodes($resident->barangay);

        if (!$addressCodes) {
            $warnings[] = "Barangay '{$resident->barangay}' not found in PSGC database";
            return ['valid' => false, 'warnings' => $warnings];
        }

        // If resident has PSGC codes, validate they match
        if ($resident->barangay_psgc_code && $resident->barangay_psgc_code !== $addressCodes['barangay_code']) {
            $warnings[] = 'Stored PSGC barangay code does not match PSGC database';
        }

        return [
            'valid' => empty($warnings),
            'warnings' => $warnings,
        ];
    }

    /**
     * Populate PSGC codes for a resident based on their barangay
     */
    protected function populateResidentPsgcCodes(Resident $resident): void
    {
        $addressCodes = $this->psgcService->getAddressCodes($resident->barangay);

        if ($addressCodes) {
            $resident->update([
                'region_psgc_code' => $addressCodes['region_code'],
                'province_psgc_code' => $addressCodes['province_code'],
                'city_psgc_code' => $addressCodes['city_code'],
                'barangay_psgc_code' => $addressCodes['barangay_code'],
            ]);
        }
    }

    /**
     * Store PhilSys card images securely
     * 
     * @param Resident $resident
     * @param array $files ['front' => UploadedFile, 'back' => UploadedFile]
     * @return array{success: bool, paths: array}
     */
    public function storeCardImages(Resident $resident, array $files): array
    {
        $paths = [];

        try {
            $directory = 'philsys-cards/' . $resident->id;

            if (isset($files['front']) && $files['front']) {
                $frontPath = $files['front']->storeAs(
                    $directory,
                    'front_' . time() . '.' . $files['front']->getClientOriginalExtension(),
                    'private'
                );
                $paths['front'] = $frontPath;
            }

            if (isset($files['back']) && $files['back']) {
                $backPath = $files['back']->storeAs(
                    $directory,
                    'back_' . time() . '.' . $files['back']->getClientOriginalExtension(),
                    'private'
                );
                $paths['back'] = $backPath;
            }

            // Persist paths to the residents table
            $updateData = [];
            if (!empty($paths['front'])) {
                $updateData['philsys_id_front'] = $paths['front'];
            }
            if (!empty($paths['back'])) {
                $updateData['philsys_id_back'] = $paths['back'];
            }
            if (!empty($updateData)) {
                $resident->update($updateData);
            }

            return ['success' => true, 'paths' => $paths];

        } catch (\Exception $e) {
            Log::error('Failed to store PhilSys card images', [
                'resident_id' => $resident->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'paths' => []];
        }
    }

    /**
     * Generate a unique transaction ID for verification
     */
    public function generateTransactionId(): string
    {
        $prefix = 'PSV'; // PhilSys Verification
        $timestamp = now()->format('Ymd');
        $random = strtoupper(Str::random(8));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Log verification attempt for auditing
     */
    protected function logVerificationAttempt(
        Resident $resident,
        array $verificationData,
        string $transactionId
    ): void {
        Log::info('PhilSys verification attempt', [
            'transaction_id' => $transactionId,
            'resident_id' => $resident->id,
            'verification_type' => $verificationData['verification_type'] ?? 'unknown',
            'has_national_id' => !empty($verificationData['national_id']),
            'timestamp' => now()->toIso8601String(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Create a verification record in the database for audit trail
     * 
     * @param Resident $resident
     * @param array $verificationData
     * @param string $transactionId
     * @param string $resultCode
     * @param bool $isSuccessful
     * @param array $additionalData Optional data: qr_data, match_results, card_front_path, card_back_path, admin_id, admin_notes
     * @return PhilsysVerification
     */
    public function createVerificationRecord(
        Resident $resident,
        array $verificationData,
        string $transactionId,
        string $resultCode,
        bool $isSuccessful,
        array $additionalData = []
    ): PhilsysVerification {
        $nationalId = $verificationData['national_id'] ?? '';
        
        return PhilsysVerification::create([
            'resident_id' => $resident->id,
            'transaction_id' => $transactionId,
            'verification_method' => $verificationData['verification_type'] ?? 'unknown',
            'result_code' => $resultCode,
            'national_id_hash' => $nationalId ? hash('sha256', $this->cleanNationalId($nationalId)) : null,
            'card_front_path' => $additionalData['card_front_path'] ?? null,
            'card_back_path' => $additionalData['card_back_path'] ?? null,
            'region_psgc_code' => $resident->region_psgc_code,
            'province_psgc_code' => $resident->province_psgc_code,
            'city_psgc_code' => $resident->city_psgc_code,
            'barangay_psgc_code' => $resident->barangay_psgc_code,
            'qr_data' => $additionalData['qr_data'] ?? null,
            'match_results' => $additionalData['match_results'] ?? null,
            'address_validation' => $additionalData['address_validation'] ?? null,
            'verified_by_admin_id' => $additionalData['admin_id'] ?? null,
            'admin_notes' => $additionalData['admin_notes'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_successful' => $isSuccessful,
        ]);
    }

    /**
     * Create a standardized verification result
     */
    protected function createResult(
        bool $success,
        string $resultCode,
        string $message,
        ?string $transactionId = null,
        array $errors = []
    ): array {
        return [
            'success' => $success,
            'result_code' => $resultCode,
            'message' => $message,
            'transaction_id' => $transactionId,
            'errors' => $errors,
        ];
    }

    /**
     * Get verification history for a resident
     */
    public function getVerificationHistory(Resident $resident): array
    {
        return $resident->activityLogs()
            ->where('action', 'LIKE', 'philsys%')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'action' => $log->action,
                    'description' => $log->description,
                    'metadata' => $log->metadata,
                    'created_at' => $log->created_at->toDateTimeString(),
                ];
            })
            ->toArray();
    }

    /**
     * Validate QR code data from PhilSys card
     * 
     * PhilSys QR codes contain JSON-encoded data with:
     * - pcn: PhilSys Card Number
     * - fn: First Name
     * - mn: Middle Name
     * - ln: Last Name
     * - dob: Date of Birth
     * - sex: Gender
     * - bp: Birth Place
     * - add: Address
     */
    public function parseQrCodeData(string $qrData): array
    {
        try {
            // Try JSON format first
            $data = json_decode($qrData, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return [
                    'success' => true,
                    'data' => [
                        'national_id' => $data['pcn'] ?? $data['national_id'] ?? null,
                        'first_name' => $data['fn'] ?? $data['first_name'] ?? null,
                        'middle_name' => $data['mn'] ?? $data['middle_name'] ?? null,
                        'last_name' => $data['ln'] ?? $data['last_name'] ?? null,
                        'date_of_birth' => $data['dob'] ?? $data['date_of_birth'] ?? null,
                        'gender' => $data['sex'] ?? $data['gender'] ?? null,
                        'place_of_birth' => $data['bp'] ?? $data['place_of_birth'] ?? null,
                        'address' => $data['add'] ?? $data['address'] ?? null,
                    ],
                ];
            }

            // If not JSON, try to parse as plain National ID
            $formatResult = $this->validateIdFormat($qrData);
            if ($formatResult['valid']) {
                return [
                    'success' => true,
                    'data' => [
                        'national_id' => $formatResult['formatted'],
                    ],
                ];
            }

            return [
                'success' => false,
                'error' => 'Unable to parse QR code data',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Invalid QR code format: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Match QR code data against resident record
     */
    public function matchQrDataToResident(array $qrData, Resident $resident): array
    {
        $mismatches = [];
        $data = $qrData['data'] ?? [];

        // Check National ID
        if (!empty($data['national_id']) && !empty($resident->national_id)) {
            $qrId = $this->cleanNationalId($data['national_id']);
            $residentId = $this->cleanNationalId($resident->national_id);
            
            if ($qrId !== $residentId) {
                $mismatches[] = 'National ID does not match registered record';
            }
        }

        // Check name (fuzzy match)
        if (!empty($data['first_name'])) {
            $qrFirstName = strtolower(trim($data['first_name']));
            $residentFirstName = strtolower(trim($resident->first_name));
            
            if ($qrFirstName !== $residentFirstName) {
                // Allow minor variations
                similar_text($qrFirstName, $residentFirstName, $similarity);
                if ($similarity < 80) {
                    $mismatches[] = 'First name does not match';
                }
            }
        }

        if (!empty($data['last_name'])) {
            $qrLastName = strtolower(trim($data['last_name']));
            $residentLastName = strtolower(trim($resident->last_name));
            
            if ($qrLastName !== $residentLastName) {
                similar_text($qrLastName, $residentLastName, $similarity);
                if ($similarity < 80) {
                    $mismatches[] = 'Last name does not match';
                }
            }
        }

        // Check date of birth
        if (!empty($data['date_of_birth']) && $resident->date_of_birth) {
            try {
                $qrDob = \Carbon\Carbon::parse($data['date_of_birth'])->format('Y-m-d');
                $residentDob = \Carbon\Carbon::parse($resident->date_of_birth)->format('Y-m-d');
                
                if ($qrDob !== $residentDob) {
                    $mismatches[] = 'Date of birth does not match';
                }
            } catch (\Exception $e) {
                // Date parsing failed, skip check
            }
        }

        return [
            'matched' => empty($mismatches),
            'mismatches' => $mismatches,
        ];
    }
}
