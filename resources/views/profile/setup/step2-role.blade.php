@extends('layouts.app')

@section('title', 'Profile Setup - Step 2: Role')

@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-gray-50 antialiased font-sans min-h-screen flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-2xl">
        
        {{-- Header with Progress --}}
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-deep-forest">Profile Setup</h2>
            <p class="text-gray-600 mt-2">Step {{ $currentStep }} of {{ $totalSteps }}: <span class="text-sea-green font-bold">Household Role</span></p>
            
            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-6 relative overflow-hidden">
                <div class="bg-sea-green h-2.5 rounded-full transition-all duration-500" 
                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2 font-bold uppercase tracking-wide">
                <span class="text-sea-green">Location ✓</span>
                <span class="text-sea-green">Role</span>
                <span>Identity</span>
                <span>Details</span>
            </div>
        </div>

        {{-- Current Address Info --}}
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">Your Address:</span> {{ $household->full_address }}
                    </p>
                    <p class="text-xs text-blue-600 mt-1 font-mono">
                        {{ $household->household_number }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Step Content --}}
        <div class="bg-white rounded-xl shadow-xl border-t-4 border-tiger-orange overflow-hidden"
             x-data="roleSelector()">
            
            <div class="p-8">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 bg-tiger-orange rounded-full p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-deep-forest">Administrative Key (HHN)</h3>
                        <p class="text-sm text-gray-600">Define your household role to assign your Family Unit</p>
                    </div>
                </div>

                <form action="{{ route('profile.setup.role') }}" method="POST">
                    @csrf

                    @if(!$hasMatches)
                        {{-- CASE A: No matching surname found --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-6">
                            <div class="flex">
                                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-amber-800">No Matching Household Found</h4>
                                    <p class="text-sm text-amber-700 mt-1">
                                        We did not find any existing household with the surname "<strong>{{ $resident->last_name }}</strong>" 
                                        at this address. You will be registered as a new <strong>Household Head</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_head" value="1">
                        
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        You will be assigned as <strong>Household Head</strong> for a new family unit.
                                    </p>
                                    <p class="text-xs text-green-600 mt-1">
                                        A new HHN (Household Head Number) will be generated for you.
                                    </p>
                                </div>
                            </div>
                        </div>

                    @elseif(!$multipleMatches)
                        {{-- CASE B: Single matching household found --}}
                        @php $match = $matchingHeads->first(); @endphp
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                            <div class="flex">
                                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-blue-800">Existing Household Found</h4>
                                    <p class="text-sm text-blue-700 mt-1">
                                        We found an existing household headed by <strong>{{ $match->resident?->full_name ?? 'Unknown' }}</strong>.
                                        Are you a member of this household?
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            {{-- Option 1: Join existing household --}}
                            <label class="block p-4 rounded-lg border-2 cursor-pointer transition-all"
                                   :class="selectedOption === 'join' ? 'border-sea-green bg-green-50' : 'border-gray-200 hover:border-gray-300'">
                                <div class="flex items-start">
                                    <input type="radio" name="role_choice" value="join" 
                                           x-model="selectedOption"
                                           class="mt-1 h-4 w-4 text-sea-green focus:ring-sea-green border-gray-300">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">
                                            Yes, I am a member of this household
                                        </span>
                                        <span class="block text-sm text-gray-500 mt-1">
                                            <strong>{{ $match->resident?->full_name }}</strong> ({{ $match->household_head_number }}) - 
                                            Family Size: {{ $match->family_size }}
                                        </span>
                                    </div>
                                </div>
                            </label>

                            {{-- Option 2: Create new household --}}
                            <label class="block p-4 rounded-lg border-2 cursor-pointer transition-all"
                                   :class="selectedOption === 'new' ? 'border-sea-green bg-green-50' : 'border-gray-200 hover:border-gray-300'">
                                <div class="flex items-start">
                                    <input type="radio" name="role_choice" value="new"
                                           x-model="selectedOption"
                                           class="mt-1 h-4 w-4 text-sea-green focus:ring-sea-green border-gray-300">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">
                                            No, I am a new Household Head
                                        </span>
                                        <span class="block text-sm text-gray-500 mt-1">
                                            I want to establish my own family unit at this address
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- Hidden inputs based on selection --}}
                        <template x-if="selectedOption === 'join'">
                            <div>
                                <input type="hidden" name="is_head" value="0">
                                <input type="hidden" name="selected_head_id" value="{{ $match->id }}">
                            </div>
                        </template>
                        <template x-if="selectedOption === 'new'">
                            <div>
                                <input type="hidden" name="is_head" value="1">
                            </div>
                        </template>

                    @else
                        {{-- CASE C: Multiple matching households found --}}
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-6">
                            <div class="flex">
                                <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-purple-800">Multiple Households Found</h4>
                                    <p class="text-sm text-purple-700 mt-1">
                                        We found <strong>{{ $matchingHeads->count() }}</strong> households with the surname 
                                        "<strong>{{ $resident->last_name }}</strong>" at this address. Please select your Household Head:
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($matchingHeads as $head)
                                <label class="block p-4 rounded-lg border-2 cursor-pointer transition-all"
                                       :class="selectedHeadId === '{{ $head->id }}' ? 'border-sea-green bg-green-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="flex items-start">
                                        <input type="radio" name="selected_head_id" value="{{ $head->id }}"
                                               x-model="selectedHeadId"
                                               class="mt-1 h-4 w-4 text-sea-green focus:ring-sea-green border-gray-300">
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="block text-sm font-medium text-gray-900">
                                                        {{ $head->resident?->full_name ?? 'Unknown Head' }}
                                                    </span>
                                                    <span class="block text-xs text-gray-500 font-mono mt-1">
                                                        {{ $head->household_head_number }}
                                                    </span>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $head->family_size }} member(s)
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            {{-- Option to create new household --}}
                            <label class="block p-4 rounded-lg border-2 cursor-pointer transition-all border-dashed"
                                   :class="selectedHeadId === 'new' ? 'border-sea-green bg-green-50' : 'border-gray-300 hover:border-gray-400'">
                                <div class="flex items-start">
                                    <input type="radio" name="role_choice" value="new_head"
                                           @click="selectedHeadId = 'new'"
                                           class="mt-1 h-4 w-4 text-sea-green focus:ring-sea-green border-gray-300">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">
                                            None of the above - I am a new Household Head
                                        </span>
                                        <span class="block text-sm text-gray-500 mt-1">
                                            Create a new family unit at this address
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <input type="hidden" name="is_head" :value="selectedHeadId === 'new' ? '1' : '0'">
                    @endif

                    {{-- Info Box --}}
                    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-800">About Household Roles</h4>
                        <ul class="mt-2 text-sm text-gray-600 space-y-1">
                            <li class="flex items-start">
                                <span class="text-sea-green mr-2">•</span>
                                <span><strong>Household Head (HHN)</strong>: The primary representative of a family unit. Required for census and aid distribution.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-sea-green mr-2">•</span>
                                <span><strong>Household Member (HHM)</strong>: Linked to a Head's family unit. Benefits and services are tracked through the family.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-sea-green mr-2">•</span>
                                <span>Multiple families can live at the same address (HN) but each maintains their own family unit (HHN).</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('profile.setup', ['step' => 1]) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                            </svg>
                            Back
                        </a>
                        <button type="submit"
                                @if($multipleMatches || !$hasMatches) :disabled="!canProceed" @endif
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-sea-green hover:bg-deep-forest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-green transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Continue to Step 4
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function roleSelector() {
    return {
        selectedOption: @if(!$hasMatches) 'new' @else null @endif,
        selectedHeadId: null,
        
        get canProceed() {
            @if(!$hasMatches)
                return true;
            @elseif(!$multipleMatches)
                return this.selectedOption !== null;
            @else
                return this.selectedHeadId !== null;
            @endif
        }
    }
}
</script>
@endpush
@endsection
