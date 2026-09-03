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

<div class="dashboard-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px;">
    <div>
        <h1><i class="bi bi-calendar-check-fill" style="color:#005baa;margin-right:8px;"></i>Manajemen Pemesanan Ruangan</h1>
        <p>Kelola, verifikasi, serta hapus/batalkan pengajuan pemesanan ruangan kantor KPwBI Prov. Sulut.</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <a href="<?php echo e(route('admin.approval.create')); ?>" class="btn-primary" style="padding:9px 18px;border-radius:10px;font-weight:700;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(0,91,170,0.25);">
            <i class="bi bi-calendar-plus-fill"></i> Tambah Rapat
        </a>
        <span class="badge <?php echo e($countPending > 0 ? 'badge-warning' : 'badge-success'); ?>" style="font-size:13px;padding:8px 16px;">
            <i class="bi bi-clock-history"></i> <?php echo e($countPending); ?> Menunggu Approval
        </span>
    </div>
</div>


<div style="display:flex;gap:10px;margin-bottom:20px;border-bottom:2px solid #e2e8f0;padding-bottom:2px;flex-wrap:wrap;">
    <a href="<?php echo e(route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'pending']))); ?>" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;<?php echo e($tab === 'pending' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;'); ?>">
        <i class="bi bi-hourglass-split"></i>
        Menunggu Approval
        <span style="background:<?php echo e($tab === 'pending' ? 'rgba(255,255,255,0.25)' : '#f59e0b'); ?>;color:<?php echo e($tab === 'pending' ? '#fff' : '#fff'); ?>;padding:2px 8px;border-radius:9999px;font-size:11px;">
            <?php echo e($countPending); ?>

        </span>
    </a>

    <a href="<?php echo e(route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'disetujui']))); ?>" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;<?php echo e($tab === 'disetujui' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;'); ?>">
        <i class="bi bi-check-circle-fill"></i>
        Disetujui / Aktif
        <span style="background:<?php echo e($tab === 'disetujui' ? 'rgba(255,255,255,0.25)' : '#059669'); ?>;color:#fff;padding:2px 8px;border-radius:9999px;font-size:11px;">
            <?php echo e($countDisetujui); ?>

        </span>
    </a>

    <a href="<?php echo e(route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'selesai']))); ?>" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;<?php echo e($tab === 'selesai' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;'); ?>">
        <i class="bi bi-check2-all"></i>
        Selesai
        <span style="background:<?php echo e($tab === 'selesai' ? 'rgba(255,255,255,0.25)' : '#475569'); ?>;color:#fff;padding:2px 8px;border-radius:9999px;font-size:11px;">
            <?php echo e($countSelesai); ?>

        </span>
    </a>

    <a href="<?php echo e(route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'semua']))); ?>" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;<?php echo e($tab === 'semua' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;'); ?>">
        <i class="bi bi-collection-fill"></i>
        Semua Pemesanan
        <span style="background:<?php echo e($tab === 'semua' ? 'rgba(255,255,255,0.25)' : '#64748b'); ?>;color:#fff;padding:2px 8px;border-radius:9999px;font-size:11px;">
            <?php echo e($countSemua); ?>

        </span>
    </a>
</div>


<div class="dashboard-section" style="margin-bottom:20px;padding:16px 20px;background:#ffffff;border-radius:12px;border:1px solid #e2e8f0;">
    <form method="GET" action="<?php echo e(route('admin.approval.index')); ?>" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <input type="hidden" name="tab" value="<?php echo e($tab); ?>">

        
        <div style="flex:2;min-width:220px;position:relative;">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari kode, judul kegiatan, PIC, unit..." style="width:100%;padding:9px 12px 9px 36px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;outline:none;">
            <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
        </div>

        
        <div style="flex:1;min-width:180px;">
            <select name="ruangan_id" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:white;outline:none;">
                <option value="">-- Semua Ruangan --</option>
                <?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r->id); ?>" <?php echo e(request('ruangan_id') == $r->id ? 'selected' : ''); ?>><?php echo e($r->nama_ruangan); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div style="min-width:150px;">
            <input type="date" name="tanggal" value="<?php echo e(request('tanggal')); ?>" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;outline:none;">
        </div>

        
        <button type="submit" class="btn-primary" style="padding:9px 16px;border-radius:8px;font-size:13px;font-weight:700;">
            <i class="bi bi-filter"></i> Terapkan
        </button>

        <?php if(request()->hasAny(['q', 'ruangan_id', 'tanggal'])): ?>
        <a href="<?php echo e(route('admin.approval.index', ['tab' => $tab])); ?>" class="btn-secondary" style="padding:9px 14px;border-radius:8px;font-size:13px;text-decoration:none;" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
        </a>
        <?php endif; ?>
    </form>
</div>


<div class="dashboard-section" style="padding:0;overflow:hidden;border-radius:14px;border:1px solid #e2e8f0;background:#ffffff;">
    <div class="table-wrapper">
        <table class="data-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;text-align:left;font-size:12px;color:#475569;text-transform:uppercase;">
                    <th style="padding:14px 18px;">Kode & Tanggal</th>
                    <th style="padding:14px 18px;">Kegiatan / PIC</th>
                    <th style="padding:14px 18px;">Ruangan &amp; Layout</th>
                    <th style="padding:14px 18px;">Waktu (WITA)</th>
                    <th style="padding:14px 18px;">Pemohon / Unit</th>
                    <th style="padding:14px 18px;">Status</th>
                    <th style="padding:14px 18px;text-align:center;">Aksi Manajemen</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusVal = is_object($item->status) ? $item->status->value : $item->status;
                    $isLiveToday = $item->canBeFinishedEarly();
                ?>
                <tr style="border-bottom:1px solid #f1f5f9;font-size:13px;">
                    <td style="padding:14px 18px;">
                        <strong style="color:#003b73;font-family:monospace;font-size:12.5px;display:block;">
                            <?php echo e($item->kode_pemesanan); ?>

                        </strong>
                        <small style="color:#64748b;font-weight:600;"><i class="bi bi-calendar3"></i> <?php echo e($item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY')); ?></small>
                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#0f172a;font-size:13.5px;display:block;"><?php echo e($item->judul_kegiatan); ?></strong>
                        <small style="color:#64748b;"><i class="bi bi-person"></i> PIC: <?php echo e($item->pic_kegiatan); ?> (<?php echo e($item->jenis_pic ?? '-'); ?>)</small>
                        <?php if($item->file_disposisi): ?>
                        <br><span class="badge badge-info" style="margin-top:3px;font-size:10px;padding:2px 6px;">
                            <i class="bi bi-paperclip"></i> Ada Disposisi
                        </span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#005baa;display:block;"><?php echo e($item->ruangan->nama_ruangan); ?></strong>
                        <small style="color:#64748b;"><?php echo e($item->layout?->nama_layout ?? '-'); ?> &bull; <?php echo e($item->jumlah_tamu); ?> Tamu</small>
                    </td>
                    <td style="padding:14px 18px;white-space:nowrap;color:#334155;font-weight:600;">
                        <i class="bi bi-clock"></i> <?php echo e($item->waktu_mulai); ?> – <?php echo e($item->waktu_selesai); ?>

                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#003b73;display:block;"><?php echo e($item->user?->name ?? 'User (Dihapus)'); ?></strong>
                        <small style="color:#64748b;"><?php echo e($item->user?->nama_unit ?? '-'); ?></small>
                    </td>
                    <td style="padding:14px 18px;">
                        <?php if($statusVal === 'Selesai' || $item->is_finished): ?>
                            <span class="badge" style="font-size:11px;padding:4px 10px;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;font-weight:600;">
                                <i class="bi bi-check2-all"></i> Selesai
                            </span>
                        <?php elseif($statusVal === 'Disetujui'): ?>
                            <span class="badge badge-success" style="font-size:11px;padding:4px 10px;">
                                <i class="bi bi-check-circle-fill"></i> Disetujui
                            </span>
                        <?php elseif($statusVal === 'Pending'): ?>
                            <span class="badge badge-warning" style="font-size:11px;padding:4px 10px;">
                                <i class="bi bi-clock-history"></i> Pending
                            </span>
                        <?php elseif($statusVal === 'Ditolak'): ?>
                            <span class="badge badge-danger" style="font-size:11px;padding:4px 10px;">
                                <i class="bi bi-x-circle-fill"></i> Ditolak
                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary" style="font-size:11px;padding:4px 10px;">
                                Dibatalkan
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:14px 18px;text-align:center;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;">
                            
                            <a href="<?php echo e(route('admin.approval.show', $item)); ?>" class="btn-primary btn-sm" style="padding:6px 12px;font-size:12px;border-radius:8px;" title="Review / Kelola Pemesanan">
                                <i class="bi bi-eye"></i> Detail
                            </a>

                            
                            <?php if($isLiveToday): ?>
                            <form action="<?php echo e(route('admin.approval.selesai-awal', $item)); ?>" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Selesaikan Rapat Lebih Awal', message: 'Apakah rapat di ruangan <strong><?php echo e($item->ruangan->nama_ruangan); ?></strong> telah selesai lebih cepat dan siap dibebaskan?', type: 'primary', confirmText: 'Ya, Selesaikan Sekarang' })" style="margin:0;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-sm" style="background:#059669;color:#fff;border:none;border-radius:8px;padding:6px 10px;font-size:12px;font-weight:700;cursor:pointer;" title="Selesaikan Rapat Sekarang">
                                    <i class="bi bi-check2-circle"></i> Selesai
                                </button>
                            </form>
                            <?php endif; ?>

                            
                            <form method="POST" action="<?php echo e(route('admin.approval.destroy', $item)); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Pemesanan Ruangan', message: 'Apakah Anda yakin ingin <strong>menghapus data pemesanan <?php echo e($item->kode_pemesanan); ?></strong> (<?php echo e($item->judul_kegiatan); ?> di <?php echo e($item->ruangan->nama_ruangan); ?>) secara permanen dari sistem?', type: 'danger', confirmText: 'Ya, Hapus Pemesanan' });" style="margin:0;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-danger btn-sm" style="padding:6px 10px;background:#dc2626;color:white;border:none;border-radius:8px;cursor:pointer;font-size:12px;" title="Hapus Pemesanan">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding:48px 24px;text-align:center;">
                            <div style="width:64px;height:64px;border-radius:50%;background:#f1f5f9;color:#94a3b8;display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:12px;">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <h4 style="margin:0 0 6px 0;color:#0f172a;font-size:16px;">Tidak ada data pemesanan ditemukan</h4>
                            <p style="margin:0;color:#64748b;font-size:13px;">
                                <?php if($tab === 'pending'): ?>
                                    Tidak ada pengajuan pemesanan yang menunggu approval saat ini.
                                <?php elseif($tab === 'disetujui'): ?>
                                    Belum ada data pemesanan yang berstatus disetujui sesuai filter yang dipilih.
                                <?php else: ?>
                                    Belum ada data pemesanan di dalam sistem.
                                <?php endif; ?>
                            </p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($pemesanan->hasPages()): ?>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;background:#f8fafc;">
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/approval/index.blade.php ENDPATH**/ ?>