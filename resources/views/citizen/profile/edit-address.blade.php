<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address Information | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans min-h-screen">

    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('citizen.profile.index') }}" class="text-sea-green hover:text-deep-forest font-bold text-sm flex items-center gap-1">
                    ← Back to Profile
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-deep-forest px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Edit Address Information</h1>
                </div>

                <form method="POST" action="{{ route('citizen.profile.address.update') }}" class="p-6">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <p class="font-bold text-red-800 mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Purok/Street -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Purok/Street <span class="text-red-500">*</span></label>
                            <input type="text" name="purok" value="{{ old('purok', $resident->purok) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Barangay -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Barangay <span class="text-red-500">*</span></label>
                            <input type="text" name="barangay" value="{{ old('barangay', $resident->barangay) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Municipality -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Municipality <span class="text-red-500">*</span></label>
                            <input type="text" name="municipality" value="{{ old('municipality', $resident->municipality ?? 'Buguey') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Province -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Province <span class="text-red-500">*</span></label>
                            <input type="text" name="province" value="{{ old('province', $resident->province ?? 'Cagayan') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                    </div>

                    @if($resident->isVisitor())
                        <div class="mt-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                            <p class="text-sm text-amber-800">
                                <strong>Note:</strong> After updating your address, please visit the Barangay Hall with a valid ID to verify your residency and unlock full e-services access.
                            </p>
                        </div>
                    @endif

                    <div class="mt-8 flex gap-4 justify-end">
                        <a href="{{ route('citizen.profile.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-tiger-orange hover:bg-burnt-tangerine text-white rounded-lg font-bold shadow transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
