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
        <h1><i class="bi bi-calendar2-week" style="color:#005baa;margin-right:8px;"></i>Kelola Hari Libur &amp; Cuti Bersama</h1>
        <p>Manajemen tanggal merah, hari libur nasional, cuti bersama, dan akhir pekan sistem SILAKAN.</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <form method="POST" action="<?php echo e(route('admin.hari-libur.sync')); ?>" style="display:inline-block;" onsubmit="return submitFormWithConfirm(this, { title: 'Sinkronisasi Hari Libur', message: 'Sinkronkan data hari libur nasional &amp; cuti bersama tahun <strong><?php echo e(date('Y')); ?></strong> dari API resmi?', type: 'primary', confirmText: 'Ya, Sinkronkan' })">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tahun" value="<?php echo e(date('Y')); ?>">
            <button type="submit" class="btn-primary">
                <i class="bi bi-cloud-download"></i> Sync API Libur &amp; Cuti <?php echo e(date('Y')); ?>

            </button>
        </form>
    </div>
</div>

<div class="dashboard-grid">

    
    <div class="dashboard-section" style="margin-bottom:0;">
        <div class="section-header">
            <h2><i class="bi bi-plus-circle"></i> Tambah Hari Libur / Cuti Bersama</h2>
        </div>
        <div style="padding:24px;">
            <form method="POST" action="<?php echo e(route('admin.hari-libur.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="required">Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo e(old('tanggal')); ?>" required>
                    <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="required">Nama / Keterangan</label>
                    <input type="text" name="keterangan" value="<?php echo e(old('keterangan')); ?>" placeholder="Contoh: Cuti Bersama Idul Fitri" required>
                    <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="required">Kategori Hari Libur</label>
                    <select name="kategori" required>
                        <option value="libur_nasional" <?php echo e(old('kategori') == 'libur_nasional' ? 'selected' : ''); ?>>Hari Libur Nasional</option>
                        <option value="cuti_bersama"  <?php echo e(old('kategori') == 'cuti_bersama'  ? 'selected' : ''); ?>>Cuti Bersama</option>
                        <option value="internal"      <?php echo e(old('kategori') == 'internal'      ? 'selected' : ''); ?>>Libur Internal / Khusus BI</option>
                    </select>
                    <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-action" style="margin-top:12px;">
                    <button type="submit" class="btn-primary" style="width:100%;">
                        <i class="bi bi-check-lg"></i> Simpan Data Libur
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="dashboard-section" style="margin-bottom:0;">
        <div class="section-header">
            <h2><i class="bi bi-calendar-event"></i> Daftar Libur &amp; Cuti Bersama</h2>
            <form method="GET" action="<?php echo e(route('admin.hari-libur.index')); ?>" style="display:flex;align-items:center;gap:8px;">
                <select name="kategori" onchange="this.form.submit()" style="padding:4px 10px;font-size:12px;border-radius:6px;border:1px solid #cbd5e1;">
                    <option value="">Semua Kategori</option>
                    <option value="libur_nasional" <?php echo e(request('kategori') == 'libur_nasional' ? 'selected' : ''); ?>>Libur Nasional</option>
                    <option value="cuti_bersama"  <?php echo e(request('kategori') == 'cuti_bersama'  ? 'selected' : ''); ?>>Cuti Bersama</option>
                    <option value="internal"      <?php echo e(request('kategori') == 'internal'      ? 'selected' : ''); ?>>Internal BI</option>
                </select>

                <select name="tahun" onchange="this.form.submit()" style="padding:4px 10px;font-size:12px;border-radius:6px;border:1px solid #cbd5e1;">
                    <option value="">Semua Tahun</option>
                    <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($th); ?>" <?php echo e(request('tahun') == $th ? 'selected' : ''); ?>>Tahun <?php echo e($th); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th style="width:60px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $hariLibur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:#94a3b8;font-size:12px;"><?php echo e($hariLibur->firstItem() + $index); ?></td>
                        <td>
                            <strong style="color:#003b73;"><?php echo e($item->tanggal->isoFormat('ddd, D MMM YYYY')); ?></strong>
                        </td>
                        <td><?php echo e($item->keterangan); ?></td>
                        <td>
                            <?php if($item->kategori == 'cuti_bersama'): ?>
                                <span class="badge badge-warning" style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;"><i class="bi bi-umbrella-fill"></i> Cuti Bersama</span>
                            <?php elseif($item->kategori == 'internal'): ?>
                                <span class="badge badge-info"><i class="bi bi-building"></i> Internal BI</span>
                            <?php else: ?>
                                <span class="badge badge-danger"><i class="bi bi-flag-fill"></i> Libur Nasional</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo e(route('admin.hari-libur.destroy', $item)); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Hari Libur', message: 'Apakah Anda yakin ingin menghapus data libur <strong><?php echo e($item->keterangan); ?></strong>?', type: 'danger', confirmText: 'Ya, Hapus' })">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-danger btn-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p>Belum ada data hari libur / cuti bersama. Klik "Sync API Libur & Cuti" di atas.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($hariLibur->hasPages()): ?>
        <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
            <?php echo e($hariLibur->links()); ?>

        </div>
        <?php endif; ?>
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
<?php endif; ?>
<?php /**PATH D:\Bank Indo\silakan\resources\views/admin/hari-libur/index.blade.php ENDPATH**/ ?>