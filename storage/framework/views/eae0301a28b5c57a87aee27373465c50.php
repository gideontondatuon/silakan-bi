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
        <h1><i class="bi bi-person-gear" style="color:#005baa;margin-right:8px;"></i>Edit User</h1>
        <p>Perbarui informasi akun pengguna <strong><?php echo e($user->name ?? $user->username); ?></strong>.</p>
    </div>
    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-person"></i> Informasi Akun</h2>
    </div>

    <div style="padding:24px;">
        <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-row">
                <div class="form-group">
                    <label>Username <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="username" value="<?php echo e(old('username', $user->username)); ?>" required>
                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>">
                    <?php $__errorArgs = ['name'];
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
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label><i class="bi bi-whatsapp" style="color:#25d366;margin-right:4px;"></i> No. WhatsApp</label>
                    <input type="text" name="no_wa" value="<?php echo e(old('no_wa', $user->no_wa)); ?>" placeholder="Contoh: 081234567890">
                    <?php $__errorArgs = ['no_wa'];
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
                    <label>Password Baru <span style="font-weight:400;color:#94a3b8;">(kosongkan jika tidak diubah)</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Isi jika ingin ubah password">
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
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
                    <label>Nama Unit <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_unit" value="<?php echo e(old('nama_unit', $user->nama_unit)); ?>" required>
                    <?php $__errorArgs = ['nama_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label>Kode Unit <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_unit" value="<?php echo e(old('kode_unit', $user->kode_unit)); ?>" required>
                    <?php $__errorArgs = ['kode_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group" style="max-width:320px;">
                <label>Role / Hak Akses <span style="color:#ef4444;">*</span></label>
                <select name="role" required>
                    <option value="user"  <?php echo e(old('role', is_object($user->role) ? $user->role->value : $user->role) == 'user'  ? 'selected' : ''); ?>>User (Pengguna Biasa)</option>
                    <option value="admin" <?php echo e(old('role', is_object($user->role) ? $user->role->value : $user->role) == 'admin' ? 'selected' : ''); ?>>Admin (Administrator)</option>
                </select>
                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-action">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-secondary">
                    <i class="bi bi-x"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const p = document.getElementById('password');
    const i = document.getElementById('eyeIcon');
    if (p.type === 'password') { p.type = 'text';     i.classList.replace('bi-eye','bi-eye-slash'); }
    else                       { p.type = 'password'; i.classList.replace('bi-eye-slash','bi-eye'); }
}
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>