<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\HouseholdHead;
use App\Models\HouseholdMember;
use App\Models\Resident;
use App\Services\HouseholdLinkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * ResidentProfileController
 * 
 * Implements the "Triple-Key" Profile Architecture:
 * - HN (Geographic Key): Physical Address → Household Number
 * - HHN (Administrative Key): Family Role → Household Head Number
 * - HHM (Identity Key): Surname + Individual → Member Identification
 * 
 * The registration flow follows a smart "Auto-Recognition" workflow:
 * Step 1: Location (Home Address) → Assigns HN
 * Step 2: Role (Head or Member) → Creates or Finds HHN
 * Step 3: Identity (Last Name) → Auto-matches to existing HHN via surname
 * Step 4: Details (Personal data) → Stored at HHM level
 */
class ResidentProfileController extends Controller
{
    protected HouseholdLinkingService $linkingService;

    public function __construct(HouseholdLinkingService $linkingService)
    {
        $this->linkingService = $linkingService;
    }

    /**
     * Display the profile setup wizard
     * Shows the current step based on session state
     */
    public function showSetup(Request $request)
    {
        $resident = Auth::user();
        $step = $request->get('step', $this->determineCurrentStep($resident));

        // If already fully set up, redirect
        if ($resident->household_id && $resident->household_head_id && $resident->is_onboarding_complete) {
            return redirect()->route('dashboard')
                ->with('toast_info', 'Your profile is already complete.');
        }

        switch ($step) {
            case 1:
                return $this->showLocationStep($resident);
            case 2:
                return $this->showRoleStep($resident);
            case 3:
                return $this->showIdentityStep($resident);
            case 4:
                return $this->showDetailsStep($resident);
            default:
                return $this->showLocationStep($resident);
        }
    }

    /**
     * Determine the current step based on resident's profile state
     * @param \App\Models\Resident $resident
     */
    protected function determineCurrentStep($resident): int
    {
        // Step 1: Location (address not set)
        if (!$resident->household_id && $resident->purok === 'Pending Update') {
            return 1;
        }

        // Step 2: Role (household not determined)
        if (!$resident->household_head_id) {
            return 2;
        }

        // Step 4: Details (onboarding not complete)
        if (!$resident->is_onboarding_complete) {
            return 4;
        }

        return 1; // Default to start
    }

    // ===========================================
    // STEP 1: LOCATION (Geographic Key - HN)
    // ===========================================

    /**
     * Show the location/address step
     * This generates or assigns the HN (Household Number)
     * @param \App\Models\Resident $resident
     */
    protected function showLocationStep($resident)
    {
        $barangays = array_keys(config('barangays.list', []));
        
        return view('profile.setup.step1-location', [
            'resident' => $resident,
            'barangays' => $barangays,
            'currentStep' => 1,
            'totalSteps' => 4,
        ]);
    }

    /**
     * Process location step
     * System Action: Finds or creates HN based on address
     */
    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'house_number' => 'nullable|string|max:50',
            'street' => 'nullable|string|max:255',
            'purok' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
        ]);

        $resident = Auth::user();

        // Find or create household for this address
        $household = $this->linkingService->findOrCreateHousehold([
            'house_number' => $validated['house_number'],
            'street' => $validated['street'],
            'purok' => $validated['purok'],
            'barangay' => $validated['barangay'],
            'municipality' => $validated['municipality'] ?? 'Buguey',
            'province' => $validated['province'] ?? 'Cagayan',
        ]);

        // Update resident's address and link to household (HN)
        $resident->update([
            'household_id' => $household->id,
            'purok' => $validated['purok'],
            'barangay' => $validated['barangay'],
            'municipality' => $validated['municipality'] ?? 'Buguey',
            'province' => $validated['province'] ?? 'Cagayan',
        ]);

        // Store in session for workflow continuity
        session(['profile_setup.household_id' => $household->id]);

        return redirect()->route('profile.setup', ['step' => 2])
            ->with('toast_success', 'Location saved. Now let\'s determine your household role.');
    }

    // ===========================================
    // STEP 2: ROLE (Administrative Key - HHN)
    // ===========================================

    /**
     * Show the role selection step
     * Checks for existing household heads with matching surname
     * @param \App\Models\Resident $resident
     */
    protected function showRoleStep($resident)
    {
        $household = $resident->household;
        
        if (!$household) {
            return redirect()->route('profile.setup', ['step' => 1])
                ->with('toast_error', 'Please complete the location step first.');
        }

        // Check for existing families at this address with same surname
        $matchingHeads = $this->linkingService->findMatchingHeadsAtHousehold(
            $household->id,
            $resident->last_name
        );

        // Get all families at this address (for reference)
        $allFamilies = $household->householdHeads()
            ->with('resident')
            ->active()
            ->get();

        return view('profile.setup.step2-role', [
            'resident' => $resident,
            'household' => $household,
            'matchingHeads' => $matchingHeads,
            'allFamilies' => $allFamilies,
            'hasMatches' => $matchingHeads->isNotEmpty(),
            'multipleMatches' => $matchingHeads->count() > 1,
            'currentStep' => 2,
            'totalSteps' => 4,
        ]);
    }

    /**
     * Process role selection
     * 
     * Branching Logic:
     * - If Head: Creates new HHN linked to HN
     * - If Member (No Match): Creates new HHN as de-facto head
     * - If Member (One Match): Links to that HHN
     * - If Member (Multiple Matches): User selects HHN
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'is_head' => 'required|boolean',
            'selected_head_id' => 'nullable|exists:household_heads,id',
            'confirm_new_family' => 'nullable|boolean',
        ]);

        $resident = Auth::user();
        $household = $resident->household;

        if (!$household) {
            return redirect()->route('profile.setup', ['step' => 1])
                ->with('toast_error', 'Please complete the location step first.');
        }

        DB::transaction(function () use ($validated, $resident, $household) {
            if ($validated['is_head']) {
                // CASE A: User is declaring as Household Head
                // Create new HHN for this resident
                $this->linkingService->createHouseholdHead($resident, $household);
            } elseif ($validated['selected_head_id']) {
                // CASE B/C: User selected an existing household head
                $householdHead = HouseholdHead::findOrFail($validated['selected_head_id']);
                
                // Verify the head is at the same household
                if ($householdHead->household_id !== $household->id) {
                    throw new \Exception('Invalid household head selection.');
                }
                
                $this->linkingService->linkResidentToHead($resident, $householdHead);
            } else {
                // No head selected and not declaring as head
                // This means no matching family found - must create as new head
                $this->linkingService->createHouseholdHead($resident, $household);
            }
        });

        return redirect()->route('profile.setup', ['step' => 4])
            ->with('toast_success', 'Household role set. Now complete your personal details.');
    }

    // ===========================================
    // STEP 3: IDENTITY (Identity Key - HHM)
    // This step is integrated into Step 2 via surname auto-recognition
    // The "Relationship to Head" is auto-filled based on surname match
    // ===========================================

    /**
     * Show identity confirmation step (optional - for complex cases)
     * @param \App\Models\Resident $resident
     */
    protected function showIdentityStep($resident)
    {
        // This step is typically handled automatically
        // Show only if explicit confirmation needed
        return view('profile.setup.step3-identity', [
            'resident' => $resident,
            'currentStep' => 3,
            'totalSteps' => 4,
        ]);
    }

    /**
     * Store relationship to head
     */
    public function storeIdentity(Request $request)
    {
        $validated = $request->validate([
            'relationship' => 'required|string|max:100',
        ]);

        $resident = Auth::user();
        
        $resident->update([
            'household_relationship' => $validated['relationship'],
        ]);

        return redirect()->route('profile.setup', ['step' => 4])
            ->with('toast_success', 'Relationship set. Now complete your profile details.');
    }

    // ===========================================
    // STEP 4: DETAILS (Personal Data at HHM Level)
    // ===========================================

    /**
     * Show the personal details step (socio-economic profiling)
     * @param \App\Models\Resident $resident
     */
    protected function showDetailsStep($resident)
    {
        if (!$resident->household_head_id) {
            return redirect()->route('profile.setup', ['step' => 2])
                ->with('toast_error', 'Please complete the household role step first.');
        }

        return view('profile.setup.step4-details', [
            'resident' => $resident,
            'household' => $resident->household,
            'householdHead' => $resident->householdHeadRelation,
            'currentStep' => 4,
            'totalSteps' => 4,
        ]);
    }

    /**
     * Store personal details and complete onboarding
     */
    public function storeDetails(Request $request)
    {
        $validated = $request->validate([
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|in:Single,Married,Widowed,Legally Separated',
            'occupation' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'vulnerable_sector' => 'nullable|string|max:255',
            
            // Socio-economic data
            'residential_type' => 'nullable|string|max:255',
            'house_materials' => 'nullable|string|max:255',
            'water_source' => 'nullable|string|max:255',
            'flood_prone' => 'nullable|boolean',
            'sanitary_toilet' => 'nullable|boolean',
        ]);

        $resident = Auth::user();

        $resident->update([
            'place_of_birth' => $validated['place_of_birth'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'occupation' => $validated['occupation'],
            'contact_number' => $validated['contact_number'],
            'vulnerable_sector' => $validated['vulnerable_sector'],
            'residential_type' => $validated['residential_type'],
            'house_materials' => $validated['house_materials'],
            'water_source' => $validated['water_source'],
            'flood_prone' => $request->boolean('flood_prone'),
            'sanitary_toilet' => $request->boolean('sanitary_toilet'),
            'is_onboarding_complete' => true,
            'onboarding_completed_at' => now(),
        ]);

        // Log completion
        $resident->logActivity(
            'profile_complete',
            'Resident completed profile setup with Triple-Key architecture',
            [
                'severity' => 'info',
                'household_id' => $resident->household_id,
                'household_head_id' => $resident->household_head_id,
            ]
        );

        // Calculate profile completeness
        $this->updateProfileCompleteness($resident);

        return redirect()->route('dashboard')
            ->with('toast_success', 'Profile setup complete! Welcome to RESIDENTE.');
    }

    /**
     * Update profile completeness score
     * @param \App\Models\Resident $resident
     */
    protected function updateProfileCompleteness($resident): void
    {
        // Could implement a profile_completeness field if needed
        // For now, the is_onboarding_complete flag handles this
    }

    // ===========================================
    // AJAX ENDPOINTS FOR DYNAMIC FORM UPDATES
    // ===========================================

    /**
     * Search for matching household heads by surname at a specific address
     * Called via AJAX when user enters their surname
     */
    public function searchMatchingHeads(Request $request)
    {
        $request->validate([
            'surname' => 'required|string|max:255',
            'household_id' => 'required|exists:households,id',
        ]);

        $matches = $this->linkingService->findMatchingHeadsAtHousehold(
            $request->household_id,
            $request->surname
        );

        return response()->json([
            'success' => true,
            'has_matches' => $matches->isNotEmpty(),
            'match_count' => $matches->count(),
            'matches' => $matches->map(fn($head) => [
                'id' => $head->id,
                'household_head_number' => $head->household_head_number,
                'surname' => $head->surname,
                'head_name' => $head->resident?->full_name ?? 'Unknown',
                'family_size' => $head->family_size,
            ]),
            'prompt' => $this->generatePromptMessage($matches),
        ]);
    }

    /**
     * Generate appropriate prompt message based on matches
     */
    protected function generatePromptMessage($matches): string
    {
        if ($matches->isEmpty()) {
            return 'No existing household found with your surname at this address. You will be registered as a new Household Head.';
        }

        if ($matches->count() === 1) {
            $head = $matches->first();
            return "We found an existing household headed by {$head->resident?->full_name}. Are you a member of this household?";
        }

        return 'We found multiple households with your surname at this address. Please select your Household Head from the list below.';
    }

    /**
     * Check address and return HN info
     * Called via AJAX to show what HN will be assigned
     */
    public function checkAddress(Request $request)
    {
        $request->validate([
            'purok' => 'required|string',
            'barangay' => 'required|string',
            'house_number' => 'nullable|string',
            'street' => 'nullable|string',
        ]);

        // Check if household exists
        $existing = Household::where('barangay', $request->barangay)
            ->where('purok', $request->purok)
            ->when($request->house_number, fn($q) => $q->where('house_number', $request->house_number))
            ->when($request->street, fn($q) => $q->where('street', $request->street))
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'exists' => true,
                'household_number' => $existing->household_number,
                'family_count' => $existing->householdHeads()->count(),
                'full_address' => $existing->full_address,
                'message' => "This address is registered as {$existing->household_number} with {$existing->householdHeads()->count()} family/families.",
            ]);
        }

        $nextHn = Household::generateHouseholdNumber();
        return response()->json([
            'success' => true,
            'exists' => false,
            'household_number' => $nextHn,
            'message' => "This is a new address. It will be assigned {$nextHn}.",
        ]);
    }

    /**
     * Get full Triple-Key identifier for current user
     */
    public function getTripleKey(Request $request)
    {
        $resident = Auth::user();
        $resident->load(['household', 'householdHeadRelation']);

        return response()->json([
            'success' => true,
            'triple_key' => [
                'hn' => $resident->household?->household_number ?? 'Not Assigned',
                'hhn' => $resident->householdHeadRelation?->household_head_number ?? 'Not Assigned',
                'hhm' => $resident->household_member_number ?? 'HHM-' . str_pad($resident->id, 3, '0', STR_PAD_LEFT),
            ],
            'full_identifier' => $this->buildFullIdentifier($resident),
            'address' => $resident->household?->full_address ?? 'Not Set',
            'head_name' => $resident->householdHeadRelation?->resident?->full_name ?? 'Self',
        ]);
    }

    /**
     * Build full identifier string
     * @param \App\Models\Resident $resident
     */
    protected function buildFullIdentifier($resident): string
    {
        $parts = [];
        
        if ($resident->household) {
            $parts[] = $resident->household->household_number;
        }
        
        if ($resident->householdHeadRelation) {
            $parts[] = $resident->householdHeadRelation->household_head_number;
        }
        
        $parts[] = 'HHM-' . str_pad($resident->id, 3, '0', STR_PAD_LEFT);
        
        return implode(' / ', $parts);
    }
}
