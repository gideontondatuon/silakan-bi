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

        /* ===== SILENT REAL-TIME NOTIFICATION TOAST ===== */
        #silakan-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 400px;
            width: calc(100vw - 32px);
            pointer-events: none;
        }

        .silakan-toast-card {
            pointer-events: auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-left: 5px solid #005baa;
            border-radius: 12px;
            padding: 14px 16px;
            box-shadow: 0 12px 30px -4px rgba(0, 59, 115, 0.22), 0 4px 10px -2px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            transition: opacity 0.3s ease, transform 0.3s ease;
            position: relative;
        }

        .silakan-toast-card.removing {
            opacity: 0;
            transform: translateX(60px);
        }

        .silakan-toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #e0f2fe;
            color: #005baa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .silakan-toast-body {
            flex: 1;
            min-width: 0;
        }

        .silakan-toast-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #003b73;
            margin: 0 0 3px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .silakan-toast-message {
            font-size: 12.5px;
            color: #334155;
            margin: 0 0 6px 0;
            line-height: 1.4;
            word-break: break-word;
        }

        .silakan-toast-time {
            font-size: 11px;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .silakan-toast-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            font-size: 18px;
            line-height: 1;
            margin-left: 6px;
            transition: color 0.15s;
        }

        .silakan-toast-close:hover {
            color: #475569;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes bellSwing {
            0% { transform: rotate(0); }
            15% { transform: rotate(15deg); }
            30% { transform: rotate(-15deg); }
            45% { transform: rotate(10deg); }
            60% { transform: rotate(-10deg); }
            75% { transform: rotate(4deg); }
            100% { transform: rotate(0); }
        }

        .bell-ringing {
            animation: bellSwing 0.8s ease-in-out;
            color: #f59e0b !important;
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
            @php
                $hasFileError = $errors->has('file_disposisi');
                $otherErrors = collect($errors->keys())
                    ->reject(fn($key) => $key === 'file_disposisi')
                    ->flatMap(fn($key) => $errors->get($key));
            @endphp

            {{-- Tampilkan pesan error lain di banner merah hanya jika BUKAN error file_disposisi --}}
            @if($otherErrors->isNotEmpty())
            <div class="alert alert-danger" id="global-flash-validation" style="padding:14px 18px;background:#fef2f2;border:1px solid #fecdd3;border-radius:12px;color:#9f1239;margin-bottom:20px;box-shadow:0 4px 12px rgba(225,29,72,0.12);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:14px;font-weight:700;">
                        <i class="bi bi-exclamation-octagon-fill" style="font-size:18px;color:#dc2626;"></i>
                        <span>Terdapat beberapa kesalahan pengisian:</span>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;color:#9f1239;cursor:pointer;font-size:18px;padding:0;">&times;</button>
                </div>
                <ul style="margin:0;padding-left:24px;font-size:13px;">
                    @foreach($otherErrors as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Error file disposisi langsung dimunculkan sebagai POPUP MODAL --}}
            @if($hasFileError)
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showErrorAlertModal === 'function') {
                    showErrorAlertModal({
                        title: 'Ukuran Berkas Melebihi Batas',
                        message: 'Ukuran berkas lembar disposisi yang diunggah <strong>tidak boleh lebih dari 5 MB</strong>.<br><br><span style="color:#64748b;font-size:12.5px;">Silakan pilih atau kompres berkas PDF / Gambar lain dengan ukuran maksimal 5 MB.</span>',
                        confirmText: 'Mengerti & Pilih Ulang'
                    });
                }
            });
            </script>
            @endif
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
    const cancelBtn = modal?.querySelector('.btn-secondary');
    if (cancelBtn) cancelBtn.style.display = '';
    document.body.style.overflow = '';
    pendingConfirmCallback = null;
}

// Modal Popup khusus Notifikasi Error / Peringatan (Single Action)
function showErrorAlertModal(options) {
    const modal = document.getElementById('global-confirm-modal');
    const titleEl = document.getElementById('global-modal-title');
    const messageEl = document.getElementById('global-modal-message');
    const iconEl = document.getElementById('global-modal-icon');
    const cancelBtn = modal?.querySelector('.btn-secondary');
    const confirmBtn = document.getElementById('global-modal-btn-confirm');
    
    if (!modal) {
        alert(options.message || 'Ukuran berkas tidak boleh lebih dari 5 MB.');
        return;
    }

    titleEl.textContent = options.title || 'Ukuran Berkas Melebihi Batas';
    messageEl.innerHTML = options.message || 'Ukuran berkas tidak boleh melebihi 5 MB.';
    
    iconEl.style.background = '#fee2e2';
    iconEl.style.borderColor = '#fecdd3';
    iconEl.style.color = '#dc2626';
    iconEl.innerHTML = '<i class="bi bi-file-earmark-x-fill" style="font-size:20px;"></i>';

    if (cancelBtn) cancelBtn.style.display = 'none';
    confirmBtn.style.background = 'linear-gradient(135deg,#005baa,#003b73)';
    confirmBtn.style.color = '#fff';
    confirmBtn.style.border = 'none';
    confirmBtn.style.padding = '9px 24px';
    confirmBtn.innerHTML = `<i class="bi bi-check-lg"></i> ${options.confirmText || 'Mengerti'}`;

    pendingConfirmCallback = function() {
        closeGlobalConfirmModal();
    };

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
window.showErrorAlertModal = showErrorAlertModal;

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
    let safetyTimer     = null;

    /* --- Start loading sequence --- */
    function startLoading(maxDuration) {
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
        clearInterval(progressTimer);
        progressTimer = setInterval(function() {
            if (progressVal < 80) {
                var increment = (80 - progressVal) * 0.08;
                progressVal += Math.max(increment, 0.5);
                progress.style.width = progressVal + '%';
            } else {
                clearInterval(progressTimer);
            }
        }, 120);

        /* Failsafe timeout: memastikan loading screen tidak pernah macet selamanya */
        clearTimeout(safetyTimer);
        safetyTimer = setTimeout(function() {
            finishLoading();
        }, maxDuration || 7000);
    }

    /* --- Finish loading sequence --- */
    function finishLoading() {
        clearInterval(progressTimer);
        clearTimeout(safetyTimer);
        isNavigating = false;

        progress.classList.add('complete');
        overlay.classList.remove('visible');
        spinner.classList.remove('visible');

        setTimeout(function() {
            progress.classList.remove('loading', 'complete');
            progress.style.width = '0%';
        }, 500);
    }

    /* Ekspos fungsi loading secara global */
    window.startPageLoading = startLoading;
    window.finishPageLoading = finishLoading;

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
        const isDownload    = anchor.hasAttribute('download') ||
                              anchor.dataset.noLoading !== undefined ||
                              anchor.classList.contains('no-loading') ||
                              /\.(xlsx|xls|pdf|docx?|zip|csv)(\?.*)?$/i.test(href) ||
                              href.includes('export-excel') ||
                              href.includes('download-');

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

{{-- ===================================================
     SILAKAN SILENT REAL-TIME BACKGROUND SYNC ENGINE
     Memperbarui notifikasi, lonceng navbar, badge sidebar,
     tabel pemesanan, dan memainkan audio chime secara hening
     tanpa reload halaman dan tanpa memunculkan loading screen.
     =================================================== --}}
<div id="silakan-toast-container"></div>

@auth
<script>
(function() {
    const LIVE_SYNC_URL = "{{ route('notifications.liveSync') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;
    let lastUnreadCount = null;
    let lastPendingCount = null;
    let lastBookingId = null;
    let lastUpdatedAt = null;
    let isSyncing = false;

    /* --- Pleasant Dual-Tone Chime (Web Audio API) --- */
    function playChime() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            if (ctx.state === 'suspended') ctx.resume();
            const now = ctx.currentTime;

            // Tone 1: C5 (523.25Hz)
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(523.25, now);
            gain1.gain.setValueAtTime(0.14, now);
            gain1.gain.exponentialRampToValueAtTime(0.0001, now + 0.35);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(now);
            osc1.stop(now + 0.35);

            // Tone 2: G5 (783.99Hz)
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(783.99, now + 0.12);
            gain2.gain.setValueAtTime(0.16, now + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.0001, now + 0.55);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(now + 0.12);
            osc2.stop(now + 0.55);
        } catch(e) {
            /* Handled gracefully if browser audio autoplay blocked */
        }
    }

    /* --- Floating Toast Notification --- */
    function showToast(title, message, url, timeText) {
        const container = document.getElementById('silakan-toast-container');
        if (!container) return;

        const card = document.createElement('div');
        card.className = 'silakan-toast-card';
        card.innerHTML = `
            <div class="silakan-toast-icon">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div class="silakan-toast-body">
                <div class="silakan-toast-title">
                    <span>${title}</span>
                    <button type="button" class="silakan-toast-close" title="Tutup">&times;</button>
                </div>
                <p class="silakan-toast-message">${message}</p>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span class="silakan-toast-time"><i class="bi bi-clock"></i> ${timeText || 'Baru saja'}</span>
                    ${url ? `<a href="${url}" style="font-size:11.5px;font-weight:700;color:#005baa;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">Buka <i class="bi bi-arrow-right-short"></i></a>` : ''}
                </div>
            </div>
        `;

        card.querySelector('.silakan-toast-close').addEventListener('click', (e) => {
            e.stopPropagation();
            card.classList.add('removing');
            setTimeout(() => card.remove(), 300);
        });

        if (url) {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                if (e.target.closest('.silakan-toast-close') || e.target.tagName === 'A') return;
                window.location.href = url;
            });
        }

        container.appendChild(card);

        // Auto remove after 8 seconds
        setTimeout(() => {
            if (card.parentNode) {
                card.classList.add('removing');
                setTimeout(() => card.remove(), 300);
            }
        }, 8000);
    }

    /* --- Ring Bell Icon Animation --- */
    function animateBell() {
        const bellIcon = document.getElementById('navbarBellIcon');
        if (bellIcon) {
            bellIcon.classList.remove('bell-ringing');
            void bellIcon.offsetWidth; // Reflow
            bellIcon.classList.add('bell-ringing');
            setTimeout(() => bellIcon.classList.remove('bell-ringing'), 1000);
        }
    }

    /* --- Silent In-Page DOM Updater (Zero Reload / Zero Loading Overlay) --- */
    async function silentRefreshCurrentPage() {
        try {
            const res = await fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Silakan-Silent-Refresh': 'true'
                }
            });
            if (!res.ok) return;
            const html = await res.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const targets = [
                '#live-approval-container',
                '#live-pemesanan-container',
                '.live-cards-grid',
                '.stat-grid',
                '.data-table tbody'
            ];

            targets.forEach(sel => {
                const newElem = doc.querySelector(sel);
                const currElem = document.querySelector(sel);
                if (newElem && currElem) {
                    currElem.innerHTML = newElem.innerHTML;
                }
            });
        } catch(err) {
            console.log('SILAKAN silent refresh notice', err);
        }
    }

    /* --- Main Sync Loop --- */
    async function performLiveSync() {
        if (isSyncing) return;
        isSyncing = true;

        try {
            const res = await fetch(LIVE_SYNC_URL, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN || ''
                }
            });

            if (!res.ok) {
                isSyncing = false;
                return;
            }

            const data = await res.json();
            if (data.status !== 'success') {
                isSyncing = false;
                return;
            }

            const unreadCount = data.unread_count || 0;
            const extra = data.extra || {};

            // Initial baseline run
            if (lastUnreadCount === null) {
                lastUnreadCount = unreadCount;
                lastPendingCount = extra.count_pending ?? extra.count_my_pending ?? 0;
                lastBookingId = extra.latest_booking_id ?? extra.latest_my_booking_id ?? 0;
                lastUpdatedAt = extra.latest_updated_at ?? extra.latest_my_updated_at ?? 0;
                isSyncing = false;
                return;
            }

            let hasNewNotification = (unreadCount > lastUnreadCount);
            let hasBookingChange = false;

            if (extra.is_admin) {
                if ((extra.count_pending !== undefined && extra.count_pending !== lastPendingCount) ||
                    (extra.latest_booking_id !== undefined && extra.latest_booking_id !== lastBookingId) ||
                    (extra.latest_updated_at !== undefined && extra.latest_updated_at !== lastUpdatedAt)) {
                    hasBookingChange = true;
                }
            } else {
                if ((extra.latest_my_updated_at !== undefined && extra.latest_my_updated_at !== lastUpdatedAt) ||
                    (extra.latest_my_booking_id !== undefined && extra.latest_my_booking_id !== lastBookingId)) {
                    hasBookingChange = true;
                }
            }

            // 1. Update unread notification badges
            const navCount = document.getElementById('navbarNotificationCount');
            const navBadge = document.getElementById('navbarNotificationBadge');
            const sideBadge = document.getElementById('sidebarNotificationBadge');

            if (navCount) {
                navCount.textContent = unreadCount;
                navCount.style.display = unreadCount > 0 ? '' : 'none';
            }
            if (navBadge) {
                navBadge.textContent = `${unreadCount} Baru`;
                navBadge.style.display = unreadCount > 0 ? '' : 'none';
            }
            if (sideBadge) {
                sideBadge.textContent = unreadCount;
                sideBadge.style.display = unreadCount > 0 ? '' : 'none';
            }

            // 2. Update admin pending booking badge in sidebar
            if (extra.is_admin && extra.count_pending !== undefined) {
                const sidePendingBadge = document.getElementById('sidebarPendingBadge');
                if (sidePendingBadge) {
                    sidePendingBadge.textContent = extra.count_pending;
                    sidePendingBadge.style.display = extra.count_pending > 0 ? '' : 'none';
                }
            }

            // 3. Render notification list in navbar dropdown
            if (data.notifications && data.notifications.length > 0) {
                const listContainer = document.getElementById('navbarNotificationList');
                if (listContainer) {
                    let itemsHtml = '';
                    data.notifications.forEach(n => {
                        itemsHtml += `
                            <a href="${n.url}" class="notification-item">
                                <div class="notification-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="notification-content">
                                    <strong>${n.judul}</strong>
                                    <p>${n.pesan}</p>
                                    <small><i class="bi bi-clock"></i> ${n.waktu}</small>
                                </div>
                            </a>
                        `;
                    });
                    listContainer.innerHTML = itemsHtml;
                }
            } else if (unreadCount === 0) {
                const listContainer = document.getElementById('navbarNotificationList');
                if (listContainer) {
                    listContainer.innerHTML = `
                        <div class="notification-empty">
                            <i class="bi bi-bell-slash"></i>
                            <p>Tidak ada notifikasi baru.</p>
                        </div>
                    `;
                }
            }

            // 4. Trigger alert if new notification arrived
            if (hasNewNotification) {
                playChime();
                animateBell();

                // Show toast for latest notification
                if (data.notifications && data.notifications.length > 0) {
                    const topNotif = data.notifications[0];
                    showToast(topNotif.judul, topNotif.pesan, topNotif.url, topNotif.waktu);
                }
            }

            // 5. If booking state changed, silently refresh page content
            if (hasBookingChange) {
                if (!hasNewNotification) {
                    playChime();
                    if (extra.is_admin && extra.count_pending > (lastPendingCount || 0)) {
                        showToast('Pengajuan Pemesanan Baru', 'Terdapat pengajuan pemesanan ruangan baru yang menunggu verifikasi Anda.', '{{ route("admin.approval.index") }}', 'Baru saja');
                    }
                }
                silentRefreshCurrentPage();
            }

            // Update baseline state
            lastUnreadCount = unreadCount;
            if (extra.is_admin) {
                lastPendingCount = extra.count_pending;
                lastBookingId = extra.latest_booking_id;
                lastUpdatedAt = extra.latest_updated_at;
            } else {
                lastPendingCount = extra.count_my_pending;
                lastBookingId = extra.latest_my_booking_id;
                lastUpdatedAt = extra.latest_my_updated_at;
            }

        } catch (err) {
            console.log('SILAKAN live sync exception:', err);
        } finally {
            isSyncing = false;
        }
    }

    // Polling setiap 8 detik (ringan, cepat, hening)
    setInterval(performLiveSync, 8000);
    // Baseline check setelah 2.5 detik
    setTimeout(performLiveSync, 2500);
})();
</script>
@endauth

@stack('scripts')

</body>
</html>