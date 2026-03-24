<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the onboarding wizard.
     */
    public function showOnboarding(Request $request): View|RedirectResponse
    {
        $resident = $request->user();
        
        // If already completed, redirect to dashboard
        if ($resident->is_onboarding_complete) {
            return redirect()->route('dashboard')
                ->with('toast_info', 'You have already completed your profile.');
        }
        
        return view('profile.onboarding', [
            'resident' => $resident,
        ]);
    }

    /**
     * Store the onboarding data and activate the account.
     */
    public function storeOnboarding(Request $request): RedirectResponse
    {
        $resident = $request->user();
        
        // Validate the incoming data
        $validated = $request->validate([
            'residential_type' => 'nullable|string|max:255',
            'house_materials' => 'nullable|string|max:255',
            'water_source' => 'nullable|string|max:255',
            'flood_prone' => 'nullable|boolean',
            'sanitary_toilet' => 'nullable|boolean',
            'crops' => 'nullable|array',
            'crops.*' => 'string',
            'aquaculture' => 'nullable|array',
            'aquaculture.*' => 'string',
            'livestock' => 'nullable|array',
            'livestock.*' => 'string',
            'fisheries' => 'nullable|array',
            'fisheries.*' => 'string',
        ]);
        
        // Update resident with socio-economic data
        $resident->update([
            'residential_type' => $request->residential_type,
            'house_materials' => $request->house_materials,
            'water_source' => $request->water_source,
            'flood_prone' => $request->has('flood_prone'),
            'sanitary_toilet' => $request->has('sanitary_toilet'),
            
            // These will automatically be converted to JSON strings in the database
            'crops' => $request->crops ?? [],
            'aquaculture' => $request->aquaculture ?? [],
            'livestock' => $request->livestock ?? [],
            'fisheries' => $request->fisheries ?? [],
            
            // Finalize Activation
            'is_onboarding_complete' => true,
            'onboarding_completed_at' => now(),
        ]);
        
        // Log the completion
        Log::info('Resident completed onboarding', [
            'resident_id' => $resident->id,
            'national_id' => $resident->national_id,
        ]);
        
        return redirect()->route('dashboard')
            ->with('toast_success', 'Profile complete. Welcome to RESIDENTE.');
    }
}
