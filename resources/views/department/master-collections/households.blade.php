@extends('layouts.department')
@section('title', 'Households')
@section('content')
<div class="space-y-6">
    @include('department.components._module-header', ['icon'=>'🏠','title'=>'Household Registry','subtitle'=>'All registered households in the municipality.'])
    @include('department.master-collections._collections-nav')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b">
                <th class="pb-3">Household #</th><th class="pb-3">Barangay</th><th class="pb-3">Purok</th><th class="pb-3">Members</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($households as $hh)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 font-mono font-bold">{{ $hh->household_number ?? 'N/A' }}</td>
                    <td class="py-3">{{ $hh->barangay }}</td>
                    <td class="py-3">{{ $hh->purok }}</td>
                    <td class="py-3"><span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">{{ $hh->residents_count }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $households->links() }}</div>
    </div>
</div>
@endsection
