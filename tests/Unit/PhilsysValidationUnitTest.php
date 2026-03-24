<?php

namespace Tests\Unit;

use App\Models\Resident;
use App\Services\PhilsysValidationService;
use App\Services\PsgcService;
use Mockery;
use Tests\TestCase;

/**
 * PhilSys Validation Unit Tests
 * 
 * Tests the PhilSys (Philippine Identification System) validation service
 * including Luhn-10 checksum validation, ID format validation, and QR parsing.
 * 
 * These are unit tests that don't require database connections.
 */
class PhilsysValidationUnitTest extends TestCase
{
    protected PhilsysValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PhilsysValidationService::class);
    }

    // ==========================================
    // Luhn-10 Checksum Tests
    // ==========================================

    public function test_luhn_checksum_validates_correct_12_digit_id(): void
    {
        // Generate a valid 12-digit ID
        $partial = '12345678901';
        $checkDigit = $this->service->generateCheckDigit($partial);
        $validId = $partial . $checkDigit;

        $this->assertTrue($this->service->validateLuhn10Checksum($validId));
    }

    public function test_luhn_checksum_validates_correct_16_digit_id(): void
    {
        // Generate a valid 16-digit ID
        $partial = '123456789012345';
        $checkDigit = $this->service->generateCheckDigit($partial);
        $validId = $partial . $checkDigit;

        $this->assertTrue($this->service->validateLuhn10Checksum($validId));
    }

    public function test_luhn_checksum_rejects_invalid_id(): void
    {
        // Random 12-digit number unlikely to pass Luhn
        $invalidId = '123456789012';
        
        $this->assertFalse($this->service->validateLuhn10Checksum($invalidId));
    }

    public function test_luhn_checksum_validates_known_valid_number(): void
    {
        // Credit card test number (known Luhn-valid)
        $validNumber = '4532015112830366';
        
        $this->assertTrue($this->service->validateLuhn10Checksum($validNumber));
    }

    public function test_generate_check_digit_produces_valid_luhn(): void
    {
        $testCases = [
            '12345678901',      // 11 digits -> 12 digit PCN
            '123456789012345',  // 15 digits -> 16 digit PSN
            '98765432109',      // Another 11 digit test
            '00000000000',      // Edge case: all zeros
        ];

        foreach ($testCases as $partial) {
            $checkDigit = $this->service->generateCheckDigit($partial);
            $fullId = $partial . $checkDigit;
            
            $this->assertTrue(
                $this->service->validateLuhn10Checksum($fullId),
                "Failed for partial: $partial, generated: $fullId"
            );
        }
    }

    // ==========================================
    // ID Format Validation Tests
    // ==========================================

    public function test_validate_id_format_accepts_valid_12_digit_with_dashes(): void
    {
        $partial = '12345678901';
        $checkDigit = $this->service->generateCheckDigit($partial);
        $validId = substr($partial, 0, 4) . '-' . substr($partial, 4, 4) . '-' . substr($partial, 8, 3) . $checkDigit;

        $result = $this->service->validateIdFormat($validId);

        $this->assertTrue($result['valid']);
        $this->assertTrue($result['checksum_valid']);
        $this->assertEmpty($result['errors']);
    }

    public function test_validate_id_format_accepts_valid_16_digit_with_dashes(): void
    {
        $partial = '123456789012345';
        $checkDigit = $this->service->generateCheckDigit($partial);
        $digits = $partial . $checkDigit;
        $validId = substr($digits, 0, 4) . '-' . substr($digits, 4, 4) . '-' . 
                   substr($digits, 8, 4) . '-' . substr($digits, 12, 4);

        $result = $this->service->validateIdFormat($validId);

        $this->assertTrue($result['valid']);
        $this->assertTrue($result['checksum_valid']);
    }

    public function test_validate_id_format_formats_digits_only_input(): void
    {
        $partial = '12345678901';
        $checkDigit = $this->service->generateCheckDigit($partial);
        $validDigits = $partial . $checkDigit;

        $result = $this->service->validateIdFormat($validDigits);

        $this->assertTrue($result['valid']);
        $this->assertEquals('1234-5678-9015', $result['formatted']);
    }

    public function test_validate_id_format_rejects_invalid_checksum_in_strict_mode(): void
    {
        $invalidId = '1234-5678-9012'; // Invalid checksum

        $result = $this->service->validateIdFormat($invalidId, strictChecksum: true);

        $this->assertFalse($result['valid']);
        $this->assertFalse($result['checksum_valid']);
        $this->assertNotEmpty($result['errors']);
    }

    public function test_validate_id_format_allows_invalid_checksum_in_non_strict_mode(): void
    {
        $invalidId = '1234-5678-9012'; // Invalid checksum

        $result = $this->service->validateIdFormat($invalidId, strictChecksum: false);

        $this->assertTrue($result['valid']);
        $this->assertFalse($result['checksum_valid']);
    }

    public function test_validate_id_format_rejects_empty_input(): void
    {
        $result = $this->service->validateIdFormat('');

        $this->assertFalse($result['valid']);
        $this->assertContains('National ID is required', $result['errors']);
    }

    public function test_validate_id_format_rejects_wrong_length(): void
    {
        $result = $this->service->validateIdFormat('1234-5678'); // Only 8 digits

        $this->assertFalse($result['valid']);
    }

    public function test_validate_id_format_cleans_special_characters(): void
    {
        $partial = '12345678901';
        $checkDigit = $this->service->generateCheckDigit($partial);
        $messyInput = '  1234 5678 901' . $checkDigit . '  ';

        $result = $this->service->validateIdFormat($messyInput);

        $this->assertTrue($result['valid']);
        $this->assertEquals('1234-5678-9015', $result['formatted']);
    }

    // ==========================================
    // QR Code Parsing Tests
    // ==========================================

    public function test_parse_qr_code_parses_json_format(): void
    {
        $qrData = json_encode([
            'pcn' => '1234-5678-9015',
            'fn' => 'JUAN',
            'mn' => 'DELA',
            'ln' => 'CRUZ',
            'dob' => '1990-01-15',
            'sex' => 'M',
            'bp' => 'MANILA',
            'add' => '123 Main St, Brgy Sample',
        ]);

        $result = $this->service->parseQrCodeData($qrData);

        $this->assertTrue($result['success']);
        $this->assertEquals('1234-5678-9015', $result['data']['national_id']);
        $this->assertEquals('JUAN', $result['data']['first_name']);
        $this->assertEquals('DELA', $result['data']['middle_name']);
        $this->assertEquals('CRUZ', $result['data']['last_name']);
        $this->assertEquals('1990-01-15', $result['data']['date_of_birth']);
        $this->assertEquals('M', $result['data']['gender']);
    }

    public function test_parse_qr_code_handles_plain_id_string(): void
    {
        $partial = '12345678901';
        $checkDigit = $this->service->generateCheckDigit($partial);
        $qrData = $partial . $checkDigit;

        $result = $this->service->parseQrCodeData($qrData);

        $this->assertTrue($result['success']);
        $this->assertEquals('1234-5678-9015', $result['data']['national_id']);
    }

    public function test_parse_qr_code_handles_formatted_id_string(): void
    {
        $qrData = '1234-5678-9015';

        $result = $this->service->parseQrCodeData($qrData);

        $this->assertTrue($result['success']);
        $this->assertEquals('1234-5678-9015', $result['data']['national_id']);
    }

    public function test_parse_qr_code_returns_error_for_invalid_data(): void
    {
        $qrData = 'invalid-garbage-data-abc';

        $result = $this->service->parseQrCodeData($qrData);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }

    // ==========================================
    // Resident Matching Tests (Using Mocks)
    // ==========================================

    public function test_match_qr_data_detects_matching_resident(): void
    {
        $resident = $this->createMockResident([
            'first_name' => 'JUAN',
            'last_name' => 'CRUZ',
            'national_id' => '1234-5678-9015',
            'date_of_birth' => '1990-01-15',
        ]);

        $qrData = [
            'success' => true,
            'data' => [
                'national_id' => '1234-5678-9015',
                'first_name' => 'JUAN',
                'last_name' => 'CRUZ',
                'date_of_birth' => '1990-01-15',
            ],
        ];

        $result = $this->service->matchQrDataToResident($qrData, $resident);

        $this->assertTrue($result['matched']);
        $this->assertEmpty($result['mismatches']);
    }

    public function test_match_qr_data_detects_name_mismatch(): void
    {
        $resident = $this->createMockResident([
            'first_name' => 'PEDRO',
            'last_name' => 'CRUZ',
            'national_id' => '1234-5678-9015',
        ]);

        $qrData = [
            'success' => true,
            'data' => [
                'national_id' => '1234-5678-9015',
                'first_name' => 'JUAN',
                'last_name' => 'CRUZ',
            ],
        ];

        $result = $this->service->matchQrDataToResident($qrData, $resident);

        $this->assertFalse($result['matched']);
        $this->assertContains('First name does not match', $result['mismatches']);
    }

    public function test_match_qr_data_allows_minor_name_variations(): void
    {
        $resident = $this->createMockResident([
            'first_name' => 'JUAN',
            'last_name' => 'CRUZ',
        ]);

        $qrData = [
            'success' => true,
            'data' => [
                'first_name' => 'JUANN', // Minor typo
                'last_name' => 'CRUZ',
            ],
        ];

        $result = $this->service->matchQrDataToResident($qrData, $resident);

        // Should match due to > 80% similarity
        $this->assertTrue($result['matched']);
    }

    // ==========================================
    // Transaction ID Tests
    // ==========================================

    public function test_generate_transaction_id_has_correct_format(): void
    {
        $transactionId = $this->service->generateTransactionId();

        $this->assertMatchesRegularExpression(
            '/^PSV-\d{8}-[A-Z0-9]{8}$/',
            $transactionId
        );
    }

    public function test_generate_transaction_id_is_unique(): void
    {
        $ids = [];
        for ($i = 0; $i < 100; $i++) {
            $ids[] = $this->service->generateTransactionId();
        }

        $this->assertCount(100, array_unique($ids));
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    /**
     * Create a mock Resident object with specified attributes
     */
    protected function createMockResident(array $attributes): Resident
    {
        $resident = Mockery::mock(Resident::class)->makePartial();
        
        foreach ($attributes as $key => $value) {
            $resident->$key = $value;
        }
        
        return $resident;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
