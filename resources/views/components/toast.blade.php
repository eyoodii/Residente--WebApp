{{-- Toast Notification Component
     Professional UI/UX feedback for user actions
     Uses Alpine.js for smooth animations
--}}

{{-- Success Toast --}}
@if (session()->has('toast_success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-8"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 flex items-center bg-white border-l-4 border-green-500 shadow-xl rounded-lg px-6 py-4 min-w-[320px] max-w-md"
         role="alert">
        
        <div class="flex-shrink-0 w-10 h-10 bg-green-100 text-green-600 flex items-center justify-center rounded-full mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <div class="flex-1">
            <h4 class="text-gray-900 font-bold text-sm mb-1">Success</h4>
            <p class="text-gray-600 text-xs">{{ session('toast_success') }}</p>
        </div>

        <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

{{-- Error Toast --}}
@if (session()->has('toast_error'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 6000)" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-8"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 flex items-center bg-white border-l-4 border-red-500 shadow-xl rounded-lg px-6 py-4 min-w-[320px] max-w-md"
         role="alert">
        
        <div class="flex-shrink-0 w-10 h-10 bg-red-100 text-red-600 flex items-center justify-center rounded-full mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <div class="flex-1">
            <h4 class="text-gray-900 font-bold text-sm mb-1">Error</h4>
            <p class="text-gray-600 text-xs">{{ session('toast_error') }}</p>
        </div>

        <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

{{-- Warning Toast --}}
@if (session()->has('toast_warning'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-8"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 flex items-center bg-white border-l-4 border-yellow-500 shadow-xl rounded-lg px-6 py-4 min-w-[320px] max-w-md"
         role="alert">
        
        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-full mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        
        <div class="flex-1">
            <h4 class="text-gray-900 font-bold text-sm mb-1">Warning</h4>
            <p class="text-gray-600 text-xs">{{ session('toast_warning') }}</p>
        </div>

        <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

{{-- Info Toast --}}
@if (session()->has('toast_info'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 5000)" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-8"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 flex items-center bg-white border-l-4 border-blue-500 shadow-xl rounded-lg px-6 py-4 min-w-[320px] max-w-md"
         role="alert">
        
        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 flex items-center justify-center rounded-full mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <div class="flex-1">
            <h4 class="text-gray-900 font-bold text-sm mb-1">Information</h4>
            <p class="text-gray-600 text-xs">{{ session('toast_info') }}</p>
        </div>

        <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif
