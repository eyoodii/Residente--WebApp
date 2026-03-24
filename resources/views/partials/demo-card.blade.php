{{--
    Demo role card partial — View-only mode with live navigation.

    Props:
      $gradient    – Tailwind bg-gradient-to-br classes
      $icon        – Emoji icon
      $title       – Role display name
      $badge       – Short role code string
      $badgeColor  – Tailwind bg + text classes for the badge
      $description – One-paragraph description
      $features    – Array of feature strings (sidebar modules this role can access)
      $email       – Login email (used behind the scenes for live navigation)
      $password    – Login password (used behind the scenes for live navigation)
      $access      – Optional access level label (e.g. "Full", "Write", "Read-Only")
      $accent      – Tailwind color for left stripe & feature chips (e.g. 'emerald', 'indigo')
      $portal      – Which portal they land on: 'admin', 'department', 'citizen'
      $dashboard   – Name of the specific dashboard view they see (e.g. 'Executive Dashboard')
--}}
@php
    $accentVal = $accent ?? 'emerald';
    $stripeClass   = 'bg-' . $accentVal . '-500';
    $chipBg        = 'bg-' . $accentVal . '-50';
    $chipText      = 'text-' . $accentVal . '-700';
    $chipBorder    = 'border-' . $accentVal . '-200';
    $chipDot       = 'bg-' . $accentVal . '-400';

    $portalLabel = match($portal ?? 'citizen') {
        'admin'      => ['🔧 Admin Portal',       'bg-slate-800 text-slate-200'],
        'department' => ['🏢 Department Portal',   'bg-blue-800 text-blue-200'],
        'citizen'    => ['🏠 Citizen Portal',      'bg-emerald-800 text-emerald-200'],
        default      => ['📱 Portal',              'bg-gray-800 text-gray-200'],
    };

    $previewData = json_encode([
        'title'     => $title,
        'icon'      => $icon,
        'gradient'  => $gradient,
        'portal'    => $portal ?? 'citizen',
        'dashboard' => $dashboard ?? '',
        'access'    => $access ?? '',
        'features'  => $features,
        'accent'    => $accentVal,
        'email'     => $email ?? '',
        'password'  => $password ?? '',
    ]);
@endphp
<div class="role-card bg-white rounded-2xl shadow-lg shadow-slate-200/60 border border-slate-100 overflow-hidden card-enter relative">

    {{-- Color accent stripe (left edge) --}}
    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $stripeClass }} rounded-l-2xl"></div>

    {{-- Card header --}}
    <div class="bg-gradient-to-br {{ $gradient }} px-5 py-5 flex items-center justify-between ml-1.5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/25 rounded-xl flex items-center justify-center text-2xl shadow-inner backdrop-blur-sm">
                {{ $icon }}
            </div>
            <div>
                <h3 class="font-extrabold text-white text-lg leading-tight drop-shadow-sm">{{ $title }}</h3>
                <span class="inline-block mt-1 px-2.5 py-0.5 text-[10px] font-bold tracking-widest rounded-full {{ $badgeColor }} shadow-sm">
                    {{ $badge }}
                </span>
            </div>
        </div>
        @if(!empty($access))
            @php
                $accessStyles = match($access) {
                    'Full'      => 'bg-emerald-400/30 text-emerald-100 border border-emerald-300/40',
                    'Write'     => 'bg-blue-400/30 text-blue-100 border border-blue-300/40',
                    'Read-Only' => 'bg-amber-400/30 text-amber-100 border border-amber-300/40',
                    default     => 'bg-white/20 text-white border border-white/30',
                };
            @endphp
            <span class="text-[10px] font-bold tracking-wide px-3 py-1 rounded-full {{ $accessStyles }}">
                @if($access === 'Full') ✏️ @elseif($access === 'Write') 📝 @else 👁️ @endif
                {{ $access }}
            </span>
        @endif
    </div>

    {{-- Card body --}}
    <div class="p-5 pl-[1.625rem] flex flex-col gap-4">

        {{-- Portal & Dashboard info --}}
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold tracking-wide rounded-md {{ $portalLabel[1] }}">
                {{ $portalLabel[0] }}
            </span>
            @if(!empty($dashboard))
                <span class="text-[10px] text-slate-400 font-medium">→</span>
                <span class="text-[11px] font-semibold text-slate-700">{{ $dashboard }}</span>
            @endif
        </div>

        <p class="text-sm text-slate-600 leading-relaxed">{{ $description }}</p>

        {{-- Sidebar Modules / Feature chips --}}
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Sidebar Modules</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach($features as $feature)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-lg border {{ $chipBg }} {{ $chipText }} {{ $chipBorder }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $chipDot }} flex-shrink-0"></span>
                        {{ $feature }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- View Dashboard Preview button --}}
        <button onclick='openPreview({!! $previewData !!})'
                class="w-full py-2.5 px-4 bg-gradient-to-r {{ $gradient }} text-white font-bold text-sm rounded-xl
                       hover:opacity-90 active:scale-[0.98] transition shadow-md shadow-black/10 flex items-center justify-center gap-2">
            <span>👁️</span> View {{ $title }} Dashboard
        </button>
    </div>
</div>
