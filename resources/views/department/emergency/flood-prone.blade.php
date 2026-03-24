@extends('layouts.department')
@section('title', 'Flood-Prone Households')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🌊','title'=>'Flood-Prone Household Registry','subtitle'=>'All households located in flood-prone zones for targeted rescue operations.'])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Name</th><th class="pb-3">Barangay</th><th class="pb-3">Purok</th><th class="pb-3">Household #</th><th class="pb-3">Contact</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($residents as $r)
                <tr class="hover:bg-orange-50">
                    <td class="py-2 font-medium">{{ $r->full_name }}</td>
                    <td class="py-2">{{ $r->barangay }}</td>
                    <td class="py-2">{{ $r->purok }}</td>
                    <td class="py-2 font-mono text-xs">{{ $r->household_number ?: '—' }}</td>
                    <td class="py-2 text-gray-400">{{ $r->contact_number ?: '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $residents->links() }}</div>
    </div>
    <a href="{{ route('department.emergency.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600">← Back</a>
</div>
@endsection
