<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SILAKAN — Sistem Informasi Layanan Kantor KPwBI Provinsi Sulawesi Utara">
    <meta name="theme-color" content="#005baa">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>SILAKAN | KPwBI Prov. Sulut</title>

    
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo-bi2.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/logo-bi2.png')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/silakan.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bi-theme.css')); ?>">

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<div class="app">

    <?php if (isset($component)) { $__componentOriginal2880b66d47486b4bfeaf519598a469d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2880b66d47486b4bfeaf519598a469d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $attributes = $__attributesOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $component = $__componentOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__componentOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main">

        <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

        <section class="content">
            
            <?php if(session('success')): ?>
            <div class="alert alert-success" id="global-flash-success" style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;color:#047857;margin-bottom:20px;box-shadow:0 4px 12px rgba(16,185,129,0.12);">
                <div style="display:flex;align-items:center;gap:12px;font-size:13.5px;font-weight:600;">
                    <i class="bi bi-check-circle-fill" style="font-size:18px;color:#059669;"></i>
                    <span><?php echo e(session('success')); ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#047857;cursor:pointer;font-size:18px;padding:0;display:flex;align-items:center;">&times;</button>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="alert alert-danger" id="global-flash-error" style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:#fef2f2;border:1px solid #fecdd3;border-radius:12px;color:#9f1239;margin-bottom:20px;box-shadow:0 4px 12px rgba(225,29,72,0.12);">
                <div style="display:flex;align-items:center;gap:12px;font-size:13.5px;font-weight:600;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:18px;color:#dc2626;"></i>
                    <span><?php echo e(session('error')); ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#9f1239;cursor:pointer;font-size:18px;padding:0;display:flex;align-items:center;">&times;</button>
            </div>
            <?php endif; ?>

            <?php if(isset($errors) && $errors->any() && !request()->routeIs('login', 'register')): ?>
            <div class="alert alert-danger" id="global-flash-validation" style="padding:14px 18px;background:#fef2f2;border:1px solid #fecdd3;border-radius:12px;color:#9f1239;margin-bottom:20px;box-shadow:0 4px 12px rgba(225,29,72,0.12);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:14px;font-weight:700;">
                        <i class="bi bi-exclamation-octagon-fill" style="font-size:18px;color:#dc2626;"></i>
                        <span>Terdapat beberapa kesalahan pengisian:</span>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;color:#9f1239;cursor:pointer;font-size:18px;padding:0;">&times;</button>
                </div>
                <ul style="margin:0;padding-left:24px;font-size:13px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($err); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php echo e($slot); ?>

        </section>

    </main>

</div>


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
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH D:\Bank Indo\silakan\resources\views/layouts/app.blade.php ENDPATH**/ ?>