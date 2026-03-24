<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'RESIDENTE App') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        .error-gradient {
            background: linear-gradient(135deg, #034732 0%, #008148 50%, #ef8a17 100%);
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">
    <div class="flex-grow flex items-center justify-center p-4">
        <div class="max-w-lg w-full text-center">
            <div class="mb-8">
                <div class="floating inline-block">
                    @yield('icon')
                </div>
            </div>
            
            <h1 class="text-8xl font-extrabold error-gradient bg-clip-text text-transparent mb-4">
                @yield('code')
            </h1>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                @yield('message')
            </h2>
            
            <p class="text-gray-600 mb-8">
                @yield('description')
            </p>
            
            <div class="space-y-4">
                @yield('actions')
            </div>
            
            @hasSection('suggestions')
            <div class="mt-8 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Helpful Links</h3>
                @yield('suggestions')
            </div>
            @endif
        </div>
    </div>
    
    <footer class="py-4 text-center text-xs text-gray-500">
        <span>&copy; {{ date('Y') }} Municipality of Buguey - RESIDENTE E-Governance System</span>
    </footer>
</body>
</html>
