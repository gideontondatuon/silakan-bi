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
        <h1><i class="bi bi-people" style="color:#005baa;margin-right:8px;"></i>Data User</h1>
        <p>Manajemen akun pengguna sistem SILAKAN.</p>
    </div>
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-primary">
        <i class="bi bi-person-plus"></i> Tambah User
    </a>
</div>


<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Unit Kerja</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?php echo e($loop->iteration); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#005baa,#003b73);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;flex-shrink:0;<?php echo e($user->avatar_style); ?>">
                                <?php echo e($user->initials); ?>

                            </div>
                            <strong style="color:#003b73;"><?php echo e($user->username); ?></strong>
                        </div>
                    </td>
                    <td><?php echo e($user->name ?? '-'); ?></td>
                    <td>
                        <?php if($user->nama_unit): ?>
                            <span style="display:flex;align-items:center;gap:5px;color:#475569;">
                                <i class="bi bi-briefcase" style="color:#005baa;font-size:12px;"></i>
                                <?php echo e($user->nama_unit); ?>

                            </span>
                        <?php else: ?>
                            <span style="color:#94a3b8;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($user->role->value === 'admin'): ?>
                            <span class="badge badge-primary"><i class="bi bi-shield-check"></i> Admin</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><i class="bi bi-person"></i> User</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus User', message: 'Apakah Anda yakin ingin menghapus user <strong><?php echo e($user->name); ?></strong> (<?php echo e($user->username); ?>)?', type: 'danger', confirmText: 'Ya, Hapus' })">
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
                            <i class="bi bi-people"></i>
                            <p>Belum ada data user. <a href="<?php echo e(route('admin.users.create')); ?>">Tambah user sekarang</a></p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($users->hasPages()): ?>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        <?php echo e($users->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/users/index.blade.php ENDPATH**/ ?>