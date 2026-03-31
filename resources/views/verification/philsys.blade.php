@extends('layouts.app')

@section('title', 'PhilSys Verification')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
@endpush

@section('content')
<!-- Back to Dashboard (only visible if already PhilSys-verified, e.g. admin manual re-check) -->
@if(auth()->user()->philsys_verified_at)
<div class="mb-6">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-sea-green transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Dashboard
    </a>
</div>
@else
<!-- Locked in: show logout option only -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-2">
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <svg class="w-4 h-4 text-tiger-orange" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
        </svg>
        <span>Complete verification to access the portal</span>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-sm text-gray-400 hover:text-gray-700 underline transition-colors">
            Log Out
        </button>
    </form>
</div>
@endif

<!-- Alert Boxes -->
@if (session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <svg class="h-5 w-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    </div>
@endif

<!-- Main Verification Card -->
<div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
    <!-- Page Title -->
    <div class="px-6 py-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900">PhilSys Identity Verification</h1>
        <p class="text-sm text-gray-600 mt-1">Verify your identity to access E-Services and request official documents</p>
    </div>
    
    <!-- Progress Timeline -->
    <div class="px-6 py-6 border-b border-gray-200 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between">
                <!-- Step 1: Upload Documents -->
                <div class="flex-1 flex flex-col items-center relative">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-sea-green to-deep-forest flex items-center justify-center mb-2 shadow-lg relative z-10 ring-4 ring-sea-green/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <span class="text-[10px] sm:text-xs font-bold text-sea-green uppercase tracking-wide text-center">Upload Docs</span>
                    <div class="absolute top-6 left-1/2 w-full h-1 bg-gradient-to-r from-sea-green to-gray-300" style="margin-left: 50%;"></div>
                </div>

                <!-- Step 2: Verifying -->
                <div class="flex-1 flex flex-col items-center relative">
                    <div id="step-verifying" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-300 flex items-center justify-center mb-2 relative z-10 transition-all duration-500 ring-4 ring-gray-200">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span id="step-verifying-text" class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide transition-colors duration-500">Verifying</span>
                    <div id="progress-line-1" class="absolute top-6 right-1/2 w-full h-1 bg-gray-300 transition-all duration-700" style="margin-right: 50%;"></div>
                    <div id="progress-line-2" class="absolute top-6 left-1/2 w-full h-1 bg-gray-300 transition-all duration-700" style="margin-left: 50%;"></div>
                </div>

                <!-- Step 3: Complete -->
                <div class="flex-1 flex flex-col items-center relative">
                    <div id="step-complete" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-300 flex items-center justify-center mb-2 relative z-10 transition-all duration-500 ring-4 ring-gray-200">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span id="step-complete-text" class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide transition-colors duration-500">Complete</span>
                    <div id="progress-line-3" class="absolute top-6 right-1/2 w-full h-1 bg-gray-300 transition-all duration-700" style="margin-right: 50%;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Section -->
    <div class="bg-gradient-to-r from-sea-green/10 to-deep-forest/10 px-6 py-4 border-b border-sea-green/20">
        <h2 class="text-lg font-semibold text-deep-forest mb-2">Why PhilSys Verification?</h2>
        <p class="text-gray-700 text-sm">
            PhilSys verification ensures that only legitimate residents can request official documents and certificates. 
            This protects against identity fraud and maintains the integrity of government services.
        </p>
    </div>

    <!-- Verification Form -->
    <div class="p-4 sm:p-6">
                <form method="POST" action="{{ route('verification.philsys.verify') }}" id="verification-form" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden Transaction ID field (populated by QR scanner) -->
                    <input type="hidden" name="transaction_id" id="transaction_id" value="">
                    
                    <!-- Hidden QR Data field (for audit trail) -->
                    <input type="hidden" name="qr_data" id="qr_data" value="">
                    
                    <!-- Hidden file inputs to hold the uploaded card images -->
                    <input type="file" name="card_front" id="card_front_input" class="hidden" accept="image/*">
                    <input type="file" name="card_back" id="card_back_input" class="hidden" accept="image/*">

                    <!-- Verification Method -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Verification Method
                        </label>
                        <select name="verification_type" id="verification_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-sea-green focus:border-sea-green sm:text-sm rounded-md" required>
                            <option value="">Select verification method</option>
                            <option value="qr_scan">QR Code Scan</option>
                            <option value="manual_verify">Manual Entry</option>
                        </select>
                        @error('verification_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- National ID -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            PhilSys National ID Number
                        </label>
                        <input 
                            type="text" 
                            name="national_id" 
                            id="national_id"
                            value="{{ old('national_id', auth()->user()->national_id) }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-sea-green focus:border-sea-green sm:text-sm"
                            placeholder="Enter your PhilSys ID number"
                            required
                        >
                        <p class="mt-1 text-xs text-gray-500">This should match the ID number on your PhilSys card</p>
                        @error('national_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Manual Entry Card Upload Section -->
                    <div id="manual-upload-section" class="mb-6 hidden">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-r">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>📸 Upload both sides of your PhilSys card</strong> and we'll automatically extract your ID number using OCR technology.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Grid for Front and Back Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- FRONT CARD -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    📄 Front of PhilSys Card <span class="text-red-600">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                                    <!-- Front Upload Area -->
                                    <div id="upload-area-front" class="text-center">
                                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="text-gray-600 text-xs mb-2">Upload front side</p>
                                        <label for="card-front-upload" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                            <svg class="h-4 w-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Choose
                                        </label>
                                        <input type="file" id="card-front-upload" accept="image/*" class="hidden">
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (Max 5MB)</p>
                                    </div>
                                    
                                    <!-- Front Preview -->
                                    <div id="image-preview-front" class="hidden">
                                        <div class="relative">
                                            <img id="card-image-preview-front" src="" alt="Front card preview" class="w-full rounded-lg mb-2">
                                            <button type="button" id="remove-front-btn" class="absolute top-1 right-1 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 shadow-lg">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="bg-green-50 border border-green-200 rounded p-2 text-center">
                                            <p class="text-green-700 text-xs font-medium">✓ Front uploaded</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- BACK CARD -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    📄 Back of PhilSys Card <span class="text-red-600">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                                    <!-- Back Upload Area -->
                                    <div id="upload-area-back" class="text-center">
                                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="text-gray-600 text-xs mb-2">Upload back side</p>
                                        <label for="card-back-upload" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                            <svg class="h-4 w-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Choose
                                        </label>
                                        <input type="file" id="card-back-upload" accept="image/*" class="hidden">
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (Max 5MB)</p>
                                    </div>
                                    
                                    <!-- Back Preview -->
                                    <div id="image-preview-back" class="hidden">
                                        <div class="relative">
                                            <img id="card-image-preview-back" src="" alt="Back card preview" class="w-full rounded-lg mb-2">
                                            <button type="button" id="remove-back-btn" class="absolute top-1 right-1 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 shadow-lg">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="bg-green-50 border border-green-200 rounded p-2 text-center">
                                            <p class="text-green-700 text-xs font-medium">✓ Back uploaded</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Scan Button (appears when both images uploaded) -->
                        <div id="scan-both-container" class="hidden mt-4">
                            <button type="button" id="scan-both-btn" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Scan Both Images to Extract ID
                            </button>
                        </div>
                        
                        <!-- Processing State -->
                        <div id="processing-state" class="hidden mt-4 text-center py-8 border-2 border-sea-green/30 rounded-lg bg-gradient-to-br from-deep-forest/5 to-sea-green/10 relative overflow-hidden">
                            <!-- Shimmer background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[ocrShimmer_2s_ease-in-out_infinite]"></div>

                            <!-- ID card scan animation -->
                            <div class="relative inline-block mb-4">
                                <div class="w-20 h-14 rounded-lg border-2 border-sea-green/40 bg-white shadow-md flex items-center justify-center relative overflow-hidden">
                                    <!-- Card icon -->
                                    <svg class="w-8 h-8 text-sea-green/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                                    </svg>
                                    <!-- Scan line sweeping -->
                                    <div class="absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-sea-green to-transparent animate-[scanSweep_1.5s_ease-in-out_infinite]"></div>
                                </div>
                                <!-- Pulsing ring -->
                                <div class="absolute -inset-2 rounded-xl border-2 border-sea-green/20 animate-[ocrPulse_2s_ease-in-out_infinite]"></div>
                            </div>

                            <p class="text-deep-forest font-semibold text-sm">Scanning card images...</p>
                            <p class="text-gray-500 text-xs mt-1">Extracting ID number using OCR</p>

                            <!-- Progress dots -->
                            <div class="flex justify-center gap-1.5 mt-3">
                                <span class="w-2 h-2 rounded-full bg-sea-green animate-[ocrDot_1.2s_ease-in-out_infinite]" style="animation-delay:0s"></span>
                                <span class="w-2 h-2 rounded-full bg-sea-green animate-[ocrDot_1.2s_ease-in-out_infinite]" style="animation-delay:0.2s"></span>
                                <span class="w-2 h-2 rounded-full bg-sea-green animate-[ocrDot_1.2s_ease-in-out_infinite]" style="animation-delay:0.4s"></span>
                            </div>
                        </div>
                        
                        <!-- Success State -->
                        <div id="scan-success-state" class="hidden mt-4 text-center py-6 border-2 border-green-200 rounded-lg bg-green-50">
                            <svg class="mx-auto h-12 w-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-green-600 font-medium mb-2">✅ ID Number Extracted Successfully!</p>
                            <p class="text-gray-600 text-sm">Please verify the ID number below is correct.</p>
                        </div>
                        
                        <!-- OCR Error Message -->
                        <div id="ocr-error-message" class="hidden mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-red-700 text-sm"></p>
                        </div>
                        
                        <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r">
                            <p class="text-yellow-700 text-xs">
                                <strong>💡 Tip:</strong> Upload both sides of your card. Ensure images are clear, well-lit, and the ID number is fully visible on the front.
                            </p>
                        </div>
                    </div>

                    <!-- QR Code Section (shown when QR scan is selected) -->
                    <div id="qr-section" class="mb-6 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Scan PhilSys QR Code
                        </label>
                        
                        <!-- Camera Preview -->
                        <div id="qr-camera-container" class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                            <!-- Initial State -->
                            <div id="qr-initial-state" class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <p class="text-gray-600 text-sm mb-4">Scan the QR code on the back of your PhilSys ID card</p>

                                <!-- Two options side by side -->
                                <div class="flex flex-col sm:flex-row justify-center gap-3">
                                    <!-- Live Camera Button -->
                                    <button type="button" id="start-camera-btn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Open Camera
                                    </button>

                                    <!-- Upload QR Photo Button -->
                                    <label for="qr-photo-upload" class="inline-flex items-center px-4 py-2 border border-sea-green shadow-sm text-sm font-medium rounded-md text-sea-green bg-white hover:bg-green-50 focus:outline-none cursor-pointer">
                                        <svg class="h-5 w-5 mr-2 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Upload QR Photo
                                    </label>
                                    <input type="file" id="qr-photo-upload" accept="image/*" class="hidden">
                                </div>

                                <p class="text-xs text-gray-400 mt-3">💡 No camera access? Take a clear photo of the QR code and upload it.</p>
                            </div>

                            
                            <!-- Video Preview -->
                            <div id="qr-video-container" class="hidden">
                                <div class="relative">
                                    <video id="qr-video" class="w-full rounded-lg" playsinline></video>
                                    <canvas id="qr-canvas" class="hidden"></canvas>
                                    
                                    <!-- Scanning Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="w-48 h-48 border-4 border-blue-500 rounded-lg shadow-lg animate-pulse"></div>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div id="qr-status" class="absolute top-4 left-4 px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-full">
                                        📷 Scanning...
                                    </div>
                                </div>
                                
                                <div class="mt-3 flex justify-center gap-2">
                                    <button type="button" id="stop-camera-btn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="h-5 w-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Stop Camera
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Success State -->
                            <div id="qr-success-state" class="hidden text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-green-600 font-medium mb-2">✅ QR Code Scanned Successfully!</p>
                                <p class="text-gray-600 text-sm">Your PhilSys information has been loaded.</p>
                            </div>
                        </div>
                        
                        <!-- Error Message -->
                        <div id="qr-error-message" class="hidden mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-red-700 text-sm"></p>
                        </div>
                    </div>

                    <!-- Card Storage & Security Information -->
                    <div class="mb-6">
                        <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-800">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                <strong>Security Assurance:</strong> Your uploaded ID cards are encrypted and securely stored in our protected database with restricted access. All data is handled in compliance with data privacy regulations.
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 border-t">
                        <button 
                            type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-sea-green to-deep-forest hover:from-sea-green/90 hover:to-deep-forest/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-green transition-all"
                        >
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Verify Identity
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-white shadow rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-sea-green mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><strong>QR Code Scan:</strong> Use your device camera to scan the QR code on your PhilSys ID card for instant verification.</span>
                </div>
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><strong>Manual Entry:</strong> Upload a clear photo of your PhilSys card and the system will automatically extract your ID number, or type it manually.</span>
                </div>
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-sea-green mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Your National ID must match the one registered in our system. Contact support if there's a mismatch.</span>
                </div>
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-sea-green mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>For best OCR results, ensure the card image is clear, well-lit, and the ID number is fully visible without glare or shadows.</span>
                </div>
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-sea-green mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>This verification process is secure and your data is encrypted according to Data Privacy Act of 2012.</span>
                </div>
            </div>
        </div>

{{-- PhilSys Upload Loader Overlay --}}
<div id="philsys-upload-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-gradient-to-br from-deep-forest via-[#022b1f] to-[#011a13] transition-all duration-500 opacity-0 invisible pointer-events-none">
    {{-- Subtle glow --}}
    <div class="absolute w-72 h-72 rounded-full bg-[radial-gradient(circle,rgba(0,129,72,0.15)_0%,transparent_70%)] pointer-events-none"></div>

    {{-- Animated ID card icon --}}
    <div class="relative mb-6" style="animation: loaderFadeUp 0.5s ease both;">
        {{-- Outer pulsing ring --}}
        <div class="absolute -inset-4 rounded-2xl border-2 border-golden-glow/20 animate-[ocrPulse_2s_ease-in-out_infinite]"></div>
        {{-- Card container --}}
        <div class="w-28 h-20 rounded-xl border-2 border-white/20 bg-white/10 backdrop-blur flex items-center justify-center relative overflow-hidden shadow-2xl">
            {{-- Card silhouette --}}
            <svg class="w-12 h-12 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
            </svg>
            {{-- Scan line --}}
            <div class="absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-golden-glow to-transparent animate-[scanSweep_1.5s_ease-in-out_infinite]"></div>
        </div>
    </div>

    {{-- Upload arrow animation --}}
    <div class="mb-5" style="animation: loaderFadeUp 0.5s ease 0.2s both;">
        <svg class="w-8 h-8 text-golden-glow animate-[uploadArrow_1.2s_ease-in-out_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
    </div>

    {{-- Text --}}
    <p class="text-white font-bold text-lg tracking-wide mb-1" style="font-family:'Figtree',sans-serif; animation: loaderFadeUp 0.5s ease 0.3s both;">
        Verifying Your PhilSys ID
    </p>
    <p id="upload-loader-status" class="text-white/50 text-sm mb-6 transition-opacity duration-300" style="font-family:'Figtree',sans-serif; animation: loaderFadeUp 0.5s ease 0.4s both;">
        Uploading documents & verifying identity...
    </p>

    {{-- Three-dot bounce --}}
    <div class="flex gap-1.5 mb-6" style="animation: loaderFadeUp 0.5s ease 0.5s both;">
        <span class="w-2.5 h-2.5 rounded-full bg-golden-glow block animate-[loaderDotBounce_1.2s_ease-in-out_infinite]" style="animation-delay:0s"></span>
        <span class="w-2.5 h-2.5 rounded-full bg-golden-glow block animate-[loaderDotBounce_1.2s_ease-in-out_infinite]" style="animation-delay:0.15s"></span>
        <span class="w-2.5 h-2.5 rounded-full bg-golden-glow block animate-[loaderDotBounce_1.2s_ease-in-out_infinite]" style="animation-delay:0.3s"></span>
    </div>

    {{-- Bottom progress bar --}}
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/5 overflow-hidden">
        <div class="h-full bg-gradient-to-r from-deep-forest via-golden-glow to-sea-green rounded animate-[loaderProgress_2s_ease-in-out_infinite]"></div>
    </div>
</div>

<style>
    /* PhilSys Upload Loader Animations */
    @keyframes scanSweep {
        0%   { top: 0; opacity: 0; }
        10%  { opacity: 1; }
        90%  { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    @keyframes ocrPulse {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50%      { opacity: 0.8; transform: scale(1.05); }
    }
    @keyframes ocrDot {
        0%, 80%, 100% { transform: scale(0.5); opacity: 0.3; }
        40%            { transform: scale(1);   opacity: 1; }
    }
    @keyframes ocrShimmer {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
    @keyframes uploadArrow {
        0%, 100% { transform: translateY(0); opacity: 0.7; }
        50%      { transform: translateY(-6px); opacity: 1; }
    }
    @keyframes loaderDotBounce {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.3; }
        40%            { transform: scale(1);   opacity: 1; }
    }
    @keyframes loaderFadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes loaderProgress {
        0%   { width: 0%; margin-left: 0; }
        50%  { width: 60%; margin-left: 20%; }
        100% { width: 0%; margin-left: 100%; }
    }
    #philsys-upload-loader.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Form References =====
        const verificationForm = document.getElementById('verification-form');
        const nationalIdInput = document.getElementById('national_id');
        const transactionIdInput = document.getElementById('transaction_id');
        const qrDataInput = document.getElementById('qr_data');
        const cardFrontInput = document.getElementById('card_front_input');
        const cardBackInput = document.getElementById('card_back_input');
        
        // ===== Progress Timeline Management =====
        const stepVerifying = document.getElementById('step-verifying');
        const stepVerifyingText = document.getElementById('step-verifying-text');
        const stepComplete = document.getElementById('step-complete');
        const stepCompleteText = document.getElementById('step-complete-text');
        const progressLine1 = document.getElementById('progress-line-1');
        const progressLine2 = document.getElementById('progress-line-2');
        const progressLine3 = document.getElementById('progress-line-3');
        
        function updateProgressStep(step) {
            if (step >= 2) {
                // Activate Verifying step with gradient and animated progress
                progressLine1.classList.remove('bg-gray-300');
                progressLine1.classList.add('bg-gradient-to-r', 'from-sea-green', 'to-golden-glow');
                
                stepVerifying.classList.remove('bg-gray-300', 'ring-gray-200');
                stepVerifying.classList.add('bg-gradient-to-br', 'from-sea-green', 'to-deep-forest', 'shadow-lg', 'ring-sea-green/20');
                stepVerifying.querySelector('svg').classList.remove('text-gray-500');
                stepVerifying.querySelector('svg').classList.add('text-white');
                
                stepVerifyingText.classList.remove('text-gray-500', 'font-medium');
                stepVerifyingText.classList.add('text-sea-green', 'font-bold');
            }
            
            if (step >= 3) {
                // Activate Complete step with success colors
                progressLine2.classList.remove('bg-gray-300');
                progressLine2.classList.add('bg-gradient-to-r', 'from-golden-glow', 'to-sea-green');
                
                progressLine3.classList.remove('bg-gray-300');
                progressLine3.classList.add('bg-gradient-to-l', 'from-gray-300', 'to-sea-green');
                
                stepComplete.classList.remove('bg-gray-300', 'ring-gray-200');
                stepComplete.classList.add('bg-gradient-to-br', 'from-sea-green', 'to-deep-forest', 'shadow-lg', 'ring-sea-green/20');
                stepComplete.querySelector('svg').classList.remove('text-gray-500');
                stepComplete.querySelector('svg').classList.add('text-white');
                
                stepCompleteText.classList.remove('text-gray-500', 'font-medium');
                stepCompleteText.classList.add('text-sea-green', 'font-bold');
            }
        }
        
        // Handle form submission for progress update
        if (verificationForm) {
            verificationForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Validate National ID format before submission
                const nationalId = nationalIdInput.value.trim();
                
                if (!nationalId || nationalId.length < 12) {
                    showFormError('Please enter a valid National ID number (minimum 12 digits).');
                    return false;
                }
                
                // Validate format via API
                try {
                    const response = await fetch('{{ route("verification.philsys.validate-format") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ national_id: nationalId }),
                    });
                    
                    const result = await response.json();
                    
                    if (!result.valid) {
                        showFormError(result.errors[0] || 'Invalid National ID format.');
                        return false;
                    }
                    
                    // Update the input with formatted value
                    if (result.formatted) {
                        nationalIdInput.value = result.formatted;
                    }
                    
                } catch (error) {
                    console.error('Validation error:', error);
                    // Continue with submission if validation API fails
                }
                
                // Update progress to verifying step
                updateProgressStep(2);

                // Show the full-screen upload loader
                const uploadLoader = document.getElementById('philsys-upload-loader');
                if (uploadLoader) {
                    uploadLoader.classList.add('active');

                    // Cycle status messages for visual feedback
                    const statusEl = document.getElementById('upload-loader-status');
                    const messages = [
                        'Uploading documents & verifying identity...',
                        'Encrypting & securing your data...',
                        'Validating PhilSys ID number...',
                        'Almost there, finalizing verification...',
                    ];
                    let msgIndex = 0;
                    const msgInterval = setInterval(() => {
                        msgIndex = (msgIndex + 1) % messages.length;
                        if (statusEl) {
                            statusEl.style.opacity = '0';
                            setTimeout(() => {
                                statusEl.textContent = messages[msgIndex];
                                statusEl.style.opacity = '1';
                            }, 300);
                        }
                    }, 2500);
                }

                // Show loading state on submit button
                const submitBtn = verificationForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Verifying Identity...
                `;
                
                // Submit the form
                verificationForm.submit();
            });
        }
        
        // Show form error message
        function showFormError(message) {
            // Remove existing error
            const existingError = document.getElementById('form-validation-error');
            if (existingError) existingError.remove();
            
            // Create error element
            const errorDiv = document.createElement('div');
            errorDiv.id = 'form-validation-error';
            errorDiv.className = 'mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded';
            errorDiv.innerHTML = `
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-700">${message}</p>
                </div>
            `;
            
            // Insert at the top of the form
            verificationForm.insertBefore(errorDiv, verificationForm.firstChild);
            
            // Scroll to error
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Auto-remove after 10 seconds
            setTimeout(() => {
                if (errorDiv.parentNode) errorDiv.remove();
            }, 10000);
        }
        
        // Show/hide sections based on verification method
        const verificationTypeSelect = document.getElementById('verification_type');
        const qrSection = document.getElementById('qr-section');
        const manualUploadSection = document.getElementById('manual-upload-section');
        
        console.log('PhilSys Verification initialized');
        console.log('Manual upload section found:', manualUploadSection ? 'Yes' : 'No');
        
        if (verificationTypeSelect) {
            verificationTypeSelect.addEventListener('change', function() {
                console.log('Verification method changed to:', this.value);
                
                // Hide all optional sections
                if (qrSection) qrSection.classList.add('hidden');
                if (manualUploadSection) manualUploadSection.classList.add('hidden');
                
                // Show relevant section
                if (this.value === 'qr_scan' && qrSection) {
                    console.log('Showing QR section');
                    qrSection.classList.remove('hidden');
                } else if (this.value === 'manual_verify' && manualUploadSection) {
                    console.log('Showing manual upload section');
                    manualUploadSection.classList.remove('hidden');
                } else {
                    stopCamera(); // Stop camera if user switches away
                }
            });
        }
        
        // QR Code Scanner Implementation
        let videoStream = null;
        let scanning = false;
        
        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const startBtn = document.getElementById('start-camera-btn');
        const stopBtn = document.getElementById('stop-camera-btn');
        const initialState = document.getElementById('qr-initial-state');
        const videoContainer = document.getElementById('qr-video-container');
        const successState = document.getElementById('qr-success-state');
        const qrStatus = document.getElementById('qr-status');
        const errorMessage = document.getElementById('qr-error-message');
        // nationalIdInput and transactionIdInput already declared above — reusing them here for QR scan

        // Start Camera
        if (startBtn) {
            startBtn.addEventListener('click', async function() {
                try {
                    // Request camera access
                    videoStream = await navigator.mediaDevices.getUserMedia({ 
                        video: { facingMode: 'environment' } // Use back camera on mobile
                    });
                    
                    video.srcObject = videoStream;
                    video.setAttribute('playsinline', true);
                    video.play();
                    
                    // Show video container
                    initialState.classList.add('hidden');
                    videoContainer.classList.remove('hidden');
                    successState.classList.add('hidden');
                    errorMessage.classList.add('hidden');
                    
                    // Start scanning
                    scanning = true;
                    requestAnimationFrame(scanQRCode);
                    
                } catch (error) {
                    console.error('Camera access error:', error);
                    showError('📷 Camera access denied or unavailable. Please use the "Upload QR Photo" option instead.');
                }
            });
        }
        
        // Stop Camera
        if (stopBtn) stopBtn.addEventListener('click', stopCamera);

        // ===== QR Photo Upload (fallback for when camera is unavailable) =====
        const qrPhotoUpload = document.getElementById('qr-photo-upload');
        const qrImageCanvas = document.createElement('canvas');

        if (qrPhotoUpload) {
            qrPhotoUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const img = new Image();
                const reader = new FileReader();

                reader.onload = function(evt) {
                    img.onload = function() {
                        if (typeof jsQR === 'undefined') {
                            showError('QR scanner library not loaded. Please refresh and try again.');
                            return;
                        }

                        // Try multiple resolutions and preprocessing to find QR code
                        const attempts = [];

                        // Helper: draw image to canvas at given max dimension and return ImageData
                        function getImageData(maxDim, enhanceContrast) {
                            const scale = Math.min(1, maxDim / Math.max(img.width, img.height));
                            const w = Math.round(img.width * scale);
                            const h = Math.round(img.height * scale);
                            qrImageCanvas.width = w;
                            qrImageCanvas.height = h;
                            const ctx = qrImageCanvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, w, h);

                            if (enhanceContrast) {
                                const data = ctx.getImageData(0, 0, w, h);
                                const d = data.data;
                                // Convert to grayscale and boost contrast
                                for (let i = 0; i < d.length; i += 4) {
                                    let gray = 0.299 * d[i] + 0.587 * d[i+1] + 0.114 * d[i+2];
                                    // Increase contrast
                                    gray = gray < 128 ? Math.max(0, gray * 0.5) : Math.min(255, 128 + (gray - 128) * 2);
                                    d[i] = d[i+1] = d[i+2] = gray;
                                }
                                ctx.putImageData(data, 0, 0);
                            }

                            return ctx.getImageData(0, 0, w, h);
                        }

                        // Attempt 1: Original size (capped at 1500px)
                        const sizes = [1500, 1000, 800, 600];
                        const options = { inversionAttempts: 'attemptBoth' };
                        let code = null;

                        for (const size of sizes) {
                            // Normal
                            let imageData = getImageData(size, false);
                            code = jsQR(imageData.data, imageData.width, imageData.height, options);
                            if (code) break;

                            // Enhanced contrast
                            imageData = getImageData(size, true);
                            code = jsQR(imageData.data, imageData.width, imageData.height, options);
                            if (code) break;
                        }

                        // Attempt with rotations if still not found (phone orientation)
                        if (!code) {
                            for (const angle of [90, 180, 270]) {
                                const maxDim = 1000;
                                const scale = Math.min(1, maxDim / Math.max(img.width, img.height));
                                const w = Math.round(img.width * scale);
                                const h = Math.round(img.height * scale);
                                const rw = (angle === 90 || angle === 270) ? h : w;
                                const rh = (angle === 90 || angle === 270) ? w : h;
                                qrImageCanvas.width = rw;
                                qrImageCanvas.height = rh;
                                const ctx = qrImageCanvas.getContext('2d');
                                ctx.translate(rw / 2, rh / 2);
                                ctx.rotate((angle * Math.PI) / 180);
                                ctx.drawImage(img, -w / 2, -h / 2, w, h);
                                const imageData = ctx.getImageData(0, 0, rw, rh);
                                code = jsQR(imageData.data, imageData.width, imageData.height, options);
                                if (code) break;
                            }
                        }

                        if (code) {
                            handleQRCodeDetected(code.data);
                        } else {
                            showError('No QR code found in the image. Try these tips:\n• Crop the photo to focus on just the QR code area\n• Ensure the QR code is not blurry, obstructed, or at an angle\n• Use good lighting without glare or shadows\n• Try uploading a different photo of the QR code');
                        }
                    };
                    img.src = evt.target.result;
                };
                reader.readAsDataURL(file);

                // Reset input so same file can be re-uploaded if needed
                qrPhotoUpload.value = '';
            });
        }

        function stopCamera() {
            scanning = false;
            
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            
            if (video) {
                video.srcObject = null;
            }
            
            // Reset UI
            if (initialState && videoContainer && successState) {
                videoContainer.classList.add('hidden');
                initialState.classList.remove('hidden');
                successState.classList.add('hidden');
            }
        }
        
        // Scan QR Code
        function scanQRCode() {
            if (!scanning || !video.readyState === video.HAVE_ENOUGH_DATA) {
                if (scanning) requestAnimationFrame(scanQRCode);
                return;
            }
            
            // Set canvas dimensions
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            
            // Check if jsQR is loaded
            if (typeof jsQR !== 'undefined') {
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert',
                });
                
                if (code) {
                    // QR Code detected!
                    handleQRCodeDetected(code.data);
                    return;
                }
            }
            
            // Continue scanning
            if (scanning) {
                requestAnimationFrame(scanQRCode);
            }
        }
        
        // Handle QR Code Detection
        function handleQRCodeDetected(qrData) {
            console.log('QR Code detected:', qrData);
            
            // Store the raw QR data for audit trail
            qrDataInput.value = qrData;
            
            // Update status
            qrStatus.textContent = '✅ QR Code Detected!';
            qrStatus.classList.remove('bg-blue-600');
            qrStatus.classList.add('bg-green-600');
            
            // Stop scanning
            scanning = false;
            
            // Use our API to parse the QR code data
            fetch('{{ route("verification.philsys.parse-qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ qr_data: qrData }),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    // Populate form fields from parsed data
                    if (result.data.national_id) {
                        nationalIdInput.value = result.data.national_id;
                        
                        // Validate the format immediately
                        validateNationalIdFormat(result.data.national_id);
                    }
                    
                    // Check if data matches resident record
                    if (result.match_result) {
                        if (result.match_result.matched) {
                            showQrSuccess('✅ QR code data verified! All information matches your profile.');
                        } else {
                            const warnings = result.match_result.mismatches.join(', ');
                            showError('⚠️ Data mismatch detected: ' + warnings + '. Please verify your information.');
                        }
                    } else {
                        showQrSuccess('✅ PhilSys QR code scanned successfully!');
                    }
                    
                    // Update progress to verifying
                    updateProgressStep(2);
                    
                    // Show success state
                    setTimeout(() => {
                        videoContainer.classList.add('hidden');
                        successState.classList.remove('hidden');
                        stopCamera();
                    }, 1000);
                } else {
                    // Fall back to client-side parsing
                    handleFallbackParsing(qrData);
                }
            })
            .catch(error => {
                console.error('API error:', error);
                handleFallbackParsing(qrData);
            });
        }
        
        // Fallback QR parsing when API fails
        function handleFallbackParsing(qrData) {
            try {
                let parsedData;
                try {
                    parsedData = JSON.parse(qrData);
                } catch {
                    // If not JSON, treat as plain National ID
                    parsedData = { national_id: qrData.trim() };
                }
                
                if (parsedData.national_id || parsedData.pcn) {
                    nationalIdInput.value = parsedData.national_id || parsedData.pcn;
                    validateNationalIdFormat(nationalIdInput.value);
                }
                
                if (parsedData.transaction_id) {
                    transactionIdInput.value = parsedData.transaction_id;
                }
                
                updateProgressStep(2);
                
                setTimeout(() => {
                    videoContainer.classList.add('hidden');
                    successState.classList.remove('hidden');
                    stopCamera();
                }, 1000);
            } catch (e) {
                showError('Invalid QR code format. Please try again or use manual entry.');
                stopCamera();
            }
        }
        
        // Helper to show QR success message
        function showQrSuccess(message) {
            successState.querySelector('p.text-green-600').textContent = message;
        }
        
        // Real-time National ID format validation
        let validationTimeout = null;
        function validateNationalIdFormat(nationalId) {
            // Clear previous timeout
            if (validationTimeout) {
                clearTimeout(validationTimeout);
            }
            
            // Debounce the API call
            validationTimeout = setTimeout(() => {
                if (!nationalId || nationalId.length < 12) return;
                
                fetch('{{ route("verification.philsys.validate-format") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ national_id: nationalId }),
                })
                .then(response => response.json())
                .then(result => {
                    updateValidationUI(result);
                })
                .catch(error => {
                    console.error('Validation error:', error);
                });
            }, 300);
        }
        
        // Update UI based on validation result
        function updateValidationUI(result) {
            const validationIndicator = document.getElementById('national-id-validation');
            
            if (!validationIndicator) {
                // Create validation indicator if it doesn't exist
                const indicator = document.createElement('div');
                indicator.id = 'national-id-validation';
                indicator.className = 'mt-1 text-sm flex items-center';
                nationalIdInput.parentNode.appendChild(indicator);
            }
            
            const indicator = document.getElementById('national-id-validation');
            
            if (result.valid) {
                indicator.innerHTML = `
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-600">Valid PhilSys ID format</span>
                `;
                nationalIdInput.classList.remove('border-red-500', 'ring-red-500');
                nationalIdInput.classList.add('border-green-500', 'ring-green-500');
                
                // Update value with formatted version
                if (result.formatted) {
                    nationalIdInput.value = result.formatted;
                }
            } else {
                indicator.innerHTML = `
                    <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-600">${result.errors[0] || 'Invalid format'}</span>
                `;
                nationalIdInput.classList.remove('border-green-500', 'ring-green-500');
                nationalIdInput.classList.add('border-red-500', 'ring-red-500');
            }
        }
        
        // Attach real-time validation to input
        if (nationalIdInput) {
            nationalIdInput.addEventListener('input', function(e) {
                const value = e.target.value;
                
                // Reset styling
                if (value.length < 12) {
                    const indicator = document.getElementById('national-id-validation');
                    if (indicator) {
                        indicator.innerHTML = '';
                    }
                    nationalIdInput.classList.remove('border-green-500', 'ring-green-500', 'border-red-500', 'ring-red-500');
                    return;
                }
                
                validateNationalIdFormat(value);
            });
            
            // Also validate on blur
            nationalIdInput.addEventListener('blur', function(e) {
                const value = e.target.value;
                if (value.length >= 12) {
                    validateNationalIdFormat(value);
                }
            });
        }
        
        // Show Error Message
        function showError(message) {
            errorMessage.querySelector('p').textContent = message;
            errorMessage.classList.remove('hidden');
            
            setTimeout(() => {
                errorMessage.classList.add('hidden');
            }, 5000);
        }
        
        // Clean up on page unload
        window.addEventListener('beforeunload', stopCamera);
        
        // ===== Manual Upload & OCR Functionality (Front and Back) =====
        const cardFrontUpload = document.getElementById('card-front-upload');
        const cardBackUpload = document.getElementById('card-back-upload');
        const uploadAreaFront = document.getElementById('upload-area-front');
        const uploadAreaBack = document.getElementById('upload-area-back');
        const imagePreviewFront = document.getElementById('image-preview-front');
        const imagePreviewBack = document.getElementById('image-preview-back');
        const cardImagePreviewFront = document.getElementById('card-image-preview-front');
        const cardImagePreviewBack = document.getElementById('card-image-preview-back');
        const removeFrontBtn = document.getElementById('remove-front-btn');
        const removeBackBtn = document.getElementById('remove-back-btn');
        const scanBothContainer = document.getElementById('scan-both-container');
        const scanBothBtn = document.getElementById('scan-both-btn');
        const processingState = document.getElementById('processing-state');
        const scanSuccessState = document.getElementById('scan-success-state');
        const ocrErrorMessage = document.getElementById('ocr-error-message');
        
        let frontCardFile = null;
        let backCardFile = null;
        
        // Handle FRONT card file selection
        if (cardFrontUpload) {
            cardFrontUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (!file.type.match('image.*')) {
                    showOCRError('Please upload a valid image file (JPG, PNG)');
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    showOCRError('Front image is too large. Max 5MB.');
                    return;
                }
                
                frontCardFile = file;
                
                // Copy file to hidden form input for submission
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                cardFrontInput.files = dataTransfer.files;
                
                previewFrontImage(file);
                checkBothUploaded();
            });
        }
        
        // Handle BACK card file selection
        if (cardBackUpload) {
            cardBackUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (!file.type.match('image.*')) {
                    showOCRError('Please upload a valid image file (JPG, PNG)');
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    showOCRError('Back image is too large. Max 5MB.');
                    return;
                }
                
                backCardFile = file;
                
                // Copy file to hidden form input for submission
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                cardBackInput.files = dataTransfer.files;
                
                previewBackImage(file);
                checkBothUploaded();
            });
        }
        
        // Preview FRONT image
        function previewFrontImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cardImagePreviewFront.src = e.target.result;
                uploadAreaFront.classList.add('hidden');
                imagePreviewFront.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
        
        // Preview BACK image
        function previewBackImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cardImagePreviewBack.src = e.target.result;
                uploadAreaBack.classList.add('hidden');
                imagePreviewBack.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
        
        // Remove FRONT image
        if (removeFrontBtn) {
            removeFrontBtn.addEventListener('click', function() {
                frontCardFile = null;
                cardFrontUpload.value = '';
                cardImagePreviewFront.src = '';
                imagePreviewFront.classList.add('hidden');
                uploadAreaFront.classList.remove('hidden');
                
                // Clear the hidden form input
                cardFrontInput.value = '';
                
                checkBothUploaded();
            });
        }
        
        // Remove BACK image
        if (removeBackBtn) {
            removeBackBtn.addEventListener('click', function() {
                backCardFile = null;
                cardBackUpload.value = '';
                cardImagePreviewBack.src = '';
                imagePreviewBack.classList.add('hidden');
                uploadAreaBack.classList.remove('hidden');
                
                // Clear the hidden form input
                cardBackInput.value = '';
                
                checkBothUploaded();
            });
        }
        
        // Check if both images uploaded
        function checkBothUploaded() {
            if (frontCardFile && backCardFile && scanBothContainer) {
                scanBothContainer.classList.remove('hidden');
                processingState.classList.add('hidden');
                scanSuccessState.classList.add('hidden');
            } else if (scanBothContainer) {
                scanBothContainer.classList.add('hidden');
            }
        }
        
        // Scan BOTH images using OCR
        if (scanBothBtn) {
            scanBothBtn.addEventListener('click', async function() {
                if (!frontCardFile || !backCardFile) {
                    showOCRError('Please upload both front and back card images.');
                    return;
                }
                
                // Show processing state
                scanBothContainer.classList.add('hidden');
                processingState.classList.remove('hidden');
                
                try {
                    // Check if Tesseract is loaded
                    if (typeof Tesseract === 'undefined') {
                        throw new Error('OCR library not loaded. Please refresh the page.');
                    }
                    
                    // Scan FRONT card first (ID is typically on front)
                    console.log('Scanning front card...');
                    const frontResult = await Tesseract.recognize(
                        frontCardFile,
                        'eng',
                        {
                            logger: info => console.log('Front:', info)
                        }
                    );
                    
                    const frontText = frontResult.data.text;
                    console.log('Front card text:', frontText);
                    
                    // Try to extract ID from front
                    const idPattern = /\b\d{4}[-\s]?\d{4}[-\s]?\d{4}\b/g;
                    let matches = frontText.match(idPattern);
                    
                    // If not found on front, try back card
                    if (!matches || matches.length === 0) {
                        console.log('ID not found on front, scanning back card...');
                        const backResult = await Tesseract.recognize(
                            backCardFile,
                            'eng',
                            {
                                logger: info => console.log('Back:', info)
                            }
                        );
                        
                        const backText = backResult.data.text;
                        console.log('Back card text:', backText);
                        matches = backText.match(idPattern);
                    }
                    
                    if (matches && matches.length > 0) {
                        // Clean and format the ID
                        let extractedId = matches[0].replace(/\s/g, '-');
                        
                        // Populate the national ID field
                        nationalIdInput.value = extractedId;
                        
                        // Validate the extracted ID via API
                        try {
                            const response = await fetch('{{ route("verification.philsys.validate-format") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ national_id: extractedId }),
                            });
                            
                            const result = await response.json();
                            
                            if (result.valid) {
                                // Update with properly formatted ID
                                if (result.formatted) {
                                    nationalIdInput.value = result.formatted;
                                }
                                
                                // Update progress to verifying
                                updateProgressStep(2);
                                
                                // Show success state with checksum info
                                processingState.classList.add('hidden');
                                scanSuccessState.classList.remove('hidden');
                                
                                const statusText = result.checksum_valid 
                                    ? '✅ Valid PhilSys ID extracted and verified!'
                                    : '⚠️ ID extracted but checksum could not be verified.';
                                
                                scanSuccessState.querySelector('p.text-green-600').textContent = statusText;
                                
                                // Highlight the input field
                                nationalIdInput.classList.add('ring-2', 'ring-green-500', 'border-green-500');
                                setTimeout(() => {
                                    nationalIdInput.classList.remove('ring-2', 'ring-green-500', 'border-green-500');
                                }, 3000);
                                
                            } else {
                                // Show warning but allow manual correction
                                processingState.classList.add('hidden');
                                scanBothContainer.classList.remove('hidden');
                                showOCRError('Extracted ID may be invalid: ' + (result.errors[0] || 'Unknown error') + '. Please verify or correct manually.');
                            }
                            
                        } catch (validationError) {
                            console.error('Validation API error:', validationError);
                            // Continue showing success even if API fails
                            updateProgressStep(2);
                            processingState.classList.add('hidden');
                            scanSuccessState.classList.remove('hidden');
                        }
                        
                    } else {
                        throw new Error('Could not find a valid National ID number in either card image. Please ensure the ID is clearly visible and try again, or enter it manually.');
                    }
                    
                } catch (error) {
                    console.error('OCR error:', error);
                    processingState.classList.add('hidden');
                    scanBothContainer.classList.remove('hidden');
                    showOCRError(error.message || 'Failed to scan ID cards. Please try again with clearer images or enter the ID manually.');
                }
            });
        }
        
        // Show OCR Error Message
        function showOCRError(message) {
            if (ocrErrorMessage) {
                ocrErrorMessage.querySelector('p').textContent = message;
                ocrErrorMessage.classList.remove('hidden');
                
                setTimeout(() => {
                    ocrErrorMessage.classList.add('hidden');
                }, 7000);
            }
        }
    });
</script>
@endpush
@endsection
