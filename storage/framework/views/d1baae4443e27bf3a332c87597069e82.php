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

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-bell-fill" style="color:#005baa;margin-right:8px;"></i>Notifikasi</h1>
        <p>Riwayat informasi dan aktivitas pemesanan ruangan Anda.</p>
    </div>
    <?php if(isset($notifications) && $notifications->count() > 0): ?>
    <div style="display:flex;gap:10px;align-items:center;">
        <form method="POST" action="<?php echo e(route('notifications.readAll')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-secondary">
                <i class="bi bi-check-all" style="font-size:18px;"></i> Tandai Semua Dibaca
            </button>
        </form>
        <form method="POST" action="<?php echo e(route('notifications.destroyAll')); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Semua Notifikasi', message: 'Apakah Anda yakin ingin menghapus seluruh riwayat notifikasi?', type: 'danger', confirmText: 'Ya, Hapus Semua' })">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn-secondary" style="color:#dc2626;border-color:#fecdd3;background:#fff1f2;">
                <i class="bi bi-trash-fill" style="color:#dc2626;"></i> Hapus Semua
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-inbox-fill"></i> Daftar Notifikasi</h2>
        <?php if(isset($notifications) && $notifications->total() > 0): ?>
        <span class="badge badge-primary"><?php echo e($notifications->total()); ?> Notifikasi</span>
        <?php endif; ?>
    </div>

    <div class="notification-list">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="notification-card-wrapper" style="position:relative;margin-bottom:12px;">
            <a href="<?php echo e(route('notification.read', $notification->id)); ?>"
               class="notification-card <?php echo e(!$notification->read_at ? 'unread' : 'read'); ?>" style="padding-right:60px;">
                <div class="notification-card-icon">
                    <?php if(str_contains(strtolower($notification->data['judul'] ?? ''), 'disetujui')): ?>
                        <i class="bi bi-check-circle-fill" style="color:#10b981;"></i>
                    <?php elseif(str_contains(strtolower($notification->data['judul'] ?? ''), 'ditolak')): ?>
                        <i class="bi bi-x-circle-fill" style="color:#ef4444;"></i>
                    <?php else: ?>
                        <i class="bi bi-bell-fill" style="color:#005baa;"></i>
                    <?php endif; ?>
                </div>

                <div class="notification-card-body">
                    <div class="notification-card-top">
                        <strong class="notification-card-title"><?php echo e($notification->data['judul'] ?? 'Notifikasi'); ?></strong>
                        <?php if(!$notification->read_at): ?>
                            <span class="badge badge-warning"><i class="bi bi-circle-fill" style="font-size:7px;"></i> Baru</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><i class="bi bi-check2"></i> Dibaca</span>
                        <?php endif; ?>
                    </div>
                    <p class="notification-card-message"><?php echo e($notification->data['pesan'] ?? ''); ?></p>
                    <small class="notification-card-time">
                        <i class="bi bi-clock"></i> <?php echo e($notification->data['waktu'] ?? $notification->created_at->diffForHumans()); ?>

                    </small>
                </div>
            </a>

            
            <form action="<?php echo e(route('notification.destroy', $notification->id)); ?>" method="POST" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);z-index:10;" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Notifikasi', message: 'Apakah Anda yakin ingin menghapus notifikasi ini?', type: 'danger', confirmText: 'Hapus' })">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:8px;border-radius:8px;transition:all 0.2s ease;" onmouseover="this.style.color='#ef4444';this.style.background='#fff1f2';" onmouseout="this.style.color='#94a3b8';this.style.background='none';" title="Hapus Notifikasi Ini">
                    <i class="bi bi-trash-fill" style="font-size:16px;"></i>
                </button>
            </form>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-state">
            <i class="bi bi-bell-slash"></i>
            <p>Belum ada notifikasi.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if($notifications->hasPages()): ?>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        <?php echo e($notifications->links()); ?>

    </div>
    <?php endif; ?>
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/notifications/index.blade.php ENDPATH**/ ?>