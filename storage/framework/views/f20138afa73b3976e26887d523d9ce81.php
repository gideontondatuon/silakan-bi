<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

<style>
@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr !important;
    }
    .profile-banner {
        padding: 20px 18px !important;
        flex-direction: column !important;
        align-items: flex-start !important;
    }
    .profile-banner-left {
        flex-direction: row !important;
        align-items: center !important;
        gap: 14px !important;
        width: 100% !important;
    }
    .profile-banner-avatar {
        width: 58px !important;
        height: 58px !important;
        min-width: 58px !important;
    }
    .profile-banner-right {
        text-align: left !important;
        width: 100% !important;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 12px !important;
        margin-top: 4px !important;
    }
}
</style>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-person-badge-fill" style="color:#005baa;margin-right:8px;"></i>Profil Saya</h1>
        <p>Kelola informasi akun dan pengaturan keamanan profil Anda.</p>
    </div>
</div>

<div style="display:flex;flex-direction:column;gap:24px;max-width:900px;">

    
    <div class="profile-banner" style="background:linear-gradient(135deg, #003b73 0%, #005baa 100%);border-radius:16px;padding:28px 32px;color:white;box-shadow:0 10px 25px -5px rgba(0,91,170,0.25);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
        <div class="profile-banner-left" style="display:flex;align-items:center;gap:20px;">
            <div class="profile-banner-avatar" style="width:72px;height:72px;border-radius:50%;background:white;color:#003b73;display:flex;align-items:center;justify-content:center;font-weight:800;box-shadow:0 4px 12px rgba(0,0,0,0.15);flex-shrink:0;<?php echo e($user->avatar_style); ?>">
                <span style="font-size:1.4em;"><?php echo e($user->initials); ?></span>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:800;margin:0 0 6px 0;letter-spacing:-0.3px;color:#ffffff;line-height:1.2;"><?php echo e($user->name); ?></h2>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span style="background:rgba(255,255,255,0.2);backdrop-filter:blur(4px);padding:4px 12px;border-radius:9999px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-shield-check"></i> <?php echo e($user->role->label()); ?>

                    </span>
                    <?php if($user->kode_unit): ?>
                    <span style="background:rgba(255,255,255,0.2);backdrop-filter:blur(4px);padding:4px 12px;border-radius:9999px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-tag-fill"></i> Kode Unit: <?php echo e($user->kode_unit); ?>

                    </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="profile-banner-right" style="text-align:right;">
            <span style="display:block;font-size:12px;opacity:0.85;">Username Akun</span>
            <strong style="font-size:16px;font-family:monospace;letter-spacing:0.5px;"><?php echo e($user->username); ?></strong>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;" class="profile-grid">
        
        
        <div class="dashboard-section" style="margin:0;background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.05);border:1px solid #e2e8f0;overflow:hidden;">
            <div class="section-header" style="padding:18px 24px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-person-vcard" style="color:#005baa;"></i> Informasi Akun
                </h2>
            </div>
            <div style="padding:24px;">
                <?php echo $__env->make('profile.partials.update-profile-information-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        
        <div class="dashboard-section" style="margin:0;background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.05);border:1px solid #e2e8f0;overflow:hidden;">
            <div class="section-header" style="padding:18px 24px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-shield-lock-fill" style="color:#005baa;"></i> Keamanan &amp; Password
                </h2>
            </div>
            <div style="padding:24px;">
                <?php echo $__env->make('profile.partials.update-password-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>

</div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\Bank Indo\silakan\resources\views/profile/edit.blade.php ENDPATH**/ ?>