<form method="post" action="<?php echo e(route('profile.update')); ?>" style="display:flex;flex-direction:column;gap:18px;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('patch'); ?>

    <?php
        $isAdmin = auth()->user()->role->value === 'admin';
    ?>

    
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
            Nama Unit / Pengguna
            <?php if(!$isAdmin): ?>
                <span style="font-size:11px;font-weight:600;color:#dc2626;margin-left:6px;"><i class="bi bi-lock-fill"></i> (Terkunci)</span>
            <?php endif; ?>
        </label>
        <div style="position:relative;">
            <?php if($isAdmin): ?>
                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;font-weight:600;" placeholder="Masukkan Nama Administrator">
            <?php else: ?>
                <input type="text" value="<?php echo e($user->name); ?>" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f1f5f9;color:#475569;font-weight:600;font-size:13.5px;cursor:not-allowed;">
            <?php endif; ?>
            <i class="bi bi-building" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        <?php if($isAdmin): ?>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php else: ?>
            <span style="display:block;font-size:11.5px;color:#64748b;margin-top:6px;line-height:1.4;">
                <i class="bi bi-info-circle" style="color:#005baa;"></i> Nama unit dikunci oleh sistem. Hubungi Administrator jika terdapat penyesuaian nama unit.
            </span>
        <?php endif; ?>
    </div>

    
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">
            Username ID
            <?php if(!$isAdmin): ?>
                <span style="font-size:11px;font-weight:600;color:#dc2626;margin-left:6px;"><i class="bi bi-lock-fill"></i> (Terkunci)</span>
            <?php endif; ?>
        </label>
        <div style="position:relative;">
            <?php if($isAdmin): ?>
                <input type="text" name="username" value="<?php echo e(old('username', $user->username)); ?>" required style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;font-weight:600;" placeholder="Masukkan Username Admin">
            <?php else: ?>
                <input type="text" value="<?php echo e($user->username); ?>" readonly disabled style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;background:#f1f5f9;color:#475569;font-weight:600;font-size:13.5px;cursor:not-allowed;">
            <?php endif; ?>
            <i class="bi bi-person-badge" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        <?php if($isAdmin): ?>
            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color:#dc2626;font-size:12px;margin-top:4px;display:block;"><i class="bi bi-exclamation-circle"></i> <?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php endif; ?>
    </div>

    
    <div class="form-group" style="margin-bottom:0;">
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">Alamat Email <small style="color:#64748b;font-weight:400;">(Opsional)</small></label>
        <div style="position:relative;">
            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;" placeholder="nama@bi.go.id (opsional)">
            <i class="bi bi-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#005baa;font-size:15px;"></i>
        </div>
        <?php $__errorArgs = ['email'];
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
        <label style="display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px;">Nomor WhatsApp</label>
        <div style="position:relative;">
            <input type="text" name="no_wa" value="<?php echo e(old('no_wa', $user->no_wa)); ?>" style="width:100%;padding:10px 14px 10px 42px !important;padding-left:42px !important;border:1px solid #cbd5e1;border-radius:10px;font-size:13.5px;outline:none;transition:all .2s;" placeholder="Contoh: 081340693458">
            <i class="bi bi-whatsapp" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#25d366;font-size:15px;"></i>
        </div>
        <span style="display:block;font-size:11.5px;color:#64748b;margin-top:5px;">Menerima notifikasi WhatsApp resmi pengajuan &amp; status pemesanan.</span>
        <?php $__errorArgs = ['no_wa'];
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
        <button type="submit" class="btn-primary" style="padding:10px 20px;font-weight:600;display:inline-flex;align-items:center;gap:6px;border-radius:10px;">
            <i class="bi bi-check2-circle"></i> Simpan Perubahan
        </button>

        <?php if(session('status') === 'profile-updated'): ?>
            <span style="color:#059669;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                <i class="bi bi-check-circle-fill"></i> Profil berhasil diperbarui.
            </span>
        <?php endif; ?>
    </div>
</form>
<?php /**PATH D:\Bank Indo\silakan\resources\views/profile/partials/update-profile-information-form.blade.php ENDPATH**/ ?>