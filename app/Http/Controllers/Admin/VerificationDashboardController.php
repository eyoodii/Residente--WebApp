<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\HouseholdHead;
use App\Models\HouseholdMember;
use App\Models\Resident;
use App\Services\HouseholdLinkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * VerificationDashboardController
 * 
 * Implements the LGU Secretary's Verification Dashboard for:
 * - Cross-verification of residents and their household links
 * - Identifying "Ghost Members" (residents with incomplete linkage)
 * - Manual override capabilities to move members between HHNs
 * - Data integrity validation
 */
class VerificationDashboardController extends Controller
{
    protected HouseholdLinkingService $linkingService;

    public function __construct(HouseholdLinkingService $linkingService)
    {
        $this->linkingService = $linkingService;
    }

    /**
     * Display the main verification dashboard
     * Shows overview stats and quick actions
     */
    public function index()
    {
        // Get ghost members (incomplete linkage)
        $ghostMembers = $this->linkingService->findGhostMembers();

        // Get recent linkage activities
        $recentLinkages = Resident::whereNotNull('household_head_id')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->with(['household', 'householdHeadRelation.resident'])
            ->get();

        // Stats
        $stats = [
            'total_households' => Household::active()->count(),
            'total_families' => HouseholdHead::active()->count(),
            'linked_residents' => Resident::whereNotNull('household_head_id')->count(),
            'unlinked_residents' => Resident::whereNull('household_head_id')
                ->whereNotNull('email_verified_at')
                ->count(),
            'ghost_members' => $ghostMembers->count(),
        ];

        // Addresses with multiple families (potential for confusion)
        $multipleFamily = Household::withCount('householdHeads')
            ->having('household_heads_count', '>', 1)
            ->with(['householdHeads.resident'])
            ->orderBy('household_heads_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.households.verification.dashboard', compact(
            'stats',
            'ghostMembers',
            'recentLinkages',
            'multipleFamily'
        ));
    }

    /**
     * Show detailed view of a household with all families
     * Cross-Verification Table for multiple families with same surname
     */
    public function verifyHousehold(Household $household)
    {
        $household->load([
            'householdHeads' => function ($query) {
                $query->with(['resident', 'members.linkedResident'])->active();
            },
            'residents',
        ]);

        // Group families by surname for cross-verification
        $familiesBySurname = $household->householdHeads->groupBy('surname');

        // Get all residents at this address, grouped by their HHN
        $residentsByFamily = $household->residents->groupBy('household_head_id');

        // Validate all linkages
        $validationResults = collect();
        foreach ($household->residents as $resident) {
            $validationResults->push($this->linkingService->validateResidentLinkage($resident));
        }

        return view('admin.households.verification.household', compact(
            'household',
            'familiesBySurname',
            'residentsByFamily',
            'validationResults'
        ));
    }

    /**
     * Show detailed view of a household head with all members
     * Allows verification of individual members
     */
    public function verifyFamily(HouseholdHead $householdHead)
    {
        $householdHead->load([
            'household',
            'resident',
            'members.linkedResident',
        ]);

        // Get registered residents linked to this family
        $linkedResidents = Resident::where('household_head_id', $householdHead->id)
            ->where('id', '!=', $householdHead->resident_id)
            ->get();

        // Find other families at same address with same surname (potential conflicts)
        $sameSurnameFamilies = HouseholdHead::where('household_id', $householdHead->household_id)
            ->where('surname', $householdHead->surname)
            ->where('id', '!=', $householdHead->id)
            ->with('resident')
            ->get();

        // Validate all members
        $memberValidation = collect();
        foreach ($linkedResidents as $resident) {
            $memberValidation->push($this->linkingService->validateResidentLinkage($resident));
        }

        return view('admin.households.verification.family', compact(
            'householdHead',
            'linkedResidents',
            'sameSurnameFamilies',
            'memberValidation'
        ));
    }

    /**
     * Manual override: Transfer a member from one HHN to another
     * Used when a resident was mistakenly linked to wrong family
     */
    public function transferMember(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'new_head_id' => 'required|exists:household_heads,id',
            'reason' => 'required|string|max:500',
        ]);

        $resident = Resident::findOrFail($validated['resident_id']);
        $newHead = HouseholdHead::findOrFail($validated['new_head_id']);

        // Perform the transfer
        $success = $this->linkingService->transferMemberToHead(
            $resident,
            $newHead,
            $validated['reason']
        );

        if ($success) {
            // Log the administrative action using the resident's own log method
            $resident->logActivity(
                'member_transfer',
                "Member transferred to household {$newHead->household_head_number}",
                [
                    'severity' => 'info',
                    'new_head_id' => $newHead->id,
                    'reason' => $validated['reason'],
                ]
            );

            return redirect()->back()
                ->with('success', "Successfully transferred {$resident->full_name} to {$newHead->resident?->full_name}'s household.");
        }

        return redirect()->back()
            ->with('error', 'Failed to transfer member. Please try again.');
    }

    /**
     * Fix a ghost member by assigning them to a household
     */
    public function fixGhostMember(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'household_id' => 'required|exists:households,id',
            'household_head_id' => 'required|exists:household_heads,id',
        ]);

        $resident = Resident::findOrFail($validated['resident_id']);
        $householdHead = HouseholdHead::findOrFail($validated['household_head_id']);

        // Verify the head belongs to the household
        if ($householdHead->household_id != $validated['household_id']) {
            return redirect()->back()
                ->with('error', 'Invalid household/head combination.');
        }

        try {
            DB::transaction(function () use ($resident, $householdHead) {
                $resident->update([
                    'household_id' => $householdHead->household_id,
                    'household_head_id' => $householdHead->id,
                ]);

                $householdHead->updateFamilySize();
            });

            return redirect()->back()
                ->with('success', "Successfully linked {$resident->full_name} to household.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to fix ghost member: ' . $e->getMessage());
        }
    }

    /**
     * Get list of ghost members (AJAX)
     */
    public function getGhostMembers(Request $request)
    {
        $ghostMembers = $this->linkingService->findGhostMembers();

        return response()->json([
            'success' => true,
            'count' => $ghostMembers->count(),
            'members' => $ghostMembers->map(function ($item) {
                return [
                    'id' => $item['resident']->id,
                    'name' => $item['resident']->full_name,
                    'email' => $item['resident']->email,
                    'barangay' => $item['resident']->barangay,
                    'missing_hn' => $item['missing_hn'],
                    'missing_hhn' => $item['missing_hhn'],
                ];
            }),
        ]);
    }

    /**
     * Get Triple-Key details for a resident (AJAX)
     */
    public function getResidentTripleKey(Resident $resident)
    {
        $hierarchy = $this->linkingService->getTripleKeyHierarchy($resident);

        return response()->json([
            'success' => true,
            'resident' => [
                'id' => $resident->id,
                'name' => $resident->full_name,
            ],
            'triple_key' => $hierarchy,
        ]);
    }

    /**
     * Search residents with flexible criteria
     */
    public function searchResidents(Request $request)
    {
        $query = $request->get('query', '');
        $filter = $request->get('filter', 'all'); // all, linked, unlinked, ghost

        $residents = Resident::query()
            ->when($query, function ($q) use ($query) {
                $q->where(function ($inner) use ($query) {
                    $inner->where('first_name', 'like', "%{$query}%")
                          ->orWhere('last_name', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%")
                          ->orWhere('national_id', 'like', "%{$query}%");
                });
            })
            ->when($filter === 'linked', function ($q) {
                $q->whereNotNull('household_head_id');
            })
            ->when($filter === 'unlinked', function ($q) {
                $q->whereNull('household_head_id');
            })
            ->when($filter === 'ghost', function ($q) {
                $q->where(function ($inner) {
                    $inner->whereNull('household_id')
                          ->orWhereNull('household_head_id');
                })->whereNotNull('email_verified_at');
            })
            ->with(['household', 'householdHeadRelation.resident'])
            ->limit(50)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $residents->count(),
                'residents' => $residents->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'name' => $r->full_name,
                        'email' => $r->email,
                        'hn' => $r->household?->household_number ?? 'N/A',
                        'hhn' => $r->householdHeadRelation?->household_head_number ?? 'N/A',
                        'head_name' => $r->householdHeadRelation?->resident?->full_name ?? 'N/A',
                        'is_head' => $r->is_household_head,
                    ];
                }),
            ]);
        }

        return view('admin.households.verification.search', compact('residents', 'query', 'filter'));
    }
}
