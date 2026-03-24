{{-- Page Loader Overlay --}}
<div id="page-loader" style="
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    background: #034732;
    transition: opacity 0.5s ease, visibility 0.5s ease;
">
    <img src="{{ asset('logo_buguey.png') }}"
         alt="Buguey Logo"
         style="width: 90px; height: 90px; object-fit: contain; border-radius: 50%; background: white; padding: 6px; box-shadow: 0 8px 32px rgba(0,0,0,0.4); animation: loaderPulse 1.4s ease-in-out infinite;">

    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
        {{-- Spinner --}}
        <div style="
            width: 44px; height: 44px;
            border: 4px solid rgba(255,255,255,0.2);
            border-top-color: #c6c013;
            border-radius: 50%;
            animation: loaderSpin 0.8s linear infinite;
        "></div>
        <span style="color: #c6c013; font-size: 13px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; font-family: sans-serif;">
            Loading&hellip;
        </span>
    </div>

    <p style="color: rgba(255,255,255,0.5); font-size: 11px; font-family: sans-serif; margin-top: 4px;">
        Municipality of Buguey &mdash; RESIDENTE
    </p>
</div>

<style>
    @keyframes loaderSpin {
        to { transform: rotate(360deg); }
    }
    @keyframes loaderPulse {
        0%, 100% { transform: scale(1);    opacity: 1; }
        50%       { transform: scale(1.06); opacity: 0.85; }
    }
    #page-loader.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
</style>

<script>
    window.addEventListener('load', function () {
        const loader = document.getElementById('page-loader');
        if (loader) {
            setTimeout(function () {
                loader.classList.add('hidden');
                setTimeout(function () { loader.remove(); }, 550);
            }, 300);
        }
    });
</script>
