@extends('layouts.admin')

@section('title', 'Barangay Overview')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
            <span>📍</span> Barangay Overview
        </h1>
        <p class="text-gray-600 mt-2">Statistical overview and comparison across all barangays</p>
    </div>

    <!-- Barangay Selector -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('admin.barangay-overview') }}" class="flex gap-4 items-center">
            <label class="text-sm font-bold text-gray-700">Select Barangay:</label>
            <select name="barangay" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent" onchange="this.form.submit()">
                <option value="">-- All Barangays (Overview) --</option>
                @foreach($barangays as $barangay)
                    <option value="{{ $barangay->barangay }}" {{ $selectedBarangay === $barangay->barangay ? 'selected' : '' }}>
                        {{ $barangay->barangay }} ({{ $barangay->code }}) - {{ number_format($barangay->total_residents) }} residents
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($barangayDetails)
        <!-- Selected Barangay Details -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-deep-forest mb-6">{{ $barangayDetails['name'] }} ({{ $barangayDetails['code'] }}) - Detailed Statistics</h2>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-sea-green">
                    <p class="text-gray-600 text-sm font-medium">Total Residents</p>
                    <p class="text-4xl font-bold text-deep-forest mt-2">{{ number_format($barangayDetails['total_residents']) }}</p>
                    <div class="mt-4 text-sm space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Citizens:</span>
                            <span class="font-bold text-sea-green">{{ number_format($barangayDetails['citizens']) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Visitors:</span>
                            <span class="font-bold text-tiger-orange">{{ number_format($barangayDetails['visitors']) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Verified:</span>
                            <span class="font-bold text-golden-glow">{{ number_format($barangayDetails['verified']) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-tiger-orange">
                    <p class="text-gray-600 text-sm font-medium">Household Data</p>
                    <div class="mt-4 space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Households (HN)</p>
                            <p class="text-3xl font-bold text-deep-forest">{{ number_format($barangayDetails['households']) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Families (HHN)</p>
                            <p class="text-3xl font-bold text-deep-forest">{{ number_format($barangayDetails['families']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-golden-glow col-span-2">
                    <p class="text-gray-600 text-sm font-medium mb-4">Age Distribution</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                        @php
                            $ageGroups = [
                                'infants' => ['label' => 'Infants', 'range' => '0-5', 'icon' => '👶', 'color' => 'bg-pink-50 border-pink-200'],
                                'children' => ['label' => 'Children', 'range' => '6-12', 'icon' => '🧒', 'color' => 'bg-blue-50 border-blue-200'],
                                'teens' => ['label' => 'Teens', 'range' => '13-17', 'icon' => '👦', 'color' => 'bg-purple-50 border-purple-200'],
                                'young_adults' => ['label' => 'Young Adults', 'range' => '18-35', 'icon' => '👨', 'color' => 'bg-green-50 border-green-200'],
                                'middle_aged' => ['label' => 'Middle Age', 'range' => '36-59', 'icon' => '🧑', 'color' => 'bg-yellow-50 border-yellow-200'],
                                'seniors' => ['label' => 'Seniors', 'range' => '60-79', 'icon' => '👴', 'color' => 'bg-orange-50 border-orange-200'],
                                'elderly' => ['label' => 'Elderly', 'range' => '80+', 'icon' => '👵', 'color' => 'bg-red-50 border-red-200'],
                            ];
                            $totalResidents = $barangayDetails['total_residents'] ?: 1;
                        @endphp
                        @foreach($ageGroups as $key => $group)
                        <div class="text-center p-3 rounded-lg border {{ $group['color'] }}">
                            <span class="text-2xl">{{ $group['icon'] }}</span>
                            <p class="text-2xl font-bold text-deep-forest mt-1">{{ number_format($barangayDetails['age_distribution'][$key] ?? 0) }}</p>
                            <p class="text-xs font-semibold text-gray-700">{{ $group['label'] }}</p>
                            <p class="text-xs text-gray-500">({{ $group['range'] }} yrs)</p>
                            <p class="text-xs text-sea-green font-medium mt-1">{{ round((($barangayDetails['age_distribution'][$key] ?? 0) / $totalResidents) * 100, 1) }}%</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Gender Distribution -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-deep-forest mb-4">Gender Distribution</h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach($barangayDetails['gender_distribution'] as $gender => $count)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-3xl font-bold text-deep-forest">{{ number_format($count) }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ ucfirst($gender) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ round(($count / $barangayDetails['total_residents']) * 100, 1) }}%</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- All Barangays Comparison -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-deep-forest">All Barangays - Comparison Table</h3>
                <p class="text-sm text-gray-600 mt-1">Click a barangay name to view detailed statistics</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Barangay</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Total Residents</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Citizens</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Visitors</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Verified</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Verification Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($barangays as $barangay)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.barangay-overview', ['barangay' => $barangay->barangay]) }}" class="font-bold text-sea-green hover:underline">
                                    {{ $barangay->barangay }} ({{ $barangay->code }})
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-deep-forest">{{ number_format($barangay->total_residents) }}</td>
                            <td class="px-6 py-4 text-center text-sea-green font-medium">{{ number_format($barangay->citizens) }}</td>
                            <td class="px-6 py-4 text-center text-tiger-orange font-medium">{{ number_format($barangay->visitors) }}</td>
                            <td class="px-6 py-4 text-center text-golden-glow font-medium">{{ number_format($barangay->verified) }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="bg-sea-green h-2 rounded-full" style="width: {{ $barangay->total_residents > 0 ? round(($barangay->verified / $barangay->total_residents) * 100, 1) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">
                                        {{ $barangay->total_residents > 0 ? round(($barangay->verified / $barangay->total_residents) * 100, 1) : 0 }}%
                                    </span>
                                </div>
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
