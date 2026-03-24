@extends('layouts.department')
@section('title', 'Staff Management')
@section('subtitle', 'HRMO — Manage LGU Department Accounts')

@section('content')
<div class="p-8 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Department Staff Roster</h2>
            <p class="text-sm text-gray-500 mt-0.5">Create, view, and manage LGU department accounts.</p>
        </div>
        <a href="{{ route('department.staff.create') }}"
           class="px-5 py-2.5 bg-deep-forest text-white text-sm font-bold rounded-xl hover:bg-sea-green transition-colors flex items-center gap-2">
            ➕ Add Staff Member
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-deep-forest/5 border-b border-gray-100">
                <tr class="text-left text-xs text-gray-500">
                    <th class="px-6 py-4 font-semibold">Name</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">Role</th>
                    <th class="px-6 py-4 font-semibold">Department</th>
                    <th class="px-6 py-4 font-semibold">Access</th>
                    <th class="px-6 py-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($staff as $member)
                @php $cfg = config('department_permissions.' . $member->department_role, []); @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3.5 font-semibold text-gray-800">{{ $member->full_name }}</td>
                    <td class="px-6 py-3.5 text-gray-500 text-xs">{{ $member->email }}</td>
                    <td class="px-6 py-3.5">
                        <span class="px-2 py-1 bg-deep-forest text-white text-xs font-bold rounded-lg">{{ $member->department_role }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-gray-600 text-xs">{{ $cfg['department'] ?? 'N/A' }}</td>
                    <td class="px-6 py-3.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ ($cfg['access'] ?? '') === 'read_only' ? 'bg-yellow-100 text-yellow-700' :
                               (($cfg['access'] ?? '') === 'full' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst($cfg['access'] ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <a href="{{ route('department.staff.edit', $member) }}"
                           class="px-3 py-1.5 bg-sea-green/10 text-sea-green text-xs font-semibold rounded-lg hover:bg-sea-green hover:text-white transition-colors">
                            ✏️ Edit Role
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No department staff assigned. <a href="{{ route('department.staff.create') }}" class="text-sea-green underline">Add one now →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
