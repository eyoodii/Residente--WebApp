<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\Family;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Super Admin Data Collection Controller
 * Handles the HN (House) → HHN (Family) → HHM (Member) hierarchical data collection
 * Implements automatic surname-based family linking with validation flags
 */
class DataCollectionController extends Controller
{
    /**
     * Display the master data collection dashboard
     * Executes auto-linking logic and displays hierarchical data
     */
    public function index(Request $request)
    {
        // Run the background auto-linking validation logic
        $this->runAutoLinkingLogic();

        // Get search parameters
        $search = $request->get('search');
        $barangay = $request->get('barangay');

        // Fetch hierarchical data with eager loading for performance
        // HN (Household) → HHN (Families) → HHM (Members)
        $households = Household::with(['families.members', 'families.householdHead'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('household_number', 'like', "%{$search}%")
                      ->orWhere('full_address', 'like', "%{$search}%")
                      ->orWhere('barangay', 'like', "%{$search}%")
                      ->orWhereHas('families', function ($fq) use ($search) {
                          $fq->where('hhn_number', 'like', "%{$search}%")
                             ->orWhere('head_surname', 'like', "%{$search}%");
                      });
                });
            })
            ->when($barangay, function ($query, $barangay) {
                $query->where('barangay', $barangay);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get validation statistics for the admin
        $stats = [
            'total_households' => Household::count(),
            'total_families' => Family::count(),
            'total_residents' => Resident::count(),
            'auto_linked_pending' => Resident::where('is_auto_linked', true)->count(),
        ];

        // Get list of all 30 barangays in Buguey
        $barangays = $this->getBugueyBarangays();

        return view('admin.data-collection', compact('households', 'stats', 'barangays', 'search', 'barangay'));
    }

    /**
     * Auto-linking logic: Match residents to families by surname and address
     * This runs in the background when the dashboard loads
     */
    protected function runAutoLinkingLogic()
    {
        // Find all residents that are not yet assigned to a family
        $unassignedResidents = Resident::whereNull('family_id')
            ->whereNotNull('household_id')
            ->get();

        foreach ($unassignedResidents as $member) {
            // Skip if no household
            if (!$member->household) {
                continue;
            }

            // Find a family at the EXACT same physical address with the EXACT same surname
            $matchingFamily = $member->household->families()
                ->where('head_surname', $member->last_name)
                ->first();

            if ($matchingFamily) {
                // Auto-Link and trigger the validation flag for admin review
                $member->update([
                    'family_id' => $matchingFamily->id,
                    'is_auto_linked' => true, // Flag for admin validation
                ]);
            }
        }
    }

    /**
     * Approve an auto-linked member (confirm the family assignment)
     */
    public function approveAutoLink(Request $request, Resident $resident)
    {
        if (!$resident->is_auto_linked) {
            return back()->with('error', 'This resident is not auto-linked.');
        }

        $resident->update(['is_auto_linked' => false]);

        return back()->with('success', "Member {$resident->first_name} {$resident->last_name} has been verified and confirmed in the family.");
    }

    /**
     * Reject an auto-link and unassign from family
     */
    public function rejectAutoLink(Request $request, Resident $resident)
    {
        if (!$resident->is_auto_linked) {
            return back()->with('error', 'This resident is not auto-linked.');
        }

        $resident->update([
            'family_id' => null,
            'is_auto_linked' => false,
        ]);

        return back()->with('success', "Member {$resident->first_name} {$resident->last_name} has been removed from the family.");
    }

    /**
     * Get the list of all 30 barangays in Buguey, Cagayan
     */
    protected function getBugueyBarangays(): array
    {
        return array_keys(config('barangays.list', []));
    }
}
