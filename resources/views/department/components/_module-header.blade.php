{{--
    _module-header.blade.php
    Reusable banner for all department module pages.

    Props:
        $icon    — emoji icon
        $title   — page title
        $subtitle — description line
--}}
<div class="bg-gradient-to-r from-deep-forest to-sea-green text-white rounded-2xl p-7 shadow-xl flex items-center justify-between mb-8">
    <div>
        <p class="text-golden-glow text-xs font-bold uppercase tracking-widest mb-1">
            {{ auth()->user()->getDepartmentConfig()['department'] ?? '' }}
        </p>
        <h2 class="text-3xl font-extrabold tracking-tight">{{ $title }}</h2>
        <p class="text-gray-200 mt-1 text-sm max-w-xl">{{ $subtitle }}</p>
    </div>
    <div class="text-6xl opacity-20 hidden lg:block select-none">{{ $icon }}</div>
</div>
