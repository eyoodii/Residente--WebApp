@extends('layouts.department')
@section('title', 'Household Management')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🏘️','title'=>'Household Management','subtitle'=>'Drill-down into household and housing profile data.'])
    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5"><p class="text-xs text-gray-400">Total Households</p><p class="text-3xl font-black text-deep-forest">{{ number_format($totalHouseholds) }}</p></div>
        <div class="bg-white rounded-2xl shadow-sm border p-5"><p class="text-xs text-gray-400">Flood-Prone</p><p class="text-3xl font-black text-orange-600">{{ number_format($floodProneCount) }}</p></div>
        <div class="bg-white rounded-2xl shadow-sm border p-5"><p class="text-xs text-gray-400">Without Toilet</p><p class="text-3xl font-black text-red-600">{{ number_format($withoutToilet) }}</p></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Household #</th><th class="pb-3">Barangay</th><th class="pb-3">Purok</th><th class="pb-3">Members</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($households as $hh)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 font-mono font-bold">{{ $hh->household_number ?? 'N/A' }}</td>
                    <td class="py-3">{{ $hh->barangay }}</td>
                    <td class="py-3">{{ $hh->purok }}</td>
                    <td class="py-3">{{ $hh->residents_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $households->links() }}</div>
    </div>
</div>
@endsection
