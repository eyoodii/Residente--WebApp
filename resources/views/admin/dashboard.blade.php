<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-deep-forest text-white flex flex-col shadow-xl flex-shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-sea-green border-opacity-30">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-deep-forest font-bold mr-3 shadow-sm">
                <span class="text-xs">SP</span>
            </div>
            <span class="font-bold text-xl tracking-wide">ADMIN</span>
        </div>
        
        <div class="p-4 flex-1 overflow-y-auto">
            <p class="text-xs uppercase text-golden-glow font-bold tracking-wider mb-4 mt-2">Admin Menu</p>
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 bg-white bg-opacity-15 border-l-4 border-golden-glow text-deep-forest font-semibold rounded-r-lg transition-all duration-200">
                    <span class="text-base">📊</span>
                    <span class="text-sm">Dashboard</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-2.5 border-l-4 border-transparent hover:bg-white hover:bg-opacity-10 hover:border-sea-green text-gray-200 hover:text-white rounded-r-lg transition-all duration-200">
                    <span class="text-base">⚙️</span>
                    <span class="text-sm">Service Management</span>
                </a>
                <!-- Master Collections Dropdown -->
                <div x-data="{ expanded: false }">
                    <button @click="expanded = !expanded" class="flex items-center justify-between w-full px-4 py-2.5 border-l-4 border-transparent hover:bg-white hover:bg-opacity-10 hover:border-sea-green text-gray-200 hover:text-white rounded-r-lg transition-all duration-200 cursor-pointer">
                        <div class="flex items-center gap-3">
                            <span class="text-base">📊</span>
                            <span class="text-sm">Master Collections</span>
                        </div>
                        <span class="text-[10px] opacity-60 transition-transform duration-300" :class="{'rotate-180': expanded}">▼</span>
                    </button>

                    <div x-show="expanded" x-collapse class="mt-0.5 space-y-0.5 ml-4 border-l border-white border-opacity-10 pl-3">
                        <a href="{{ route('admin.master-collections') }}" class="block py-1.5 px-3 text-gray-400 hover:text-white rounded-lg transition-colors duration-150 text-sm">
                            Overview
                        </a>
                        <a href="{{ route('admin.barangay-overview') }}" class="block py-1.5 px-3 text-gray-400 hover:text-white rounded-lg transition-colors duration-150 text-sm">
                            Barangay Overview
                        </a>
                        <a href="{{ route('admin.validation-flags') }}" class="block py-1.5 px-3 text-gray-400 hover:text-white rounded-lg transition-colors duration-150 text-sm">
                            Validation Flags
                        </a>
                        <a href="{{ route('admin.data-collection.index') }}" class="block py-1.5 px-3 text-gray-400 hover:text-white rounded-lg transition-colors duration-150 text-sm">
                            Data Collection (HN→HHN→HHM)
                        </a>
                        <a href="{{ route('admin.residents.index') }}" class="block py-1.5 px-3 text-gray-400 hover:text-white rounded-lg transition-colors duration-150 text-sm">
                            Resident Management
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 border-l-4 border-transparent hover:bg-white hover:bg-opacity-10 hover:border-sea-green text-gray-200 hover:text-white rounded-r-lg transition-all duration-200">
                    <span class="text-base">📝</span>
                    <span class="text-sm">Activity Logs</span>
                </a>
                <a href="{{ route('admin.households.index') }}" class="flex items-center gap-3 px-4 py-2.5 border-l-4 border-transparent hover:bg-white hover:bg-opacity-10 hover:border-sea-green text-gray-200 hover:text-white rounded-r-lg transition-all duration-200">
                    <span class="text-base">🏠</span>
                    <span class="text-sm">Household Management</span>
                </a>
                <a href="{{ route('admin.verification.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 border-l-4 border-transparent hover:bg-white hover:bg-opacity-10 hover:border-sea-green text-gray-200 hover:text-white rounded-r-lg transition-all duration-200">
                    <span class="text-base">✅</span>
                    <span class="text-sm">Verification Dashboard</span>
                </a>
            </nav>

            <nav class="space-y-2 mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition w-full text-left">
                        <span class="text-lg">🚪</span> Logout
                    </button>
                </form>
            </nav>
        </div>

        <div class="p-4 border-t border-sea-green border-opacity-30">
            <p class="text-xs text-golden-glow mb-1">Logged in as</p>
            <p class="text-sm font-bold truncate">{{ Auth::user()->full_name }}</p>
            <p class="text-xs text-gray-300">{{ ucfirst(Auth::user()->role) }}</p>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation Bar -->
        <header class="h-20 bg-white shadow-md flex items-center justify-between px-8 flex-shrink-0">
            <div>
                <h1 class="text-3xl font-bold text-deep-forest">Admin Dashboard</h1>
                <p class="text-sm text-gray-600">System Overview & Statistics</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">{{ now()->format('F d, Y') }}</span>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="flex-1 overflow-y-auto p-8">
            
            <!-- Statistics Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Residents -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-sea-green">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Residents</p>
                            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($totalResidents) }}</p>
                        </div>
                        <div class="bg-sea-green bg-opacity-10 p-3 rounded-lg">
                            <span class="text-2xl">👥</span>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-4 text-xs">
                        <div>
                            <span class="text-gray-500">Citizens:</span>
                            <span class="font-bold text-sea-green">{{ $citizenCount }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Visitors:</span>
                            <span class="font-bold text-tiger-orange">{{ $visitorCount }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Admins:</span>
                            <span class="font-bold text-deep-forest">{{ $adminCount }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Verification -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-tiger-orange">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Pending Verification</p>
                            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($pendingVerification) }}</p>
                        </div>
                        <div class="bg-tiger-orange bg-opacity-10 p-3 rounded-lg">
                            <span class="text-2xl">⏳</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">Visitors awaiting physical verification</p>
                </div>

                <!-- Service Requests -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-golden-glow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Service Requests</p>
                            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($totalRequests) }}</p>
                        </div>
                        <div class="bg-golden-glow bg-opacity-10 p-3 rounded-lg">
                            <span class="text-2xl">📋</span>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3 text-xs">
                        <div>
                            <span class="text-gray-500">Pending:</span>
                            <span class="font-bold">{{ $pendingRequests }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">In Progress:</span>
                            <span class="font-bold">{{ $inProgressRequests }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Ready:</span>
                            <span class="font-bold text-sea-green">{{ $readyForPickup }}</span>
                        </div>
                    </div>
                </div>

                <!-- Today's Activities -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-burnt-tangerine">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Today's Activities</p>
                            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($todayActivities) }}</p>
                        </div>
                        <div class="bg-burnt-tangerine bg-opacity-10 p-3 rounded-lg">
                            <span class="text-2xl">📊</span>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3 text-xs">
                        <div>
                            <span class="text-gray-500">Suspicious:</span>
                            <span class="font-bold text-burnt-tangerine">{{ $suspiciousActivities }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Critical:</span>
                            <span class="font-bold text-red-600">{{ $criticalActivities }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recent Registrations Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center gap-2">
                        <span>📈</span> Registration Trends
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Last 7 Days</span>
                                <span class="font-bold text-sea-green">{{ $recentRegistrations }} new</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-sea-green h-2 rounded-full" style="width: {{ min(($recentRegistrations / max($totalResidents, 1)) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Household Statistics -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center gap-2">
                        <span>🏘️</span> Household Overview
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Profiled Households</span>
                            <span class="text-2xl font-bold text-deep-forest">{{ number_format($householdsWithProfiles) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Low Income Households</span>
                            <span class="text-2xl font-bold text-tiger-orange">{{ number_format($lowIncomeHouseholds) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center justify-between">
                        <span class="flex items-center gap-2"><span>📝</span> Recent Activities</span>
                        <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-sea-green hover:underline">View All →</a>
                    </h3>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2 {{ $activity->severity === 'critical' ? 'bg-red-500' : ($activity->severity === 'warning' ? 'bg-tiger-orange' : 'bg-sea-green') }}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $activity->description }}</p>
                                <div class="flex gap-3 mt-1">
                                    <span class="text-xs text-gray-500">{{ $activity->user_email }}</span>
                                    <span class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 text-center py-8">No activities recorded yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Residents Needing Verification -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center justify-between">
                        <span class="flex items-center gap-2"><span>⏳</span> Pending Verification</span>
                        <a href="{{ route('admin.residents.index') }}?role=visitor" class="text-xs text-sea-green hover:underline">View All →</a>
                    </h3>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($residentsNeedingVerification as $resident)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $resident->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $resident->email }} • {{ $resident->barangay }}</p>
                            </div>
                            <a href="{{ route('admin.residents.show', $resident) }}" class="px-3 py-1 bg-sea-green text-white text-xs rounded hover:bg-opacity-90 transition">
                                Verify
                            </a>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 text-center py-8">No pending verifications.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>

