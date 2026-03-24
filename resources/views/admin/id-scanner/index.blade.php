@extends('layouts.admin')

@section('title', 'ID Scanner')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ID Document Scanner</h1>
            <p class="text-gray-600 mt-2">Upload and scan ID documents to automatically extract information</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Upload Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Upload Document</h2>
                
                <!-- File Upload Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select ID Document
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="id-document-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input 
                                        id="id-document-upload" 
                                        name="document" 
                                        type="file" 
                                        class="sr-only" 
                                        accept="image/jpeg,image/png,image/jpg"
                                        data-id-scanner
                                        data-document-type="id_card"
                                    >
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                        </div>
                    </div>
                </div>

                <!-- Document Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Document Type
                    </label>
                    <select id="document-type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="id_card">ID Card</option>
                        <option value="passport">Passport</option>
                        <option value="driver_license">Driver's License</option>
                    </select>
                </div>

                <!-- Preview Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Preview
                    </label>
                    <div data-preview class="min-h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <p class="text-gray-400">No image selected</p>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div data-loader class="hidden text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-sm text-gray-600">Processing document...</p>
                </div>
            </div>

            <!-- Results Section -->
            <div>
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Extracted Information</h2>
                    <div data-extracted-data>
                        <div class="text-center py-12 text-gray-400">
                            <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>Upload a document to see extracted data</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-3">Quick Tips</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Ensure the document is well-lit and in focus</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Avoid glare or reflections on the document</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Capture the entire document in the frame</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Supported formats: JPG, PNG (max 10MB)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Scans -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Scans</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No scans yet. Upload a document to get started.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/id-scanner.js') }}"></script>
<script>
    // Update document type when selection changes
    document.getElementById('document-type').addEventListener('change', function(e) {
        const input = document.getElementById('id-document-upload');
        input.dataset.documentType = e.target.value;
    });
</script>
@endpush
