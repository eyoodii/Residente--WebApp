@extends('layouts.department')

@section('title', 'Human Resource Management Office')
@section('subtitle', 'Internal System Administration')

@section('content')
<div class="p-8 space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-deep-forest to-sea-green text-white rounded-2xl p-7 shadow-xl flex items-center justify-between">
        <div>
            <p class="text-golden-glow text-xs font-bold uppercase tracking-widest mb-1">HRMO — Internal System Administration</p>
            <h2 class="text-3xl font-extrabold">HR Management Dashboard</h2>
            <p class="text-gray-200 mt-1 text-sm max-w-xl">Create LGU employee profiles, assign departmental roles, and monitor staff onboarding.</p>
        </div>
        <div class="text-5xl opacity-20 hidden lg:block">👥</div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Total Dept. Staff</p>
            <p class="text-3xl font-black text-deep-forest">{{ number_format($totalStaff) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Roles Filled</p>
            <p class="text-3xl font-black text-sea-green">{{ count($filledRoles) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Vacant Roles</p>
            <p class="text-3xl font-black text-burnt-tangerine">{{ count($vacantRoles) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Admin Accounts</p>
            <p class="text-3xl font-black text-tiger-orange">{{ number_format($adminCount) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Role Coverage Matrix --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🔑 Role Coverage Matrix</h3>
            <div class="space-y-2 max-h-96 overflow-y-auto pr-1">
                @foreach($allRoles as $code => $roleConfig)
                @php $isFilled = in_array($code, $filledRoles); @endphp
                <div class="flex items-center justify-between p-2.5 rounded-lg {{ $isFilled ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}">
                    <div class="flex items-center gap-2">
                        <span class="text-sm">{{ $isFilled ? '✅' : '❌' }}</span>
                        <div>
                            <p class="text-xs font-bold {{ $isFilled ? 'text-green-800' : 'text-red-800' }}">{{ $code }}</p>
                            <p class="text-xs {{ $isFilled ? 'text-green-600' : 'text-red-500' }} leading-tight">{{ $roleConfig['label'] }}</p>
                        </div>
                    </div>
                    @if(!$isFilled)
                    <a href="{{ route('department.staff.create') }}" class="text-xs text-red-600 underline flex-shrink-0">+ Assign</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Staff Roster --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">📋 Active Department Staff</h3>
                <a href="{{ route('department.staff.create') }}"
                   class="px-4 py-2 bg-deep-forest text-white text-sm font-semibold rounded-xl hover:bg-sea-green transition-colors flex items-center gap-2">
                    ➕ Add Staff
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-semibold">Name</th>
                            <th class="pb-3 font-semibold">Role Code</th>
                            <th class="pb-3 font-semibold">Department</th>
                            <th class="pb-3 font-semibold">Access</th>
                            <th class="pb-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($allStaff as $staff)
                        @php $staffConfig = config('department_permissions.' . $staff->department_role, []); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-2.5">
                                <p class="font-semibold text-gray-800">{{ $staff->full_name }}</p>
                                <p class="text-xs text-gray-400">{{ $staff->email }}</p>
                            </td>
                            <td class="py-2.5">
                                <span class="px-2 py-1 bg-deep-forest text-white text-xs font-bold rounded-lg">{{ $staff->department_role }}</span>
                            </td>
                            <td class="py-2.5 text-gray-600 text-xs">{{ $staffConfig['department'] ?? 'N/A' }}</td>
                            <td class="py-2.5">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ ($staffConfig['access'] ?? '') === 'read_only' ? 'bg-yellow-100 text-yellow-700' :
                                       (($staffConfig['access'] ?? '') === 'full' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ ucfirst($staffConfig['access'] ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="py-2.5">
                                <a href="{{ route('department.staff.edit', $staff) }}"
                                   class="text-xs text-sea-green underline">Edit Role</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-6 text-center text-gray-400">No department staff assigned yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Admin Activity --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🕐 Recent Admin Activity</h3>
        <div class="space-y-2">
            @forelse($recentLogs as $log)
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0
                    {{ $log->severity === 'critical' ? 'bg-red-500' : ($log->severity === 'warning' ? 'bg-yellow-400' : 'bg-blue-400') }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700">{{ $log->description }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }} · {{ $log->user_email }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-4">No recent activity logs.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
