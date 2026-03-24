<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-deep-forest text-white flex flex-col shadow-xl flex-shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-sea-green border-opacity-30">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-deep-forest font-bold mr-3 shadow-sm">LGU</div>
            <span class="font-bold text-xl tracking-wide">RESIDENTE</span>
        </div>
        
        <div class="p-4 flex-1 overflow-y-auto">
            <p class="text-xs uppercase text-golden-glow font-bold tracking-wider mb-4 mt-2">Navigation</p>
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">🏠</span> Dashboard
                </a>
                @if($resident->canAccessServices())
                    <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                        <span class="text-lg">📚</span> E-Services Directory
                    </a>
                    <a href="{{ route('services.my-requests') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                        <span class="text-lg">📋</span> My Requests
                    </a>
                @else
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg">
                        <span class="text-lg">📚</span> E-Services Directory <span class="ml-auto text-xs">🔒</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg">
                        <span class="text-lg">📋</span> My Requests <span class="ml-auto text-xs">🔒</span>
                    </div>
                @endif
                <a href="{{ route('citizen.profile.index') }}" class="flex items-center gap-3 px-4 py-3 bg-sea-green rounded-lg font-medium shadow-sm hover:bg-opacity-90 transition">
                    <span class="text-lg">👤</span> My Profile
                </a>
            </nav>
        </div>
        
        <div class="p-4 border-t border-sea-green border-opacity-30">
            <div class="flex flex-col mb-4">
                <span class="text-sm font-bold truncate">{{ $resident->first_name }} {{ $resident->last_name }}</span>
                <span class="text-xs text-gray-300 truncate">{{ $resident->email }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center py-2 border border-tiger-orange text-tiger-orange hover:bg-tiger-orange hover:text-white rounded-md transition font-medium text-sm">
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-gray-100">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 flex-shrink-0">
            <h1 class="text-2xl font-bold text-deep-forest">My Profile</h1>
            <div class="flex items-center gap-2">
                @if($resident->is_verified)
                    <span class="text-xs font-bold bg-green-100 text-green-700 px-3 py-1 rounded-full border border-green-200">✓ Verified Resident</span>
                @else
                    <span class="text-xs font-bold bg-amber-100 text-amber-700 px-3 py-1 rounded-full border border-amber-200">⏳ Verification Pending</span>
                @endif
            </div>
        </header>

        <div class="p-8 space-y-6">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Personal Information Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-deep-forest px-6 py-4 flex justify-between items-center">
                    <h3 class="font-bold text-white text-lg flex items-center gap-2">
                        👤 Personal Information
                    </h3>
                    <a href="{{ route('citizen.profile.personal.edit') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-4 py-2 rounded-lg font-bold text-sm shadow transition">
                        Edit
                    </a>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Full Name</p>
                        <p class="text-gray-900 font-semibold">{{ $resident->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Email Address</p>
                        <p class="text-gray-900">{{ $resident->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Date of Birth</p>
                        <p class="text-gray-900">{{ $resident->date_of_birth ? $resident->date_of_birth->format('F d, Y') : 'Not set' }} 
                            @if($resident->date_of_birth)
                                <span class="text-gray-500">({{ $resident->age }} years old)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Place of Birth</p>
                        <p class="text-gray-900">{{ $resident->place_of_birth ?? 'Pending Update' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Gender</p>
                        <p class="text-gray-900">{{ $resident->gender ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Civil Status</p>
                        <p class="text-gray-900">{{ $resident->civil_status ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Blood Type</p>
                        <p class="text-gray-900">{{ $resident->blood_type ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Contact Number</p>
                        <p class="text-gray-900">{{ $resident->contact_number ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Occupation</p>
                        <p class="text-gray-900">{{ $resident->occupation ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Vulnerable Sector</p>
                        <p class="text-gray-900">{{ $resident->vulnerable_sector ?? 'None' }}</p>
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-deep-forest px-6 py-4 flex justify-between items-center">
                    <h3 class="font-bold text-white text-lg flex items-center gap-2">
                        📍 Address Information
                    </h3>
                    <a href="{{ route('citizen.profile.address.edit') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-4 py-2 rounded-lg font-bold text-sm shadow transition">
                        Edit
                    </a>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Purok/Street</p>
                        <p class="text-gray-900">{{ $resident->purok ?? 'Pending Update' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Barangay</p>
                        <p class="text-gray-900">{{ $resident->barangay }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Municipality</p>
                        <p class="text-gray-900">{{ $resident->municipality ?? 'Buguey' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Province</p>
                        <p class="text-gray-900">{{ $resident->province ?? 'Cagayan' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Full Address</p>
                        <p class="text-gray-900">{{ $resident->purok }}, {{ $resident->barangay }}, {{ $resident->municipality ?? 'Buguey' }}, {{ $resident->province ?? 'Cagayan' }}</p>
                    </div>
                </div>
            </div>

            <!-- Household Profile Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-deep-forest px-6 py-4 flex justify-between items-center">
                    <h3 class="font-bold text-white text-lg flex items-center gap-2">
                        🏠 Household Profile
                    </h3>
                    <a href="{{ route('citizen.profile.household.edit') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-4 py-2 rounded-lg font-bold text-sm shadow transition">
                        {{ $householdProfile ? 'Edit' : 'Add' }}
                    </a>
                </div>
                @if($householdProfile)
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Housing Type</p>
                            <p class="text-gray-900">{{ $householdProfile->housing_type }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Dwelling Type</p>
                            <p class="text-gray-900">{{ $householdProfile->dwelling_type ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Number of Rooms</p>
                            <p class="text-gray-900">{{ $householdProfile->number_of_rooms ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Water Source</p>
                            <p class="text-gray-900">{{ $householdProfile->water_source ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Utilities</p>
                            <div class="flex gap-2 flex-wrap">
                                @if($householdProfile->has_electricity)<span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">⚡ Electricity</span>@endif
                                @if($householdProfile->has_water_supply)<span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">💧 Water</span>@endif
                                @if($householdProfile->has_internet_access)<span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">📡 Internet</span>@endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Monthly Household Income</p>
                            <p class="text-gray-900">{{ $householdProfile->total_household_income ? '₱' . number_format($householdProfile->total_household_income, 2) : 'Not disclosed' }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p class="mb-2">No household profile data yet.</p>
                        <p class="text-sm">Click "Add" to provide household information.</p>
                    </div>
                @endif
            </div>

            <!-- Household Members Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-deep-forest px-6 py-4 flex justify-between items-center">
                    <h3 class="font-bold text-white text-lg flex items-center gap-2">
                        👨‍👩‍👧‍👦 Household Members
                    </h3>
                    <a href="{{ route('citizen.profile.members.add') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-4 py-2 rounded-lg font-bold text-sm shadow transition">
                        + Add Member
                    </a>
                </div>
                @if($householdMembers && $householdMembers->count() > 0)
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($householdMembers as $member)
                                <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-sea-green bg-opacity-20 text-sea-green rounded-full flex items-center justify-center font-bold flex-shrink-0">
                                                {{ strtoupper(substr($member->first_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $member->full_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $member->relationship }} • {{ $member->age }} years old • {{ $member->gender }}</p>
                                                @if($member->occupation)
                                                    <p class="text-xs text-gray-600 mt-1">{{ $member->occupation }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('citizen.profile.members.delete', $member) }}" onsubmit="return confirm('Are you sure you want to remove this member?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-bold">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- OFW/Migrant Status Badges -->
                                    @if($member->is_active_ofw || $member->is_returned_ofw || $member->is_local_migrant || $member->is_pwd || $member->is_senior_citizen || $member->is_solo_parent || $member->is_indigenous_people || $member->is_4ps_beneficiary)
                                        <div class="flex gap-2 flex-wrap mb-3">
                                            @if($member->is_active_ofw)
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-semibold">🌏 Active OFW</span>
                                            @endif
                                            @if($member->is_returned_ofw)
                                                <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full font-semibold">🏠 Returned OFW</span>
                                            @endif
                                            @if($member->is_local_migrant)
                                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-semibold">📍 Local Migrant</span>
                                            @endif
                                            @if($member->is_pwd)
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full font-semibold">♿ PWD</span>
                                            @endif
                                            @if($member->is_senior_citizen)
                                                <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full font-semibold">👴 Senior</span>
                                            @endif
                                            @if($member->is_solo_parent)
                                                <span class="text-xs bg-pink-100 text-pink-800 px-2 py-1 rounded-full font-semibold">👨‍👧 Solo Parent</span>
                                            @endif
                                            @if($member->is_indigenous_people)
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-semibold">🏞️ IP</span>
                                            @endif
                                            @if($member->is_4ps_beneficiary)
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-semibold">💰 4Ps</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- OFW Detailed Information -->
                                    @if($member->is_active_ofw || $member->is_returned_ofw || $member->is_local_migrant)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-lg border-l-2 border-blue-500">
                                            <p class="text-xs font-bold text-gray-700 mb-2">OFW/Migrant Details:</p>
                                            <div class="space-y-1 text-xs text-gray-600">
                                                @if($member->is_active_ofw)
                                                    @if($member->ofw_country)
                                                        <p>• <strong>Country:</strong> {{ $member->ofw_country }}</p>
                                                    @endif
                                                    @if($member->ofw_nature_of_work)
                                                        <p>• <strong>Work:</strong> {{ $member->ofw_nature_of_work }}</p>
                                                    @endif
                                                    @if($member->ofw_year_deployed)
                                                        <p>• <strong>Deployed:</strong> {{ $member->ofw_year_deployed }} ({{ date('Y') - $member->ofw_year_deployed }} years ago)</p>
                                                    @endif
                                                @endif
                                                
                                                @if($member->is_returned_ofw)
                                                    @if($member->ofw_year_returned)
                                                        <p>• <strong>Returned:</strong> {{ $member->ofw_year_returned }} ({{ date('Y') - $member->ofw_year_returned }} years ago)</p>
                                                    @endif
                                                    @if($member->ofw_nature_of_return)
                                                        <p>• <strong>Return Type:</strong> {{ $member->ofw_nature_of_return }}</p>
                                                    @endif
                                                @endif
                                                
                                                @if($member->is_local_migrant && $member->local_migrant_location)
                                                    <p>• <strong>Working in:</strong> {{ $member->local_migrant_location }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p class="mb-2">No household members added yet.</p>
                        <p class="text-sm">Click "+ Add Member" to add your family members.</p>
                    </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>
