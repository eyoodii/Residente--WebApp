<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | RESIDENTE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased font-sans min-h-screen flex flex-col items-center justify-center px-4 py-12">
    @include('partials.loader')

    <!-- Card -->
    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-14 h-14 object-contain rounded-2xl shadow-lg mb-3 bg-white">
            <h1 class="text-2xl font-extrabold text-deep-forest tracking-tight">RESIDENTE</h1>
            <p class="text-sm text-gray-400 mt-0.5">Municipality of Buguey — e-Governance Portal</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-deep-forest px-8 py-5">
                <h2 class="text-white font-bold text-lg leading-tight">Verify Your Email Address</h2>
                <p class="text-gray-300 text-xs mt-0.5">One more step to activate your account</p>
            </div>

            <!-- Card Body -->
            <div class="px-8 py-6">

                <p class="text-sm text-gray-600 leading-relaxed mb-5">
                    Welcome! Before you can access the portal, please verify your email address by clicking the link we sent to your inbox.
                </p>

                <!-- Verification Steps -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5">
                    <p class="text-xs font-bold text-amber-800 uppercase tracking-wide mb-3">📋 Account Activation Process</p>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-tiger-orange text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Email Verification</p>
                                <p class="text-xs text-gray-500">Click the link sent to your registered email address</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-tiger-orange text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Residency Verification</p>
                                <p class="text-xs text-gray-500">Visit your Barangay Hall with a valid ID to unlock full access to e-services</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('status') === 'verification-link-sent')
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 font-medium">
                        ✅ A new verification link has been sent to your email address.
                    </div>
                @endif

                <p class="text-xs text-gray-400 italic text-center mb-5">
                    Both steps are required to access online services and request documents.
                </p>

                <!-- Actions -->
                <div class="flex items-center justify-between gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="px-5 py-2.5 bg-tiger-orange text-white text-sm font-bold rounded-xl hover:bg-burnt-tangerine transition-colors shadow-sm">
                            📧 Resend Email
                        </button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-400 hover:text-gray-700 underline transition-colors">
                            Log Out
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} Municipality of Buguey, Cagayan</p>
    </div>

</body>
</html>
