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
        <h1><i class="bi bi-plus-circle" style="color:#005baa;margin-right:8px;"></i>Tambah Ruangan</h1>
        <p>Tambahkan data ruangan baru ke dalam sistem SILAKAN.</p>
    </div>
    <a href="<?php echo e(route('admin.ruangan.index')); ?>" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-building"></i> Informasi Ruangan</h2>
    </div>

    <div style="padding:24px;">
        <form method="POST" action="<?php echo e(route('admin.ruangan.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Ruangan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_ruangan" value="<?php echo e(old('nama_ruangan')); ?>" placeholder="Contoh: Ruang Rapat Utama" required>
                    <?php $__errorArgs = ['nama_ruangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label>Lokasi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="lokasi" value="<?php echo e(old('lokasi')); ?>" placeholder="Contoh: Lantai 3, Gedung A" required>
                    <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kapasitas <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="kapasitas" value="<?php echo e(old('kapasitas')); ?>" min="1" placeholder="Jumlah orang" required>
                    <?php $__errorArgs = ['kapasitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label>Status Ruangan <span style="color:#ef4444;">*</span></label>
                    <select name="status" required>
                        <option value="aktif"      <?php echo e(old('status') == 'aktif'      ? 'selected' : ''); ?>>Aktif</option>
                        <option value="nonaktif"   <?php echo e(old('status') == 'nonaktif'   ? 'selected' : ''); ?>>Nonaktif</option>
                        <option value="perawatan"  <?php echo e(old('status') == 'perawatan'  ? 'selected' : ''); ?>>Perawatan</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group">
                <label>Layout Ruangan yang Dapat Diterapkan</label>
                <p class="form-hint">Centang layout yang berlaku untuk ruangan ini. Jika hanya 1 layout, pengguna tidak perlu memilih layout saat membuat pemesanan.</p>
                <div class="facility-list" style="margin-top:10px;">
                    <?php $__currentLoopData = $layouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layoutItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label style="cursor:pointer;user-select:none;position:relative;">
                        <input type="checkbox" name="layouts[]" value="<?php echo e($layoutItem->id); ?>"
                               <?php echo e(in_array($layoutItem->id, old('layouts', [])) ? 'checked' : ''); ?>>
                        <span class="custom-check-badge"><i class="bi bi-check-lg"></i></span>
                        <span><?php echo e($layoutItem->nama_layout); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="form-action">
                <a href="<?php echo e(route('admin.ruangan.index')); ?>" class="btn-secondary">
                    <i class="bi bi-x"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Ruangan
                </button>
            </div>

        </form>
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/ruangan/create.blade.php ENDPATH**/ ?>