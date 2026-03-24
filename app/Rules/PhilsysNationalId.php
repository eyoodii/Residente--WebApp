<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * PhilSys National ID Validation Rule
 * 
 * Validates that a value matches the PhilSys National ID format:
 * - XXXX-XXXX-XXXX (12 digits with dashes - older format)
 * - XXXX-XXXX-XXXX-XXXX (16 digits with dashes - newer format)
 * - Or digits only (12-16 digits)
 */
class PhilsysNationalId implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || empty($value)) {
            $fail('The :attribute is required.');
            return;
        }

        // Clean the value - remove spaces
        $cleaned = trim($value);

        // Check for formatted patterns
        $patterns = [
            '/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/', // XXXX-XXXX-XXXX-XXXX
            '/^[0-9]{4}-[0-9]{4}-[0-9]{4}$/',          // XXXX-XXXX-XXXX
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleaned)) {
                return; // Valid
            }
        }

        // Check for digits only
        $digitsOnly = preg_replace('/[^0-9]/', '', $cleaned);
        
        if (strlen($digitsOnly) >= 12 && strlen($digitsOnly) <= 16) {
            return; // Valid
        }

        $fail('The :attribute must be a valid PhilSys National ID (format: XXXX-XXXX-XXXX or XXXX-XXXX-XXXX-XXXX).');
    }

    /**
     * Static helper to format a national ID properly
     */
    public static function format(string $nationalId): string
    {
        $digits = preg_replace('/[^0-9]/', '', $nationalId);
        
        if (strlen($digits) === 12) {
            return substr($digits, 0, 4) . '-' . substr($digits, 4, 4) . '-' . substr($digits, 8, 4);
        }
        
        if (strlen($digits) === 16) {
            return substr($digits, 0, 4) . '-' . substr($digits, 4, 4) . '-' . 
                   substr($digits, 8, 4) . '-' . substr($digits, 12, 4);
        }

        return $nationalId; // Return as-is if can't format
    }

    /**
     * Static helper to clean a national ID
     */
    public static function clean(string $nationalId): string
    {
        return preg_replace('/[^0-9-]/', '', trim($nationalId));
    }
}
