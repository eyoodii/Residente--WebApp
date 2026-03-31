{{-- Page Loader Overlay --}}
<div id="page-loader" style="
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0;
    background: linear-gradient(160deg, #034732 0%, #022b1f 50%, #011a13 100%);
    transition: opacity 0.6s ease, visibility 0.6s ease, transform 0.6s ease;
">
    {{-- Subtle radial glow behind logo --}}
    <div style="
        position: absolute;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,129,72,0.15) 0%, transparent 70%);
        pointer-events: none;
    "></div>

    {{-- Logo with animated ring --}}
    <div style="position: relative; margin-bottom: 28px;">
        {{-- Spinning ring behind logo --}}
        <div style="
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.06);
            border-top-color: #c6c013;
            border-right-color: rgba(198,192,19,0.3);
            animation: loaderRingSpin 1.8s linear infinite;
        "></div>
        <img src="{{ asset('logo_buguey.png') }}"
             alt="Buguey Logo"
             style="
                width: 88px; height: 88px;
                object-fit: contain;
                border-radius: 50%;
                background: white;
                padding: 6px;
                box-shadow: 0 4px 24px rgba(0,0,0,0.5), 0 0 40px rgba(0,129,72,0.2);
                animation: loaderLogoIn 0.7s ease both;
                position: relative; z-index: 1;
             ">
    </div>

    {{-- RESIDENTE lettering --}}
    <div style="
        display: flex; gap: 3px;
        margin-bottom: 16px;
        animation: loaderFadeUp 0.6s ease 0.3s both;
    ">
        @foreach(str_split('RESIDENTE') as $i => $letter)
            <span style="
                color: {{ $i === 0 ? '#c6c013' : 'rgba(255,255,255,0.9)' }};
                font-size: 18px;
                font-weight: 800;
                letter-spacing: 4px;
                font-family: 'Figtree', sans-serif;
                animation: loaderLetterIn 0.4s ease {{ 0.4 + ($i * 0.05) }}s both;
            ">{{ $letter }}</span>
        @endforeach
    </div>

    {{-- Three-dot bounce indicator --}}
    <div style="display: flex; gap: 6px; margin-bottom: 20px; animation: loaderFadeUp 0.5s ease 0.8s both;">
        <span class="loader-dot" style="animation-delay: 0s;"></span>
        <span class="loader-dot" style="animation-delay: 0.15s;"></span>
        <span class="loader-dot" style="animation-delay: 0.3s;"></span>
    </div>
    

    {{-- Bottom progress bar --}}
    <div style="
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        background: rgba(255,255,255,0.05);
        overflow: hidden;
    ">
        <div style="
            height: 100%;
            background: linear-gradient(90deg, #034732, #c6c013, #008148);
            animation: loaderProgress 1.8s ease-in-out infinite;
            border-radius: 2px;
        "></div>
    </div>
</div>

<style>
    @keyframes loaderRingSpin {
        to { transform: rotate(360deg); }
    }
    @keyframes loaderLogoIn {
        from { opacity: 0; transform: scale(0.7); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes loaderLetterIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes loaderFadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes loaderDotBounce {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.3; }
        40%            { transform: scale(1);   opacity: 1; }
    }
    @keyframes loaderProgress {
        0%   { width: 0%; margin-left: 0; }
        50%  { width: 60%; margin-left: 20%; }
        100% { width: 0%; margin-left: 100%; }
    }
    .loader-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #c6c013;
        animation: loaderDotBounce 1.2s ease-in-out infinite;
        display: block;
    }
    #page-loader.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: scale(1.02);
    }
</style>

<script>
    window.addEventListener('load', function () {
        var loader = document.getElementById('page-loader');
        if (loader) {
            setTimeout(function () {
                loader.classList.add('hidden');
                setTimeout(function () { loader.remove(); }, 650);
            }, 3000);
        }
    });
</script>
