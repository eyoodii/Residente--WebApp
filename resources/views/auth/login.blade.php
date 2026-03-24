<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased font-sans flex min-h-screen">
    @include('partials.loader')

    <!-- Left Panel - Branding (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-deep-forest relative flex-col justify-center px-12 xl:px-24 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-sea-green opacity-10 transform -skew-x-12 scale-150"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-golden-glow rounded-full opacity-20 blur-3xl"></div>

        <div class="relative z-10 text-white">
            <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-32 h-32 object-contain rounded-full shadow-lg mb-8 bg-white">
            <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tight mb-4">
                Municipality of Buguey
            </h1>
            <h2 class="text-2xl text-golden-glow font-bold mb-6">
                RESIDENTE Digital Portal
            </h2>
            <p class="text-gray-300 text-lg leading-relaxed max-w-lg">
                Access your digital records, request barangay clearances, and stay updated with the latest networked transactions and e-services in our municipality.
            </p>
        </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border-t-4 border-tiger-orange relative">
            
            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center items-center gap-3 mb-8">
                <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full shadow-md bg-white">
                <h2 class="text-2xl font-extrabold text-deep-forest">RESIDENTE</h2>
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900">Welcome Back</h3>
                <p class="text-sm text-gray-500 mt-2">Please enter your credentials to access your dashboard.</p>
            </div>

            <!-- Error Display -->
            @error('email')
                <div class="mb-6 bg-burnt-tangerine bg-opacity-10 border-l-4 border-burnt-tangerine p-4 rounded-r-md flex items-center">
                    <span class="text-xl mr-3 text-burnt-tangerine">⚠️</span>
                    <p class="text-sm text-burnt-tangerine font-bold">{{ $message }}</p>
                </div>
            @enderror

            <!-- Login Form -->
            <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1 relative">
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-sea-green focus:border-sea-green sm:text-sm transition">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required autocomplete="current-password" class="password-input appearance-none block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-sea-green focus:border-sea-green sm:text-sm transition">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg id="password-eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="password-eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path id="password-eye-open-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path id="password-eye-closed" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-sea-green focus:ring-sea-green border-gray-300 rounded cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm font-medium text-gray-900 cursor-pointer">
                            Keep me logged in
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-semibold text-tiger-orange hover:text-burnt-tangerine transition">Forgot your password?</a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button id="submitButton" type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-lg shadow-md text-lg font-bold text-white bg-deep-forest hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-deep-forest transition transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="buttonText">LOG IN</span>
                        <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Registration Link -->
            <div class="mt-8 text-center pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    Wala ka pa bang account? 
                    <a href="{{ route('register') }}" class="font-bold text-tiger-orange hover:text-burnt-tangerine transition uppercase tracking-wide ml-1">Mag Register</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === "password" ? "text" : "password";
        }

        function togglePasswordVisibility(fieldId) {
            const input = document.getElementById(fieldId);
            const eyeOpen = document.getElementById(fieldId + '-eye-open');
            const eyeOpen2 = document.getElementById(fieldId + '-eye-open-2');
            const eyeClosed = document.getElementById(fieldId + '-eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeOpen2.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeOpen2.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm')?.addEventListener('submit', function(e) {
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            // Show loading state
            submitButton.disabled = true;
            buttonText.textContent = 'LOGGING IN...';
            loadingSpinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
