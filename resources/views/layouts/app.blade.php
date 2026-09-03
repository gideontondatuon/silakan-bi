<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SILAKAN — Sistem Informasi Layanan Kantor KPwBI Provinsi Sulawesi Utara">
    <meta name="theme-color" content="#005baa">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>SILAKAN | KPwBI Prov. Sulut</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bi2.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-bi2.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/silakan.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bi-theme.css') }}">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    {{-- =============================================
         PAGE LOADING SYSTEM
         Menampilkan progress bar & overlay tipis saat
         berpindah menu untuk menggantikan animasi berat.
         ============================================= --}}
    <style>
        /* ===== TOP PROGRESS BAR ===== */
        #page-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #005baa, #0284c7, #38bdf8);
            z-index: 999999;
            transition: width 0.25s ease, opacity 0.3s ease;
            opacity: 0;
            box-shadow: 0 0 8px rgba(0, 91, 170, 0.6);
            pointer-events: none;
        }

        #page-progress.loading {
            opacity: 1;
        }

        #page-progress.complete {
            width: 100% !important;
            opacity: 0;
            transition: width 0.15s ease, opacity 0.4s ease 0.15s;
        }

        /* ===== SHIMMER GLOW at tip ===== */
        #page-progress::after {
            content: '';
            position: absolute;
            right: 0;
            top: -2px;
            width: 80px;
            height: 7px;
            background: radial-gradient(ellipse at right, rgba(56,189,248,0.8) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* ===== CONTENT OVERLAY (subtle fade) ===== */
        #page-loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(248, 250, 252, 0);
            z-index: 99998;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s ease;
        }

        #page-loading-overlay.visible {
            opacity: 1;
            background: rgba(248, 250, 252, 0.45);
            pointer-events: all;
            cursor: wait;
        }

        /* ===== LOADING SPINNER (center, subtle) ===== */
        #page-loading-spinner {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 999999;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 59, 115, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.7);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        }

        #page-loading-spinner.visible {
            opacity: 1;
            transform: scale(1);
        }

        #page-loading-spinner svg {
            width: 20px;
            height: 20px;
            animation: spin-loader 0.75s linear infinite;
        }

        @keyframes spin-loader {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* Sidebar nav links don't get cursor-wait when loading */
        .sidebar-nav-link, .sidebar-link {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="app">

    <x-sidebar />

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main">

        <x-navbar />

        <section class="content">
            {{-- Global Flash Success & Error Alerts --}}
            @if(session('success'))
            <div class="alert alert-success" id="global-flash-success" style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;color:#047857;margin-bottom:20px;box-shadow:0 4px 12px rgba(16,185,129,0.12);">
                <div style="display:flex;align-items:center;gap:12px;font-size:13.5px;font-weight:600;">
                    <i class="bi bi-check-circle-fill" style="font-size:18px;color:#059669;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#047857;cursor:pointer;font-size:18px;padding:0;display:flex;align-items:center;">&times;</button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger" id="global-flash-error" style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:#fef2f2;border:1px solid #fecdd3;border-radius:12px;color:#9f1239;margin-bottom:20px;box-shadow:0 4px 12px rgba(225,29,72,0.12);">
                <div style="display:flex;align-items:center;gap:12px;font-size:13.5px;font-weight:600;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:18px;color:#dc2626;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#9f1239;cursor:pointer;font-size:18px;padding:0;display:flex;align-items:center;">&times;</button>
            </div>
            @endif

            @if(isset($errors) && $errors->any() && !request()->routeIs('login', 'register'))
            <div class="alert alert-danger" id="global-flash-validation" style="padding:14px 18px;background:#fef2f2;border:1px solid #fecdd3;border-radius:12px;color:#9f1239;margin-bottom:20px;box-shadow:0 4px 12px rgba(225,29,72,0.12);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:14px;font-weight:700;">
                        <i class="bi bi-exclamation-octagon-fill" style="font-size:18px;color:#dc2626;"></i>
                        <span>Terdapat beberapa kesalahan pengisian:</span>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;color:#9f1239;cursor:pointer;font-size:18px;padding:0;">&times;</button>
                </div>
                <ul style="margin:0;padding-left:24px;font-size:13px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{ $slot }}
        </section>

    </main>

</div>

{{-- ===== PAGE LOADING ELEMENTS ===== --}}
<div id="page-progress"></div>
<div id="page-loading-overlay"></div>
<div id="page-loading-spinner" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="9" stroke="#e2e8f0" stroke-width="2.5"/>
        <path d="M12 3a9 9 0 019 9" stroke="#005baa" stroke-width="2.5" stroke-linecap="round"/>
    </svg>
</div>

{{-- Global Custom Confirmation Modal --}}
<div id="global-confirm-modal" class="custom-modal-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.6);backdrop-filter:blur(5px);z-index:99999;align-items:center;justify-content:center;padding:16px;">
    <div class="custom-modal-box" style="background:#fff;width:100%;max-width:440px;border-radius:16px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);overflow:hidden;animation:modalScaleIn .2s cubic-bezier(0.16,1,0.3,1);">
        <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:14px;background:#f8fafc;">
            <div id="global-modal-icon" style="width:42px;height:42px;border-radius:50%;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;border:1px solid #fecdd3;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
                <h3 id="global-modal-title" style="margin:0;font-size:16px;font-weight:700;color:#0f172a;">Konfirmasi Tindakan</h3>
                <p id="global-modal-subtitle" style="margin:2px 0 0;font-size:12px;color:#64748b;">Sistem Informasi SILAKAN BI</p>
            </div>
        </div>

        <div style="padding:22px 24px;">
            <p id="global-modal-message" style="margin:0;font-size:13.5px;color:#334155;line-height:1.5;">Apakah Anda yakin ingin melanjutkan?</p>
        </div>

        <div style="padding:16px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:flex-end;gap:10px;">
            <button type="button" class="btn-secondary" onclick="closeGlobalConfirmModal()" style="padding:9px 18px;font-size:13px;border-radius:10px;">
                Batal
            </button>
            <button type="button" id="global-modal-btn-confirm" class="btn-danger" style="padding:9px 20px;font-size:13px;font-weight:600;border-radius:10px;display:inline-flex;align-items:center;gap:6px;border:none;cursor:pointer;">
                <i class="bi bi-check-lg"></i> Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
let pendingConfirmCallback = null;

function confirmAction(options) {
    const modal = document.getElementById('global-confirm-modal');
    const titleEl = document.getElementById('global-modal-title');
    const messageEl = document.getElementById('global-modal-message');
    const iconEl = document.getElementById('global-modal-icon');
    const confirmBtn = document.getElementById('global-modal-btn-confirm');
    
    if (!modal) return false;
    
    titleEl.textContent = options.title || 'Konfirmasi Tindakan';
    messageEl.innerHTML = options.message || 'Apakah Anda yakin ingin melanjutkan?';
    
    if (options.type === 'primary') {
        iconEl.style.background = '#e0f2fe';
        iconEl.style.borderColor = '#bae6fd';
        iconEl.style.color = '#0284c7';
        iconEl.innerHTML = '<i class="bi bi-question-circle-fill"></i>';
        confirmBtn.style.background = '#005baa';
        confirmBtn.style.color = '#fff';
    } else if (options.type === 'warning') {
        iconEl.style.background = '#fef3c7';
        iconEl.style.borderColor = '#fde68a';
        iconEl.style.color = '#d97706';
        iconEl.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i>';
        confirmBtn.style.background = '#d97706';
        confirmBtn.style.color = '#fff';
    } else {
        iconEl.style.background = '#fee2e2';
        iconEl.style.borderColor = '#fecdd3';
        iconEl.style.color = '#dc2626';
        iconEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
        confirmBtn.style.background = '#dc2626';
        confirmBtn.style.color = '#fff';
    }

    confirmBtn.innerHTML = `<i class="bi bi-check-lg"></i> ${options.confirmText || 'Ya, Lanjutkan'}`;
    pendingConfirmCallback = options.onConfirm;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeGlobalConfirmModal() {
    const modal = document.getElementById('global-confirm-modal');
    if (modal) modal.style.display = 'none';
    document.body.style.overflow = '';
    pendingConfirmCallback = null;
}

document.getElementById('global-modal-btn-confirm')?.addEventListener('click', function() {
    if (typeof pendingConfirmCallback === 'function') {
        const callback = pendingConfirmCallback;
        closeGlobalConfirmModal();
        callback();
    }
});

// Close modal on ESC key
window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGlobalConfirmModal();
    }
});

// Submit form using custom UI confirm modal
function submitFormWithConfirm(form, options) {
    confirmAction({
        title: options.title || 'Konfirmasi',
        message: options.message || 'Apakah Anda yakin ingin melanjutkan?',
        type: options.type || 'danger',
        confirmText: options.confirmText || 'Ya, Lanjutkan',
        onConfirm: function() {
            // Trigger page loading bar before actual form submit
            const progress = document.getElementById('page-progress');
            const overlay  = document.getElementById('page-loading-overlay');
            const spinner  = document.getElementById('page-loading-spinner');
            if (progress) {
                progress.style.width = '0%';
                progress.style.opacity = '1';
                progress.classList.add('loading');
                progress.classList.remove('complete');
                setTimeout(function() { progress.style.width = '60%'; }, 50);
            }
            if (overlay) overlay.classList.add('visible');
            if (spinner) spinner.classList.add('visible');
            form.submit();
        }
    });
    return false;
}

/* Real-Time Live Countdown Timer for Kegiatan Berlangsung */
function updateLiveCountdowns() {
    const countdownElements = document.querySelectorAll('[data-end-time]');
    if (!countdownElements.length) return;
    
    const now = new Date();

    countdownElements.forEach(el => {
        const endTimeStr = el.getAttribute('data-end-time');
        if (!endTimeStr) return;

        const endTime = new Date(endTimeStr);
        const diffMs = endTime - now;
        const valueSpan = el.querySelector('.countdown-value') || el.querySelector('.countdown-text') || el;

        if (diffMs <= 0) {
            valueSpan.textContent = 'Waktu Selesai';
            el.style.background = '#dc2626';
            el.style.color = '#ffffff';
            el.style.borderColor = '#fecdd3';
        } else {
            const totalSeconds = Math.floor(diffMs / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            let formatted = 'Sisa ';
            if (hours > 0) {
                formatted += `${hours}j ${minutes}m ${seconds}s`;
            } else if (minutes > 0) {
                formatted += `${minutes}m ${seconds}s`;
            } else {
                formatted += `${seconds}s`;
            }

            valueSpan.textContent = formatted;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    updateLiveCountdowns();
    setInterval(updateLiveCountdowns, 1000);
});

/* ==========================================================
   PAGE LOADING SYSTEM
   — Menampilkan progress bar tipis & overlay saat navigasi
   — Menghindari animasi CSS berat saat perpindahan halaman
   ========================================================== */
(function() {
    const progress  = document.getElementById('page-progress');
    const overlay   = document.getElementById('page-loading-overlay');
    const spinner   = document.getElementById('page-loading-spinner');

    if (!progress) return;

    let progressTimer   = null;
    let progressVal     = 0;
    let isNavigating    = false;

    /* --- Start loading sequence --- */
    function startLoading() {
        if (isNavigating) return;
        isNavigating = true;
        progressVal  = 0;

        progress.style.width   = '0%';
        progress.style.opacity = '1';
        progress.classList.add('loading');
        progress.classList.remove('complete');
        overlay.classList.add('visible');
        spinner.classList.add('visible');

        /* Fast start: 0 → 35% in 200ms */
        progressVal = 35;
        progress.style.width = '35%';

        /* Crawl: inch toward 80% slowly */
        progressTimer = setInterval(function() {
            if (progressVal < 80) {
                var increment = (80 - progressVal) * 0.08;
                progressVal += Math.max(increment, 0.5);
                progress.style.width = progressVal + '%';
            } else {
                clearInterval(progressTimer);
            }
        }, 120);
    }

    /* --- Finish loading sequence --- */
    function finishLoading() {
        clearInterval(progressTimer);
        isNavigating = false;

        progress.classList.add('complete');
        overlay.classList.remove('visible');
        spinner.classList.remove('visible');

        setTimeout(function() {
            progress.classList.remove('loading', 'complete');
            progress.style.width = '0%';
        }, 500);
    }

    /* --- Intercept all internal navigation links --- */
    document.addEventListener('click', function(e) {
        /* Find closest anchor tag */
        const anchor = e.target.closest('a');
        if (!anchor) return;

        const href = anchor.getAttribute('href');
        if (!href) return;

        /* Skip: external links, hash-only, js:void, file, mailto, target _blank */
        const isExternal    = anchor.hostname && anchor.hostname !== window.location.hostname;
        const isHash        = href.startsWith('#');
        const isJavascript  = href.startsWith('javascript');
        const isSpecial     = href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('file:');
        const isNewTab      = anchor.target === '_blank';
        const isSamePage    = href === window.location.href || href === window.location.pathname;
        const isDownload    = anchor.hasAttribute('download');

        if (isExternal || isHash || isJavascript || isSpecial || isNewTab || isSamePage || isDownload) return;

        /* Skip logout form triggers (non-GET) */
        if (anchor.closest('form')) return;

        startLoading();
    }, true);

    /* --- Form submit (non-AJAX navigation) --- */
    document.addEventListener('submit', function(e) {
        const form = e.target;
        /* Only intercept standard GET/POST forms that cause full page nav */
        if (form.dataset.noLoading) return;
        /* Exclude forms handled via onsubmit JS (confirm modals) — they return false */
        /* We do a small delay so JS handlers can cancel first */
        setTimeout(function() {
            if (!e.defaultPrevented) startLoading();
        }, 0);
    }, true);

    /* --- Hide loading on browser back/forward (popstate / bfcache) --- */
    window.addEventListener('popstate', finishLoading);
    window.addEventListener('pageshow', function(e) {
        finishLoading();
    });

    /* --- Safety: always finish when DOM is ready on new page --- */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', finishLoading);
    } else {
        finishLoading();
    }

})();
</script>

@stack('scripts')

</body>
</html>