@extends('layouts.admin')

@section('title', 'Data Collection')
@section('subtitle', 'Hierarchical Household & Resident Tracking')

@section('content')
<div class="p-8">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <p class="text-green-800 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
        <p class="text-red-800 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div class="mb-6 flex flex-wrap items-center gap-4">
        <div class="relative flex-1 min-w-64">
            <form method="GET" action="{{ route('admin.data-collection.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by Address, HN, HHN, or Surname..." class="flex-1 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-sea-green focus:border-sea-green text-sm">
                <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                <button type="submit" class="px-4 py-2 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-bold text-sm">Search</button>
            </form>
        </div>

        <form method="GET" action="{{ route('admin.data-collection.index') }}" class="flex gap-2 items-center">
            <label class="text-sm font-bold text-gray-700">Barangay:</label>
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <select name="barangay" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-sea-green focus:border-sea-green" onchange="this.form.submit()">
                <option value="">All Barangays</option>
                @foreach(config('barangays.list', []) as $name => $code)
                    <option value="{{ $name }}" {{ ($barangay ?? '') == $name ? 'selected' : '' }}>{{ $name }} ({{ $code }})</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Hierarchical Data Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-12 gap-4 bg-gray-100 px-6 py-4 border-b border-gray-200 text-xs uppercase tracking-wider font-bold text-gray-600">
            <div class="col-span-5">Physical Address / Entity</div>
            <div class="col-span-3">Identifier Code</div>
            <div class="col-span-2 text-center">Status / Members</div>
            <div class="col-span-2 text-right">Actions</div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($households as $house)
            <div x-data="{ expandedHouse: false }" class="w-full">

                {{-- Level 1: Physical House (HN) --}}
                <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50 transition items-center cursor-pointer" @click="expandedHouse = !expandedHouse">
                    <div class="col-span-5 flex items-center gap-3">
                        <span class="transform transition-transform duration-200 text-gray-400" :class="expandedHouse ? 'rotate-90' : ''">▶</span>
                        <div class="w-10 h-10 rounded bg-deep-forest bg-opacity-10 flex items-center justify-center text-deep-forest text-lg">🏠</div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $house->full_address }}</p>
                            <p class="text-xs text-gray-500">Brgy. {{ $house->barangay }}, Purok {{ $house->purok }}</p>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <span class="bg-gray-100 text-gray-700 border border-gray-300 px-2 py-1 rounded text-xs font-mono font-bold">{{ $house->household_number }}</span>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="text-sm font-bold text-deep-forest">{{ $house->families->count() }} {{ Str::plural('Family', $house->families->count()) }}</span>
                    </div>
                    <div class="col-span-2 text-right">
                        <button class="text-sea-green hover:text-deep-forest font-bold text-xs uppercase tracking-wide">Edit House</button>
                    </div>
                </div>

                {{-- Level 2: Families (HHN) --}}
                <div x-show="expandedHouse" x-collapse class="bg-gray-50 border-t border-gray-100 divide-y divide-gray-200">
                    @forelse($house->families as $family)
                    <div x-data="{ expandedFamily: false }" class="w-full">

                        <div class="grid grid-cols-12 gap-4 px-6 py-3 pl-14 hover:bg-white transition items-center cursor-pointer border-l-4 border-tiger-orange" @click="expandedFamily = !expandedFamily">
                            <div class="col-span-5 flex items-center gap-3">
                                <span class="transform transition-transform duration-200 text-gray-400 text-xs" :class="expandedFamily ? 'rotate-90' : ''">▶</span>
                                <div class="w-8 h-8 rounded-full bg-tiger-orange bg-opacity-20 flex items-center justify-center text-tiger-orange">👨‍👩‍👧</div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Family: {{ $family->head_surname }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($family->householdHead)
                                            Head: {{ $family->householdHead->first_name }} {{ $family->householdHead->last_name }}
                                        @else
                                            Head of Household
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-span-3">
                                <span class="bg-tiger-orange bg-opacity-10 text-tiger-orange border border-tiger-orange border-opacity-30 px-2 py-1 rounded text-xs font-mono font-bold">{{ $family->hhn_number }}</span>
                            </div>
                            <div class="col-span-2 text-center">
                                <span class="text-sm font-bold text-gray-700">{{ $family->members->count() }} {{ Str::plural('Member', $family->members->count()) }}</span>
                            </div>
                            <div class="col-span-2 text-right">
                                <button class="text-tiger-orange hover:text-burnt-tangerine font-bold text-xs uppercase tracking-wide">View Profile</button>
                            </div>
                        </div>

                        {{-- Level 3: Members (HHM) --}}
                        <div x-show="expandedFamily" x-collapse class="bg-white border-t border-gray-100">
                            <div class="px-6 py-2 pl-24 bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Registered Members
                            </div>
                            @forelse($family->members as $member)
                            <div class="grid grid-cols-12 gap-4 px-6 py-3 pl-24 hover:bg-gray-50 transition items-center border-l-4 border-golden-glow">
                                <div class="col-span-5 flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">👤</div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $member->first_name }} {{ $member->last_name }}</p>
                                    @if($member->is_auto_linked)
                                        <span class="bg-golden-glow text-deep-forest text-[10px] font-bold px-2 py-0.5 rounded shadow-sm flex items-center gap-1">
                                            <span>⚠️</span> Auto-Linked
                                        </span>
                                    @endif
                                </div>
                                <div class="col-span-3">
                                    <span class="text-xs text-gray-500 font-mono">ID: HHM-{{ str_pad($member->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    @if($member->is_auto_linked)
                                        <div class="flex gap-1 justify-center">
                                            <form method="POST" action="{{ route('admin.data-collection.approve-link', $member) }}">
                                                @csrf
                                                <button type="submit" class="text-xs bg-sea-green text-white px-2 py-1 rounded hover:bg-opacity-90 transition font-bold shadow-sm">✓ Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.data-collection.reject-link', $member) }}">
                                                @csrf
                                                <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-opacity-90 transition font-bold shadow-sm">✗ Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-sea-green font-bold">✓ Verified</span>
                                    @endif
                                </div>
                                <div class="col-span-2 text-right">
                                    <button class="text-gray-400 hover:text-deep-forest transition">•••</button>
                                </div>
                            </div>
                            @empty
                            <div class="px-6 py-4 pl-24 text-sm text-gray-500 italic">
                                No members registered yet.
                            </div>
                            @endforelse
                        </div>

                    </div>
                    @empty
                    <div class="px-6 py-4 pl-14 text-sm text-gray-500 italic">
                        No families registered at this address yet.
                    </div>
                    @endforelse
                </div>

            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <p class="text-lg font-medium">No households found.</p>
                <p class="text-sm mt-2">Start by adding household data from the census collection system.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $households->links() }}
        </div>
    </div>

</div>
@endsection
