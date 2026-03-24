@extends('layouts.department')
@section('title', 'Role Assignment')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🎖️','title'=>'Department Role Assignment','subtitle'=>'Assign or revoke department roles for municipal staff residents.'])

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Currently Assigned --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="font-semibold text-gray-700">Currently Assigned Staff</h3>
            @forelse($assigned as $resident)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium">{{ $resident->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $resident->barangay }} · <span class="font-mono bg-gray-100 px-1 rounded">{{ $resident->department_role }}</span></p>
                </div>
                <form action="{{ route('department.role-assignment.revoke', $resident) }}" method="POST" onsubmit="return confirm('Revoke role from {{ addslashes($resident->full_name) }}?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-600 border border-red-200 px-3 py-1 rounded-lg hover:bg-red-50">Revoke</button>
                </form>
            </div>
            @empty
            <p class="text-sm text-gray-400">No department roles currently assigned.</p>
            @endforelse
        </div>

        {{-- Assign Role --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="font-semibold text-gray-700">Assign a Role</h3>
            <form action="{{ route('department.role-assignment.assign', 0) }}" method="POST" id="assign-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Resident</label>
                    <select name="resident_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="">— Select resident —</option>
                        @foreach($residents as $r)
                        <option value="{{ $r->id }}">{{ $r->full_name }} ({{ $r->barangay }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Role</label>
                    <select name="department_role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="">— Select role —</option>
                        @foreach($roles as $code => $label)
                        <option value="{{ $code }}">{{ $code }} — {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white text-sm py-2 rounded-lg hover:bg-indigo-700">Assign Role</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('assign-form').addEventListener('submit', function(e) {
    const residentId = this.querySelector('[name=resident_id]').value;
    this.action = this.action.replace('/0', '/' + residentId);
});
</script>
@endsection
