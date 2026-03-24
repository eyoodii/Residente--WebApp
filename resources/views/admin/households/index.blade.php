@extends('layouts.admin')

@section('title', 'Household Management')

@section('content')
<div class="p-8 space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Household Management</h1>
            <p class="text-gray-600 mt-2">LGU Secretary Dashboard - Manage households, families, and members</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Households (HN)</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_households']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Families (HHN)</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_families']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Household Members (HHM)</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_members']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Registered Residents</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_residents']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Options -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Level 1: Search by Address/HN -->
            <a href="{{ route('admin.households.search.address') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow border-l-4 border-blue-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold ml-4">Search by Address</h2>
                </div>
                <p class="text-gray-600 text-sm">Search for households by physical address or Household Number (HN). View all families at each location.</p>
                <div class="mt-4 text-blue-600 font-medium">Level 1: HN Search →</div>
            </a>

            <!-- Level 2: Search by HHN/Head -->
            <a href="{{ route('admin.households.search.head') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow border-l-4 border-green-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold ml-4">Search by Household Head</h2>
                </div>
                <p class="text-gray-600 text-sm">Search for family units by surname or Household Head Number (HHN). View all members of each family.</p>
                <div class="mt-4 text-green-600 font-medium">Level 2: HHN Search →</div>
            </a>

            <!-- Level 3: Individual Search -->
            <a href="{{ route('admin.households.search.individual') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow border-l-4 border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold ml-4">Search Individual</h2>
                </div>
                <p class="text-gray-600 text-sm">Search for any resident or member by name. View their full ancestry (Head, HN, Address).</p>
                <div class="mt-4 text-purple-600 font-medium">Level 3: Individual Search →</div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.households.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Register New Household
                </a>
                <a href="{{ route('admin.residents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Manage Residents
                </a>
            </div>
        </div>

        <!-- Recent Households -->
        @if($recentHouseholds->count() > 0)
        <div x-data="quickAddHHN()">
            <h2 class="text-xl font-semibold mb-4">Recent Households</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentHouseholds as $household)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-sea-green/10 text-sea-green border border-sea-green/20 mb-2">
                                {{ $household->household_number }}
                            </div>
                            <h3 class="font-semibold text-deep-forest text-sm">{{ $household->full_address }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $household->barangay }}</p>
                        </div>
                        {{-- Quick Add Button --}}
                        <button type="button"
                                @click="openAddHeadModal({{ $household->id }}, '{{ addslashes($household->full_address) }}', '{{ $household->barangay }}', {{ json_encode($household->availableResidents->map(fn($r) => ['id' => $r->id, 'full_name' => $r->full_name, 'email' => $r->email])->values()) }})"
                                class="p-2 hover:bg-sea-green/10 rounded-lg transition-colors text-sea-green"
                                title="Add new family">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    {{-- Family Count Badge --}}
                    <div class="mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            {{ $household->householdHeads->count() }} {{ Str::plural('family', $household->householdHeads->count()) }}
                        </span>
                    </div>

                    {{-- Recent Families List (top 3) --}}
                    @if($household->householdHeads->count() > 0)
                        <div class="space-y-2 mb-3 bg-gray-50 rounded p-2">
                            @foreach($household->householdHeads->take(3) as $head)
                            <a href="{{ route('admin.households.head.show', $head) }}"
                               class="text-xs font-medium text-sea-green hover:text-deep-forest transition-colors flex items-center gap-2">
                                <span>{{ $head->household_head_number }}</span>
                                <span class="text-gray-400">·</span>
                                <span class="truncate">{{ $head->head_name }}</span>
                                <span class="text-gray-400">({{ $head->members_count }})</span>
                            </a>
                            @endforeach
                            @if($household->householdHeads->count() > 3)
                            <div class="text-xs text-gray-500 pt-1 border-t border-gray-200">
                                +{{ $household->householdHeads->count() - 3 }} more families
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-xs text-gray-400 mb-3 italic">No families registered yet</div>
                    @endif

                    {{-- View All Link --}}
                    <a href="{{ route('admin.households.show', $household) }}"
                       class="text-xs font-semibold text-deep-forest hover:text-sea-green transition-colors">
                        View All Families →
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Quick Add HHN Modal --}}
            <div id="add-head-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-sea-green/10 text-sea-green flex items-center justify-center">👥</div>
                        <h3 class="text-lg font-bold text-deep-forest">Add New Family</h3>
                    </div>

                    <form :action="`/admin/households/${newHead.householdId}/head`" method="POST">
                        @csrf
                        <input type="hidden" name="entry_mode" :value="newHead.mode">

                        <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs font-semibold text-gray-600 mb-1">HOUSEHOLD</p>
                            <p class="text-sm font-semibold text-deep-forest" x-text="newHead.address"></p>
                            <p class="text-xs text-gray-500 mt-1">Barangay: <span x-text="newHead.barangay"></span></p>
                        </div>

                        {{-- ── Entry Mode Toggle ── --}}
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <p class="text-xs font-bold text-deep-forest mb-2.5">How would you like to register the head?</p>
                            
                            <div class="space-y-2">
                                {{-- Link a registered resident --}}
                                <label :class="newHead.mode === 'resident' ? 'border-sea-green bg-sea-green/5' : 'border-gray-200 bg-white hover:border-sea-green/40'"
                                       class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-2.5 transition-colors"
                                       :disabled="!newHead.hasResidents">
                                    <input type="radio" x-model="newHead.mode" value="resident"
                                           :disabled="!newHead.hasResidents"
                                           class="mt-0.5 accent-sea-green disabled:opacity-40">
                                    <div class="flex-1">
                                        <span class="text-xs font-semibold text-slate-700"
                                              :class="!newHead.hasResidents ? 'opacity-40' : ''">Link a Registered Resident</span>
                                        <p class="text-xs text-slate-500 mt-0.5"
                                           :class="!newHead.hasResidents ? 'opacity-40' : ''">
                                            <span x-show="newHead.hasResidents" x-text="`${newHead.residentCount} available`"></span>
                                            <span x-show="!newHead.hasResidents">No residents available</span>
                                        </p>
                                    </div>
                                </label>

                                {{-- Manual entry --}}
                                <label :class="newHead.mode === 'manual' ? 'border-sea-green bg-sea-green/5' : 'border-gray-200 bg-white hover:border-sea-green/40'"
                                       class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-2.5 transition-colors">
                                    <input type="radio" x-model="newHead.mode" value="manual" class="mt-0.5 accent-sea-green">
                                    <div class="flex-1">
                                        <span class="text-xs font-semibold text-slate-700">Enter Name Manually</span>
                                        <p class="text-xs text-slate-500 mt-0.5">Enter the head's name to create the family.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- ── Resident Selector (resident mode) ── --}}
                        <div x-show="newHead.mode === 'resident'" x-transition class="mb-4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Select Resident <span class="text-red-500">*</span>
                            </label>
                            <select name="resident_id"
                                    :required="newHead.mode === 'resident'"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                <option value="">Choose a resident...</option>
                                <template x-for="resident in newHead.residents" :key="resident.id">
                                    <option :value="resident.id" x-text="`${resident.full_name} — ${resident.email}`"></option>
                                </template>
                            </select>
                        </div>

                        {{-- ── Manual Name Entry (manual mode) ── --}}
                        <div x-show="newHead.mode === 'manual'" x-transition class="space-y-3 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="head_last_name"
                                       :required="newHead.mode === 'manual'"
                                       maxlength="100" placeholder="e.g., Santos"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="head_first_name"
                                       :required="newHead.mode === 'manual'"
                                       maxlength="100" placeholder="e.g., Juan"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent">
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-200">
                            <button type="button"
                                    onclick="document.getElementById('add-head-modal').classList.add('hidden')"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 bg-sea-green hover:bg-deep-forest text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                Create Family
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
            function quickAddHHN() {
                return {
                    newHead: {
                        householdId: null,
                        address: '',
                        barangay: '',
                        mode: 'resident',
                        residents: [],
                        residentCount: 0,
                        hasResidents: false
                    },
                    openAddHeadModal(householdId, address, barangay, residents) {
                        this.newHead.householdId = householdId;
                        this.newHead.address = address;
                        this.newHead.barangay = barangay;
                        this.newHead.residents = residents;
                        this.newHead.residentCount = residents.length;
                        this.newHead.hasResidents = residents.length > 0;
                        // Default to resident mode if available, else manual
                        this.newHead.mode = residents.length > 0 ? 'resident' : 'manual';
                        document.getElementById('add-head-modal').classList.remove('hidden');
                    }
                };
            }
            </script>
        </div>
        @endif

        <!-- Hierarchy Explanation -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-blue-800">Household Hierarchy System</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">Level 1</span>
                    </div>
                    <h4 class="font-semibold">HN - Household Number</h4>
                    <p class="text-sm text-gray-600 mt-1">Physical house/address. Multiple families can reside at one HN.</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">Level 2</span>
                    </div>
                    <h4 class="font-semibold">HHN - Household Head Number</h4>
                    <p class="text-sm text-gray-600 mt-1">Family unit within a household. Identified by surname of the head.</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">Level 3</span>
                    </div>
                    <h4 class="font-semibold">HHM - Household Member</h4>
                    <p class="text-sm text-gray-600 mt-1">Individual family member. Auto-linked by surname recognition.</p>
                </div>
            </div>
        </div>
</div>
@endsection

