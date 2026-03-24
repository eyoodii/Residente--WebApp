@extends('layouts.department')
@section('title', 'Sanitation Data')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🚽','title'=>'Sanitation by Barangay','subtitle'=>'Households with and without sanitary toilet facilities.'])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Barangay</th><th class="pb-3">With Toilet</th><th class="pb-3">Without Toilet</th><th class="pb-3">Total</th><th class="pb-3">Coverage</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($byBarangay as $b)
                @php $pct = $b->total > 0 ? round(($b->with_toilet/$b->total)*100,1) : 0; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="py-2 font-medium">{{ $b->barangay }}</td>
                    <td class="py-2 text-green-700 font-bold">{{ number_format($b->with_toilet) }}</td>
                    <td class="py-2 text-red-700 font-bold">{{ number_format($b->without_toilet) }}</td>
                    <td class="py-2">{{ number_format($b->total) }}</td>
                    <td class="py-2"><span class="px-2 py-1 rounded text-xs font-bold {{ $pct >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $pct }}%</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
