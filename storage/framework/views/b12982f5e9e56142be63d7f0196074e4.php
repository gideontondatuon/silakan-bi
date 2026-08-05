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

<div class="dashboard-header" style="margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:800;color:#003b73;display:flex;align-items:center;gap:10px;">
            <i class="bi bi-file-earmark-bar-graph-fill" style="color:#005baa;"></i>
            Laporan & Ekspor Pemesanan Ruangan
        </h1>
        <p style="color:#64748b;font-size:13.5px;margin-top:4px;">
            Rekapitulasi dan analisis data pemesanan ruangan kantor Bank Indonesia KPwBI Prov. Sulut.
        </p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="<?php echo e(route('admin.laporan.export-excel', request()->query())); ?>" class="btn-secondary" style="background:#059669;color:white;border:none;display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none;">
            <i class="bi bi-file-earmark-excel-fill"></i> Ekspor Excel (.xlsx)
        </a>
        <a href="<?php echo e(route('admin.laporan.cetak', request()->query())); ?>" target="_blank" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none;">
            <i class="bi bi-printer-fill"></i> Cetak / Preview PDF
        </a>
    </div>
</div>



<div class="dashboard-section" style="margin-bottom:24px;padding:22px 24px;background:#ffffff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
    <form method="GET" action="<?php echo e(route('admin.laporan.index')); ?>" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:16px;align-items:end;">
        
        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="<?php echo e(request('tanggal_mulai')); ?>" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;">
        </div>

        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="<?php echo e(request('tanggal_selesai')); ?>" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;">
        </div>

        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Ruangan</label>
            <select name="ruangan_id" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:white;">
                <option value="">-- Semua Ruangan --</option>
                <?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r->id); ?>" <?php echo e(request('ruangan_id') == $r->id ? 'selected' : ''); ?>><?php echo e($r->nama_ruangan); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Status</label>
            <select name="status" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:white;">
                <option value="">-- Semua Status --</option>
                <option value="Disetujui" <?php echo e(request('status') === 'Disetujui' ? 'selected' : ''); ?>>Disetujui</option>
                <option value="Pending" <?php echo e(request('status') === 'Pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="Ditolak" <?php echo e(request('status') === 'Ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                <option value="Cancel" <?php echo e(request('status') === 'Cancel' ? 'selected' : ''); ?>>Cancel</option>
            </select>
        </div>

        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Pemohon / Unit</label>
            <select name="user_id" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:white;">
                <option value="">-- Semua Unit --</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->nama_unit ?? $u->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn-primary" style="flex:1;padding:9px 14px;border-radius:8px;font-size:13px;font-weight:700;">
                <i class="bi bi-filter"></i> Filter
            </button>
            <a href="<?php echo e(route('admin.laporan.index')); ?>" class="btn-secondary" style="padding:9px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>

    </form>
</div>



<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:24px;">
    <div style="background:#ffffff;padding:18px 20px;border-radius:12px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:14px;">
        <div style="width:44px;height:44px;border-radius:10px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:20px;">
            <i class="bi bi-file-earmark-text-fill"></i>
        </div>
        <div>
            <small style="color:#64748b;font-weight:600;font-size:12px;display:block;">Total Data</small>
            <strong style="font-size:20px;color:#0f172a;"><?php echo e($totalPemesanan); ?> Pemesanan</strong>
        </div>
    </div>

    <div style="background:#ffffff;padding:18px 20px;border-radius:12px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:14px;">
        <div style="width:44px;height:44px;border-radius:10px;background:#dcfce7;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:20px;">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <small style="color:#64748b;font-weight:600;font-size:12px;display:block;">Disetujui</small>
            <strong style="font-size:20px;color:#16a34a;"><?php echo e($totalDisetujui); ?> Kegiatan</strong>
        </div>
    </div>

    <div style="background:#ffffff;padding:18px 20px;border-radius:12px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:14px;">
        <div style="width:44px;height:44px;border-radius:10px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:20px;">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div>
            <small style="color:#64748b;font-weight:600;font-size:12px;display:block;">Ditolak</small>
            <strong style="font-size:20px;color:#dc2626;"><?php echo e($totalDitolak); ?> Kegiatan</strong>
        </div>
    </div>
</div>



<div class="dashboard-section" style="padding:0;overflow:hidden;border-radius:14px;border:1px solid #e2e8f0;background:#ffffff;">
    <div class="table-wrapper">
        <table class="data-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;text-align:left;font-size:12px;color:#475569;text-transform:uppercase;">
                    <th style="padding:14px 18px;">No</th>
                    <th style="padding:14px 18px;">Kode / Tanggal</th>
                    <th style="padding:14px 18px;">Ruangan</th>
                    <th style="padding:14px 18px;">Kegiatan</th>
                    <th style="padding:14px 18px;">Unit / PIC</th>
                    <th style="padding:14px 18px;">Waktu</th>
                    <th style="padding:14px 18px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom:1px solid #f1f5f9;font-size:13px;">
                    <td style="padding:14px 18px;color:#64748b;"><?php echo e($pemesanan->firstItem() + $index); ?></td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#003b73;display:block;font-size:13px;"><?php echo e($item->kode_pemesanan); ?></strong>
                        <small style="color:#64748b;"><i class="bi bi-calendar3"></i> <?php echo e($item->tanggal_kegiatan ? $item->tanggal_kegiatan->format('d/m/Y') : '-'); ?></small>
                    </td>
                    <td style="padding:14px 18px;font-weight:700;color:#334155;">
                        <?php echo e($item->ruangan->nama_ruangan ?? '-'); ?>

                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#0f172a;display:block;"><?php echo e($item->judul_kegiatan); ?></strong>
                        <?php if($item->layout): ?>
                            <small style="color:#64748b;"><i class="bi bi-grid-3x3-gap"></i> Layout: <?php echo e($item->layout->nama_layout); ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#0284c7;display:block;"><?php echo e($item->user->nama_unit ?? $item->user->name); ?></strong>
                        <small style="color:#64748b;"><i class="bi bi-person"></i> <?php echo e($item->pic_kegiatan); ?></small>
                    </td>
                    <td style="padding:14px 18px;white-space:nowrap;color:#475569;font-weight:600;">
                        <i class="bi bi-clock"></i> <?php echo e($item->waktu_mulai); ?> - <?php echo e($item->waktu_selesai); ?>

                    </td>
                    <td style="padding:14px 18px;">
                        <?php
                            $val = is_object($item->status) ? $item->status->value : $item->status;
                        ?>
                        <?php if($val === 'Disetujui'): ?>
                            <span class="badge badge-success" style="padding:4px 10px;border-radius:6px;font-size:11px;"><i class="bi bi-check-circle-fill"></i> Disetujui</span>
                        <?php elseif($val === 'Pending'): ?>
                            <span class="badge badge-warning" style="padding:4px 10px;border-radius:6px;font-size:11px;"><i class="bi bi-clock-fill"></i> Pending</span>
                        <?php elseif($val === 'Ditolak'): ?>
                            <span class="badge badge-danger" style="padding:4px 10px;border-radius:6px;font-size:11px;"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                        <?php else: ?>
                            <span class="badge badge-secondary" style="padding:4px 10px;border-radius:6px;font-size:11px;">Cancel</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" style="padding:40px 20px;text-align:center;color:#64748b;">
                        <i class="bi bi-search" style="font-size:32px;color:#94a3b8;display:block;margin-bottom:8px;"></i>
                        Tidak ada data laporan yang sesuai dengan filter.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($pemesanan->hasPages()): ?>
    <div style="padding:16px 20px;border-top:1px solid #e2e8f0;background:#f8fafc;">
        <?php echo e($pemesanan->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/laporan/index.blade.php ENDPATH**/ ?>