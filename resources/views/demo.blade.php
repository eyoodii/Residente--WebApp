<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Demo | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-enter { animation: fadeInUp 0.4s ease both; }

        .role-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .role-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
        }

        /* Sticky warning bar */
        #demo-banner {
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Section header accent line */
        .section-header {
            position: relative;
            padding-left: 1rem;
        }
        .section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.15rem;
            bottom: 0.15rem;
            width: 4px;
            border-radius: 9999px;
        }
        .section-header-green::before  { background: #008148; }
        .section-header-indigo::before { background: #4f46e5; }
        .section-header-teal::before   { background: #0d9488; }
        .section-header-amber::before  { background: #d97706; }
        .section-header-rose::before   { background: #e11d48; }
        .section-header-blue::before   { background: #2563eb; }
    </style>

    {{-- Tailwind safelist: force-include dynamic accent color classes --}}
    <div class="hidden
        bg-emerald-500 bg-emerald-50 text-emerald-700 border-emerald-200 bg-emerald-400
        bg-slate-500 bg-slate-50 text-slate-700 border-slate-200 bg-slate-400
        bg-green-500 bg-green-50 text-green-700 border-green-200 bg-green-400
        bg-amber-500 bg-amber-50 text-amber-700 border-amber-200 bg-amber-400
        bg-indigo-500 bg-indigo-50 text-indigo-700 border-indigo-200 bg-indigo-400
        bg-violet-500 bg-violet-50 text-violet-700 border-violet-200 bg-violet-400
        bg-teal-500 bg-teal-50 text-teal-700 border-teal-200 bg-teal-400
        bg-cyan-500 bg-cyan-50 text-cyan-700 border-cyan-200 bg-cyan-400
        bg-yellow-500 bg-yellow-50 text-yellow-700 border-yellow-200 bg-yellow-400
        bg-orange-500 bg-orange-50 text-orange-700 border-orange-200 bg-orange-400
        bg-pink-500 bg-pink-50 text-pink-700 border-pink-200 bg-pink-400
        bg-red-500 bg-red-50 text-red-700 border-red-200 bg-red-400
        bg-rose-500 bg-rose-50 text-rose-700 border-rose-200 bg-rose-400
        bg-lime-500 bg-lime-50 text-lime-700 border-lime-200 bg-lime-400
        bg-blue-500 bg-blue-50 text-blue-700 border-blue-200 bg-blue-400
        bg-sky-500 bg-sky-50 text-sky-700 border-sky-200 bg-sky-400
        bg-gray-500 bg-gray-50 text-gray-700 border-gray-200 bg-gray-400
        bg-purple-500 bg-purple-50 text-purple-700 border-purple-200 bg-purple-400
    "></div>
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen">

    {{-- ── Sticky Warning Banner ─────────────────────────────────────────── --}}
    <div id="demo-banner" class="bg-amber-500 text-amber-950 text-xs font-bold tracking-wide text-center py-2 px-4 shadow-md">
        ⚠️ DEVELOPMENT DEMO ONLY — View-only dashboard showcase. Remove this page before deploying to production.
    </div>

    {{-- ── Header ───────────────────────────────────────────────────────── --}}
    <header class="bg-deep-forest text-white shadow-2xl relative overflow-hidden">
        {{-- Subtle top accent stripe --}}
        <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-golden-glow to-transparent opacity-70"></div>
        {{-- Bottom border accent --}}
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between gap-6">

                {{-- ── Brand Identity (Left) ── --}}
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <div class="absolute inset-0 rounded-full bg-golden-glow/20 blur-sm"></div>
                        <img src="{{ asset('logo_buguey.png') }}" alt="Seal of the Municipality of Buguey"
                             class="relative w-14 h-14 rounded-full bg-white object-contain shadow-lg ring-2 ring-white/20 p-0.5">
                    </div>
                    <div class="border-l border-white/15 pl-4">
                        <div class="flex items-center gap-2.5 mb-0.5">
                            <h1 class="text-xl font-extrabold tracking-widest leading-none uppercase">RESIDENTE</h1>
                            <span class="hidden sm:inline-flex items-center text-[9px] font-bold tracking-widest uppercase px-2 py-0.5 rounded border border-golden-glow/40 text-golden-glow bg-golden-glow/10">
                                Gov. Info System
                            </span>
                        </div>
                        <p class="text-white/55 text-[11px] font-medium tracking-wide">
                            Municipality of Buguey &bull; Cagayan Province, Philippines
                        </p>
                    </div>
                </div>

                {{-- ── Clock + Navigation (Right) ── --}}
                <div class="flex flex-col items-end gap-2">
                    <div class="text-right leading-tight">
                        <div id="header-clock" class="text-lg font-mono font-bold tracking-tight text-golden-glow tabular-nums">--:--:-- --</div>
                        <div class="text-white/40 text-[9px] font-semibold tracking-widest uppercase mt-0.5">Philippine Standard Time &bull; UTC+8</div>
                    </div>
                    <a href="{{ url('/') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-medium text-white/60 hover:text-golden-glow border border-white/10 hover:border-golden-glow/40 rounded-md px-3 py-1.5 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Homepage
                    </a>
                </div>

            </div>
        </div>
    </header>

    {{-- ── Intro ────────────────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 flex items-start gap-4">
            <div class="text-3xl flex-shrink-0">🗂️</div>
            <div>
                <h2 class="font-bold text-slate-800 text-lg mb-1">How to use this page</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Click <strong>"View Dashboard"</strong> on any card below to preview that role's dashboard layout,
                    sidebar modules, and available features. From the preview, click <strong>"🚀 Open Live Dashboard"</strong>
                    to navigate to the actual dashboard and explore its real functionality.
                </p>
            </div>
        </div>

        {{-- Architecture info box --}}
        <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-2xl border border-blue-200/60 p-6 mb-8">
            <h3 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">🏗️ Dashboard Architecture</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-600">
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold tracking-wide rounded-md bg-emerald-800 text-emerald-200">🏠 Citizen Portal</span>
                    </div>
                    <p class="leading-relaxed"><strong>Routes:</strong> <code class="text-emerald-700">/dashboard</code></p>
                    <p class="mt-1 leading-relaxed">Single dashboard for all citizen & visitor roles. Shows announcements, service request status, profile/household info, and onboarding prompts.</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold tracking-wide rounded-md bg-slate-800 text-slate-200">🔧 Admin Portal</span>
                    </div>
                    <p class="leading-relaxed"><strong>Routes:</strong> <code class="text-slate-700">/admin/dashboard</code></p>
                    <p class="mt-1 leading-relaxed">Shared between Super Admin & General Admin. Full CRUD: services, residents, households, verification, data collection, and activity logs.</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold tracking-wide rounded-md bg-blue-800 text-blue-200">🏢 Department Portal</span>
                    </div>
                    <p class="leading-relaxed"><strong>Routes:</strong> <code class="text-blue-700">/department/dashboard</code></p>
                    <p class="mt-1 leading-relaxed">22 department roles mapped to <strong>7 dashboard clusters</strong> (Executive, Planning, Financial, Social, Sector, Legislative, HRMO). Each cluster shows role-specific KPIs. Access level (Read-Only / Write / Full) controls sidebar modules.</p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3 text-[10px]">
                <span class="font-bold text-slate-500 tracking-wide uppercase self-center">Access Levels:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-bold border border-amber-200">👁️ Read-Only — View data, no edits</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-bold border border-blue-200">📝 Write — Create &amp; edit records</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold border border-emerald-200">✏️ Full — Write + approve/reject + manage</span>
            </div>
        </div>

        {{-- ── Section: Core Roles ─────────────────────────────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-green">Core Application Roles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            {{-- Super Admin --}}
            @include('partials.demo-card', [
                'color'       => 'deep-forest',
                'gradient'    => 'from-[#034732] to-[#008148]',
                'icon'        => '👑',
                'title'       => 'Super Admin',
                'badge'       => 'SA',
                'badgeColor'  => 'bg-golden-glow text-deep-forest',
                'description' => 'Full unrestricted access to every module. Manages services, residents, households, and all department portals.',
                'features'    => ['Admin Dashboard', 'Service Management', 'Resident & Household Management', 'Verification Dashboard', 'Activity Logs', 'Data Collection (HN→HHN→HHM)'],
                'email'       => 'superadmin@buguey.gov.ph',
                'password'    => 'SuperAdmin@2026',
                'accent'      => 'emerald',
                'portal'      => 'admin',
                'dashboard'   => 'Admin Dashboard (Full Access)',
            ])

            {{-- Admin --}}
            @include('partials.demo-card', [
                'color'       => 'slate',
                'gradient'    => 'from-slate-700 to-slate-900',
                'icon'        => '🛡️',
                'title'       => 'General Admin',
                'badge'       => 'ADMIN',
                'badgeColor'  => 'bg-slate-200 text-slate-800',
                'description' => 'Administrative user with access to the admin panel. Can view and manage records within assigned scope.',
                'features'    => ['Admin Dashboard', 'Resident Records', 'Master Collections', 'Barangay Overview', 'Activity Logs'],
                'email'       => 'admin@buguey.gov.ph',
                'password'    => 'Admin@2026',
                'accent'      => 'slate',
                'portal'      => 'admin',
                'dashboard'   => 'Admin Dashboard',
            ])

            {{-- Verified Citizen --}}
            @include('partials.demo-card', [
                'color'       => 'sea-green',
                'gradient'    => 'from-[#008148] to-emerald-700',
                'icon'        => '👤',
                'title'       => 'Verified Citizen',
                'badge'       => 'CITIZEN',
                'badgeColor'  => 'bg-emerald-100 text-emerald-800',
                'description' => 'A verified resident who can file e-service requests, track status, and manage their household profile.',
                'features'    => ['Resident Dashboard', 'E-Services Directory', 'My Requests', 'My Profile & Household', 'Announcements & News'],
                'email'       => 'citizen@test.com',
                'password'    => 'Citizen@2026',
                'accent'      => 'green',
                'portal'      => 'citizen',
                'dashboard'   => 'Resident Dashboard',
            ])

            {{-- Visitor --}}
            @include('partials.demo-card', [
                'color'       => 'amber',
                'gradient'    => 'from-amber-500 to-orange-600',
                'icon'        => '🔎',
                'title'       => 'Unverified Visitor',
                'badge'       => 'VISITOR',
                'badgeColor'  => 'bg-amber-100 text-amber-800',
                'description' => 'A newly registered account that has not yet completed PhilSys verification. Services are locked until verified.',
                'features'    => ['Basic Dashboard (limited)', 'Verification Prompt / CTA', 'Read-Only Announcements'],
                'email'       => 'visitor@test.com',
                'password'    => 'Visitor@2026',
                'accent'      => 'amber',
                'portal'      => 'citizen',
                'dashboard'   => 'Resident Dashboard (Limited)',
            ])

        </div>

        {{-- ── Section: Executive / Legislative ───────────────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-indigo">Executive &amp; Legislative</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @include('partials.demo-card', [
                'color'       => 'indigo',
                'gradient'    => 'from-indigo-600 to-indigo-800',
                'icon'        => '🏛️',
                'title'       => 'Municipal Mayor',
                'badge'       => 'MAYOR',
                'badgeColor'  => 'bg-indigo-100 text-indigo-800',
                'description' => 'Executive read-only overview. Access to analytics, master collections, and all activity logs.',
                'features'    => ['Executive Dashboard', 'Population Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'mayor@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'indigo',
                'portal'      => 'department',
                'dashboard'   => 'Executive Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'violet',
                'gradient'    => 'from-violet-600 to-purple-800',
                'icon'        => '🪑',
                'title'       => 'Vice Mayor',
                'badge'       => 'VMYOR',
                'badgeColor'  => 'bg-violet-100 text-violet-800',
                'description' => 'Vice-executive read-only access to analytics, master collections, and activity logs.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'vmyor@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'violet',
                'portal'      => 'department',
                'dashboard'   => 'Legislative Analytics',
            ])

        </div>

        {{-- ── Section: Planning & Engineering ─────────────────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-teal">Planning, Engineering &amp; Development</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @include('partials.demo-card', [
                'color'       => 'teal',
                'gradient'    => 'from-teal-600 to-teal-800',
                'icon'        => '📐',
                'title'       => 'MPDC',
                'badge'       => 'MPDC',
                'badgeColor'  => 'bg-teal-100 text-teal-800',
                'description' => 'Municipal Planning & Development Coordinator. Full access to spatial analytics, household management, and clearances.',
                'features'    => ['Master Collections', 'Household Management', 'Analytics', 'Locational Clearance'],
                'email'       => 'mpdc@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Write',
                'accent'      => 'teal',
                'portal'      => 'department',
                'dashboard'   => 'Planning & Development Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'cyan',
                'gradient'    => 'from-cyan-600 to-sky-700',
                'icon'        => '⚙️',
                'title'       => 'Municipal Engineer',
                'badge'       => 'ENGR',
                'badgeColor'  => 'bg-cyan-100 text-cyan-800',
                'description' => 'Handles building permit applications, engineering assessments, and household management.',
                'features'    => ['Building Permits', 'Household Management', 'Analytics'],
                'email'       => 'engr@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Write',
                'accent'      => 'cyan',
                'portal'      => 'department',
                'dashboard'   => 'Engineering & Infrastructure Dashboard',
            ])

        </div>

        {{-- ── Section: Financial ───────────────────────────────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-amber">Financial Management</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @include('partials.demo-card', [
                'color'       => 'yellow',
                'gradient'    => 'from-yellow-500 to-amber-600',
                'icon'        => '💰',
                'title'       => 'Municipal Treasurer',
                'badge'       => 'TRESR',
                'badgeColor'  => 'bg-yellow-100 text-yellow-800',
                'description' => 'Full access to the financial module, service fee management, and activity logs.',
                'features'    => ['Financial Module', 'Service Management', 'Activity Logs'],
                'email'       => 'treasurer@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Full',
                'accent'      => 'yellow',
                'portal'      => 'department',
                'dashboard'   => 'Revenue & Collections Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'orange',
                'gradient'    => 'from-orange-500 to-orange-700',
                'icon'        => '📊',
                'title'       => 'Municipal Accountant',
                'badge'       => 'ACCT',
                'badgeColor'  => 'bg-orange-100 text-orange-800',
                'description' => 'Read-only financial module view for audit and record reconciliation.',
                'features'    => ['Financial Module (Read-Only)', 'Activity Logs'],
                'email'       => 'accountant@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'orange',
                'portal'      => 'department',
                'dashboard'   => 'Internal Audit Dashboard',
            ])

        </div>

        {{-- ── Section: Social / Health / Emergency ─────────────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-rose">Social Services, Health &amp; Emergency</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @include('partials.demo-card', [
                'color'       => 'pink',
                'gradient'    => 'from-pink-500 to-rose-600',
                'icon'        => '🤝',
                'title'       => 'MSWDO',
                'badge'       => 'MSWDO',
                'badgeColor'  => 'bg-pink-100 text-pink-800',
                'description' => 'Municipal Social Welfare & Development Officer. Manages welfare targeting, livelihood programs, and household data.',
                'features'    => ['Welfare Targeting', 'Household Management', 'Analytics', 'Service Requests'],
                'email'       => 'mswdo@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Write',
                'accent'      => 'pink',
                'portal'      => 'department',
                'dashboard'   => 'Social Welfare & Aid Targeting',
            ])

            @include('partials.demo-card', [
                'color'       => 'red',
                'gradient'    => 'from-red-500 to-rose-700',
                'icon'        => '🏥',
                'title'       => 'Municipal Health Officer',
                'badge'       => 'MHO',
                'badgeColor'  => 'bg-red-100 text-red-800',
                'description' => 'Full health services dashboard — patient records, health stats, and service requests.',
                'features'    => ['Health Services Module', 'Analytics', 'Service Requests'],
                'email'       => 'mho@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Full',
                'accent'      => 'red',
                'portal'      => 'department',
                'dashboard'   => 'Health Services Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'rose',
                'gradient'    => 'from-rose-600 to-red-800',
                'icon'        => '🚨',
                'title'       => 'DRRMO',
                'badge'       => 'DRRMO',
                'badgeColor'  => 'bg-rose-100 text-rose-800',
                'description' => 'Disaster Risk Reduction & Management Officer. Manages emergency alerts, blotter, and affected household data.',
                'features'    => ['Emergency Alerts', 'Blotter Management', 'Welfare Targeting', 'Household Management'],
                'email'       => 'drrmo@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Full',
                'accent'      => 'rose',
                'portal'      => 'department',
                'dashboard'   => 'Emergency Management & Alerts',
            ])

        </div>

        {{-- ── Section: Sector-Specific Services ────────────────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-blue">Sector-Specific &amp; Administrative Services</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @include('partials.demo-card', [
                'color'       => 'lime',
                'gradient'    => 'from-lime-600 to-green-700',
                'icon'        => '🌾',
                'title'       => 'Municipal Agriculturist',
                'badge'       => 'AGRI',
                'badgeColor'  => 'bg-lime-100 text-lime-800',
                'description' => 'Access to livelihood programs, household data, and agricultural analytics.',
                'features'    => ['Livelihood Module', 'Household Management', 'Analytics'],
                'email'       => 'agri@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Write',
                'accent'      => 'lime',
                'portal'      => 'department',
                'dashboard'   => 'Agriculture & Livelihood',
            ])

            @include('partials.demo-card', [
                'color'       => 'blue',
                'gradient'    => 'from-blue-500 to-blue-700',
                'icon'        => '🏪',
                'title'       => 'Business Permit Licensing',
                'badge'       => 'BPLO',
                'badgeColor'  => 'bg-blue-100 text-blue-800',
                'description' => 'Full management of business permit applications, renewals, and fee collection.',
                'features'    => ['Business Permits Module', 'Service Requests', 'Analytics'],
                'email'       => 'bplo@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Full',
                'accent'      => 'blue',
                'portal'      => 'department',
                'dashboard'   => 'Business Permits & Licensing',
            ])

            @include('partials.demo-card', [
                'color'       => 'sky',
                'gradient'    => 'from-sky-500 to-sky-700',
                'icon'        => '📋',
                'title'       => 'Civil Registrar',
                'badge'       => 'REGST',
                'badgeColor'  => 'bg-sky-100 text-sky-800',
                'description' => 'Full management of civil registry entries, birth/death/marriage certificate processing.',
                'features'    => ['Civil Registry Module', 'Verification Dashboard', 'Service Requests'],
                'email'       => 'registrar@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Full',
                'accent'      => 'sky',
                'portal'      => 'department',
                'dashboard'   => 'Civil Registry',
            ])

            @include('partials.demo-card', [
                'color'       => 'emerald',
                'gradient'    => 'from-emerald-500 to-emerald-700',
                'icon'        => '📜',
                'title'       => 'Sangguniang Bayan Secretary',
                'badge'       => 'SBSEC',
                'badgeColor'  => 'bg-emerald-100 text-emerald-800',
                'description' => 'Full access to transparency board, master collections, and legislative records management.',
                'features'    => ['Transparency Board', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbsec@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Full',
                'accent'      => 'emerald',
                'portal'      => 'department',
                'dashboard'   => 'SB Information Portal',
            ])

        </div>

        {{-- ── Section: Legislative (SB Committee Chairs) ───────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-indigo">Legislative — SB Committee Chairs &amp; SK Federation</h2>
        <p class="text-xs text-slate-500 mb-5 ml-4 leading-relaxed max-w-3xl">
            Each Sangguniang Bayan committee chair has their own <strong>themed dashboard</strong> with unique colors and KPIs relevant to their committee.
            All SB chairs share <strong>Read-Only</strong> access to analytics and master collections.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @include('partials.demo-card', [
                'color'       => 'blue',
                'gradient'    => 'from-blue-700 to-cyan-600',
                'icon'        => '💰',
                'title'       => 'SB Finance, Budget & Comprehensive',
                'badge'       => 'SBFIN',
                'badgeColor'  => 'bg-blue-100 text-blue-800',
                'description' => 'Oversight of budget allocation, appropriation ordinances, and fiscal policy. Reviews municipal financial reports.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbfin@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'blue',
                'portal'      => 'department',
                'dashboard'   => 'SB Finance Committee Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'teal',
                'gradient'    => 'from-teal-700 to-emerald-600',
                'icon'        => '🏥',
                'title'       => 'SB Health, Sanitation & Ecology',
                'badge'       => 'SBHLT',
                'badgeColor'  => 'bg-teal-100 text-teal-800',
                'description' => 'Monitors public health programs, sanitation ordinances, and environmental initiatives.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbhlt@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'teal',
                'portal'      => 'department',
                'dashboard'   => 'SB Health Committee Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'pink',
                'gradient'    => 'from-pink-600 to-purple-600',
                'icon'        => '👩‍⚖️',
                'title'       => "SB Women, Children & Family",
                'badge'       => 'SBWMN',
                'badgeColor'  => 'bg-pink-100 text-pink-800',
                'description' => 'Advocates for gender equality, child welfare, and family support policies and programs.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbwmn@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'pink',
                'portal'      => 'department',
                'dashboard'   => 'SB Women & Family Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'slate',
                'gradient'    => 'from-slate-700 to-gray-600',
                'icon'        => '⚖️',
                'title'       => 'SB Rules, Laws & Privileges',
                'badge'       => 'SBRLS',
                'badgeColor'  => 'bg-slate-100 text-slate-800',
                'description' => 'Reviews proposed ordinances, resolutions, and legal matters of the Sangguniang Bayan.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbrls@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'slate',
                'portal'      => 'department',
                'dashboard'   => 'SB Rules & Laws Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'indigo',
                'gradient'    => 'from-indigo-700 to-blue-600',
                'icon'        => '📡',
                'title'       => 'SB Public Info & Communications',
                'badge'       => 'SBPIC',
                'badgeColor'  => 'bg-indigo-100 text-indigo-800',
                'description' => 'Public information campaigns, transparency initiatives, and communications oversight.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbpic@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'indigo',
                'portal'      => 'department',
                'dashboard'   => 'SB Public Info Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'amber',
                'gradient'    => 'from-yellow-500 to-orange-500',
                'icon'        => '🛺',
                'title'       => 'SB Transportation & Public Works',
                'badge'       => 'SBTSP',
                'badgeColor'  => 'bg-yellow-100 text-yellow-800',
                'description' => 'Oversight on transportation, road safety, and public infrastructure programs.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbtsp@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'yellow',
                'portal'      => 'department',
                'dashboard'   => 'SB Transportation Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'orange',
                'gradient'    => 'from-orange-600 to-red-500',
                'icon'        => '🏗️',
                'title'       => 'SB Public Works & Infrastructure',
                'badge'       => 'SBPWK',
                'badgeColor'  => 'bg-orange-100 text-orange-800',
                'description' => 'Infrastructure project oversight, building standards, and public works program monitoring.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbpwk@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'orange',
                'portal'      => 'department',
                'dashboard'   => 'SB Public Works Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'emerald',
                'gradient'    => 'from-emerald-700 to-green-600',
                'icon'        => '🌾',
                'title'       => 'SB Agriculture & Food',
                'badge'       => 'SBAGR',
                'badgeColor'  => 'bg-emerald-100 text-emerald-800',
                'description' => 'Agricultural policy, food security programs, and farming community support oversight.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbagr@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'emerald',
                'portal'      => 'department',
                'dashboard'   => 'SB Agriculture Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'violet',
                'gradient'    => 'from-purple-700 to-violet-600',
                'icon'        => '🗺️',
                'title'       => 'SB Barangay & Governance Affairs',
                'badge'       => 'SBBGA',
                'badgeColor'  => 'bg-violet-100 text-violet-800',
                'description' => 'Barangay coordination, governance standards, and inter-barangay program oversight.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'sbbga@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'violet',
                'portal'      => 'department',
                'dashboard'   => 'SB Barangay Affairs Dashboard',
            ])

            @include('partials.demo-card', [
                'color'       => 'amber',
                'gradient'    => 'from-orange-500 to-amber-500',
                'icon'        => '🎓',
                'title'       => 'SK Federation President',
                'badge'       => 'SKPRS',
                'badgeColor'  => 'bg-amber-100 text-amber-800',
                'description' => 'Youth representative. Monitors SK programs, youth development initiatives, and participates in legislative sessions.',
                'features'    => ['Analytics', 'Master Collections', 'Activity Logs'],
                'email'       => 'skpres@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Read-Only',
                'accent'      => 'amber',
                'portal'      => 'department',
                'dashboard'   => 'SK Federation Dashboard',
            ])

        </div>

        {{-- ── Section: Human Resources ──────────────────────────────────── --}}
        <h2 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest mb-5 section-header section-header-green">Human Resources</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @include('partials.demo-card', [
                'color'       => 'emerald',
                'gradient'    => 'from-[#034732] to-[#008148]',
                'icon'        => '👥',
                'title'       => 'HRMO',
                'badge'       => 'HRMO',
                'badgeColor'  => 'bg-emerald-100 text-emerald-800',
                'description' => 'Human Resource Management Officer. Manages department staff accounts, role assignments, and personnel records.',
                'features'    => ['Role Assignment', 'Staff Management', 'Activity Logs'],
                'email'       => 'hrmo@buguey.gov.ph',
                'password'    => 'Dept@2026',
                'access'      => 'Full',
                'accent'      => 'emerald',
                'portal'      => 'department',
                'dashboard'   => 'HR Management Dashboard',
            ])

        </div>

        {{-- ── Footer ────────────────────────────────────────────────────── --}}
        <div class="text-center text-xs text-slate-400 py-8 border-t border-slate-200 mt-4">
            RESIDENTE App — Municipality of Buguey &bull; This demo page is for development use only and should be removed before going live.
        </div>
    </div>

    {{-- ── Dashboard Preview Modal ────────────────────────────────────── --}}
    <div id="preview-overlay"
         class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4
                opacity-0 pointer-events-none transition-all duration-300"
         onclick="if(event.target===this)closePreview()">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            {{-- Modal header --}}
            <div id="preview-header" class="bg-gradient-to-r from-[#034732] to-[#008148] px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span id="preview-icon" class="text-3xl">👑</span>
                    <div>
                        <h2 id="preview-title" class="text-white font-extrabold text-lg">Dashboard</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span id="preview-portal-badge" class="text-[10px] font-bold tracking-wide px-2 py-0.5 rounded-md bg-white/20 text-white">Portal</span>
                            <span class="text-white/50 text-[10px]">→</span>
                            <span id="preview-dashboard-name" class="text-[11px] font-semibold text-white/90">Dashboard View</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span id="preview-access-badge" class="text-[10px] font-bold tracking-wide px-3 py-1 rounded-full bg-white/20 text-white hidden"></span>
                    <button onclick="closePreview()" class="text-white/70 hover:text-white transition text-2xl leading-none font-light">&times;</button>
                </div>
            </div>

            {{-- Modal body: simulated dashboard --}}
            <div class="flex flex-1 overflow-hidden">
                {{-- Simulated sidebar --}}
                <div class="w-56 bg-slate-900 text-white flex-shrink-0 overflow-y-auto">
                    <div class="px-4 py-4 border-b border-slate-700">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
                    </div>
                    <nav id="preview-sidebar" class="p-3 space-y-1">
                        {{-- JS populates sidebar items --}}
                    </nav>
                </div>

                {{-- Dynamic main content area --}}
                <div id="preview-main" class="flex-1 bg-slate-50 overflow-y-auto p-6">
                    {{-- JS renders content here based on active sidebar item --}}
                </div>
            </div>

            {{-- Modal footer --}}
            <div class="px-6 py-3 bg-slate-100 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[10px] text-slate-400 font-medium">Preview mode — click "Open Live Dashboard" to navigate and explore the real interface</span>
                <div class="flex items-center gap-2">
                    <button onclick="closePreview()" class="px-4 py-1.5 bg-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-300 transition">
                        Close
                    </button>
                    <button id="preview-navigate-btn" onclick="navigateToDashboard()" class="px-4 py-1.5 bg-deep-forest text-white text-xs font-bold rounded-lg hover:bg-sea-green transition flex items-center gap-1.5 shadow-md">
                        🚀 Open Live Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Hidden login form (auto-submitted for live navigation) ────────── --}}
    <form id="demo-login-form" action="{{ url('/login') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="email"    id="demo-email">
        <input type="hidden" name="password" id="demo-password">
    </form>

    {{-- ── Toast notification ────────────────────────────────────────────── --}}
    <div id="demo-toast"
         class="fixed bottom-6 right-6 bg-deep-forest text-white px-5 py-3 rounded-xl shadow-2xl text-sm font-medium
                opacity-0 translate-y-4 transition-all duration-300 pointer-events-none z-[60] flex items-center gap-2">
        <span>🚀</span>
        <span id="demo-toast-msg">Navigating…</span>
    </div>

    <script>
        // ── Live PST Clock ────────────────────────────────────────────────────
        (function () {
            function updateClock() {
                var el = document.getElementById('header-clock');
                if (!el) return;
                var now = new Date();
                // Philippine Standard Time is UTC+8
                var pst = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
                var h = pst.getHours();
                var m = pst.getMinutes();
                var s = pst.getSeconds();
                var ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                el.textContent =
                    String(h).padStart(2, '0') + ':' +
                    String(m).padStart(2, '0') + ':' +
                    String(s).padStart(2, '0') + ' ' + ampm;
            }
            updateClock();
            setInterval(updateClock, 1000);
        })();

        var currentPreviewData = null;

        function openPreview(data) {
            currentPreviewData = data;
            const overlay = document.getElementById('preview-overlay');
            const header  = document.getElementById('preview-header');

            // Set header gradient
            header.className = 'bg-gradient-to-r ' + data.gradient + ' px-6 py-4 flex items-center justify-between';
            document.getElementById('preview-icon').textContent = data.icon;
            document.getElementById('preview-title').textContent = data.title;
            document.getElementById('preview-dashboard-name').textContent = data.dashboard || data.title + ' Dashboard';
            document.getElementById('preview-content-title').textContent = (data.dashboard || data.title + ' Dashboard') + ' Overview';

            // Portal badge
            const portalBadge = document.getElementById('preview-portal-badge');
            const portalMap = {
                admin: '🔧 Admin Portal',
                department: '🏢 Department Portal',
                citizen: '🏠 Citizen Portal'
            };
            portalBadge.textContent = portalMap[data.portal] || '📱 Portal';

            // Access badge
            const accessBadge = document.getElementById('preview-access-badge');
            if (data.access) {
                const iconMap = { 'Full': '✏️', 'Write': '📝', 'Read-Only': '👁️' };
                accessBadge.textContent = (iconMap[data.access] || '') + ' ' + data.access;
                accessBadge.classList.remove('hidden');
            } else {
                accessBadge.classList.add('hidden');
            }

            // Access note
            const accessNote = document.getElementById('preview-access-note');
            const accessText = document.getElementById('preview-access-text');
            if (data.access) {
                const descriptions = {
                    'Full': 'Full access — create, edit, approve/reject, and manage all resources.',
                    'Write': 'Write access — create and edit records within assigned scope.',
                    'Read-Only': 'Read-only access — view data and reports, no editing capabilities.'
                };
                accessText.textContent = descriptions[data.access] || data.access;
                accessNote.classList.remove('hidden');
            } else {
                accessNote.classList.add('hidden');
            }

            // Populate sidebar (clickable)
            var sidebar = document.getElementById('preview-sidebar');
            var sidebarIcons = ['📊', '📋', '👥', '📁', '📈', '⚙️', '🔔', '📝', '🗂️', '📑'];
            sidebar.innerHTML = (data.features || []).map(function (f, i) {
                return '<button onclick="selectModule(' + i + ')" data-idx="' + i + '" class="sidebar-item w-full text-left flex items-center gap-2.5 px-3 py-2 rounded-lg '
                    + (i === 0 ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200')
                    + ' text-xs font-medium transition">'
                    + '<span>' + sidebarIcons[i % sidebarIcons.length] + '</span>'
                    + '<span>' + f + '</span>'
                    + '</button>';
            }).join('');

            // Show first module by default
            selectModule(0);

            // Show modal
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            const overlay = document.getElementById('preview-overlay');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.classList.remove('opacity-100');
            document.body.style.overflow = '';
        }

        function navigateToDashboard() {
            if (!currentPreviewData || !currentPreviewData.email) return;

            // Show toast
            var toast = document.getElementById('demo-toast');
            var toastMsg = document.getElementById('demo-toast-msg');
            toastMsg.textContent = 'Opening ' + currentPreviewData.title + ' dashboard…';
            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            // Fill & submit form after tiny delay so toast renders
            setTimeout(function () {
                document.getElementById('demo-email').value    = currentPreviewData.email;
                document.getElementById('demo-password').value = currentPreviewData.password;
                document.getElementById('demo-login-form').submit();
            }, 600);
        }

        function selectModule(idx) {
            if (!currentPreviewData) return;
            var features = currentPreviewData.features || [];
            var moduleName = features[idx] || 'Dashboard';
            var accent = currentPreviewData.accent || 'emerald';
            var access = currentPreviewData.access || '';

            // Highlight active sidebar item
            var items = document.querySelectorAll('#preview-sidebar .sidebar-item');
            items.forEach(function (el, i) {
                if (i === idx) {
                    el.className = el.className.replace(/text-slate-400|hover:bg-white\/5|hover:text-slate-200/g, '').replace('  ', ' ');
                    el.classList.add('bg-white/10', 'text-white');
                } else {
                    el.classList.remove('bg-white/10', 'text-white');
                    el.classList.add('text-slate-400');
                }
            });

            // Render module content
            var main = document.getElementById('preview-main');
            var content = getModuleContent(moduleName, accent, access, currentPreviewData);
            main.innerHTML = content;
        }

        // Module-specific mock content generator
        function getModuleContent(moduleName, accent, access, data) {
            var key = moduleName.toLowerCase().replace(/[^a-z0-9]/g, '');
            var html = '';

            // Breadcrumb
            html += '<div class="flex items-center gap-2 text-xs text-slate-400 mb-4">'
                + '<span class="hover:text-slate-600 cursor-default">' + (data.dashboard || 'Dashboard') + '</span>'
                + '<span>›</span>'
                + '<span class="font-semibold text-slate-700">' + moduleName + '</span>'
                + '</div>';

            // Module header
            html += '<div class="flex items-center justify-between mb-5">'
                + '<h3 class="text-lg font-bold text-slate-800">' + moduleName + '</h3>';
            if (access && access !== 'Read-Only') {
                html += '<button class="px-3 py-1.5 bg-' + accent + '-500 text-white text-[11px] font-bold rounded-lg shadow-sm cursor-default">+ New Record</button>';
            }
            html += '</div>';

            // Get module-specific mock data
            var mock = getModuleMockData(key, accent);

            // Stat row
            html += '<div class="grid grid-cols-2 md:grid-cols-' + mock.stats.length + ' gap-3 mb-6">';
            mock.stats.forEach(function (s) {
                html += '<div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">'
                    + '<div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">' + s.label + '</div>'
                    + '<div class="text-xl font-extrabold text-slate-800">' + s.value + '</div>'
                    + '<div class="text-[10px] font-semibold mt-1 ' + s.color + '">' + s.trend + '</div>'
                    + '</div>';
            });
            html += '</div>';

            // Data table
            html += '<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-5">';
            html += '<div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">'
                + '<span class="text-xs font-bold text-slate-700">' + mock.tableTitle + '</span>'
                + '<div class="flex items-center gap-2">'
                + '<input type="text" placeholder="Search..." class="text-[11px] border border-slate-200 rounded-lg px-3 py-1.5 w-40 focus:outline-none focus:border-' + accent + '-400" disabled>'
                + '<span class="text-[10px] text-slate-400">' + mock.rows.length + ' records</span>'
                + '</div></div>';

            // Table header
            html += '<table class="w-full"><thead><tr class="bg-slate-50 text-[10px] text-slate-500 font-bold uppercase tracking-wider">';
            mock.columns.forEach(function (col) {
                html += '<th class="px-4 py-2 text-left">' + col + '</th>';
            });
            if (access && access !== 'Read-Only') {
                html += '<th class="px-4 py-2 text-right">Actions</th>';
            }
            html += '</tr></thead><tbody>';

            // Table rows
            mock.rows.forEach(function (row, ri) {
                html += '<tr class="border-t border-slate-100 ' + (ri % 2 === 0 ? 'bg-white' : 'bg-slate-50/50') + ' text-xs text-slate-600">';
                row.forEach(function (cell) {
                    if (cell.badge) {
                        html += '<td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold ' + cell.badge + '">' + cell.text + '</span></td>';
                    } else {
                        html += '<td class="px-4 py-2.5">' + cell + '</td>';
                    }
                });
                if (access && access !== 'Read-Only') {
                    html += '<td class="px-4 py-2.5 text-right">'
                        + '<span class="text-' + accent + '-600 font-semibold cursor-default text-[11px]">View</span>'
                        + (access === 'Full' ? ' <span class="text-slate-300 mx-1">|</span> <span class="text-amber-600 font-semibold cursor-default text-[11px]">Edit</span>' : '')
                        + '</td>';
                }
                html += '</tr>';
            });
            html += '</tbody></table></div>';

            // Access note
            if (access) {
                var accessDesc = {
                    'Full': 'Full access — create, edit, approve/reject, and manage all resources.',
                    'Write': 'Write access — create and edit records within assigned scope.',
                    'Read-Only': 'Read-only access — view data and reports, no editing capabilities.'
                };
                var accessColor = { 'Full': 'emerald', 'Write': 'blue', 'Read-Only': 'amber' };
                var ac = accessColor[access] || 'blue';
                html += '<div class="bg-' + ac + '-50 border border-' + ac + '-200 rounded-xl p-4 text-xs text-' + ac + '-700">'
                    + '<strong>Access Level:</strong> ' + (accessDesc[access] || access)
                    + '</div>';
            }

            return html;
        }

        // Mock data per module type
        function getModuleMockData(key, accent) {
            var presets = {
                admindashboard: {
                    tableTitle: 'Recent Activity',
                    columns: ['Action', 'User', 'Module', 'Timestamp'],
                    stats: [
                        {label: 'Total Residents', value: '12,847', trend: '↑ 3.2% this month', color: 'text-emerald-600'},
                        {label: 'Active Services', value: '24', trend: '3 new this week', color: 'text-blue-600'},
                        {label: 'Pending Requests', value: '156', trend: '42 urgent', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['Record created', 'Admin User', 'Residents', 'Mar 10, 2026 09:14'],
                        ['Service approved', 'Super Admin', 'Requests', 'Mar 10, 2026 08:52'],
                        ['Household verified', 'Admin User', 'Verification', 'Mar 09, 2026 16:30'],
                        ['Data exported', 'Super Admin', 'Reports', 'Mar 09, 2026 14:15'],
                    ]
                },
                servicemanagement: {
                    tableTitle: 'Service Catalog',
                    columns: ['Service Name', 'Category', 'Fee', 'Status'],
                    stats: [
                        {label: 'Active Services', value: '24', trend: '3 new this month', color: 'text-emerald-600'},
                        {label: 'Requests Today', value: '18', trend: '↑ 22% vs yesterday', color: 'text-blue-600'},
                        {label: 'Avg Processing', value: '2.4d', trend: '↓ 0.3d improved', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Barangay Clearance', 'Certification', '₱50.00', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Business Permit', 'Licensing', '₱500.00', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Building Permit', 'Infrastructure', '₱1,200.00', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Indigency Certificate', 'Social Welfare', 'Free', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                residenthouseholdmanagement: {
                    tableTitle: 'Resident Records',
                    columns: ['Name', 'Barangay', 'Status', 'Registered'],
                    stats: [
                        {label: 'Total Residents', value: '12,847', trend: '↑ 127 new this month', color: 'text-emerald-600'},
                        {label: 'Households', value: '3,412', trend: '98% geocoded', color: 'text-blue-600'},
                        {label: 'Verified', value: '89%', trend: '↑ 2.1% this quarter', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Juan D. Cruz', 'Brgy. Centro', {text: 'Verified', badge: 'bg-emerald-100 text-emerald-700'}, 'Jan 15, 2026'],
                        ['Maria S. Reyes', 'Brgy. Palawig', {text: 'Verified', badge: 'bg-emerald-100 text-emerald-700'}, 'Feb 02, 2026'],
                        ['Pedro A. Santos', 'Brgy. Bacungan', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}, 'Mar 08, 2026'],
                        ['Ana L. Garcia', 'Brgy. Sta. Maria', {text: 'Verified', badge: 'bg-emerald-100 text-emerald-700'}, 'Mar 01, 2026'],
                    ]
                },
                verificationdashboard: {
                    tableTitle: 'Pending Verifications',
                    columns: ['Applicant', 'ID Type', 'Submitted', 'Status'],
                    stats: [
                        {label: 'Pending Review', value: '23', trend: '5 new today', color: 'text-amber-600'},
                        {label: 'Verified Today', value: '12', trend: '↑ 4 vs yesterday', color: 'text-emerald-600'},
                        {label: 'Success Rate', value: '94.2%', trend: '↑ 1.1% this month', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Carlos M. Valdez', 'PhilSys ID', 'Mar 10, 2026', {text: 'Under Review', badge: 'bg-blue-100 text-blue-700'}],
                        ['Rosa T. Magsaysay', 'PhilSys ID', 'Mar 09, 2026', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}],
                        ['Miguel R. Aquino', "Driver's License", 'Mar 09, 2026', {text: 'Approved', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Elena B. Santos', 'PhilSys ID', 'Mar 08, 2026', {text: 'Approved', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                activitylogs: {
                    tableTitle: 'System Activity Log',
                    columns: ['Timestamp', 'User', 'Action', 'Details'],
                    stats: [
                        {label: 'Today\'s Actions', value: '342', trend: '↑ 15% vs average', color: 'text-blue-600'},
                        {label: 'Active Users', value: '89', trend: '12 admins online', color: 'text-emerald-600'},
                        {label: 'System Health', value: '99.8%', trend: 'All services running', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['09:14:23', 'admin@buguey.gov.ph', 'CREATE', 'New resident record #4821'],
                        ['09:12:05', 'mayor@buguey.gov.ph', 'VIEW', 'Executive dashboard accessed'],
                        ['09:08:41', 'mswdo@buguey.gov.ph', 'UPDATE', 'Service request #1247 status changed'],
                        ['09:01:12', 'superadmin@buguey.gov.ph', 'EXPORT', 'Monthly report generated'],
                    ]
                },
                datacollectionhnhhnhhm: {
                    tableTitle: 'Household Collection Queue',
                    columns: ['Household #', 'Head of Family', 'Members', 'Status'],
                    stats: [
                        {label: 'Households Collected', value: '2,847', trend: '83% of target', color: 'text-blue-600'},
                        {label: 'Members Encoded', value: '11,204', trend: '↑ 342 this week', color: 'text-emerald-600'},
                        {label: 'Pending Encoding', value: '565', trend: '16 high priority', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['HH-2026-0412', 'Reyes, Eduardo G.', '5 members', {text: 'Complete', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['HH-2026-0413', 'Santos, Roberto G.', '3 members', {text: 'In Progress', badge: 'bg-blue-100 text-blue-700'}],
                        ['HH-2026-0414', 'Dela Cruz, Maria L.', '7 members', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}],
                        ['HH-2026-0415', 'Garcia, Manuel A.', '4 members', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}],
                    ]
                },
                residentdashboard: {
                    tableTitle: 'My Service Requests',
                    columns: ['Service', 'Request #', 'Filed', 'Status'],
                    stats: [
                        {label: 'My Requests', value: '8', trend: '2 in progress', color: 'text-blue-600'},
                        {label: 'Completed', value: '6', trend: 'Avg 2.3 days', color: 'text-emerald-600'},
                        {label: 'Announcements', value: '5', trend: '2 new this week', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['Barangay Clearance', 'REQ-2026-0842', 'Mar 08, 2026', {text: 'Processing', badge: 'bg-blue-100 text-blue-700'}],
                        ['Indigency Cert.', 'REQ-2026-0791', 'Mar 01, 2026', {text: 'Ready for Pickup', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Residency Cert.', 'REQ-2026-0688', 'Feb 15, 2026', {text: 'Completed', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                eservicesdirectory: {
                    tableTitle: 'Available E-Services',
                    columns: ['Service', 'Category', 'Processing Time', 'Fee'],
                    stats: [
                        {label: 'Services Available', value: '18', trend: 'All online', color: 'text-emerald-600'},
                        {label: 'Avg Wait Time', value: '2.1d', trend: '↓ Improving', color: 'text-emerald-600'},
                        {label: 'Satisfaction', value: '4.7/5', trend: 'Based on 1,200+ ratings', color: 'text-blue-600'},
                    ],
                    rows: [
                        ['Barangay Clearance', 'Certification', '1–2 days', '₱50.00'],
                        ['Business Permit', 'Licensing', '5–7 days', '₱500.00'],
                        ['Building Permit', 'Infrastructure', '7–14 days', '₱1,200.00'],
                        ['Indigency Certificate', 'Social Welfare', '1 day', 'Free'],
                    ]
                },
                myrequests: {
                    tableTitle: 'Request History',
                    columns: ['Request #', 'Service', 'Status', 'Last Updated'],
                    stats: [
                        {label: 'Total Filed', value: '8', trend: 'Since Jan 2026', color: 'text-blue-600'},
                        {label: 'Active', value: '2', trend: 'In progress', color: 'text-amber-600'},
                        {label: 'Completed', value: '6', trend: '100% success', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['REQ-2026-0842', 'Barangay Clearance', {text: 'Processing', badge: 'bg-blue-100 text-blue-700'}, 'Mar 09, 2026'],
                        ['REQ-2026-0791', 'Indigency Cert.', {text: 'Ready', badge: 'bg-emerald-100 text-emerald-700'}, 'Mar 05, 2026'],
                        ['REQ-2026-0688', 'Residency Cert.', {text: 'Completed', badge: 'bg-slate-100 text-slate-600'}, 'Feb 18, 2026'],
                    ]
                },
                myprofilehousehold: {
                    tableTitle: 'Household Members',
                    columns: ['Name', 'Relation', 'Age', 'Status'],
                    stats: [
                        {label: 'Household #', value: 'HH-2026-0412', trend: 'Brgy. Centro', color: 'text-blue-600'},
                        {label: 'Members', value: '5', trend: 'All verified', color: 'text-emerald-600'},
                        {label: 'Profile', value: '100%', trend: 'Complete', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Juan D. Cruz', 'Head', '42', {text: 'Verified', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Maria S. Cruz', 'Spouse', '39', {text: 'Verified', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Carlo D. Cruz', 'Son', '18', {text: 'Verified', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Ana D. Cruz', 'Daughter', '15', {text: 'Minor', badge: 'bg-slate-100 text-slate-600'}],
                    ]
                },
                announcementsnews: {
                    tableTitle: 'Latest Announcements',
                    columns: ['Title', 'Category', 'Posted', 'Views'],
                    stats: [
                        {label: 'Total Posts', value: '47', trend: '5 this week', color: 'text-blue-600'},
                        {label: 'Upcoming Events', value: '3', trend: 'Next: Mar 15', color: 'text-amber-600'},
                        {label: 'Total Views', value: '8.2K', trend: '↑ 18% this month', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Community Clean-Up Drive', 'Event', 'Mar 10, 2026', '142'],
                        ['Vaccination Schedule Update', 'Health', 'Mar 08, 2026', '389'],
                        ['Business Permit Renewal Reminder', 'Notice', 'Mar 05, 2026', '256'],
                        ['Sangguniang Bayan Session Schedule', 'Gov\'t', 'Mar 01, 2026', '198'],
                    ]
                },
                executivedashboard: {
                    tableTitle: 'Municipal Performance Summary',
                    columns: ['Department', 'KPI Score', 'Tasks Done', 'Status'],
                    stats: [
                        {label: 'Population', value: '45,821', trend: '↑ 2.1% annual growth', color: 'text-blue-600'},
                        {label: 'Budget Utilization', value: '78.4%', trend: 'On track for Q1', color: 'text-emerald-600'},
                        {label: 'Service Requests', value: '1,247', trend: '94% resolved', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Treasury', '92%', '847/920', {text: 'On Track', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Social Welfare', '88%', '612/695', {text: 'On Track', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Health Services', '95%', '1,204/1,267', {text: 'Excellent', badge: 'bg-blue-100 text-blue-700'}],
                        ['Planning & Dev', '81%', '423/522', {text: 'Needs Attention', badge: 'bg-amber-100 text-amber-700'}],
                    ]
                },
                populationanalytics: {
                    tableTitle: 'Population by Barangay',
                    columns: ['Barangay', 'Population', 'Households', 'Growth Rate'],
                    stats: [
                        {label: 'Total Population', value: '45,821', trend: 'Census 2026', color: 'text-blue-600'},
                        {label: 'Total Barangays', value: '24', trend: 'All reporting', color: 'text-emerald-600'},
                        {label: 'Avg Household Size', value: '4.3', trend: 'National avg: 4.1', color: 'text-slate-600'},
                    ],
                    rows: [
                        ['Brgy. Centro', '4,521', '1,042', '↑ 2.8%'],
                        ['Brgy. Palawig', '3,847', '894', '↑ 1.9%'],
                        ['Brgy. Bacungan', '2,195', '512', '↑ 3.1%'],
                        ['Brgy. Sta. Maria', '1,892', '438', '↑ 2.4%'],
                    ]
                },
                mastercollections: {
                    tableTitle: 'Master Data Collections',
                    columns: ['Collection', 'Records', 'Last Updated', 'Status'],
                    stats: [
                        {label: 'Collections', value: '12', trend: 'All synchronized', color: 'text-emerald-600'},
                        {label: 'Total Records', value: '48,293', trend: '↑ 1,204 this month', color: 'text-blue-600'},
                        {label: 'Data Quality', value: '97.1%', trend: '↑ 0.4% improved', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Residents', '12,847', 'Mar 10, 2026', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Households', '3,412', 'Mar 10, 2026', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Services', '24', 'Mar 08, 2026', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Barangays', '24', 'Jan 01, 2026', {text: 'Locked', badge: 'bg-slate-100 text-slate-600'}],
                    ]
                },
                analytics: {
                    tableTitle: 'Analytics Reports',
                    columns: ['Report', 'Period', 'Generated', 'Type'],
                    stats: [
                        {label: 'Reports Available', value: '15', trend: '3 scheduled', color: 'text-blue-600'},
                        {label: 'Data Points', value: '142K', trend: '↑ Real-time', color: 'text-emerald-600'},
                        {label: 'Last Refresh', value: '2m ago', trend: 'Auto-updating', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Population Overview', 'Monthly', 'Mar 10, 2026', 'Dashboard'],
                        ['Service Performance', 'Weekly', 'Mar 09, 2026', 'Chart'],
                        ['Revenue Summary', 'Quarterly', 'Mar 01, 2026', 'Report'],
                        ['Department KPIs', 'Monthly', 'Mar 01, 2026', 'Scorecard'],
                    ]
                },
                financialmodule: {
                    tableTitle: 'Recent Transactions',
                    columns: ['Transaction ID', 'Description', 'Amount', 'Status'],
                    stats: [
                        {label: 'Revenue (MTD)', value: '₱2.4M', trend: '↑ 12% vs last month', color: 'text-emerald-600'},
                        {label: 'Pending Collections', value: '₱342K', trend: '124 accounts', color: 'text-amber-600'},
                        {label: 'Disbursements', value: '₱1.8M', trend: '78% budget used', color: 'text-blue-600'},
                    ],
                    rows: [
                        ['TXN-2026-8421', 'Business Permit Fee', '₱12,500.00', {text: 'Collected', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['TXN-2026-8420', 'Real Property Tax', '₱8,200.00', {text: 'Collected', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['TXN-2026-8419', 'Building Permit Fee', '₱25,000.00', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}],
                        ['TXN-2026-8418', 'Clearance Fee', '₱50.00', {text: 'Collected', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                financialmodulereadonly: {
                    tableTitle: 'Financial Records (View Only)',
                    columns: ['Transaction ID', 'Description', 'Amount', 'Status'],
                    stats: [
                        {label: 'Revenue (MTD)', value: '₱2.4M', trend: '↑ 12% vs last month', color: 'text-emerald-600'},
                        {label: 'Audit Items', value: '18', trend: '3 flagged', color: 'text-amber-600'},
                        {label: 'Reconciled', value: '96.8%', trend: '↑ Improving', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['TXN-2026-8421', 'Business Permit Fee', '₱12,500.00', {text: 'Collected', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['TXN-2026-8420', 'Real Property Tax', '₱8,200.00', {text: 'Collected', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['TXN-2026-8419', 'Building Permit Fee', '₱25,000.00', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}],
                    ]
                },
                welfaretargeting: {
                    tableTitle: 'Welfare Beneficiaries',
                    columns: ['Name', 'Program', 'Barangay', 'Status'],
                    stats: [
                        {label: 'Active Beneficiaries', value: '2,847', trend: '15 new this week', color: 'text-blue-600'},
                        {label: 'Programs', value: '8', trend: 'All funded', color: 'text-emerald-600'},
                        {label: 'Disbursed (MTD)', value: '₱890K', trend: '72% of allocation', color: 'text-blue-600'},
                    ],
                    rows: [
                        ['Rosa T. Magsaysay', '4Ps', 'Brgy. Centro', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Pedro A. Santos', 'Senior Citizen', 'Brgy. Palawig', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Elena B. Garcia', 'PWD Support', 'Brgy. Bacungan', {text: 'For Review', badge: 'bg-amber-100 text-amber-700'}],
                        ['Carlos M. Valdez', 'Solo Parent', 'Brgy. Sta. Maria', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                householdmanagement: {
                    tableTitle: 'Household Records',
                    columns: ['Household #', 'Head', 'Barangay', 'Members'],
                    stats: [
                        {label: 'Total Households', value: '3,412', trend: '98% geocoded', color: 'text-blue-600'},
                        {label: 'Avg Members', value: '4.3', trend: 'Per household', color: 'text-slate-600'},
                        {label: 'Updated This Month', value: '247', trend: '↑ Active encoding', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['HH-2026-0412', 'Reyes, Eduardo G.', 'Brgy. Centro', '5'],
                        ['HH-2026-0413', 'Santos, Roberto G.', 'Brgy. Palawig', '3'],
                        ['HH-2026-0414', 'Dela Cruz, Maria L.', 'Brgy. Bacungan', '7'],
                        ['HH-2026-0415', 'Garcia, Manuel A.', 'Brgy. Sta. Maria', '4'],
                    ]
                },
                servicerequests: {
                    tableTitle: 'Service Request Queue',
                    columns: ['Request #', 'Resident', 'Service', 'Status'],
                    stats: [
                        {label: 'Open Requests', value: '156', trend: '42 urgent', color: 'text-amber-600'},
                        {label: 'Resolved Today', value: '23', trend: '↑ vs 18 avg', color: 'text-emerald-600'},
                        {label: 'Avg Resolution', value: '2.4d', trend: '↓ 0.3d improved', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['REQ-2026-1247', 'Juan D. Cruz', 'Barangay Clearance', {text: 'Processing', badge: 'bg-blue-100 text-blue-700'}],
                        ['REQ-2026-1246', 'Maria S. Reyes', 'Indigency Cert.', {text: 'Ready', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['REQ-2026-1245', 'Pedro A. Santos', 'Business Permit', {text: 'Under Review', badge: 'bg-amber-100 text-amber-700'}],
                        ['REQ-2026-1244', 'Ana L. Garcia', 'Building Permit', {text: 'Pending Payment', badge: 'bg-amber-100 text-amber-700'}],
                    ]
                },
                healthservicesmodule: {
                    tableTitle: 'Patient Records',
                    columns: ['Patient', 'Service', 'Date', 'Status'],
                    stats: [
                        {label: 'Patients (MTD)', value: '847', trend: '↑ 12% vs last month', color: 'text-blue-600'},
                        {label: 'Vaccinations', value: '234', trend: 'This week', color: 'text-emerald-600'},
                        {label: 'Active Programs', value: '6', trend: 'All funded', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Rosa T. Santos', 'Prenatal Checkup', 'Mar 10, 2026', {text: 'Completed', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Carlos M. Valdez', 'Vaccination', 'Mar 10, 2026', {text: 'Scheduled', badge: 'bg-blue-100 text-blue-700'}],
                        ['Elena B. Garcia', 'Dental Service', 'Mar 09, 2026', {text: 'Completed', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Miguel R. Aquino', 'Blood Pressure', 'Mar 09, 2026', {text: 'Completed', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                emergencyalerts: {
                    tableTitle: 'Active Alerts & Incidents',
                    columns: ['Alert ID', 'Type', 'Location', 'Severity'],
                    stats: [
                        {label: 'Active Alerts', value: '3', trend: '1 critical', color: 'text-red-600'},
                        {label: 'Evacuees', value: '142', trend: '2 evac centers', color: 'text-amber-600'},
                        {label: 'Response Teams', value: '5/6', trend: 'Deployed', color: 'text-blue-600'},
                    ],
                    rows: [
                        ['ALT-2026-0089', 'Flood Warning', 'Brgy. Palawig', {text: 'Critical', badge: 'bg-red-100 text-red-700'}],
                        ['ALT-2026-0088', 'Road Closure', 'Brgy. Centro', {text: 'Active', badge: 'bg-amber-100 text-amber-700'}],
                        ['ALT-2026-0087', 'Power Outage', 'Brgy. Bacungan', {text: 'Monitoring', badge: 'bg-blue-100 text-blue-700'}],
                    ]
                },
                blottermanagement: {
                    tableTitle: 'Blotter Records',
                    columns: ['Blotter #', 'Type', 'Reported By', 'Status'],
                    stats: [
                        {label: 'Total Records', value: '412', trend: 'This year', color: 'text-blue-600'},
                        {label: 'Open Cases', value: '28', trend: '5 high priority', color: 'text-amber-600'},
                        {label: 'Resolved', value: '384', trend: '93.2% resolution', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['BLT-2026-0412', 'Property Dispute', 'Juan D. Cruz', {text: 'Under Mediation', badge: 'bg-blue-100 text-blue-700'}],
                        ['BLT-2026-0411', 'Noise Complaint', 'Maria S. Reyes', {text: 'Resolved', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['BLT-2026-0410', 'Theft Report', 'Pedro A. Santos', {text: 'Referred to PNP', badge: 'bg-amber-100 text-amber-700'}],
                    ]
                },
                livelihoodmodule: {
                    tableTitle: 'Livelihood Programs',
                    columns: ['Program', 'Beneficiaries', 'Budget', 'Status'],
                    stats: [
                        {label: 'Active Programs', value: '6', trend: 'All funded', color: 'text-emerald-600'},
                        {label: 'Beneficiaries', value: '1,247', trend: '↑ 89 new this month', color: 'text-blue-600'},
                        {label: 'Disbursed', value: '₱3.2M', trend: '68% of allocation', color: 'text-blue-600'},
                    ],
                    rows: [
                        ['Rice Farming Support', '342', '₱1,200,000', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Fisheries Aid', '189', '₱800,000', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Skills Training', '95', '₱450,000', {text: 'Enrolling', badge: 'bg-blue-100 text-blue-700'}],
                        ['Micro-Enterprise Loan', '67', '₱750,000', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                businesspermitsmodule: {
                    tableTitle: 'Permit Applications',
                    columns: ['Business Name', 'Type', 'Applied', 'Status'],
                    stats: [
                        {label: 'Active Permits', value: '847', trend: '↑ 23 new this month', color: 'text-blue-600'},
                        {label: 'Revenue (MTD)', value: '₱1.2M', trend: '↑ 18% vs last year', color: 'text-emerald-600'},
                        {label: 'Pending Review', value: '34', trend: '8 for renewal', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['Reyes Sari-Sari Store', 'New', 'Mar 08, 2026', {text: 'Processing', badge: 'bg-blue-100 text-blue-700'}],
                        ['Santos Hardware', 'Renewal', 'Mar 05, 2026', {text: 'Approved', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Garcia Trading', 'New', 'Mar 01, 2026', {text: 'For Inspection', badge: 'bg-amber-100 text-amber-700'}],
                        ['Cruz Pharmacy', 'Renewal', 'Feb 28, 2026', {text: 'Approved', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                civilregistrymodule: {
                    tableTitle: 'Registry Records',
                    columns: ['Registry #', 'Type', 'Name', 'Status'],
                    stats: [
                        {label: 'Total Records', value: '24,847', trend: 'All digitized', color: 'text-blue-600'},
                        {label: 'This Month', value: '42', trend: '18 births, 12 marriages', color: 'text-emerald-600'},
                        {label: 'Pending Cert.', value: '15', trend: '3 rush requests', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['CR-2026-0842', 'Birth', 'Baby Santos', {text: 'Registered', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['CR-2026-0841', 'Marriage', 'Cruz & Reyes', {text: 'Registered', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['CR-2026-0840', 'Death', 'Pedro G. Martinez', {text: 'Processing', badge: 'bg-blue-100 text-blue-700'}],
                        ['CR-2026-0839', 'Birth', 'Baby Garcia', {text: 'Registered', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                transparencyboard: {
                    tableTitle: 'Published Documents',
                    columns: ['Document', 'Type', 'Published', 'Views'],
                    stats: [
                        {label: 'Documents', value: '156', trend: '12 new this quarter', color: 'text-blue-600'},
                        {label: 'Total Views', value: '24.8K', trend: '↑ Public access', color: 'text-emerald-600'},
                        {label: 'Compliance', value: '100%', trend: 'DILG compliant', color: 'text-emerald-600'},
                    ],
                    rows: [
                        ['Annual Budget FY 2026', 'Budget', 'Jan 15, 2026', '1,247'],
                        ['Q4 2025 Financial Report', 'Financial', 'Jan 05, 2026', '892'],
                        ['Approved Ordinances 2026', 'Legislative', 'Feb 01, 2026', '534'],
                        ['Public Bidding Results', 'Procurement', 'Mar 01, 2026', '312'],
                    ]
                },
                buildingpermits: {
                    tableTitle: 'Building Permit Applications',
                    columns: ['Permit #', 'Applicant', 'Type', 'Status'],
                    stats: [
                        {label: 'Applications (MTD)', value: '18', trend: '↑ 5 more vs Feb', color: 'text-blue-600'},
                        {label: 'Approved', value: '12', trend: 'This month', color: 'text-emerald-600'},
                        {label: 'Revenue', value: '₱245K', trend: 'From permits', color: 'text-blue-600'},
                    ],
                    rows: [
                        ['BP-2026-0089', 'Eduardo G. Reyes', 'Residential', {text: 'Approved', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['BP-2026-0088', 'Santos Trading Co.', 'Commercial', {text: 'For Inspection', badge: 'bg-amber-100 text-amber-700'}],
                        ['BP-2026-0087', 'Maria L. Garcia', 'Renovation', {text: 'Under Review', badge: 'bg-blue-100 text-blue-700'}],
                    ]
                },
                locationalclearance: {
                    tableTitle: 'Clearance Applications',
                    columns: ['Clearance #', 'Applicant', 'Purpose', 'Status'],
                    stats: [
                        {label: 'Issued (MTD)', value: '24', trend: '↑ 8 more vs Feb', color: 'text-emerald-600'},
                        {label: 'Pending', value: '7', trend: '2 for site visit', color: 'text-amber-600'},
                        {label: 'Processing Time', value: '3.2d', trend: 'Avg turnaround', color: 'text-blue-600'},
                    ],
                    rows: [
                        ['LC-2026-0124', 'Reyes Corp.', 'Commercial Bldg', {text: 'Issued', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['LC-2026-0123', 'Santos, Roberto', 'Residential', {text: 'Issued', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['LC-2026-0122', 'Garcia Enterprises', 'Warehouse', {text: 'For Site Visit', badge: 'bg-amber-100 text-amber-700'}],
                    ]
                },
                roleassignment: {
                    tableTitle: 'Department Staff Roles',
                    columns: ['Staff Name', 'Role Code', 'Department', 'Access'],
                    stats: [
                        {label: 'Total Staff', value: '28', trend: 'Active accounts', color: 'text-blue-600'},
                        {label: 'Departments', value: '7', trend: 'All staffed', color: 'text-emerald-600'},
                        {label: 'Recent Changes', value: '3', trend: 'This month', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['Lourdes G. Dela Cruz', 'MPDC', 'Planning', {text: 'Write', badge: 'bg-blue-100 text-blue-700'}],
                        ['Maricel G. Tanaka', 'TRESR', 'Financial', {text: 'Full', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Teresita G. Bautista', 'MSWDO', 'Social Welfare', {text: 'Write', badge: 'bg-blue-100 text-blue-700'}],
                        ['Rodrigo G. Aquino', 'MHO', 'Health', {text: 'Full', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                staffmanagement: {
                    tableTitle: 'Staff Directory',
                    columns: ['Name', 'Position', 'Department', 'Status'],
                    stats: [
                        {label: 'Active Staff', value: '28', trend: 'All departments', color: 'text-blue-600'},
                        {label: 'Online Now', value: '14', trend: 'Last 24 hours', color: 'text-emerald-600'},
                        {label: 'Pending Accounts', value: '2', trend: 'Awaiting approval', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['Eduardo G. Reyes', 'Municipal Mayor', 'Executive', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Milagros G. Evangelista', 'SB Secretary', 'Legislative', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Remedios G. Castillo', 'HR Officer', 'HRMO', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                        ['Josefina G. Hernandez', 'Licensing Head', 'BPLO', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                    ]
                },
                residentrecords: {
                    tableTitle: 'Resident Database',
                    columns: ['Name', 'Barangay', 'Verified', 'Registered'],
                    stats: [
                        {label: 'Total Records', value: '12,847', trend: '↑ 127 this month', color: 'text-blue-600'},
                        {label: 'Verified', value: '11,434', trend: '89% of total', color: 'text-emerald-600'},
                        {label: 'Unverified', value: '1,413', trend: 'Outreach pending', color: 'text-amber-600'},
                    ],
                    rows: [
                        ['Juan D. Cruz', 'Brgy. Centro', {text: '✓ Yes', badge: 'bg-emerald-100 text-emerald-700'}, 'Jan 15, 2026'],
                        ['Maria S. Reyes', 'Brgy. Palawig', {text: '✓ Yes', badge: 'bg-emerald-100 text-emerald-700'}, 'Feb 02, 2026'],
                        ['Pedro A. Santos', 'Brgy. Bacungan', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}, 'Mar 08, 2026'],
                        ['Ana L. Garcia', 'Brgy. Sta. Maria', {text: '✓ Yes', badge: 'bg-emerald-100 text-emerald-700'}, 'Mar 01, 2026'],
                    ]
                },
                barangayoverview: {
                    tableTitle: 'Barangay Summary',
                    columns: ['Barangay', 'Captain', 'Population', 'Households'],
                    stats: [
                        {label: 'Total Barangays', value: '24', trend: 'All active', color: 'text-emerald-600'},
                        {label: 'Total Population', value: '45,821', trend: 'Census 2026', color: 'text-blue-600'},
                        {label: 'Avg Population', value: '1,909', trend: 'Per barangay', color: 'text-slate-600'},
                    ],
                    rows: [
                        ['Brgy. Centro', 'Kap. Reyes', '4,521', '1,042'],
                        ['Brgy. Palawig', 'Kap. Santos', '3,847', '894'],
                        ['Brgy. Bacungan', 'Kap. Garcia', '2,195', '512'],
                        ['Brgy. Sta. Maria', 'Kap. Cruz', '1,892', '438'],
                    ]
                },
            };

            // Try exact match, then fuzzy match
            if (presets[key]) return presets[key];

            // Fuzzy: find partial key matches
            var found = null;
            Object.keys(presets).forEach(function (k) {
                if (!found && (key.indexOf(k) !== -1 || k.indexOf(key) !== -1)) found = presets[k];
            });
            if (found) return found;

            // Generic fallback
            return {
                tableTitle: moduleName + ' Records',
                columns: ['ID', 'Description', 'Date', 'Status'],
                stats: [
                    {label: 'Total Records', value: '247', trend: '↑ Active', color: 'text-blue-600'},
                    {label: 'This Month', value: '34', trend: 'New entries', color: 'text-emerald-600'},
                    {label: 'Pending', value: '12', trend: 'For review', color: 'text-amber-600'},
                ],
                rows: [
                    ['REC-001', 'Sample record entry', 'Mar 10, 2026', {text: 'Active', badge: 'bg-emerald-100 text-emerald-700'}],
                    ['REC-002', 'Another record entry', 'Mar 09, 2026', {text: 'Pending', badge: 'bg-amber-100 text-amber-700'}],
                    ['REC-003', 'Third record entry', 'Mar 08, 2026', {text: 'Completed', badge: 'bg-slate-100 text-slate-600'}],
                ]
            };
        }

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePreview();
        });
    </script>

</body>
</html>
