<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $service->name }} | Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans">

    <div class="min-h-screen p-8">
        <!-- Header -->
        <div class="max-w-5xl mx-auto mb-8">
            <a href="{{ route('admin.services.show', $service) }}" 
               class="inline-flex items-center gap-2 text-sea-green hover:text-deep-forest font-semibold mb-4">
                ← Back to Service Details
            </a>
            <h1 class="text-3xl font-bold text-deep-forest">✏️ Edit Service: {{ $service->name }}</h1>
            <p class="text-gray-600 mt-2">Update service details, requirements, and processing steps</p>
        </div>

        <!-- Form Content -->
        <form action="{{ route('admin.services.update', $service) }}" method="POST" class="max-w-5xl mx-auto">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-deep-forest mb-4 flex items-center gap-2">
                            <span>📋</span> Basic Information
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Service Name *</label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', $service->name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                       placeholder="e.g., MHO Health Certificate"
                                       required>
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Department *</label>
                                <select name="department" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                        required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ old('department', $service->department) == $dept ? 'selected' : '' }}>
                                            {{ $dept }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea name="description" 
                                          rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                          placeholder="Describe what this service provides...">{{ old('description', $service->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Classification *</label>
                                    <select name="classification" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                            required>
                                        @foreach($classifications as $classification)
                                            <option value="{{ $classification }}" {{ old('classification', $service->classification) == $classification ? 'selected' : '' }}>
                                                {{ $classification }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                                    <select name="type" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                            required>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ old('type', $service->type) == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Who May Avail</label>
                                <input type="text" 
                                       name="who_may_avail" 
                                       value="{{ old('who_may_avail', $service->who_may_avail) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                       placeholder="e.g., All residents of Buguey">
                            </div>
                        </div>
                    </div>

                    <!-- Fees and Processing Time -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-deep-forest mb-4 flex items-center gap-2">
                            <span>💰</span> Fees & Processing Time
                        </h2>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fee (₱)</label>
                                    <input type="number" 
                                           name="fee" 
                                           value="{{ old('fee', $service->fee) }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                           placeholder="0.00">
                                    <p class="text-xs text-gray-500 mt-1">Leave 0 for FREE services</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fee Description</label>
                                    <input type="text" 
                                           name="fee_description" 
                                           value="{{ old('fee_description', $service->fee_description) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                           placeholder="e.g., Variable, ₱50-₱150">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Processing Time (minutes)</label>
                                <input type="number" 
                                       name="processing_time_minutes" 
                                       value="{{ old('processing_time_minutes', $service->processing_time_minutes) }}"
                                       min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent"
                                       placeholder="e.g., 30, 60, 120">
                                <p class="text-xs text-gray-500 mt-1">Total time to complete the service</p>
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-deep-forest mb-4 flex items-center gap-2">
                            <span>📑</span> Required Documents
                        </h2>

                        <div class="mb-3 grid grid-cols-12 gap-3 text-xs font-semibold text-gray-600 uppercase px-1">
                            <span class="col-span-6">Document Name</span>
                            <span class="col-span-5">Where to Secure</span>
                            <span class="col-span-1 text-center">Required</span>
                        </div>

                        <div id="requirementsContainer" class="space-y-3">
                            @foreach($service->requirements as $index => $requirement)
                                <div class="requirement-item flex gap-3 items-start">
                                    <div class="flex-1 grid grid-cols-12 gap-3">
                                        <input type="text" 
                                               name="requirements[{{ $index }}][requirement]" 
                                               value="{{ $requirement->requirement }}"
                                               class="col-span-6 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green"
                                               placeholder="e.g., Valid Government ID">
                                        <input type="text" 
                                               name="requirements[{{ $index }}][where_to_secure]" 
                                               value="{{ $requirement->where_to_secure }}"
                                               class="col-span-5 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green"
                                               placeholder="e.g., PhilSys, Driver's License, Passport">
                                        <label class="col-span-1 flex items-center justify-center" title="Mark as required">
                                            <input type="checkbox" 
                                                   name="requirements[{{ $index }}][is_required]" 
                                                   value="1" 
                                                   {{ $requirement->is_required ? 'checked' : '' }}
                                                   class="w-5 h-5 rounded text-sea-green">
                                        </label>
                                    </div>
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-semibold transition" title="Remove this requirement">
                                        🗑️
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" 
                                onclick="addRequirement()"
                                class="mt-4 px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-semibold transition">
                            ➕ Add Requirement
                        </button>
                        
                        <p class="text-xs text-gray-500 mt-3">
                            💡 Tip: Add all documents residents need to submit. Uncheck "Required" for optional documents.
                        </p>
                    </div>

                    <!-- Processing Steps -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-deep-forest mb-4 flex items-center gap-2">
                            <span>📍</span> Processing Steps
                        </h2>

                        <div id="stepsContainer" class="space-y-3">
                            @foreach($service->steps as $index => $step)
                                <div class="step-item border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-sea-green">Step {{ $index + 1 }}</span>
                                        <button type="button" onclick="this.closest('.step-item').remove()" class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-sm">
                                            🗑️ Remove
                                        </button>
                                    </div>
                                    <input type="text" 
                                           name="steps[{{ $index }}][title]" 
                                           value="{{ $step->title }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-2"
                                           placeholder="Step title">
                                    <textarea name="steps[{{ $index }}][description]" 
                                              rows="2"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-2"
                                              placeholder="Step description">{{ $step->description }}</textarea>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" 
                                               name="steps[{{ $index }}][assigned_office]" 
                                               value="{{ $step->assigned_office }}"
                                               class="px-4 py-2 border border-gray-300 rounded-lg"
                                               placeholder="Assigned office">
                                        <input type="number" 
                                               name="steps[{{ $index }}][duration_minutes]" 
                                               value="{{ $step->duration_minutes }}"
                                               class="px-4 py-2 border border-gray-300 rounded-lg"
                                               placeholder="Duration (min)">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" 
                                onclick="addStep()"
                                class="mt-4 px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-semibold transition">
                            ➕ Add Step
                        </button>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Visual Settings -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 sticky top-8">
                        <h3 class="text-lg font-bold text-deep-forest mb-4">🎨 Visual Settings</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Icon (Emoji)</label>
                                <input type="text" 
                                       name="icon" 
                                       value="{{ old('icon', $service->icon) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg text-center text-2xl"
                                       placeholder="📄">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Color Theme</label>
                                <select name="color" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="sea-green" {{ old('color', $service->color) == 'sea-green' ? 'selected' : '' }}>Sea Green</option>
                                    <option value="deep-forest" {{ old('color', $service->color) == 'deep-forest' ? 'selected' : '' }}>Deep Forest</option>
                                    <option value="golden-glow" {{ old('color', $service->color) == 'golden-glow' ? 'selected' : '' }}>Golden Glow</option>
                                    <option value="blue" {{ old('color', $service->color) == 'blue' ? 'selected' : '' }}>Blue</option>
                                    <option value="purple" {{ old('color', $service->color) == 'purple' ? 'selected' : '' }}>Purple</option>
                                </select>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded text-sea-green">
                                    <span class="font-semibold text-gray-700">Service is Active</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2">Uncheck to make unavailable</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-sea-green hover:bg-deep-forest text-white px-6 py-3 rounded-lg font-bold transition shadow-sm">
                            💾 Update Service
                        </button>
                        <a href="{{ route('admin.services.show', $service) }}" 
                           class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-bold transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        let requirementIndex = {{ $service->requirements->count() }};
        let stepIndex = {{ $service->steps->count() }};

        function addRequirement() {
            const container = document.getElementById('requirementsContainer');
            const newRequirement = document.createElement('div');
            newRequirement.className = 'requirement-item flex gap-3 items-start';
            newRequirement.innerHTML = `
                <div class="flex-1 grid grid-cols-12 gap-3">
                    <input type="text" 
                           name="requirements[${requirementIndex}][requirement]" 
                           class="col-span-6 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green"
                           placeholder="e.g., Barangay Clearance">
                    <input type="text" 
                           name="requirements[${requirementIndex}][where_to_secure]" 
                           class="col-span-5 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green"
                           placeholder="e.g., From your barangay hall">
                    <label class="col-span-1 flex items-center justify-center" title="Mark as required">
                        <input type="checkbox" name="requirements[${requirementIndex}][is_required]" value="1" checked class="w-5 h-5 rounded text-sea-green">
                    </label>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-semibold transition" title="Remove this requirement">
                    🗑️
                </button>
            `;
            container.appendChild(newRequirement);
            requirementIndex++;
        }

        function addStep() {
            const container = document.getElementById('stepsContainer');
            const newStep = document.createElement('div');
            newStep.className = 'step-item border border-gray-200 rounded-lg p-4';
            newStep.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-sea-green">Step ${stepIndex + 1}</span>
                    <button type="button" onclick="this.closest('.step-item').remove()" class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-sm">
                        🗑️ Remove
                    </button>
                </div>
                <input type="text" 
                       name="steps[${stepIndex}][title]" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-2"
                       placeholder="Step title">
                <textarea name="steps[${stepIndex}][description]" 
                          rows="2"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-2"
                          placeholder="Step description"></textarea>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" 
                           name="steps[${stepIndex}][assigned_office]" 
                           class="px-4 py-2 border border-gray-300 rounded-lg"
                           placeholder="Assigned office">
                    <input type="number" 
                           name="steps[${stepIndex}][duration_minutes]" 
                           class="px-4 py-2 border border-gray-300 rounded-lg"
                           placeholder="Duration (min)">
                </div>
            `;
            container.appendChild(newStep);
            stepIndex++;
        }
    </script>

</body>
</html>
