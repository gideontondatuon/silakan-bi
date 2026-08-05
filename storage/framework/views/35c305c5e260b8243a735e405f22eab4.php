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
        <h1><i class="bi bi-grid-fill" style="color:#005baa;margin-right:8px;"></i>Dashboard Admin</h1>
        <p>Selamat datang kembali, <strong><?php echo e(auth()->user()->name); ?></strong> &mdash; <?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
    </div>
    <div class="dashboard-date">
        <i class="bi bi-calendar3"></i>
        <?php echo e(now()->translatedFormat('d F Y')); ?>

    </div>
</div>



<?php if($kegiatanBerlangsung->count() > 0): ?>
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



<div class="stat-grid">
    <?php if (isset($component)) { $__componentOriginal179d930850cbc0b57567dfb2ba44c92f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal179d930850cbc0b57567dfb2ba44c92f = $attributes; } ?>
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Total Ruangan','value' => $totalRuangan] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'building','color' => 'blue']); ?>
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
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Total Pemesanan','value' => $totalPemesanan] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar','color' => 'teal']); ?>
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
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Menunggu Approval','value' => $waitingApproval] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Disetujui','value' => $disetujui] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Ditolak','value' => $ditolak] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'x-circle','color' => 'red']); ?>
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
<?php $component = App\View\Components\StatCard::resolve(['title' => 'Pemesanan Bulan Ini','value' => $pemesananBulanIni] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'graph-up','color' => 'purple']); ?>
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



<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-calendar-event-fill"></i> Agenda &amp; Kegiatan Mendatang</h2>
        <a href="<?php echo e(route('kalender.index')); ?>">
            <i class="bi bi-calendar3"></i> Kalender
        </a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Kegiatan</th>
                    <th>Ruangan &amp; Layout</th>
                    <th>Pemohon / Unit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $agendaMendatang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <strong style="color:#003b73;"><?php echo e($item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY')); ?></strong><br>
                        <small style="color:#64748b;"><i class="bi bi-clock"></i> <?php echo e($item->waktu_mulai); ?> – <?php echo e($item->waktu_selesai); ?> WITA</small>
                    </td>
                    <td>
                        <strong><?php echo e($item->judul_kegiatan); ?></strong><br>
                        <small style="color:#64748b;">PIC: <?php echo e($item->pic_kegiatan); ?> (<?php echo e($item->jenis_pic); ?>)</small>
                    </td>
                    <td>
                        <span style="font-weight:700;color:#005baa;"><?php echo e($item->ruangan->nama_ruangan); ?></span><br>
                        <small style="color:#64748b;"><?php echo e($item->layout?->nama_layout ?? '-'); ?> &middot; <?php echo e($item->jumlah_tamu); ?> Tamu</small>
                    </td>
                    <td>
                        <strong><?php echo e($item->user->name); ?></strong><br>
                        <small style="color:#64748b;"><?php echo e($item->user->nama_unit ?? '-'); ?></small>
                    </td>
                    <td><span class="badge badge-success"><i class="bi bi-check-circle"></i> Disetujui</span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p>Belum ada agenda kegiatan mendatang yang disetujui.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<div class="dashboard-grid">

    
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-clock-history"></i> Waiting Approval</h2>
            <a href="<?php echo e(route('admin.approval.index')); ?>">
                <i class="bi bi-arrow-right"></i> Semua
            </a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Kegiatan</th>
                        <th>User</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $waitingList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge badge-secondary"><?php echo e($item->kode_pemesanan); ?></span></td>
                        <td><?php echo e($item->judul_kegiatan); ?></td>
                        <td><?php echo e($item->user->name); ?></td>
                        <td><?php echo e($item->ruangan->nama_ruangan); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>Tidak ada pemesanan menunggu.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-sun"></i> Agenda Hari Ini</h2>
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
        <div class="empty-state" style="padding:30px 24px;">
            <i class="bi bi-calendar-check"></i>
            <p>Tidak ada agenda hari ini.</p>
        </div>
        <?php endif; ?>
    </div>

</div>



<div class="dashboard-grid">

    
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-trophy"></i> Ruangan Terpopuler</h2>
        </div>
        <?php $__currentLoopData = $ruanganTerpopuler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="ranking-item">
            <span>
                <span class="ranking-rank"><?php echo e($index + 1); ?></span>
                <?php echo e($item->ruangan->nama_ruangan); ?>

            </span>
            <strong><?php echo e($item->total); ?> booking</strong>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-activity"></i> Aktivitas Terbaru</h2>
        </div>
        <?php $__currentLoopData = $aktivitasTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="activity-item">
            <div class="activity-dot"></div>
            <div>
                <strong><?php echo e($item->user->name); ?></strong>
                membuat pemesanan ruangan<br>
                <small><i class="bi bi-hash"></i> <?php echo e($item->kode_pemesanan); ?></small>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>


<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:20px;margin-top:24px;margin-bottom:24px;">
    
    
    <div class="dashboard-section" style="margin-bottom:0;padding:20px;background:#ffffff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
        <div class="section-header" style="margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">
            <h2 style="font-size:15px;font-weight:700;color:#003b73;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-graph-up-arrow" style="color:#005baa;"></i> Tren Pemesanan (6 Bulan)
            </h2>
        </div>
        <div style="position:relative;height:220px;">
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>

    
    <div class="dashboard-section" style="margin-bottom:0;padding:20px;background:#ffffff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
        <div class="section-header" style="margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">
            <h2 style="font-size:15px;font-weight:700;color:#003b73;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-pie-chart-fill" style="color:#005baa;"></i> Pemakaian per Unit Kerja
            </h2>
        </div>
        <div style="position:relative;height:220px;">
            <canvas id="unitDistributionChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Monthly Trend Chart
    const ctxMonthly = document.getElementById('monthlyTrendChart');
    if (ctxMonthly) {
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chartMonthlyLabels); ?>,
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: <?php echo json_encode($chartMonthlyData); ?>,
                    borderColor: '#005baa',
                    backgroundColor: 'rgba(0, 91, 170, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#003b73'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Unit Distribution Chart
    const ctxUnit = document.getElementById('unitDistributionChart');
    if (ctxUnit) {
        new Chart(ctxUnit, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($chartUnitLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($chartUnitData); ?>,
                    backgroundColor: [
                        '#005baa', '#0284c7', '#0ea5e9', '#38bdf8', '#7dd3fc'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }
});
</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>