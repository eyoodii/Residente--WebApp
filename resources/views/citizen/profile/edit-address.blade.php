@extends('layouts.citizen')

@section('title', 'Edit Address Information | RESIDENTE App')
@section('page-title', 'Edit Address Information')

@section('content')
    <div class="px-4 lg:px-6 py-6">
        <div class="max-w-4xl mx-auto">
            <nav class="mb-6">
                <a href="{{ route('citizen.profile.index') }}" class="text-sea-green hover:text-deep-forest font-semibold text-sm flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Profile
                </a>
            </nav>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-deep-forest to-sea-green px-6 sm:px-8 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-extrabold text-white">Edit Address Information</h1>
                            <p class="text-white/75 text-sm mt-0.5 hidden sm:block">Update your residential address details</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('citizen.profile.address.update') }}" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                            <p class="font-bold text-red-800 mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Purok/Street <span class="text-red-500">*</span></label>
                            <input type="text" name="purok" value="{{ old('purok', $resident->purok) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="e.g., Purok 1, Street Name">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Barangay <span class="text-red-500">*</span></label>
                            <input type="text" name="barangay" value="{{ old('barangay', $resident->barangay) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Municipality <span class="text-red-500">*</span></label>
                            <input type="text" name="municipality" value="{{ old('municipality', $resident->municipality ?? 'Buguey') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Province <span class="text-red-500">*</span></label>
                            <input type="text" name="province" value="{{ old('province', $resident->province ?? 'Cagayan') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                        </div>
                    </div>

                    @if($resident->isVisitor())
                        <div class="mt-6 bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl">
                            <p class="text-sm text-amber-800">
                                <strong>Note:</strong> After updating your address, please visit the Barangay Hall with a valid ID to verify your residency and unlock full e-services access.
                            </p>
                        </div>
                    @endif

                    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-slate-100">
                        <a href="{{ route('citizen.profile.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition text-center shadow-sm">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-deep-forest to-sea-green hover:from-sea-green hover:to-deep-forest text-white rounded-xl font-bold shadow-lg transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
