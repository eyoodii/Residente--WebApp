<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">
    @include('partials.loader')

    <aside class="w-64 bg-deep-forest text-white flex flex-col shadow-xl flex-shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-sea-green border-opacity-30">
            <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full shadow-sm bg-white mr-3">
            <span class="font-bold text-xl tracking-wide">RESIDENTE</span>
        </div>
        
        <div class="p-4 flex-1 overflow-y-auto">
            <p class="text-xs uppercase text-golden-glow font-bold tracking-wider mb-4 mt-2">Navigation</p>
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-sea-green rounded-lg font-medium shadow-sm hover:bg-opacity-90 transition">
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
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg relative group">
                        <span class="text-lg">📚</span> 
                        <span>E-Services Directory</span>
                        <span class="ml-auto text-xs">🔒</span>
                        <div class="hidden group-hover:block absolute left-0 top-full mt-2 w-64 bg-gray-900 text-white text-xs rounded-lg p-3 shadow-lg z-10">
                            Requires residency verification
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg relative group">
                        <span class="text-lg">📋</span> 
                        <span>My Requests</span>
                        <span class="ml-auto text-xs">🔒</span>
                        <div class="hidden group-hover:block absolute left-0 top-full mt-2 w-64 bg-gray-900 text-white text-xs rounded-lg p-3 shadow-lg z-10">
                            Requires residency verification
                        </div>
                    </div>
                @endif
                <a href="{{ route('citizen.profile.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">👤</span> My Profile
                </a>
            </nav>
            
            <p class="text-xs uppercase text-golden-glow font-bold tracking-wider mb-4 mt-6">Quick Actions</p>
            <nav class="space-y-2">
                @if($resident->canAccessServices())
                    <a href="{{ route('services.show', 'mayors-clearance') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                        <span class="text-lg">📄</span> Clearance & Certification
                    </a>
                    <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                        <span class="text-lg">📑</span> CEDULA
                    </a>
                    <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                        <span class="text-lg">📁</span> Barangay Records
                    </a>
                    <a href="{{ route('services.show', 'sanitary-permit') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                        <span class="text-lg">📜</span> Permit
                    </a>
                    <a href="{{ route('services.show', 'laboratory-services') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                        <span class="text-lg">⚕️</span> Health Service
                    </a>
                @else
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg">
                        <span class="text-lg">📄</span> Clearance & Certification <span class="ml-auto text-xs">🔒</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg">
                        <span class="text-lg">📑</span> CEDULA <span class="ml-auto text-xs">🔒</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg">
                        <span class="text-lg">📁</span> Barangay Records <span class="ml-auto text-xs">🔒</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg">
                        <span class="text-lg">📜</span> Permit <span class="ml-auto text-xs">🔒</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 opacity-50 cursor-not-allowed rounded-lg">
                        <span class="text-lg">⚕️</span> Health Service <span class="ml-auto text-xs">🔒</span>
                    </div>
                @endif
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

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-gray-100">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 flex-shrink-0">
            <h1 class="text-2xl font-bold text-deep-forest">Welcome back, {{ $resident->first_name }}!</h1>
            @if($resident->canAccessServices())
                <a href="{{ route('services.index') }}" class="bg-tiger-orange hover:bg-burnt-tangerine text-white px-5 py-2.5 rounded-lg font-bold shadow-sm transition">
                    + New Request
                </a>
            @else
                <button disabled class="bg-gray-400 text-gray-200 px-5 py-2.5 rounded-lg font-bold shadow-sm cursor-not-allowed" title="Requires residency verification">
                    🔒 New Request
                </button>
            @endif
        </header>

        <div class="p-8 space-y-8">
            
            @if($needsPhilSysVerification)
            <!-- PhilSys Verification Banner -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-600 rounded-lg shadow-md p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-600 bg-opacity-20 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🆔</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Identity Verification Required</h3>
                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="flex items-center gap-2">
                                @if($resident->email_verified_at)
                                    <span class="text-green-600 font-bold">✓</span>
                                    <span><strong>Email Verified</strong> - Your email address has been confirmed</span>
                                @else
                                    <span class="text-amber-600 font-bold">⏳</span>
                                    <span><strong>Email Verification Pending</strong> - Please check your email inbox</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-blue-600 font-bold">⏳</span>
                                <span><strong>PhilSys Verification Pending</strong> - Verify your identity using your PhilSys ID</span>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-white bg-opacity-70 rounded-md border border-blue-200">
                            <p class="text-sm font-semibold text-gray-900 mb-2">📱 Next Steps:</p>
                            <ol class="text-sm text-gray-700 space-y-1 ml-4 list-decimal">
                                <li>Click the button below to start PhilSys verification</li>
                                <li>Have your PhilSys National ID or ePhilID ready</li>
                                <li>Scan the QR code on your PhilSys ID or enter your PSN manually</li>
                                <li>Complete your socio-economic profile to activate your account</li>
                            </ol>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('verification.philsys') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-md">
                                🆔 Verify PhilSys ID Now
                            </a>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 italic">Note: E-Services and online requests require PhilSys verification for security.</p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-deep-forest px-6 py-4 flex justify-between items-center">
                            <h3 class="font-bold text-white text-lg flex items-center gap-2">
                                📢 Public Information & Transparency Board
                            </h3>
                        </div>
                        
                        <div class="flex border-b border-gray-200 bg-gray-50 px-4 pt-2">
                            <button onclick="switchTab('memos')" id="tab-memos" class="tab-btn active-tab px-6 py-3 font-bold text-sm text-deep-forest border-b-4 border-tiger-orange transition relative">
                                LGU Memorandums
                                @if($memos->count() > 0)
                                    <span class="ml-2 bg-burnt-tangerine text-white text-xs px-2 py-0.5 rounded-full">{{ $memos->count() }}</span>
                                @endif
                            </button>
                            <button onclick="switchTab('ordinances')" id="tab-ordinances" class="tab-btn px-6 py-3 font-bold text-sm text-gray-500 border-b-4 border-transparent hover:text-deep-forest transition relative">
                                Brgy Ordinances
                                @if($ordinances->count() > 0)
                                    <span class="ml-2 bg-golden-glow text-deep-forest text-xs px-2 py-0.5 rounded-full">{{ $ordinances->count() }}</span>
                                @endif
                            </button>
                            <button onclick="switchTab('news')" id="tab-news" class="tab-btn px-6 py-3 font-bold text-sm text-gray-500 border-b-4 border-transparent hover:text-deep-forest transition relative">
                                News & Updates
                                @if($news->count() > 0)
                                    <span class="ml-2 bg-sea-green text-white text-xs px-2 py-0.5 rounded-full">{{ $news->count() }}</span>
                                @endif
                            </button>
                        </div>
                        
                        <div class="p-6 h-96 overflow-y-auto" id="announcement-container">
                            
                            <!-- LGU Memorandums -->
                            <div id="content-memos" class="tab-content space-y-4">
                                @forelse($memos as $memo)
                                <div class="p-4 rounded-lg border border-gray-200 hover:shadow-md transition bg-white relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-1 h-full bg-burnt-tangerine"></div>
                                    <div class="flex justify-between items-start mb-2 pl-2">
                                        <h4 class="font-bold text-gray-900 text-md">{{ $memo->title }}</h4>
                                        <span class="text-xs font-bold text-burnt-tangerine bg-red-50 px-2 py-1 rounded border border-red-100">{{ $memo->category }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3 pl-2">{{ Str::limit($memo->content, 150) }}</p>
                                    <div class="flex justify-between items-center pl-2">
                                        <span class="text-xs font-medium text-gray-500">Posted: {{ $memo->formatted_posted_at }}</span>
                                        <button class="text-xs font-bold text-sea-green hover:text-deep-forest underline">Read Full Document</button>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p class="text-sm">No LGU memorandums at this time.</p>
                                </div>
                                @endforelse
                                
                                @if($memos->count() >= 10)
                                <div class="text-center pt-4">
                                    <button onclick="loadMore('memos')" id="load-more-memos" class="px-6 py-2 bg-deep-forest text-white rounded-lg hover:bg-opacity-90 transition font-bold text-sm">
                                        Load More
                                    </button>
                                </div>
                                @endif
                            </div>

                            <!-- Barangay Ordinances -->
                            <div id="content-ordinances" class="tab-content hidden space-y-4">
                                @forelse($ordinances as $ordinance)
                                <div class="p-4 rounded-lg border border-gray-200 hover:shadow-md transition bg-white relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-1 h-full bg-golden-glow"></div>
                                    <div class="flex justify-between items-start mb-2 pl-2">
                                        <h4 class="font-bold text-gray-900 text-md">{{ $ordinance->title }}</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3 pl-2">{{ Str::limit($ordinance->content, 150) }}</p>
                                    <div class="flex justify-between items-center pl-2">
                                        <span class="text-xs font-medium text-gray-500">Posted: {{ $ordinance->formatted_posted_at }}</span>
                                        <button class="text-xs font-bold text-sea-green hover:text-deep-forest underline">Download PDF</button>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p class="text-sm">No barangay ordinances at this time.</p>
                                </div>
                                @endforelse
                                
                                @if($ordinances->count() >= 10)
                                <div class="text-center pt-4">
                                    <button onclick="loadMore('ordinances')" id="load-more-ordinances" class="px-6 py-2 bg-deep-forest text-white rounded-lg hover:bg-opacity-90 transition font-bold text-sm">
                                        Load More
                                    </button>
                                </div>
                                @endif
                            </div>

                            <!-- News & Updates -->
                            <div id="content-news" class="tab-content hidden space-y-4">
                                @forelse($news as $item)
                                <div class="p-4 rounded-lg border border-gray-200 hover:shadow-md transition bg-white relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-1 h-full bg-sea-green"></div>
                                    <div class="flex justify-between items-start mb-2 pl-2">
                                        <h4 class="font-bold text-gray-900 text-md">{{ $item->title }}</h4>
                                        <span class="text-xs font-bold {{ $item->category_badge_color }} px-2 py-1 rounded border">{{ $item->category }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3 pl-2">{{ Str::limit($item->content, 150) }}</p>
                                    <div class="flex justify-between items-center pl-2">
                                        <span class="text-xs font-medium text-gray-500">Posted: {{ $item->formatted_posted_at }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p class="text-sm">No news and updates at this time.</p>
                                </div>
                                @endforelse
                                
                                @if($news->count() >= 10)
                                <div class="text-center pt-4">
                                    <button onclick="loadMore('news')" id="load-more-news" class="px-6 py-2 bg-deep-forest text-white rounded-lg hover:bg-opacity-90 transition font-bold text-sm">
                                        Load More
                                    </button>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-deep-forest text-lg">Active Requests</h3>
                            <span class="bg-tiger-orange text-white text-xs font-bold px-2 py-1 rounded-full">1</span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition bg-gray-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-sea-green bg-opacity-20 text-sea-green rounded-full flex items-center justify-center text-xl">📄</div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">Certificate of Residency</p>
                                        <p class="text-xs text-tiger-orange font-bold mt-1 uppercase tracking-wide">For pick-up</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-deep-forest text-white rounded-xl shadow-sm overflow-hidden p-6 relative">
                        <div class="absolute top-0 right-0 p-4 opacity-20 text-6xl">📊</div>
                        <h3 class="font-bold text-lg mb-2 relative z-10">Data Accuracy</h3>
                        <p class="text-sm text-gray-300 mb-4 relative z-10">Help the municipality serve you better by keeping your socio-economic profile updated.</p>
                        <button class="bg-golden-glow text-deep-forest hover:bg-white px-4 py-2 rounded shadow font-bold text-sm transition relative z-10 w-full">
                            Update My Profile
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script>
        function switchTab(tabId) {
            // Hide all content blocks
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Remove active styling from all buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active-tab', 'text-deep-forest', 'border-tiger-orange');
                el.classList.add('text-gray-500', 'border-transparent');
            });
            
            // Show the selected content block
            document.getElementById('content-' + tabId).classList.remove('hidden');
            
            // Add active styling to the clicked button
            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.remove('text-gray-500', 'border-transparent');
            activeBtn.classList.add('active-tab', 'text-deep-forest', 'border-tiger-orange');
        }

        // Track offset for each category
        let offsets = {
            memos: {{ $memos->count() }},
            ordinances: {{ $ordinances->count() }},
            news: {{ $news->count() }}
        };

        async function loadMore(category) {
            const button = document.getElementById('load-more-' + category);
            const container = document.getElementById('content-' + category);
            
            // Disable button and show loading state
            button.disabled = true;
            button.textContent = 'Loading...';
            
            try {
                const response = await fetch(`/dashboard/load-more-announcements?category=${category}&offset=${offsets[category]}`);
                const data = await response.json();
                
                if (data.announcements && data.announcements.length > 0) {
                    // Create elements for new announcements
                    data.announcements.forEach(announcement => {
                        const div = document.createElement('div');
                        div.className = 'p-4 rounded-lg border border-gray-200 hover:shadow-md transition bg-white relative overflow-hidden';
                        
                        let borderColor = 'bg-sea-green';
                        let badgeHtml = '';
                        
                        if (category === 'memos') {
                            borderColor = 'bg-burnt-tangerine';
                            badgeHtml = `<span class="text-xs font-bold text-burnt-tangerine bg-red-50 px-2 py-1 rounded border border-red-100">${announcement.category}</span>`;
                        } else if (category === 'ordinances') {
                            borderColor = 'bg-golden-glow';
                        } else {
                            badgeHtml = `<span class="text-xs font-bold ${announcement.category_badge_color} px-2 py-1 rounded border">${announcement.category}</span>`;
                        }
                        
                        div.innerHTML = `
                            <div class="absolute top-0 left-0 w-1 h-full ${borderColor}"></div>
                            <div class="flex justify-between items-start mb-2 pl-2">
                                <h4 class="font-bold text-gray-900 text-md">${announcement.title}</h4>
                                ${badgeHtml}
                            </div>
                            <p class="text-sm text-gray-600 mb-3 pl-2">${announcement.content_preview}</p>
                            <div class="flex justify-between items-center pl-2">
                                <span class="text-xs font-medium text-gray-500">Posted: ${announcement.formatted_posted_at}</span>
                                ${category === 'memos' ? '<button class="text-xs font-bold text-sea-green hover:text-deep-forest underline">Read Full Document</button>' : ''}
                                ${category === 'ordinances' ? '<button class="text-xs font-bold text-sea-green hover:text-deep-forest underline">Download PDF</button>' : ''}
                            </div>
                        `;
                        
                        // Insert before the button container
                        button.parentElement.parentElement.insertBefore(div, button.parentElement);
                    });
                    
                    // Update offset
                    offsets[category] += data.announcements.length;
                    
                    // Hide button if no more announcements
                    if (!data.hasMore) {
                        button.parentElement.remove();
                    } else {
                        button.disabled = false;
                        button.textContent = 'Load More';
                    }
                } else {
                    button.parentElement.remove();
                }
            } catch (error) {
                console.error('Error loading more announcements:', error);
                button.disabled = false;
                button.textContent = 'Load More';
                alert('Failed to load more announcements. Please try again.');
            }
        }
    </script>
</body>
</html>
