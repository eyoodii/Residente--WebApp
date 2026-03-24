<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class RegisterResidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Allow letters, spaces, periods, hyphens, and apostrophes (common in names like Jr., Sr., O'Brien, etc.)
            'first_name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\sñÑ.\'-]+$/'],
            'middle_name' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z\sñÑ.\'-]+$/'],
            'last_name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\sñÑ.\'-]+$/'],
            'extension_name' => ['nullable', 'string', Rule::in(['', 'Jr.', 'Sr.', 'II', 'III', 'IV', 'V'])],
            
            // Ensure realistic dates to prevent database errors
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            
            // Strict Barangay Validation for Buguey - Only 30 official barangays allowed
            'barangay' => ['required', 'string', Rule::in(array_keys(config('barangays.list', [])))],
            
            // Email validation (removed DNS check to prevent rate limiting)
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:residents,email'],
            
            // Household relationship validation
            'household_relationship' => ['required', 'string', Rule::in([
                'Household Head', 'Spouse', 'Child', 'Parent', 'Sibling',
                'Grandchild', 'Grandparent', 'Other Relative', 'Non-Relative'
            ])],
            
              // Physical address fields (optional at registration)
            'purok' => ['nullable', 'string', 'max:100'],
            'house_number' => ['nullable', 'string', 'max:100'],
            'street' => ['nullable', 'string', 'max:150'],
            'family_registration_type' => ['nullable', 'string', Rule::in(['new_family', 'existing_family'])],

            // Postal code validation
            'postal_code' => ['required', 'string', 'size:4', 'regex:/^[0-9]{4}$/'],
            
            // Enforce strong passwords (removed breach check to prevent rate limiting)
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            
            // Ensure terms are explicitly checked
            'terms' => ['accepted'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.regex' => 'The first name may only contain letters, spaces, periods, hyphens, and apostrophes.',
            'middle_name.regex' => 'The middle name may only contain letters, spaces, periods, hyphens, and apostrophes.',
            'last_name.regex' => 'The last name may only contain letters, spaces, periods, hyphens, and apostrophes.',
            'barangay.required' => 'Please select your barangay.',
            'barangay.in' => 'The selected barangay is not valid. Please select from the official barangays of Buguey.',
            'household_relationship.required' => 'Please select your relationship to the household.',
            'household_relationship.in' => 'The selected relationship type is not valid.',
            'postal_code.required' => 'Please enter your postal code.',
            'postal_code.size' => 'Postal code must be exactly 4 digits.',
            'postal_code.regex' => 'Postal code must contain only numbers.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'terms.accepted' => 'You must read and accept the Terms and Conditions and Privacy Policy to register.',
        ];
    }
}
