<?php

namespace App\Services;

use App\Models\Household;
use App\Models\HouseholdHead;
use App\Models\HouseholdMember;
use App\Models\Resident;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * HouseholdLinkingService
 * 
 * Handles the automatic surname-based member linking logic.
 * Implements the "Surname Recognition" workflow and Conflict Resolution Logic.
 * 
 * VALIDATION FLOWCHART:
 * 1. Input Phase: User provides Address + Surname
 * 2. Location Check: System identifies HN for that address
 * 3. Surname Scan: Query all HHNs at that HN with matching surname
 * 4. Branching Logic:
 *    - Case A: No Match → Ask if user is Head, create new HHN
 *    - Case B: One Match → Ask if user is member of that household
 *    - Case C: Multiple Matches → Display list, user selects their Head
 * 
 * THE "STRICT LINK" RULE:
 * Once a member confirms their Head, their profile is permanently tagged
 * with that specific HHN_ID. Even if another family with the same surname
 * moves to the same address, existing members remain correctly grouped.
 */
class HouseholdLinkingService
{
    /**
     * Find potential household matches for a new resident based on address and surname
     * 
     * This implements the cross-reference logic:
     * 1. Check the HN (address) the member is registering under
     * 2. Scan all HHNs associated with that specific HN
     * 3. If a matching surname is found, suggest auto-linking
     *
     * @param array $residentData Array containing 'last_name', 'purok', 'barangay'
     * @return Collection Collection of matching HouseholdHead records
     */
    public function findMatchingHouseholds(array $residentData): Collection
    {
        $surname = $residentData['last_name'] ?? null;
        $purok = $residentData['purok'] ?? null;
        $barangay = $residentData['barangay'] ?? null;

        if (!$surname || !$barangay) {
            return collect();
        }

        // Find households at the same address
        $householdsQuery = Household::where('barangay', $barangay)
            ->where('is_active', true);

        if ($purok) {
            $householdsQuery->where('purok', $purok);
        }

        $householdIds = $householdsQuery->pluck('id');

        // Find household heads with matching surname at those addresses
        $matchingHeads = HouseholdHead::whereIn('household_id', $householdIds)
            ->where('surname', 'LIKE', $surname)
            ->where('is_active', true)
            ->with(['household', 'resident'])
            ->get();

        return $matchingHeads;
    }

    /**
     * Find or create a household for the given address
     *
     * @param array $addressData
     * @return Household
     */
    public function findOrCreateHousehold(array $addressData): Household
    {
        // Normalize the address data
        $purok = $addressData['purok'] ?? null;
        $barangay = $addressData['barangay'] ?? null;
        $houseNumber = $addressData['house_number'] ?? null;
        $street = $addressData['street'] ?? null;

        // Try to find existing household with same address
        $query = Household::where('barangay', $barangay)
            ->where('purok', $purok)
            ->where('is_active', true);

        if ($houseNumber) {
            $query->where('house_number', $houseNumber);
        }
        if ($street) {
            $query->where('street', $street);
        }

        $existing = $query->first();

        if ($existing) {
            return $existing;
        }

        // Create new household
        return Household::create([
            'house_number' => $houseNumber,
            'street' => $street,
            'purok' => $purok,
            'barangay' => $barangay,
            'municipality' => $addressData['municipality'] ?? 'Buguey',
            'province' => $addressData['province'] ?? 'Cagayan',
            'housing_type' => $addressData['housing_type'] ?? 'Owned',
        ]);
    }

    /**
     * Auto-link a resident to a household head based on surname
     * 
     * @param Resident $resident
     * @param HouseholdHead $householdHead
     * @return bool
     */
    public function linkResidentToHead(Resident $resident, HouseholdHead $householdHead): bool
    {
        try {
            DB::transaction(function () use ($resident, $householdHead) {
                $resident->update([
                    'household_id' => $householdHead->household_id,
                    'household_head_id' => $householdHead->id,
                ]);

                // Update family size
                $householdHead->updateFamilySize();
            });

            Log::info('Resident auto-linked to household', [
                'resident_id' => $resident->id,
                'household_head_id' => $householdHead->id,
                'surname' => $resident->last_name,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to link resident to household', [
                'resident_id' => $resident->id,
                'household_head_id' => $householdHead->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Create a new household head from a resident
     * Used when a resident wants to establish their own family unit
     *
     * @param Resident $resident
     * @param Household|null $household
     * @return HouseholdHead
     */
    public function createHouseholdHead(Resident $resident, ?Household $household = null): HouseholdHead
    {
        // If no household provided, find or create one based on resident's address
        if (!$household) {
            $household = $this->findOrCreateHousehold([
                'purok' => $resident->purok,
                'barangay' => $resident->barangay,
                'municipality' => $resident->municipality,
                'province' => $resident->province,
            ]);
        }

        return DB::transaction(function () use ($resident, $household) {
            $householdHead = HouseholdHead::create([
                'household_id' => $household->id,
                'resident_id' => $resident->id,
                'surname' => $resident->last_name,
            ]);

            $resident->update([
                'household_id' => $household->id,
                'household_head_id' => $householdHead->id,
                'is_household_head' => true,
            ]);

            return $householdHead;
        });
    }

    /**
     * Process new resident registration and check for auto-linking
     * Returns potential matches for the registration form to display
     *
     * @param array $registrationData
     * @return array
     */
    public function processNewResidentRegistration(array $registrationData): array
    {
        $matches = $this->findMatchingHouseholds($registrationData);

        if ($matches->isEmpty()) {
            return [
                'has_matches' => false,
                'matches' => [],
                'suggestion' => 'no_existing_household',
            ];
        }

        // If there's exactly one match, suggest auto-linking
        if ($matches->count() === 1) {
            $match = $matches->first();
            return [
                'has_matches' => true,
                'matches' => $matches->map(fn($h) => $this->formatMatchForDisplay($h)),
                'suggestion' => 'single_match',
                'suggested_head' => $this->formatMatchForDisplay($match),
                'prompt' => "We found an existing household under the name \"{$match->surname}\". Is {$match->resident->full_name} your Household Head?",
            ];
        }

        // Multiple matches - user needs to choose
        return [
            'has_matches' => true,
            'matches' => $matches->map(fn($h) => $this->formatMatchForDisplay($h)),
            'suggestion' => 'multiple_matches',
            'prompt' => "We found multiple households with the surname \"{$registrationData['last_name']}\" at your address. Please select your Household Head:",
        ];
    }

    /**
     * Format a HouseholdHead match for display in the UI
     *
     * @param HouseholdHead $head
     * @return array
     */
    protected function formatMatchForDisplay(HouseholdHead $head): array
    {
        return [
            'id' => $head->id,
            'household_head_number' => $head->household_head_number,
            'surname' => $head->surname,
            'head_name' => $head->resident?->full_name ?? 'Unknown',
            'family_size' => $head->family_size,
            'address' => $head->household?->full_address ?? 'Unknown',
            'household_number' => $head->household?->household_number ?? 'N/A',
        ];
    }

    /**
     * Link a HouseholdMember record to a newly registered Resident
     * Used when an existing HHM creates an account
     *
     * @param HouseholdMember $member
     * @param Resident $resident
     * @return bool
     */
    public function linkMemberToResident(HouseholdMember $member, Resident $resident): bool
    {
        try {
            DB::transaction(function () use ($member, $resident) {
                $member->update([
                    'linked_resident_id' => $resident->id,
                    'link_status' => 'confirmed',
                ]);

                $resident->update([
                    'household_id' => $member->householdHead?->household_id,
                    'household_head_id' => $member->household_head_id,
                ]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to link member to resident', [
                'member_id' => $member->id,
                'resident_id' => $resident->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Find potential HouseholdMember records that could match a new resident
     * Based on name similarity and address
     *
     * @param Resident $resident
     * @return Collection
     */
    public function findPotentialMemberMatches(Resident $resident): Collection
    {
        $householdIds = Household::where('barangay', $resident->barangay)
            ->where('purok', $resident->purok)
            ->pluck('id');

        $headIds = HouseholdHead::whereIn('household_id', $householdIds)->pluck('id');

        return HouseholdMember::whereIn('household_head_id', $headIds)
            ->where('first_name', 'LIKE', "%{$resident->first_name}%")
            ->where('last_name', $resident->last_name)
            ->whereNull('linked_resident_id')
            ->with('householdHead.household')
            ->get();
    }

    // ===========================================
    // CONFLICT RESOLUTION LOGIC
    // ===========================================

    /**
     * Find matching household heads at a specific household (HN) by surname
     * This is the core of the Conflict Resolution Logic
     * 
     * Scenario: Multiple families with same surname at same address
     * Example: Ramon Cruz (HHN-A) and Santi Cruz (HHN-B) both at HN-789
     * When Liza Cruz registers, system shows both options
     *
     * @param int $householdId The HN (physical address) ID
     * @param string $surname The surname to search for
     * @return Collection Collection of HouseholdHead records
     */
    public function findMatchingHeadsAtHousehold(int $householdId, string $surname): Collection
    {
        return HouseholdHead::where('household_id', $householdId)
            ->whereRaw('LOWER(surname) = ?', [strtolower($surname)])
            ->where('is_active', true)
            ->with(['household', 'resident'])
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Determine the linking scenario based on surname matches
     * Returns the appropriate case (A, B, or C) with context
     *
     * @param int $householdId
     * @param string $surname
     * @return array
     */
    public function determineLinkingScenario(int $householdId, string $surname): array
    {
        $matches = $this->findMatchingHeadsAtHousehold($householdId, $surname);

        if ($matches->isEmpty()) {
            // Case A: No Match Found
            return [
                'case' => 'A',
                'description' => 'no_match',
                'matches' => collect(),
                'action' => 'create_new_head',
                'prompt' => 'No existing household with your surname found at this address. Would you like to register as the Household Head?',
            ];
        }

        if ($matches->count() === 1) {
            // Case B: Single Match Found
            $match = $matches->first();
            return [
                'case' => 'B',
                'description' => 'single_match',
                'matches' => $matches,
                'suggested_head' => $match,
                'action' => 'confirm_membership',
                'prompt' => "We found an existing household headed by {$match->resident?->full_name}. Are you a member of this household?",
            ];
        }

        // Case C: Multiple Matches Found
        return [
            'case' => 'C',
            'description' => 'multiple_matches',
            'matches' => $matches,
            'action' => 'select_head',
            'prompt' => "We found {$matches->count()} households with the surname \"{$surname}\" at this address. Please select your Household Head:",
        ];
    }

    /**
     * Process resident registration with full conflict resolution
     * This is the main entry point for the auto-recognition workflow
     *
     * @param Resident $resident
     * @param array $options Additional options (is_head, selected_head_id)
     * @return array Result with status and any created/linked records
     */
    public function processResidentWithConflictResolution(Resident $resident, array $options = []): array
    {
        $household = $resident->household;
        
        if (!$household) {
            return [
                'success' => false,
                'error' => 'No household assigned to resident.',
                'step_required' => 'location',
            ];
        }

        // Get scenario
        $scenario = $this->determineLinkingScenario($household->id, $resident->last_name);

        // If user explicitly declared as head
        if (!empty($options['is_head']) && $options['is_head']) {
            $head = $this->createHouseholdHead($resident, $household);
            return [
                'success' => true,
                'action' => 'created_as_head',
                'household_head' => $head,
                'message' => "You have been registered as a Household Head ({$head->household_head_number}).",
            ];
        }

        // If user selected a specific head (Case B or C resolution)
        if (!empty($options['selected_head_id'])) {
            $selectedHead = HouseholdHead::find($options['selected_head_id']);
            
            if (!$selectedHead || $selectedHead->household_id !== $household->id) {
                return [
                    'success' => false,
                    'error' => 'Invalid household head selection.',
                ];
            }

            $this->linkResidentToHead($resident, $selectedHead);
            return [
                'success' => true,
                'action' => 'linked_to_head',
                'household_head' => $selectedHead,
                'message' => "You have been linked to {$selectedHead->resident?->full_name}'s household.",
            ];
        }

        // No explicit selection - return scenario for user to decide
        return [
            'success' => false,
            'requires_decision' => true,
            'scenario' => $scenario,
        ];
    }

    /**
     * Transfer a member from one HHN to another within the same HN
     * Used by LGU Secretary for manual override / correction
     *
     * @param Resident $resident The resident to transfer
     * @param HouseholdHead $newHead The new household head
     * @param string $reason Reason for the transfer (for audit log)
     * @return bool
     */
    public function transferMemberToHead(Resident $resident, HouseholdHead $newHead, string $reason = ''): bool
    {
        try {
            return DB::transaction(function () use ($resident, $newHead, $reason) {
                $oldHeadId = $resident->household_head_id;
                $oldHead = $oldHeadId ? HouseholdHead::find($oldHeadId) : null;

                // Update resident's household head
                $resident->update([
                    'household_head_id' => $newHead->id,
                    'household_id' => $newHead->household_id, // Ensure HN is also updated
                ]);

                // Update family sizes
                if ($oldHead) {
                    $oldHead->updateFamilySize();
                }
                $newHead->updateFamilySize();

                Log::info('Member transferred between household heads', [
                    'resident_id' => $resident->id,
                    'resident_name' => $resident->full_name,
                    'old_head_id' => $oldHeadId,
                    'new_head_id' => $newHead->id,
                    'reason' => $reason,
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Failed to transfer member', [
                'resident_id' => $resident->id,
                'new_head_id' => $newHead->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get the full Triple-Key hierarchy for a resident
     * Returns HN, HHN, and HHM details
     *
     * @param Resident $resident
     * @return array
     */
    public function getTripleKeyHierarchy(Resident $resident): array
    {
        $resident->load(['household', 'householdHeadRelation.resident']);

        return [
            'geographic_key' => [
                'label' => 'HN (Household Number)',
                'value' => $resident->household?->household_number ?? 'Not Assigned',
                'address' => $resident->household?->full_address ?? 'Not Set',
                'families_at_address' => $resident->household?->householdHeads()->count() ?? 0,
            ],
            'administrative_key' => [
                'label' => 'HHN (Household Head Number)',
                'value' => $resident->householdHeadRelation?->household_head_number ?? 'Not Assigned',
                'head_name' => $resident->householdHeadRelation?->resident?->full_name ?? 'Unknown',
                'family_size' => $resident->householdHeadRelation?->family_size ?? 1,
                'is_head' => $resident->is_household_head,
            ],
            'identity_key' => [
                'label' => 'HHM (Household Member)',
                'value' => 'HHM-' . str_pad($resident->id, 3, '0', STR_PAD_LEFT),
                'name' => $resident->full_name,
                'surname' => $resident->last_name,
            ],
            'full_identifier' => $this->buildFullIdentifier($resident),
        ];
    }

    /**
     * Build the full hierarchical identifier string
     *
     * @param Resident $resident
     * @return string
     */
    protected function buildFullIdentifier(Resident $resident): string
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

    /**
     * Verify and validate household linkage for a resident
     * Used by Secretary for data integrity checks
     *
     * @param Resident $resident
     * @return array Validation results
     */
    public function validateResidentLinkage(Resident $resident): array
    {
        $issues = [];
        $isValid = true;

        // Check HN assignment
        if (!$resident->household_id) {
            $issues[] = 'No household (HN) assigned';
            $isValid = false;
        }

        // Check HHN assignment
        if (!$resident->household_head_id) {
            $issues[] = 'No household head (HHN) assigned';
            $isValid = false;
        }

        // Verify HHN belongs to the HN
        if ($resident->household_id && $resident->household_head_id) {
            $head = HouseholdHead::find($resident->household_head_id);
            if ($head && $head->household_id !== $resident->household_id) {
                $issues[] = 'Household head does not belong to assigned household';
                $isValid = false;
            }
        }

        // Check surname consistency (warning, not error)
        if ($resident->householdHeadRelation) {
            $headSurname = strtolower($resident->householdHeadRelation->surname);
            $residentSurname = strtolower($resident->last_name);
            if ($headSurname !== $residentSurname && !$resident->is_household_head) {
                $issues[] = "Surname mismatch: resident is '{$resident->last_name}', household is '{$resident->householdHeadRelation->surname}'";
                // This is allowed (e.g., spouse with different surname) but flagged
            }
        }

        return [
            'is_valid' => $isValid,
            'issues' => $issues,
            'resident_id' => $resident->id,
            'full_name' => $resident->full_name,
        ];
    }

    /**
     * Find all "Ghost Members" - residents with incomplete linkage
     * Used by Secretary for verification dashboard
     *
     * @return Collection
     */
    public function findGhostMembers(): Collection
    {
        return Resident::where(function ($query) {
            $query->whereNull('household_id')
                  ->orWhereNull('household_head_id');
        })
        ->whereNotNull('email_verified_at') // Only verified users
        ->with(['household', 'householdHeadRelation'])
        ->get()
        ->map(function ($resident) {
            return [
                'resident' => $resident,
                'missing_hn' => is_null($resident->household_id),
                'missing_hhn' => is_null($resident->household_head_id),
                'validation' => [
                    'is_valid' => !is_null($resident->household_id) && !is_null($resident->household_head_id),
                    'issues' => collect([
                        is_null($resident->household_id) ? 'No household (HN) assigned' : null,
                        is_null($resident->household_head_id) ? 'No household head (HHN) assigned' : null,
                    ])->filter()->values()->all(),
                    'resident_id' => $resident->id,
                    'full_name' => $resident->full_name,
                ],
            ];
        });
    }
}
