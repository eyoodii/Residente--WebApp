@extends('layouts.department')
@section('title', 'Edit Staff Role')
@section('subtitle', 'HRMO — Update Department Role Assignment')

@section('content')
<div class="p-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('department.staff.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to Staff</a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        {{-- Staff Info (read-only) --}}
        <div class="flex items-center gap-4 mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
            <div class="w-12 h-12 rounded-full bg-deep-forest text-white flex items-center justify-center font-bold text-lg flex-shrink-0">
                {{ substr($resident->first_name, 0, 1) }}
            </div>
            <div>
                <p class="font-bold text-gray-800">{{ $resident->full_name }}</p>
                <p class="text-sm text-gray-400">{{ $resident->email }}</p>
                @if($resident->department_role)
                <span class="mt-1 inline-block px-2 py-0.5 bg-deep-forest text-white text-xs font-bold rounded">
                    Current: {{ $resident->department_role }}
                </span>
                @endif
            </div>
        </div>

        <h2 class="text-lg font-bold text-gray-800 mb-5">Update Department Role</h2>

        <form method="POST" action="{{ route('department.staff.update', $resident) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Department Role</label>
                <select name="department_role"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
                    <option value="">— Remove Department Role —</option>
                    @foreach($roles as $code => $cfg)
                    <option value="{{ $code }}" {{ $resident->department_role === $code ? 'selected' : '' }}>
                        [{{ $code }}] {{ $cfg['label'] }} — {{ ucfirst($cfg['access']) }} access
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Selecting "Remove" will revoke department access. The account remains as a regular admin.</p>
            </div>

            @if($resident->department_role)
            @php $currentCfg = config('department_permissions.' . $resident->department_role, []); @endphp
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 text-xs">
                <p class="font-semibold text-blue-800 mb-2">📋 Current Role: {{ $currentCfg['label'] ?? $resident->department_role }}</p>
                <p class="text-blue-700 mb-1"><strong>Department:</strong> {{ $currentCfg['department'] ?? 'N/A' }}</p>
                <p class="text-blue-700 mb-1"><strong>Access Level:</strong> {{ ucfirst($currentCfg['access'] ?? 'N/A') }}</p>
                <p class="text-blue-700"><strong>Modules:</strong> {{ implode(', ', $currentCfg['modules'] ?? []) }}</p>
            </div>
            @endif

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-deep-forest text-white font-bold rounded-xl hover:bg-sea-green transition-colors text-sm">
                    💾 Save Changes
                </button>
                <a href="{{ route('department.staff.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
