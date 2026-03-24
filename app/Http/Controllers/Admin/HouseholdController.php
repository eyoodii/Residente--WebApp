<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\HouseholdHead;
use App\Models\HouseholdMember;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * HouseholdController
 * 
 * Implements the drill-down search logic for LGU Secretary:
 * Level 1: Address/HN → Shows all HHNs (families)
 * Level 2: HHN → Shows all HHMs (members)
 * Level 3: Individual → Shows full ancestry
 */
class HouseholdController extends Controller
{
    /**
     * Display household management dashboard
     * Shows summary stats and search interface
     */
    public function index(Request $request)
    {
        $stats = [
            'total_households' => Household::active()->count(),
            'total_families' => HouseholdHead::active()->count(),
            'total_members' => HouseholdMember::count(),
            'total_residents' => Resident::whereNotNull('household_id')->count(),
        ];

        // Get barangay list for filtering
        $barangays = Household::select('barangay')
            ->distinct()
            ->orderBy('barangay')
            ->pluck('barangay');

        // Get 6 most recently updated households for quick access
        $recentHouseholds = Household::active()
            ->with(['householdHeads' => function ($query) {
                $query->latest('updated_at')->withCount('members');
            }])
            ->latest('updated_at')
            ->limit(6)
            ->get()
            ->map(function ($household) {
                // Fetch available residents for each household
                $household->availableResidents = Resident::where('barangay', $household->barangay)
                    ->where('is_household_head', false)
                    ->orderBy('last_name')
                    ->get();
                return $household;
            });

        return view('admin.households.index', compact('stats', 'barangays', 'recentHouseholds'));
    }

    /**
     * Level 1: Search by Address/HN
     * Returns list of all households matching the search
     * Each result shows the HN and number of families
     */
    public function searchByAddress(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
        ]);

        $query = Household::with(['householdHeads.resident'])
            ->withCount('householdHeads')
            ->active();

        if ($request->filled('search')) {
            $query->searchAddress($request->search);
        }

        if ($request->filled('barangay')) {
            $query->inBarangay($request->barangay);
        }

        $households = $query->orderBy('household_number')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $households,
                'html' => view('admin.households.partials.household-list', compact('households'))->render(),
            ]);
        }

        return view('admin.households.search-address', compact('households'));
    }

    /**
     * Level 1 Detail: Show specific household (HN) with all families
     * When Secretary clicks on an HN, show all HHNs at that address
     */
    public function showHousehold(Household $household)
    {
        $household->load([
            'householdHeads.resident',
            'householdHeads.members',
            'residents',
        ]);

        return view('admin.households.show-household', compact('household'));
    }

    /**
     * Level 2: Search by HHN or Head Name
     * Returns list of household heads matching the search
     */
    public function searchByHead(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
        ]);

        $query = HouseholdHead::with(['household', 'resident', 'members'])
            ->withCount('members')
            ->active();

        if ($request->filled('search')) {
            $query->searchName($request->search);
        }

        if ($request->filled('barangay')) {
            $query->whereHas('household', function ($q) use ($request) {
                $q->where('barangay', $request->barangay);
            });
        }

        $householdHeads = $query->orderBy('surname')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $householdHeads,
                'html' => view('admin.households.partials.head-list', compact('householdHeads'))->render(),
            ]);
        }

        return view('admin.households.search-head', compact('householdHeads'));
    }

    /**
     * Level 2 Detail: Show specific household head (HHN) with all members
     * When Secretary selects an HHN, list all members linked to that family
     */
    public function showHouseholdHead(HouseholdHead $householdHead)
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

        // Find potential members by surname (for auto-linking suggestions)
        $potentialMembers = $householdHead->findPotentialMembers();

        // Get available residents for quick-add Co-Head modal.
        // Exclude staff/admin accounts; include rows where is_household_head
        // is NULL (records predating that column).
        $availableResidents = Resident::whereNotIn('role', ['SA', 'admin'])
            ->where(function ($q) {
                $q->where('is_household_head', false)
                  ->orWhereNull('is_household_head');
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.households.show-head', compact(
            'householdHead',
            'linkedResidents',
            'potentialMembers',
            'availableResidents'
        ));
    }

    /**
     * Level 3: Individual Search
     * Search for any member and show their full ancestry
     */
    public function searchIndividual(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
        ]);

        $results = collect();

        if ($request->filled('search')) {
            $search = $request->search;

            // Search registered residents
            $residents = Resident::with(['household', 'householdHeadRelation.household'])
                ->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('national_id', 'like', "%{$search}%");
                })
                ->when($request->filled('barangay'), function ($q) use ($request) {
                    $q->where('barangay', $request->barangay);
                })
                ->limit(50)
                ->get()
                ->map(function ($resident) {
                    return [
                        'type' => 'resident',
                        'data' => $resident,
                        'name' => $resident->full_name,
                        'hn' => $resident->household?->household_number ?? 'N/A',
                        'hhn' => $resident->householdHeadRelation?->household_head_number ?? 'N/A',
                        'address' => $resident->household?->full_address ?? "{$resident->purok}, {$resident->barangay}",
                    ];
                });

            // Search household members (non-registered)
            $members = HouseholdMember::with(['householdHead.household', 'householdHead.resident'])
                ->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->when($request->filled('barangay'), function ($q) use ($request) {
                    $q->whereHas('householdHead.household', function ($hq) use ($request) {
                        $hq->where('barangay', $request->barangay);
                    });
                })
                ->limit(50)
                ->get()
                ->map(function ($member) {
                    return [
                        'type' => 'member',
                        'data' => $member,
                        'name' => $member->full_name,
                        'hn' => $member->householdHead?->household?->household_number ?? 'N/A',
                        'hhn' => $member->householdHead?->household_head_number ?? 'N/A',
                        'address' => $member->householdHead?->household?->full_address ?? 'N/A',
                        'head_name' => $member->householdHead?->head_name ?? 'N/A',
                    ];
                });

            $results = $residents->merge($members)->sortBy('name');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $results,
            ]);
        }

        return view('admin.households.search-individual', compact('results'));
    }

    /**
     * Show individual's full ancestry
     * Shows their Head, HN, and Address
     */
    public function showIndividual(Request $request, $type, $id)
    {
        if ($type === 'resident') {
            $individual = Resident::with([
                'household.householdHeads.resident',
                'householdHeadRelation.members',
                'householdHeadRelation.household',
                'householdMembers',
            ])->findOrFail($id);
        } else {
            $individual = HouseholdMember::with([
                'householdHead.resident',
                'householdHead.household',
                'householdHead.members',
                'linkedResident',
            ])->findOrFail($id);
        }

        return view('admin.households.show-individual', compact('individual', 'type'));
    }

    /**
     * Register a new household (physical address)
     */
    public function createHousehold()
    {
        $barangays = array_keys(config('barangays.list', []));
        
        return view('admin.households.create-household', compact('barangays'));
    }

    /**
     * Store a new household
     */
    public function storeHousehold(Request $request)
    {
        $validated = $request->validate([
            'house_number' => 'nullable|string|max:50',
            'street' => 'nullable|string|max:255',
            'purok' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'housing_type' => 'required|in:Owned,Rented,Rent-Free with Consent,Informal Settler',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['municipality'] = $validated['municipality'] ?? 'Buguey';
        $validated['province'] = $validated['province'] ?? 'Cagayan';

        $household = Household::create($validated);

        return redirect()
            ->route('admin.households.show', $household)
            ->with('success', "Household {$household->household_number} created successfully.");
    }

    /**
     * Register a new household head (family) at an existing household
     */
    public function createHead(Household $household)
    {
        // Get residents who could be heads: not already a head, not staff/admin.
        // No barangay filter — residents may have registered under a different
        // barangay spelling or before completing their profile setup.
        // NULL check covers rows created before the is_household_head column existed.
        $availableResidents = Resident::whereNotIn('role', ['SA', 'admin'])
            ->where(function ($q) {
                $q->where('is_household_head', false)
                  ->orWhereNull('is_household_head');
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.households.create-head', compact('household', 'availableResidents'));
    }

    /**
     * Store a new household head
     */
    public function storeHead(Request $request, Household $household)
    {
        $entryMode = $request->input('entry_mode', 'resident'); // 'resident' or 'manual'

        if ($entryMode === 'manual') {
            $validated = $request->validate([
                'head_first_name'    => 'required|string|max:100',
                'head_last_name'     => 'required|string|max:100',
                'head_extension_name' => ['nullable', 'string', \Illuminate\Validation\Rule::in(['', 'Jr.', 'Sr.', 'II', 'III', 'IV', 'V'])],
                'family_name'        => 'nullable|string|max:255',
                'is_4ps_beneficiary' => 'boolean',
            ]);

            DB::transaction(function () use ($household, $validated) {
                HouseholdHead::create([
                    'household_id'       => $household->id,
                    'resident_id'        => null,
                    'head_first_name'    => strip_tags($validated['head_first_name']),
                    'head_last_name'     => strip_tags($validated['head_last_name']),
                    'head_extension_name' => $validated['head_extension_name'] ?? null,
                    'surname'            => strip_tags($validated['head_last_name']),
                    'family_name'        => $validated['family_name'] ?? null,
                    'is_4ps_beneficiary' => $validated['is_4ps_beneficiary'] ?? false,
                ]);
            });
        } else {
            $validated = $request->validate([
                'resident_id'        => 'required|exists:residents,id',
                'family_name'        => 'nullable|string|max:255',
                'is_4ps_beneficiary' => 'boolean',
            ]);

            $resident = Resident::findOrFail($validated['resident_id']);

            DB::transaction(function () use ($household, $resident, $validated) {
                $householdHead = HouseholdHead::create([
                    'household_id'      => $household->id,
                    'resident_id'       => $resident->id,
                    'surname'           => $resident->last_name,
                    'family_name'       => $validated['family_name'] ?? null,
                    'is_4ps_beneficiary' => $validated['is_4ps_beneficiary'] ?? false,
                ]);

                // Update resident to mark as household head
                $resident->update([
                    'household_id'     => $household->id,
                    'household_head_id' => $householdHead->id,
                    'is_household_head' => true,
                ]);
            });
        }

        // If this was triggered from the co-head modal on a specific head's page, redirect back there
        if ($request->filled('from_head')) {
            $fromHead = HouseholdHead::find((int) $request->input('from_head'));
            if ($fromHead) {
                return redirect()
                    ->route('admin.households.head.show', $fromHead)
                    ->with('success', 'Co-head registered successfully with a new HHN.');
            }
        }

        return redirect()
            ->route('admin.households.show', $household)
            ->with('success', 'Household head registered successfully.');
    }

    /**
     * Add a member to a household head (family)
     */
    public function createMember(HouseholdHead $householdHead)
    {
        return view('admin.households.create-member', compact('householdHead'));
    }

    /**
     * Store a new household member
     */
    public function storeMember(Request $request, HouseholdHead $householdHead)
    {
        // Determine validation rules based on entry mode
        $isResidentMode = !empty($request->input('resident_id'));
        
        $rules = [
            'first_name' => $isResidentMode ? 'nullable' : 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => $isResidentMode ? 'nullable' : 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'relationship' => 'required|in:Co-Head,Spouse,Son,Daughter,Father,Mother,Brother,Sister,Grandchild,Other Relative',
            'civil_status' => 'nullable|in:Single,Married,Widowed,Legally Separated',
            'occupation' => 'nullable|string|max:255',
            'resident_id' => 'nullable|exists:residents,id',
        ];
        
        $validated = $request->validate($rules);

        $member_resident_id = null;
        
        // If resident_id provided, fetch resident and auto-populate name fields
        if (!empty($validated['resident_id'])) {
            $resident = Resident::findOrFail($validated['resident_id']);
            $validated['first_name'] = $resident->first_name;
            $validated['last_name'] = $resident->last_name;
            $validated['middle_name'] = $resident->middle_name ?? $validated['middle_name'];
            $member_resident_id = $resident->id;
        }

        $validated['household_head_id'] = $householdHead->id;
        $validated['resident_id'] = $member_resident_id;
        $validated['link_status'] = 'manual';

        HouseholdMember::create($validated);

        return redirect()
            ->route('admin.households.head.show', $householdHead)
            ->with('success', 'Household member added successfully.');
    }

    /**
     * Auto-link a resident to a household head based on surname
     */
    public function autoLink(Request $request, HouseholdHead $householdHead)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
        ]);

        $resident = Resident::findOrFail($request->resident_id);

        // Verify surname matches
        if (strtolower($resident->last_name) !== strtolower($householdHead->surname)) {
            return back()->with('error', 'Surname does not match. Cannot auto-link.');
        }

        // Link the resident
        $result = $householdHead->autoLinkResident($resident);

        if ($result) {
            return back()->with('success', "{$resident->full_name} has been linked to this family.");
        }

        return back()->with('error', 'Failed to link resident.');
    }

    /**
     * Confirm auto-link suggestion (when resident registers)
     */
    public function confirmAutoLink(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'household_head_id' => 'required|exists:household_heads,id',
            'confirm' => 'required|boolean',
        ]);

        $resident = Resident::findOrFail($request->resident_id);
        $householdHead = HouseholdHead::findOrFail($request->household_head_id);

        if ($request->confirm) {
            $resident->update([
                'household_id' => $householdHead->household_id,
                'household_head_id' => $householdHead->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'You have been linked to the household.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Link declined. You can set up your own household later.',
        ]);
    }

    /**
     * Show edit form for a household head
     */
    public function editHead(HouseholdHead $householdHead)
    {
        $householdHead->load('household', 'resident');

        $availableResidents = Resident::where('barangay', $householdHead->household->barangay)
            ->whereNotIn('role', ['SA', 'admin'])
            ->where(function ($q) use ($householdHead) {
                $q->where('is_household_head', false)
                  ->orWhereNull('is_household_head')
                  ->orWhere('id', $householdHead->resident_id); // always include current head
            })
            ->orderBy('last_name')
            ->get();

        return view('admin.households.edit-head', compact('householdHead', 'availableResidents'));
    }

    /**
     * Update a household head
     */
    public function updateHead(Request $request, HouseholdHead $householdHead)
    {
        $entryMode = $request->input('entry_mode', $householdHead->resident_id ? 'resident' : 'manual');

        if ($entryMode === 'manual') {
            $validated = $request->validate([
                'head_first_name'     => 'required|string|max:100',
                'head_last_name'      => 'required|string|max:100',
                'head_extension_name' => ['nullable', 'string', \Illuminate\Validation\Rule::in(['', 'Jr.', 'Sr.', 'II', 'III', 'IV', 'V'])],
                'family_name'         => 'nullable|string|max:255',
                'is_4ps_beneficiary'  => 'boolean',
            ]);

            // If there was a previously linked resident, unlink them
            if ($householdHead->resident_id) {
                Resident::where('id', $householdHead->resident_id)->update([
                    'household_head_id'  => null,
                    'is_household_head'  => false,
                ]);
            }

            $householdHead->update([
                'resident_id'         => null,
                'head_first_name'     => strip_tags($validated['head_first_name']),
                'head_last_name'      => strip_tags($validated['head_last_name']),
                'head_extension_name' => $validated['head_extension_name'] ?? null,
                'surname'             => strip_tags($validated['head_last_name']),
                'family_name'         => $validated['family_name'] ?? null,
                'is_4ps_beneficiary'  => $validated['is_4ps_beneficiary'] ?? false,
            ]);
        } else {
            $validated = $request->validate([
                'resident_id'        => 'required|exists:residents,id',
                'family_name'        => 'nullable|string|max:255',
                'is_4ps_beneficiary' => 'boolean',
            ]);

            $newResident = Resident::findOrFail($validated['resident_id']);

            DB::transaction(function () use ($householdHead, $newResident, $validated) {
                // Unlink old resident if changed
                if ($householdHead->resident_id && $householdHead->resident_id !== $newResident->id) {
                    Resident::where('id', $householdHead->resident_id)->update([
                        'household_head_id' => null,
                        'is_household_head' => false,
                    ]);
                }

                $householdHead->update([
                    'resident_id'         => $newResident->id,
                    'head_first_name'     => null,
                    'head_last_name'      => null,
                    'head_extension_name' => null,
                    'surname'             => $newResident->last_name,
                    'family_name'         => $validated['family_name'] ?? null,
                    'is_4ps_beneficiary'  => $validated['is_4ps_beneficiary'] ?? false,
                ]);

                $newResident->update([
                    'household_id'      => $householdHead->household_id,
                    'household_head_id' => $householdHead->id,
                    'is_household_head' => true,
                ]);
            });
        }

        return redirect()
            ->route('admin.households.show', $householdHead->household_id)
            ->with('success', 'Family unit updated successfully.');
    }

    /**
     * Show edit form for a household member
     */
    public function editMember(HouseholdHead $householdHead, HouseholdMember $householdMember)
    {
        if ($householdMember->household_head_id !== $householdHead->id) {
            abort(403);
        }
        return view('admin.households.edit-member', compact('householdHead', 'householdMember'));
    }

    /**
     * Update a household member
     */
    public function updateMember(Request $request, HouseholdHead $householdHead, HouseholdMember $householdMember)
    {
        if ($householdMember->household_head_id !== $householdHead->id) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'relationship' => 'required|in:Co-Head,Spouse,Son,Daughter,Father,Mother,Brother,Sister,Grandchild,Other Relative',
            'civil_status' => 'nullable|in:Single,Married,Widowed,Legally Separated',
            'occupation' => 'nullable|string|max:255',
        ]);

        $householdMember->update($validated);

        return redirect()->route('admin.households.head.show', $householdHead)
            ->with('success', 'Family member updated successfully.');
    }

    /**
     * Delete a household member (soft delete)
     */
    public function destroyMember(HouseholdHead $householdHead, HouseholdMember $householdMember)
    {
        if ($householdMember->household_head_id !== $householdHead->id) {
            abort(403);
        }
        $householdMember->delete();
        return redirect()->route('admin.households.head.show', $householdHead)
            ->with('success', 'Family member archived successfully.');
    }

    /**
     * Archive (soft delete) a household head and unlink its resident
     */
    public function destroyHead(HouseholdHead $householdHead)
    {
        $householdId = $householdHead->household_id;

        DB::transaction(function () use ($householdHead) {
            // Unlink the resident so they are no longer flagged as household head
            if ($householdHead->resident_id) {
                Resident::where('id', $householdHead->resident_id)->update([
                    'household_head_id' => null,
                    'is_household_head' => false,
                ]);
            }

            // Soft-delete the head (and cascade will handle members via model events / DB)
            $householdHead->delete();
        });

        return redirect()
            ->route('admin.households.show', $householdId)
            ->with('success', 'Family unit archived successfully.');
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats(Request $request)
    {
        $barangay = $request->get('barangay');

        $query = Household::active();
        if ($barangay) {
            $query->where('barangay', $barangay);
        }

        $householdIds = $query->pluck('id');

        $stats = [
            'households' => $query->count(),
            'families' => HouseholdHead::whereIn('household_id', $householdIds)->active()->count(),
            'beneficiaries' => HouseholdHead::whereIn('household_id', $householdIds)
                ->where('is_4ps_beneficiary', true)
                ->count(),
        ];

        return response()->json($stats);
    }
}
