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


<div class="dashboard-header" style="background: linear-gradient(135deg, #003b73 0%, #005baa 100%); padding: 28px 32px; border-radius: 18px; color: white; margin-bottom: 28px; box-shadow: 0 10px 25px -5px rgba(0, 59, 115, 0.25); position: relative; overflow: hidden;">
    <div style="position: absolute; right: -20px; top: -30px; font-size: 160px; color: rgba(255,255,255,0.05); pointer-events: none; line-height: 1;">
        <i class="bi bi-broadcast"></i>
    </div>
    <div style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.25);">
                <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: inline-block; box-shadow: 0 0 10px #ef4444; animation: pulseRed 1.5s infinite;"></span>
                Live Monitoring
            </div>
            <h1 style="font-size: 26px; font-weight: 800; margin: 0 0 6px 0; color: #ffffff; letter-spacing: 0.5px;">Kegiatan Berlangsung</h1>
            <p style="margin: 0; font-size: 13.5px; color: rgba(255,255,255,0.85); font-weight: 500;">Pantau penggunaan ruangan rapat &amp; kantor yang sedang aktif saat ini secara real-time.</p>
        </div>
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 12px 20px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.2); text-align: right;">
            <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: rgba(255,255,255,0.8);">Ruangan Terpakai</div>
            <div style="font-size: 22px; font-weight: 800; color: #ffffff; margin-top: 2px;">
                <i class="bi bi-door-open-fill" style="color: #fef08a; margin-right: 6px;"></i><?php echo e($kegiatan->count()); ?> Ruangan
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulseRed {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
.live-monitor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 22px;
}
.live-monitor-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px -2px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
}
.live-monitor-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px -4px rgba(0, 91, 170, 0.15);
    border-color: #cbd5e1;
}
.live-monitor-card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 18px 22px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.live-monitor-room-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #003b73;
    color: #ffffff;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 3px 8px rgba(0, 59, 115, 0.2);
}
.live-monitor-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecdd3;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
}
.live-monitor-body {
    padding: 22px;
}
.live-monitor-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 16px 0;
    line-height: 1.4;
}
.live-monitor-meta {
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #f8fafc;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
}
.live-monitor-meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #475569;
}
.live-monitor-meta-item i {
    font-size: 16px;
    color: #005baa;
    width: 20px;
    text-align: center;
}
</style>

<div class="dashboard-section" style="background: transparent; padding: 0; box-shadow: none; border: none;">

    <?php if($kegiatan->count() > 0): ?>
    <div class="live-monitor-grid">
        <?php $__currentLoopData = $kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="live-monitor-card">
            <div class="live-monitor-card-header">
                <div class="live-monitor-room-badge">
                    <i class="bi bi-building"></i>
                    <?php echo e($item->ruangan?->nama_ruangan ?? 'Ruangan'); ?>

                </div>
                <div class="live-monitor-status-pill">
                    <span style="width: 7px; height: 7px; background: #dc2626; border-radius: 50%; animation: pulseRed 1.5s infinite;"></span>
                    LIVE SAAT INI
                </div>
            </div>

            <div class="live-monitor-body">
                <h3 class="live-monitor-title">
                    <?php echo e($item->judul_kegiatan); ?>

                </h3>

                
                <div style="margin-bottom: 16px;">
                    <div class="live-countdown-badge" data-end-time="<?php echo e($item->tanggal_kegiatan->format('Y-m-d')); ?>T<?php echo e($item->waktu_selesai); ?>" style="width: 100%; justify-content: center; padding: 10px 16px; font-size: 13px; background: #0f172a; color: #fef08a; border-radius: 12px; border: 1px solid rgba(254, 240, 138, 0.3);">
                        <i class="bi bi-hourglass-split" style="animation: spinHourglass 2.5s infinite linear; font-size: 15px; color: #fef08a;"></i>
                        <span class="countdown-value" style="font-weight: 800; letter-spacing: 0.5px;">Menghitung sisa waktu...</span>
                    </div>
                </div>

                <div class="live-monitor-meta">
                    <div class="live-monitor-meta-item">
                        <i class="bi bi-clock-history"></i>
                        <span>Waktu Rapat: <strong style="color:#0f172a;"><?php echo e($item->waktu_mulai); ?> – <?php echo e($item->waktu_selesai); ?> WITA</strong></span>
                    </div>
                    <div class="live-monitor-meta-item">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Pemohon / PIC: <strong style="color:#005baa;"><?php echo e($item->pic_kegiatan ?: ($item->user?->name ?? 'User')); ?></strong> (<?php echo e($item->user?->nama_unit ?? $item->user?->name ?? 'Unit'); ?>)</span>
                    </div>
                    <div class="live-monitor-meta-item">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        <span>Layout Ruangan: <strong style="color:#0f172a;"><?php echo e($item->layout?->nama_layout ?? '-'); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="empty-state" style="background:#ffffff;padding:50px 30px;border-radius:18px;border:1px dashed #cbd5e1;text-align:center;">
        <div style="width:70px;height:70px;border-radius:50%;background:#f1f5f9;color:#64748b;display:inline-flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:16px;">
            <i class="bi bi-calendar-x"></i>
        </div>
        <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:0 0 6px 0;">Tidak Ada Kegiatan Berlangsung</h3>
        <p style="font-size:13.5px;color:#64748b;margin:0;">Saat ini tidak ada ruangan rapat yang sedang terpakai.</p>
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/kegiatan-berlangsung/index.blade.php ENDPATH**/ ?>