@extends('layouts.admin')

@section('title', 'Resident Management')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
            <span>👥</span> Resident Management
        </h1>
        <p class="text-gray-600 mt-2">Manage and verify resident profiles</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-sea-green bg-opacity-10 border border-sea-green text-sea-green px-6 py-4 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('admin.residents.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        <option value="">All Roles</option>
                        <option value="visitor" {{ request('role') === 'visitor' ? 'selected' : '' }}>Visitor</option>
                        <option value="citizen" {{ request('role') === 'citizen' ? 'selected' : '' }}>Citizen</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="SA" {{ request('role') === 'SA' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Verified Status</label>
                    <select name="verified" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        <option value="">All</option>
                        <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Matched</label>
                    <select name="profile_matched" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        <option value="">All</option>
                        <option value="1" {{ request('profile_matched') === '1' ? 'selected' : '' }}>Matched</option>
                        <option value="0" {{ request('profile_matched') === '0' ? 'selected' : '' }}>Not Matched</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-bold">
                    🔍 Filter
                </button>
                @if(request()->hasAny(['search', 'role', 'verified', 'profile_matched']))
                <a href="{{ route('admin.residents.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-bold">
                    Clear Filters
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Residents Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Residents List</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $residents->total() }} residents found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Resident</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($residents as $resident)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-bold text-deep-forest">{{ $resident->full_name }}</p>
                                @if($resident->national_id)
                                <p class="text-sm text-gray-500 font-mono">{{ $resident->national_id }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="text-gray-900">{{ $resident->email }}</p>
                                @if($resident->contact_number)
                                <p class="text-gray-500">{{ $resident->contact_number }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($resident->barangay)
                                <p class="text-gray-900">{{ $resident->barangay }}</p>
                                @if($resident->purok)
                                <p class="text-gray-500">Purok {{ $resident->purok }}</p>
                                @endif
                                @else
                                <p class="text-gray-400">N/A</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                @if($resident->is_verified)
                                <span class="inline-flex px-2 py-1 text-xs font-bold bg-sea-green bg-opacity-10 text-sea-green rounded">✓ Verified</span>
                                @else
                                <span class="inline-flex px-2 py-1 text-xs font-bold bg-burnt-tangerine bg-opacity-10 text-burnt-tangerine rounded">⚠️ Unverified</span>
                                @endif
                                @if($resident->profile_matched)
                                <span class="inline-flex px-2 py-1 text-xs font-bold bg-golden-glow bg-opacity-10 text-golden-glow rounded">Matched</span>
                                @endif
                                @if($resident->is_auto_linked)
                                <span class="inline-flex px-2 py-1 text-xs font-bold bg-tiger-orange bg-opacity-10 text-tiger-orange rounded">Auto-Linked</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-bold rounded uppercase
                                {{ $resident->role === 'citizen' ? 'bg-sea-green text-white' : '' }}
                                {{ $resident->role === 'visitor' ? 'bg-gray-200 text-gray-700' : '' }}
                                {{ $resident->role === 'admin' ? 'bg-deep-forest text-white' : '' }}">
                                {{ $resident->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.residents.show', $resident) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-medium text-sm">
                                    View
                                </a>
                                @if($resident->role !== 'SA')
                                <button
                                    type="button"
                                    onclick="document.getElementById('delete-modal-{{ $resident->id }}').classList.remove('hidden')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium text-sm">
                                    🗑 Delete
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No residents found. Try adjusting your filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($residents->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $residents->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modals --}}
@foreach($residents as $resident)
@if($resident->role !== 'SA')
<div id="delete-modal-{{ $resident->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl">🗑️</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Delete Resident</h3>
            <p class="text-gray-600 mt-2">Are you sure you want to permanently delete <strong>{{ $resident->full_name }}</strong>? This action <span class="text-red-600 font-semibold">cannot be undone</span>.</p>
        </div>
        <div class="flex gap-3">
            <button
                type="button"
                onclick="document.getElementById('delete-modal-{{ $resident->id }}').classList.add('hidden')"
                class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                Cancel
            </button>
            <form method="POST" action="{{ route('admin.residents.destroy', $resident) }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-bold">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

@endsection
