@extends('layouts.department')
@section('title', 'Demographics List')
@section('content')
<div class="space-y-6">
    @include('department.components._module-header', ['icon'=>'👥','title'=>'Resident Demographics','subtitle'=>'Paginated resident listing with demographic attributes.'])
    @include('department.master-collections._collections-nav')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b">
                <th class="pb-3">Name</th><th class="pb-3">Barangay</th><th class="pb-3">Purok</th><th class="pb-3">Gender</th><th class="pb-3">Age</th><th class="pb-3">Civil Status</th><th class="pb-3">Sector</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($residents as $r)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 font-medium">{{ $r->last_name }}, {{ $r->first_name }}</td>
                    <td class="py-2">{{ $r->barangay }}</td>
                    <td class="py-2">{{ $r->purok }}</td>
                    <td class="py-2">{{ $r->gender }}</td>
                    <td class="py-2">{{ $r->date_of_birth ? \Carbon\Carbon::parse($r->date_of_birth)->age : '—' }}</td>
                    <td class="py-2">{{ $r->civil_status }}</td>
                    <td class="py-2">{{ $r->vulnerable_sector ?: '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $residents->links() }}</div>
    </div>
</div>
@endsection
