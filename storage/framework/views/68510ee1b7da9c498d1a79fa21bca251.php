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
        <h1><i class="bi bi-grid-fill" style="color:#005baa;margin-right:8px;"></i>Dashboard SILAKAN</h1>
        <p>Selamat datang kembali, <strong><?php echo e(auth()->user()->name); ?></strong>
            <?php if(auth()->user()->nama_unit): ?> &mdash; <?php echo e(auth()->user()->nama_unit); ?> <?php endif; ?>
        </p>
    </div>
    <div class="dashboard-date">
        <i class="bi bi-calendar3"></i>
        <?php echo e(now()->translatedFormat('d F Y')); ?>

    </div>
</div>



<?php if(isset($kegiatanBerlangsung) && $kegiatanBerlangsung->count() > 0): ?>
<div class="live-banner">
    <div class="live-banner-header">
        <div class="live-banner-title">
            <span class="live-indicator-dot"></span>
            Kegiatan Sedang Berlangsung — Live Saat Ini
        </div>
        <span class="live-count">
            <i class="bi bi-building"></i> <?php echo e($kegiatanBerlangsung->count()); ?> Ruangan Terpakai
        </span>
    </div>
    <div class="live-cards-grid">
        <?php $__currentLoopData = $kegiatanBerlangsung; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $live): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="live-card">
            <div class="live-card-room" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <strong style="font-size:15px;color:#fef08a;"><i class="bi bi-building"></i> <?php echo e($live->ruangan->nama_ruangan); ?></strong>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span class="live-card-time">
                        <i class="bi bi-clock-history"></i>
                        <?php echo e($live->waktu_mulai); ?> – <?php echo e($live->waktu_selesai); ?> WITA
                    </span>
                    <span class="live-countdown-badge" data-end-time="<?php echo e($live->tanggal_kegiatan->format('Y-m-d')); ?>T<?php echo e($live->waktu_selesai); ?>">
                        <i class="bi bi-hourglass-split" style="animation:spinHourglass 2.5s infinite linear;color:#fef08a;"></i>
                        <span class="countdown-value">Hitung sisa...</span>
                    </span>
                </div>
            </div>
            <div class="live-card-title" style="font-size:16px;font-weight:700;color:#ffffff;margin:8px 0 10px 0;"><?php echo e($live->judul_kegiatan); ?></div>
            <div class="live-card-pic" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;font-size:12.5px;color:rgba(255,255,255,0.9);padding-top:10px;border-top:1px solid rgba(255,255,255,0.15);">
                <span><i class="bi bi-people-fill" style="color:#93c5fd;margin-right:4px;"></i> Unit Penyelenggara: <strong style="color:#ffffff;"><?php echo e($live->user->nama_unit ?? $live->user->name); ?></strong></span>
                <?php if($live->pic_kegiatan): ?>
                    <span><i class="bi bi-person-badge-fill" style="color:#93c5fd;margin-right:4px;"></i> PIC: <strong style="color:#ffffff;"><?php echo e($live->pic_kegiatan); ?></strong></span>
                <?php endif; ?>
                <?php if($live->layout): ?>
                    <span><i class="bi bi-grid-3x3-gap-fill" style="color:#93c5fd;margin-right:4px;"></i> Layout: <strong style="color:#ffffff;"><?php echo e($live->layout->nama_layout); ?></strong></span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>



<div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px;">
    <a href="<?php echo e(route('pemesanan.create')); ?>" class="btn-primary">
        <i class="bi bi-plus-circle-fill" style="font-size:16px;"></i> Buat Pemesanan Ruangan
    </a>
    <a href="<?php echo e(route('kalender.index')); ?>" class="btn-secondary">
        <i class="bi bi-calendar-range" style="font-size:16px;"></i> Kalender Ruangan
    </a>
    <a href="<?php echo e(route('pemesanan.index')); ?>" class="btn-secondary">
        <i class="bi bi-journal-text" style="font-size:16px;"></i> Riwayat Pemesanan
    </a>
</div>



<div class="stat-grid">
    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Total Pemesanan','value' => $totalPemesanan] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar-check','color' => 'blue']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Menunggu Approval','value' => $pendingPemesanan] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'clock-history','color' => 'yellow']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Disetujui','value' => $approvedPemesanan] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'check-circle','color' => 'green']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Kegiatan Mendatang','value' => $upcomingPemesanan] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar-event','color' => 'purple']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $attributes = $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__attributesOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f)): ?>
<?php $component = $__componentOriginal179d930850cbc0b57567dfb2ba44c92f; ?>
<?php unset($__componentOriginal179d930850cbc0b57567dfb2ba44c92f); ?>
<?php endif; ?>
</div>



<div class="dashboard-user-layout">

    
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-clock-history"></i> Pemesanan Terbaru Saya</h2>
            <a href="<?php echo e(route('pemesanan.index')); ?>">
                <i class="bi bi-arrow-right"></i> Lihat Semua
            </a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Kegiatan</th>
                        <th>Ruangan</th>
                        <th>Tanggal &amp; Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pemesananTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span style="font-family:monospace;font-size:11.5px;color:#005baa;font-weight:700;"><?php echo e($item->kode_pemesanan); ?></span></td>
                        <td><strong><?php echo e($item->judul_kegiatan); ?></strong></td>
                        <td>
                            <?php echo e($item->ruangan->nama_ruangan); ?><br>
                            <small style="color:#64748b;"><?php echo e($item->layout?->nama_layout ?? '-'); ?></small>
                        </td>
                        <td>
                            <?php echo e($item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY')); ?><br>
                            <small style="color:#64748b;"><i class="bi bi-clock"></i> <?php echo e($item->waktu_mulai); ?> – <?php echo e($item->waktu_selesai); ?></small>
                        </td>
                        <td>
                            <?php if($item->status->value == 'Pending'): ?>
                                <span class="badge badge-warning"><i class="bi bi-clock"></i> Pending</span>
                            <?php elseif($item->status->value == 'Disetujui'): ?>
                                <span class="badge badge-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                            <?php elseif($item->status->value == 'Ditolak'): ?>
                                <span class="badge badge-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><i class="bi bi-dash-circle"></i> Cancel</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="<?php echo e(route('pemesanan.show', $item)); ?>" class="btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <?php if($item->status->value == 'Pending'): ?>
                                <form action="<?php echo e(route('pemesanan.cancel', $item)); ?>" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Batalkan Pemesanan', message: 'Apakah Anda yakin ingin membatalkan pengajuan pemesanan <strong><?php echo e($item->kode_pemesanan); ?></strong>?', type: 'warning', confirmText: 'Ya, Batalkan' })">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-danger btn-sm">
                                        <i class="bi bi-x-circle"></i> Batalkan
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>Belum ada pengajuan pemesanan.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div style="display:flex;flex-direction:column;gap:20px;">

        
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="bi bi-calendar-day"></i> Agenda Hari Ini</h2>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $kegiatanHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="agenda-card" style="padding:14px 16px;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:12px;background:#ffffff;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px;flex-wrap:wrap;">
                    <strong style="color:#003b73;font-size:13px;"><i class="bi bi-building"></i> <?php echo e($item->ruangan->nama_ruangan); ?></strong>
                    <span class="badge badge-info" style="font-size:11px;padding:3px 9px;border-radius:6px;background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;font-weight:600;">
                        <i class="bi bi-people-fill"></i> <?php echo e($item->user->nama_unit ?? $item->user->name); ?>

                    </span>
                </div>
                <p style="margin:0 0 6px 0;font-weight:700;color:#0f172a;font-size:13.5px;line-height:1.35;"><?php echo e($item->judul_kegiatan); ?></p>
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;color:#64748b;flex-wrap:wrap;gap:6px;">
                    <span style="color:#005baa;font-weight:600;"><i class="bi bi-clock"></i> <?php echo e($item->waktu_mulai); ?> – <?php echo e($item->waktu_selesai); ?> WITA</span>
                    <?php if($item->pic_kegiatan): ?>
                        <span style="color:#475569;font-weight:500;"><i class="bi bi-person-fill"></i> PIC: <?php echo e($item->pic_kegiatan); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state" style="padding:24px 20px;">
                <i class="bi bi-calendar-x"></i>
                <p>Tidak ada agenda hari ini.</p>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="dashboard-section" style="background:linear-gradient(135deg,#005baa,#003b73);color:white;border:none;">
            <div style="padding:20px;">
                <h2 style="color:white;font-size:14px;display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                    <i class="bi bi-info-circle"></i> Panduan Pemesanan
                </h2>
                <div style="display:flex;flex-direction:column;gap:12px;font-size:13px;">
                    <?php $__currentLoopData = [
                        ['1', 'Pilih ruangan & tata letak (layout) sesuai kebutuhan acara.'],
                        ['2', 'Isi formulir pengajuan pemesanan secara lengkap.'],
                        ['3', 'Tunggu verifikasi & persetujuan dari Administrator.'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div style="background:rgba(255,255,255,0.2);width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;"><?php echo e($step[0]); ?></div>
                        <div style="padding-top:3px;"><?php echo e($step[1]); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/dashboard.blade.php ENDPATH**/ ?>