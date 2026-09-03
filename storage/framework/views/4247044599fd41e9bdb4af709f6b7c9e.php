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
        <h1><i class="bi bi-journal-text" style="color:#005baa;margin-right:8px;"></i>Pemesanan Saya</h1>
        <p>Daftar seluruh pengajuan penggunaan ruangan Anda.</p>
    </div>
    <a href="<?php echo e(route('pemesanan.create')); ?>" class="btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Pemesanan
    </a>
</div>



<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Kegiatan</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><span style="font-family:monospace;font-size:11.5px;color:#005baa;font-weight:700;"><?php echo e($item->kode_pemesanan); ?></span></td>
                    <td><strong style="color:#003b73;"><?php echo e($item->judul_kegiatan); ?></strong></td>
                    <td>
                        <strong><?php echo e($item->ruangan->nama_ruangan); ?></strong>
                        <?php if($item->layout): ?>
                            <br><small style="color:#64748b;"><?php echo e($item->layout->nama_layout); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo e($item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY')); ?></strong></td>
                    <td style="color:#64748b;white-space:nowrap;"><?php echo e($item->waktu_mulai); ?> – <?php echo e($item->waktu_selesai); ?></td>
                    <td>
                        <?php if($item->status->value == 'Selesai' || $item->is_finished): ?>
                            <span class="badge" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;font-weight:700;"><i class="bi bi-check2-all"></i> Selesai</span>
                        <?php elseif($item->status->value == 'Pending'): ?>
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
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada pengajuan pemesanan. <a href="<?php echo e(route('pemesanan.create')); ?>">Buat sekarang</a></p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($pemesanan->hasPages()): ?>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/pemesanan/index.blade.php ENDPATH**/ ?>