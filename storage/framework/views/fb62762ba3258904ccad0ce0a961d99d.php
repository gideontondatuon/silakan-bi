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
        <h1><i class="bi bi-layout-text-sidebar-reverse" style="color:#005baa;margin-right:8px;"></i>Data Layout Ruangan</h1>
        <p>Kelola konfigurasi layout dan kapasitas ruangan kantor.</p>
    </div>
    <a href="<?php echo e(route('admin.layout.create')); ?>" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Layout
    </a>
</div>

<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Nama Layout</th>
                    <th>Terdapat di Ruangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $layouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?php echo e($loop->iteration); ?></td>
                    <td>
                        <span style="display:flex;align-items:center;gap:9px;">
                            <span style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#e8f1fb,#cce0f5);display:flex;align-items:center;justify-content:center;color:#005baa;font-size:15px;flex-shrink:0;">
                                <i class="bi bi-layout-wtf"></i>
                            </span>
                            <strong style="color:#003b73;"><?php echo e($item->nama_layout); ?></strong>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            <?php if(isset($item->ruangans) && $item->ruangans->count() > 0): ?>
                                <?php $__currentLoopData = $item->ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge badge-secondary" style="font-size:11px;">
                                    <i class="bi bi-building"></i> <?php echo e($r->nama_ruangan); ?>

                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php elseif(isset($item->ruangan) && $item->ruangan): ?>
                                <span class="badge badge-secondary" style="font-size:11px;">
                                    <i class="bi bi-building"></i> <?php echo e($item->ruangan->nama_ruangan); ?>

                                </span>
                            <?php else: ?>
                                <span style="color:#94a3b8;font-style:italic;font-size:12px;">Belum ditautkan ke ruangan</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="<?php echo e(route('admin.layout.edit', $item)); ?>" class="btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.layout.destroy', $item)); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Layout', message: 'Apakah Anda yakin ingin menghapus layout <strong><?php echo e($item->nama_layout); ?></strong>?', type: 'danger', confirmText: 'Ya, Hapus' })">
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
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="bi bi-layout-text-sidebar"></i>
                            <p>Belum ada data layout ruangan. <a href="<?php echo e(route('admin.layout.create')); ?>">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($layouts->hasPages()): ?>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        <?php echo e($layouts->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/layout/index.blade.php ENDPATH**/ ?>