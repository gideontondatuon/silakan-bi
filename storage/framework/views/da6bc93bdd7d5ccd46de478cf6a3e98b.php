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
        <h1><i class="bi bi-building" style="color:#005baa;margin-right:8px;"></i>Data Ruangan</h1>
        <p>Kelola data ruangan yang tersedia pada sistem SILAKAN.</p>
    </div>
    <a href="<?php echo e(route('admin.ruangan.create')); ?>" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Ruangan
    </a>
</div>


<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Nama Ruangan</th>
                    <th>Lokasi</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ruangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?php echo e($ruangans->firstItem() + $i); ?></td>
                    <td>
                        <strong style="color:#003b73;"><?php echo e($ruangan->nama_ruangan); ?></strong>
                    </td>
                    <td>
                        <span style="display:flex;align-items:center;gap:6px;color:#475569;">
                            <i class="bi bi-geo-alt" style="color:#005baa;"></i>
                            <?php echo e($ruangan->lokasi); ?>

                        </span>
                    </td>
                    <td>
                        <span style="display:flex;align-items:center;gap:5px;">
                            <i class="bi bi-people" style="color:#005baa;font-size:13px;"></i>
                            <?php echo e($ruangan->kapasitas); ?> Orang
                        </span>
                    </td>
                    <td>
                        <?php if($ruangan->status == 'aktif'): ?>
                            <span class="badge badge-success"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Aktif</span>
                        <?php elseif($ruangan->status == 'perawatan'): ?>
                            <span class="badge badge-warning"><i class="bi bi-tools" style="font-size:10px;"></i> Perawatan</span>
                        <?php else: ?>
                            <span class="badge badge-danger"><i class="bi bi-x-circle" style="font-size:10px;"></i> Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="<?php echo e(route('admin.ruangan.edit', $ruangan)); ?>" class="btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.ruangan.destroy', $ruangan)); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Ruangan', message: 'Apakah Anda yakin ingin menghapus data ruangan <strong><?php echo e($ruangan->nama_ruangan); ?></strong>?', type: 'danger', confirmText: 'Ya, Hapus' })">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-building"></i>
                            <p>Belum ada data ruangan. <a href="<?php echo e(route('admin.ruangan.create')); ?>">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($ruangans->hasPages()): ?>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        <?php echo e($ruangans->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/ruangan/index.blade.php ENDPATH**/ ?>