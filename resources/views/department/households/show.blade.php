@extends('layouts.department')
@section('title', 'Household Detail')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🏠','title'=>'Household #' . ($household->household_number ?? 'N/A'),'subtitle'=>$household->barangay . ', Purok ' . $household->purok])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Household Members ({{ $household->residents->count() }})</h3>
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Name</th><th class="pb-3">Age</th><th class="pb-3">Gender</th><th class="pb-3">Role in Family</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($household->residents as $r)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 font-medium">{{ $r->full_name }}</td>
                    <td class="py-2">{{ $r->date_of_birth ? \Carbon\Carbon::parse($r->date_of_birth)->age : '—' }}</td>
                    <td class="py-2">{{ $r->gender }}</td>
                    <td class="py-2">{{ $r->household_relationship }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('department.households.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-deep-forest">← Back to Households</a>
</div>
@endsection
