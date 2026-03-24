@extends('layouts.department')
@section('title', 'Vulnerable Residents')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🛡️','title'=>'Vulnerable Residents','subtitle'=>'Complete list of residents tagged under vulnerable sectors.'])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Name</th><th class="pb-3">Barangay</th><th class="pb-3">Purok</th><th class="pb-3">Sector</th><th class="pb-3">Contact</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($residents as $r)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 font-medium">{{ $r->full_name }}</td>
                    <td class="py-2">{{ $r->barangay }}</td>
                    <td class="py-2">{{ $r->purok }}</td>
                    <td class="py-2"><span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">{{ $r->vulnerable_sector }}</span></td>
                    <td class="py-2 text-gray-400">{{ $r->contact_number ?: '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $residents->links() }}</div>
    </div>
    <a href="{{ route('department.welfare.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-deep-forest">← Back</a>
</div>
@endsection
