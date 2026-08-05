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
        <h1><i class="bi bi-file-earmark-text" style="color:#005baa;margin-right:8px;"></i>Detail Pemesanan</h1>
        <p>Informasi lengkap pengajuan penggunaan ruangan.</p>
    </div>
    <a href="<?php echo e(route('pemesanan.index')); ?>" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="detail-container">

    
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-info-circle"></i>
            Informasi Umum
        </div>
        <div class="detail-grid">
            <div>
                <label>Kode Pemesanan</label>
                <p style="font-family:monospace;font-weight:700;color:#005baa;"><?php echo e($pemesanan->kode_pemesanan); ?></p>
            </div>
            <div>
                <label>Status</label>
                <p>
                    <?php if($pemesanan->status->value == 'Pending'): ?>
                        <span class="badge badge-warning"><i class="bi bi-clock"></i> Menunggu Approval</span>
                    <?php elseif($pemesanan->status->value == 'Disetujui'): ?>
                        <span class="badge badge-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                    <?php elseif($pemesanan->status->value == 'Ditolak'): ?>
                        <span class="badge badge-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                    <?php else: ?>
                        <span class="badge badge-secondary"><i class="bi bi-dash-circle"></i> Dibatalkan</span>
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <label>Judul Kegiatan</label>
                <p><?php echo e($pemesanan->judul_kegiatan); ?></p>
            </div>
            <div>
                <label>Ruangan</label>
                <p style="color:#005baa;font-weight:700;"><?php echo e($pemesanan->ruangan->nama_ruangan); ?></p>
            </div>
            <div>
                <label>Tanggal Kegiatan</label>
                <p><?php echo e($pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY')); ?></p>
            </div>
            <div>
                <label>Waktu</label>
                <p><?php echo e($pemesanan->waktu_mulai); ?> – <?php echo e($pemesanan->waktu_selesai); ?> WITA</p>
            </div>
        </div>
    </div>

    
    <?php if($pemesanan->catatan_admin): ?>
    <div class="detail-card">
        <div class="detail-title" style="color:#047857;"><i class="bi bi-chat-left-text-fill"></i> Catatan Admin</div>
        <div style="padding:16px 22px;background:#ecfdf5;border-radius:10px;border:1px solid #a7f3d0;color:#047857;">
            <p style="font-size:13.5px;font-weight:600;margin:0;"><?php echo e($pemesanan->catatan_admin); ?></p>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($pemesanan->alasan_penolakan): ?>
    <div class="detail-card">
        <div class="detail-title" style="color:#be123c;"><i class="bi bi-exclamation-octagon-fill"></i> Alasan Penolakan</div>
        <div style="padding:16px 22px;background:#fff1f2;border-radius:10px;border:1px solid #fecdd3;color:#be123c;">
            <p style="font-size:13.5px;font-weight:600;margin:0;"><?php echo e($pemesanan->alasan_penolakan); ?></p>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($pemesanan->catatan_user): ?>
    <div class="detail-card">
        <div class="detail-title"><i class="bi bi-sticky"></i> Catatan Pemohon</div>
        <div style="padding:16px 22px;">
            <p style="color:#334155;font-size:13.5px;"><?php echo e($pemesanan->catatan_user); ?></p>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($pemesanan->file_disposisi): ?>
    <div class="detail-card">
        <div class="detail-title"><i class="bi bi-file-earmark-text"></i> Lembar Disposisi</div>
        <div style="padding:16px 22px;">
            <a href="<?php echo e(asset('storage/' . $pemesanan->file_disposisi)); ?>" target="_blank" class="btn-primary">
                <i class="bi bi-file-earmark-pdf"></i> Lihat / Unduh Lembar Disposisi
            </a>
        </div>
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/pemesanan/show.blade.php ENDPATH**/ ?>