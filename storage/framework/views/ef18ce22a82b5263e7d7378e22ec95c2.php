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
        <h1><i class="bi bi-clock-history" style="color:#f59e0b;margin-right:8px;"></i>Waiting List Pemesanan</h1>
        <p>Kelola dan verifikasi pengajuan penggunaan ruangan sebelum proses persetujuan.</p>
    </div>
    <span class="badge badge-warning" style="font-size:13px;padding:8px 16px;">
        <i class="bi bi-clock-history"></i> <?php echo e($pemesanan->total()); ?> Pengajuan Menunggu
    </span>
</div>


<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pemohon</th>
                    <th>Kegiatan</th>
                    <th>Ruangan</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <span class="badge badge-secondary" style="font-family:monospace;font-size:11px;">
                            <?php echo e($item->kode_pemesanan); ?>

                        </span>
                    </td>
                    <td>
                        <strong style="color:#003b73;"><?php echo e($item->user?->name ?? 'User (Dihapus)'); ?></strong><br>
                        <small style="color:#64748b;"><?php echo e($item->user?->nama_unit ?? '-'); ?></small>
                    </td>
                    <td>
                        <strong><?php echo e($item->judul_kegiatan); ?></strong><br>
                        <small style="color:#64748b;">PIC: <?php echo e($item->pic_kegiatan); ?></small>
                        <?php if($item->file_disposisi): ?>
                        <br><span class="badge badge-info" style="margin-top:4px;font-size:10.5px;">
                            <i class="bi bi-paperclip"></i> Ada Disposisi
                        </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="color:#005baa;"><?php echo e($item->ruangan->nama_ruangan); ?></strong>
                        <?php if($item->layout): ?>
                            <br><small style="color:#64748b;"><?php echo e($item->layout->nama_layout); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo e($item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY')); ?></strong><br>
                        <small style="color:#64748b;"><i class="bi bi-clock"></i> <?php echo e($item->waktu_mulai); ?> – <?php echo e($item->waktu_selesai); ?></small>
                    </td>
                    <td>
                        <span class="badge badge-warning">
                            <i class="bi bi-clock-history"></i>
                            <?php echo e($item->status->label()); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.approval.show', $item)); ?>" class="btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Review
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Tidak ada pengajuan pemesanan yang perlu direview.</p>
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/approval/index.blade.php ENDPATH**/ ?>