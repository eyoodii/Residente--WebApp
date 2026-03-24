<?php

namespace App\Http\Controllers;

use App\Models\HouseholdProfile;
use App\Models\HouseholdMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CitizenProfileController extends Controller
{
    /**
     * Display the citizen profile page
     */
    public function index()
    {
        $resident = Auth::user();
        $householdProfile = $resident->householdProfile;
        $householdMembers = $resident->householdMembers;
        
        return view('citizen.profile.index', compact(
            'resident',
            'householdProfile',
            'householdMembers'
        ));
    }

    /**
     * Show personal information edit form
     */
    public function editPersonal()
    {
        $resident = Auth::user();
        return view('citizen.profile.edit-personal', compact('resident'));
    }

    /**
     * Update personal information
     */
    public function updatePersonal(Request $request)
    {
        $resident = Auth::user();
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'extension_name' => ['nullable', 'string', 'max:10'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'civil_status' => ['required', Rule::in(['Single', 'Married', 'Widowed', 'Legally Separated'])],
            'blood_type' => ['nullable', 'string', 'max:10'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'vulnerable_sector' => ['required', Rule::in(['None', 'Senior Citizen', 'PWD', 'Solo Parent', 'Indigenous People'])],
            'household_relationship' => ['required', Rule::in(['Household Head', 'Spouse', 'Child', 'Parent', 'Sibling', 'Grandchild', 'Grandparent', 'Other Relative', 'Non-Relative'])],
            'household_number' => ['nullable', 'string', 'max:50'],
            'household_member_number' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $oldValues = $resident->only(array_keys($validated));
        
        $resident->update($validated);

        // Log the activity
        $resident->logActivity(
            'update',
            "{$resident->full_name} updated personal information",
            [
                'entity_type' => 'Resident',
                'entity_id' => $resident->id,
                'old_values' => $oldValues,
                'new_values' => $validated,
                'severity' => 'warning',
            ]
        );

        return redirect()
            ->route('citizen.profile')
            ->with('success', 'Personal information updated successfully!');
    }

    /**
     * Show address information edit form
     */
    public function editAddress()
    {
        $resident = Auth::user();
        return view('citizen.profile.edit-address', compact('resident'));
    }

    /**
     * Update address information
     */
    public function updateAddress(Request $request)
    {
        $resident = Auth::user();
        
        $validated = $request->validate([
            'purok' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
        ]);

        $oldValues = $resident->only(array_keys($validated));
        
        $resident->update($validated);

        // Log the activity
        $resident->logActivity(
            'update',
            "{$resident->full_name} updated address information",
            [
                'entity_type' => 'Resident',
                'entity_id' => $resident->id,
                'old_values' => $oldValues,
                'new_values' => $validated,
                'severity' => 'warning',
            ]
        );

        // Note: Admin needs to verify this change before role can be upgraded
        if ($resident->isVisitor()) {
            $resident->createNotification([
                'title' => 'Address Updated',
                'message' => 'Your address has been updated. Please visit the Barangay Hall with a valid ID to verify your residency.',
                'type' => 'account_update',
                'priority' => 'normal',
            ]);
        }

        return redirect()
            ->route('citizen.profile')
            ->with('success', 'Address updated successfully!');
    }

    /**
     * Show household profile form
     */
    public function editHousehold()
    {
        $resident = Auth::user();
        $householdProfile = $resident->householdProfile ?? new HouseholdProfile();
        
        return view('citizen.profile.edit-household', compact('resident', 'householdProfile'));
    }

    /**
     * Update household profile
     */
    public function updateHousehold(Request $request)
    {
        $resident = Auth::user();
        
        $validated = $request->validate([
            'housing_type' => ['required', Rule::in(['Owned', 'Rented', 'Rent-Free with Consent', 'Informal Settler'])],
            'dwelling_type' => ['nullable', Rule::in(['Single Detached', 'Duplex', 'Apartment', 'Townhouse', 'Makeshift/Salvaged', 'Others'])],
            'number_of_rooms' => ['nullable', 'integer', 'min:0'],
            'has_electricity' => ['boolean'],
            'has_water_supply' => ['boolean'],
            'water_source' => ['nullable', 'string'],
            'toilet_facility' => ['nullable', 'string'],
            'has_internet_access' => ['boolean'],
            'has_television' => ['boolean'],
            'has_radio' => ['boolean'],
            'total_household_income' => ['nullable', 'numeric', 'min:0'],
            'income_classification' => ['nullable', 'string'],
            'owns_vehicle' => ['boolean'],
            'vehicle_types' => ['nullable', 'string'],
            'owns_agricultural_land' => ['boolean'],
            'agricultural_land_area' => ['nullable', 'numeric', 'min:0'],
            'special_needs' => ['nullable', 'string'],
            'assistance_received' => ['nullable', 'string'],
        ]);

        $householdProfile = $resident->householdProfile()->updateOrCreate(
            ['resident_id' => $resident->id],
            $validated
        );

        // Log the activity
        $resident->logActivity(
            'update',
            "{$resident->full_name} updated household profile",
            [
                'entity_type' => 'HouseholdProfile',
                'entity_id' => $householdProfile->id,
                'severity' => 'info',
            ]
        );

        return redirect()
            ->route('citizen.profile')
            ->with('success', 'Household profile updated successfully!');
    }

    /**
     * Show add household member form
     */
    public function addMember()
    {
        $resident = Auth::user();
        return view('citizen.profile.add-member', compact('resident'));
    }

    /**
     * Store new household member
     */
    public function storeMember(Request $request)
    {
        $resident = Auth::user();
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'relationship' => ['required', Rule::in(['Spouse', 'Son', 'Daughter', 'Father', 'Mother', 'Brother', 'Sister', 'Grandchild', 'Other Relative'])],
            'civil_status' => ['required', Rule::in(['Single', 'Married', 'Widowed', 'Legally Separated'])],
            'occupation' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'educational_attainment' => ['nullable', Rule::in([
                'No Formal Education',
                'Elementary Undergraduate',
                'Elementary Graduate',
                'High School Undergraduate',
                'High School Graduate',
                'College Undergraduate',
                'College Graduate',
                'Vocational',
                'Post Graduate'
            ])],
            'is_pwd' => ['boolean'],
            'is_senior_citizen' => ['boolean'],
            'is_solo_parent' => ['boolean'],
            'is_indigenous_people' => ['boolean'],
            'is_4ps_beneficiary' => ['boolean'],
            'is_active_ofw' => ['boolean'],
            'ofw_country' => ['nullable', 'string', 'max:255'],
            'ofw_nature_of_work' => ['nullable', 'string', 'max:255'],
            'ofw_year_deployed' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'is_returned_ofw' => ['boolean'],
            'ofw_year_returned' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'ofw_nature_of_return' => ['nullable', Rule::in(['Permanent', 'Temporary', 'Vacation'])],
            'is_local_migrant' => ['boolean'],
            'local_migrant_location' => ['nullable', 'string', 'max:255'],
        ]);

        $member = $resident->householdMembers()->create($validated);

        // Log the activity
        $resident->logActivity(
            'create',
            "{$resident->full_name} added household member: {$member->full_name}",
            [
                'entity_type' => 'HouseholdMember',
                'entity_id' => $member->id,
                'severity' => 'info',
            ]
        );

        return redirect()
            ->route('citizen.profile')
            ->with('success', 'Household member added successfully!');
    }

    /**
     * Delete household member
     */
    public function deleteMember(HouseholdMember $member)
    {
        $resident = Auth::user();
        
        // Ensure the member belongs to the authenticated resident
        if ($member->resident_id !== $resident->id) {
            abort(403);
        }

        $memberName = $member->full_name;
        $member->delete();

        // Log the activity
        $resident->logActivity(
            'delete',
            "{$resident->full_name} removed household member: {$memberName}",
            [
                'entity_type' => 'HouseholdMember',
                'entity_id' => $member->id,
                'severity' => 'warning',
            ]
        );

        return redirect()
            ->route('citizen.profile')
            ->with('success', 'Household member removed successfully!');
    }
}
