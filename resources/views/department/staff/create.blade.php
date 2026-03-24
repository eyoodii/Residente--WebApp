@extends('layouts.department')
@section('title', 'Add Department Staff')
@section('subtitle', 'HRMO — Create New LGU Employee Account')

@section('content')
<div class="p-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('department.staff.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to Staff</a>
    </div>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-lg font-bold text-gray-800 mb-6">New Department Staff Account</h2>

        <form method="POST" action="{{ route('department.staff.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="e.g. staff@buguey.gov.ph"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Department Role *</label>
                <select name="department_role" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
                    <option value="">— Select Department Role —</option>
                    @foreach($roles as $code => $cfg)
                    <option value="{{ $code }}" {{ old('department_role') === $code ? 'selected' : '' }}>
                        [{{ $code }}] {{ $cfg['label'] }} — {{ ucfirst($cfg['access']) }} access
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password *</label>
                    <input type="password" name="password" required
                        placeholder="Min. 8 characters"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
                </div>
            </div>

            <div class="p-3 bg-golden-glow/10 border border-golden-glow/30 rounded-xl text-xs text-gray-600">
                ⚠️ The new staff member will be able to log in immediately. Remind them to change their password on first login.
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-deep-forest text-white font-bold rounded-xl hover:bg-sea-green transition-colors text-sm">
                    ✅ Create Account
                </button>
                <a href="{{ route('department.staff.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
