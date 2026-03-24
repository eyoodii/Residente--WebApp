@extends('layouts.admin')

@section('title', 'Household Details — ' . $household->household_number)

@section('content')
@php $familyCount = $household->householdHeads->count(); @endphp
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="mb-5">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="{{ route('admin.households.index') }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">🏠 Households</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li><a href="{{ route('admin.households.search.address') }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">Search by Address</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li class="text-gray-700 font-medium">{{ $household->household_number }}</li>
        </ol>
    </nav>

    {{-- ── Header Card ── --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-sea-green/10 text-sea-green border border-sea-green/20">
                        {{ $household->household_number }}
                    </span>
                    @if($household->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Active</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-deep-forest mt-2">{{ $household->full_address }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Housing Type: <span class="font-medium text-gray-700">{{ $household->housing_type }}</span></p>
            </div>
            @if($familyCount > 0)
                <a href="{{ route('admin.households.head.create', $household) }}"
                   class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-sea-green hover:bg-deep-forest text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                    <span>👤</span> Add Family (HHN)
                </a>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- Families section                                       --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    @if($familyCount === 0)
        {{-- ── Empty state — NO families yet ── --}}
        <div class="bg-white rounded-lg shadow-sm border-2 border-dashed border-sea-green/30 p-10 mb-5 flex flex-col items-center text-center">
            <div class="w-16 h-16 rounded-full bg-sea-green/10 flex items-center justify-center text-3xl mb-4">🏡</div>
            <h2 class="text-lg font-bold text-deep-forest mb-1">No families registered yet</h2>
            <p class="text-sm text-gray-500 max-w-sm mb-5">
                This household has no family units (HHN) yet. Register the first family to start tracking members at this address.
            </p>
            <a href="{{ route('admin.households.head.create', $household) }}?first=1"
               class="inline-flex items-center gap-2 px-6 py-2.5 bg-sea-green hover:bg-deep-forest text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                <span>👤</span> Register First Family
            </a>
            <p class="text-xs text-gray-400 mt-3">A unique HHN will be generated automatically.</p>
        </div>
    @else
        {{-- ── Family list header ── --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-5">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-deep-forest uppercase tracking-widest flex items-center gap-2">
                    <span>👥</span>
                    Families at this Address
                    <span class="text-gray-400 font-normal normal-case tracking-normal">
                        ({{ $familyCount }} {{ Str::plural('family', $familyCount) }})
                    </span>
                </h2>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($household->householdHeads as $head)
                    <div class="p-5 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            {{-- Head info --}}
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-sea-green/10 flex items-center justify-center text-sea-green flex-shrink-0 font-bold text-sm">
                                    {{ strtoupper(substr($head->surname, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-sea-green/10 text-sea-green border border-sea-green/20">
                                            {{ $head->household_head_number }}
                                        </span>
                                        @if($head->is_primary_family)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-golden-glow/20 text-yellow-700 border border-golden-glow/40">Primary</span>
                                        @endif
                                        @if($head->is_4ps_beneficiary)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">4Ps</span>
                                        @endif
                                    </div>
                                    <p class="font-semibold text-deep-forest">{{ $head->head_name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Surname: <strong>{{ $head->surname }}</strong>
                                        @if($head->family_name)
                                            · Family Name: <strong>{{ $head->family_name }}</strong>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $head->members->count() }} {{ Str::plural('member', $head->members->count()) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <a href="{{ route('admin.households.head.show', $head) }}"
                                   class="px-3.5 py-1.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                                    View Members
                                </a>
                                <a href="{{ route('admin.households.head.edit', $head) }}"
                                   class="px-3.5 py-1.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                                    Edit
                                </a>
                                <a href="{{ route('admin.households.member.create', $head) }}"
                                   class="px-3.5 py-1.5 bg-sea-green/10 text-sea-green text-xs font-semibold rounded-lg hover:bg-sea-green/20 transition-colors border border-sea-green/20">
                                    + Member
                                </a>
                            </div>
                        </div>

                        {{-- Member chips --}}
                        @if($head->members->count() > 0)
                            <div class="mt-3 ml-13 flex flex-wrap gap-1.5 pl-[52px]">
                                @foreach($head->members->take(6) as $member)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600 border border-gray-200">
                                        {{ $member->full_name }}
                                    </span>
                                @endforeach
                                @if($head->members->count() > 6)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-500">
                                        +{{ $head->members->count() - 6 }} more
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Registered Residents table ── --}}
    @if($household->residents->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-deep-forest uppercase tracking-widest flex items-center gap-2">
                    <span>🪪</span>
                    Registered Residents
                    <span class="text-gray-400 font-normal normal-case tracking-normal">({{ $household->residents->count() }})</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">National ID</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Family (HHN)</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($household->residents as $resident)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-sea-green/10 flex items-center justify-center text-sea-green text-xs font-bold flex-shrink-0">
                                            {{ substr($resident->first_name, 0, 1) }}{{ substr($resident->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $resident->full_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $resident->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">{{ $resident->national_id ?? '—' }}</td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($resident->householdHeadRelation)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-sea-green/10 text-sea-green border border-sea-green/20">
                                            {{ $resident->householdHeadRelation->household_head_number }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($resident->is_household_head)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-golden-glow/20 text-yellow-700 border border-golden-glow/40">Head</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Member</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
