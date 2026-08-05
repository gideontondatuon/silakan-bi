<form method="post" action="<?php echo e(route('password.update')); ?>" style="display:flex;flex-direction:column;gap:18px;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('put'); ?>

    
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;" class="required">Password Saat Ini</label>
        <div style="position:relative;">
            <input type="password" name="current_password" required autocomplete="current-password" style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;" placeholder="Masukkan password saat ini">
            <i class="bi bi-key" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;" class="required">Password Baru</label>
        <div style="position:relative;">
            <input type="password" name="password" required autocomplete="new-password" style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;" placeholder="Minimal 8 karakter">
            <i class="bi bi-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;" class="required">Konfirmasi Password Baru</label>
        <div style="position:relative;">
            <input type="password" name="password_confirmation" required autocomplete="new-password" style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;" placeholder="Ulangi password baru">
            <i class="bi bi-shield-check" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        <?php $__errorArgs = ['password_confirmation', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;flex-wrap:wrap;">
        <button type="submit" class="btn-primary" style="padding:10px 20px;font-weight:600;display:inline-flex;align-items:center;gap:6px;background:#005baa;border-radius:10px;">
            <i class="bi bi-shield-lock"></i> Perbarui Password
        </button>

        <?php if(session('status') === 'password-updated'): ?>
            <span style="color:#059669;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                <i class="bi bi-check-circle-fill"></i> Password berhasil diubah.
            </span>
        <?php endif; ?>
    </div>
</form>
<?php /**PATH D:\Bank Indo\silakan\resources\views/profile/partials/update-password-form.blade.php ENDPATH**/ ?>