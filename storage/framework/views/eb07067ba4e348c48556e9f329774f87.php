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
        <h1><i class="bi bi-people" style="color:#005baa;margin-right:8px;"></i>Data User &amp; Akun Sistem</h1>
        <p>Manajemen akun Administrator dan akun unit kerja sistem SILAKAN.</p>
    </div>
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-primary">
        <i class="bi bi-person-plus"></i> Tambah User Baru
    </a>
</div>

<div style="display:flex;flex-direction:column;gap:24px;">

    
    <div class="dashboard-section" style="margin:0;background:#ffffff;border-radius:16px;border:1px solid #cbd5e1;box-shadow:0 4px 20px rgba(0,0,0,0.04);overflow:hidden;">
        <div class="section-header" style="padding:16px 24px;background:linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);border-bottom:1.5px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#003b73,#005baa);display:flex;align-items:center;justify-content:center;color:white;">
                    <i class="bi bi-shield-lock-fill" style="font-size:17px;"></i>
                </div>
                <div>
                    <h2 style="font-size:16px;font-weight:800;color:#0f172a;margin:0;line-height:1.2;">Akun Administrator</h2>
                    <p style="margin:2px 0 0 0;font-size:12px;color:#64748b;">Pengelola sistem dengan hak akses penuh, tidak dapat dihapus demi keamanan operasional.</p>
                </div>
            </div>
            <span class="badge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;padding:6px 14px;border-radius:9999px;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-shield-check"></i> Hak Akses Penuh
            </span>
        </div>

        <div style="padding:20px 24px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(360px, 1fr));gap:16px;">
                <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:14px;padding:18px 20px;display:flex;flex-direction:column;justify-content:space-between;gap:14px;box-shadow:0 2px 8px rgba(0,0,0,0.02);transition:all .2s;" onmouseover="this.style.borderColor='#005baa';this.style.boxShadow='0 6px 16px rgba(0,91,170,0.08)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                    <div style="display:flex;align-items:flex-start;gap:14px;">
                        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#005baa,#003b73);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:17px;flex-shrink:0;box-shadow:0 3px 8px rgba(0,91,170,0.25);<?php echo e($admin->avatar_style); ?>">
                            <?php echo e($admin->initials); ?>

                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                <h3 style="margin:0;font-size:15px;font-weight:800;color:#0f172a;line-height:1.2;">
                                    <?php echo e($admin->name ?? 'Administrator Utama'); ?>

                                </h3>
                                <?php if($admin->id === auth()->id()): ?>
                                    <span style="font-size:10.5px;background:#e0f2fe;color:#0284c7;font-weight:700;padding:2px 7px;border-radius:6px;border:1px solid #bae6fd;">Akun Anda</span>
                                <?php endif; ?>
                            </div>
                            <div style="margin-top:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <code style="background:#f1f5f9;color:#005baa;padding:2px 8px;border-radius:6px;font-family:Consolas,monospace;font-size:12.5px;font-weight:700;"><?php echo e($admin->username); ?></code>
                                <span style="font-size:11.5px;color:#64748b;"><i class="bi bi-building"></i> <?php echo e($admin->nama_unit ?? 'Administrator Sarpras'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid #f1f5f9;flex-wrap:wrap;gap:8px;">
                        <span class="badge" style="background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;font-size:11.5px;padding:5px 10px;border-radius:7px;display:inline-flex;align-items:center;gap:5px;">
                            <i class="bi bi-shield-lock-fill" style="color:#005baa;"></i> Dilindungi (Tidak Dapat Dihapus)
                        </span>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <a href="<?php echo e(route('admin.users.edit', $admin)); ?>" class="btn-primary btn-sm" style="padding:6px 12px;font-size:12px;border-radius:8px;" title="Ubah Nama & Password Admin">
                                <i class="bi bi-pencil-square"></i> Ubah Nama &amp; Password
                            </a>
                            <?php if($admin->id === auth()->id()): ?>
                                <a href="<?php echo e(route('profile.edit')); ?>" class="btn-secondary btn-sm" style="padding:6px 12px;font-size:12px;border-radius:8px;" title="Buka Profil Saya">
                                    <i class="bi bi-person-gear"></i> Profil
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="padding:20px;text-align:center;color:#64748b;font-size:13px;">
                    Belum ada akun admin yang terdaftar.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    
    <div class="dashboard-section" style="margin:0;background:#ffffff;border-radius:16px;border:1px solid #cbd5e1;box-shadow:0 4px 20px rgba(0,0,0,0.04);overflow:hidden;">
        <div class="section-header" style="padding:16px 24px;background:#f8fafc;border-bottom:1.5px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#e0f2fe,#bae6fd);display:flex;align-items:center;justify-content:center;color:#0284c7;">
                    <i class="bi bi-people-fill" style="font-size:17px;"></i>
                </div>
                <div>
                    <h2 style="font-size:16px;font-weight:800;color:#0f172a;margin:0;line-height:1.2;">Daftar Akun Unit Kerja (Pengguna Biasa)</h2>
                    <p style="margin:2px 0 0 0;font-size:12px;color:#64748b;">Daftar akun pemohon ruangan per unit kerja. Admin dapat melihat password akun unit kerja.</p>
                </div>
            </div>
            <span style="background:#ffffff;color:#0f172a;border:1px solid #cbd5e1;font-weight:700;padding:5px 12px;border-radius:9999px;font-size:12px;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-person-check-fill" style="color:#005baa;"></i> <?php echo e($users->total()); ?> Akun Terdaftar
            </span>
        </div>

        <div class="table-wrapper" style="border:none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:45px;text-align:center;">#</th>
                        <th>Username ID</th>
                        <th>Nama Unit Kerja</th>
                        <th>Kode Unit</th>
                        <th>Role</th>
                        <th>Password Akun</th>
                        <th style="text-align:center;width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:#94a3b8;font-size:12px;text-align:center;"><?php echo e($users->firstItem() + $loop->index); ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#005baa,#003b73);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:12px;flex-shrink:0;<?php echo e($user->avatar_style); ?>">
                                    <?php echo e($user->initials); ?>

                                </div>
                                <div>
                                    <code style="font-weight:700;color:#003b73;font-family:Consolas,monospace;font-size:13px;"><?php echo e($user->username); ?></code>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="color:#1e293b;display:block;"><?php echo e($user->nama_unit ?? $user->name ?? '-'); ?></strong>
                        </td>
                        <td>
                            <?php if($user->kode_unit): ?>
                                <span style="background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;padding:2px 8px;border-radius:6px;font-size:11.5px;font-weight:700;">
                                    <?php echo e($user->kode_unit); ?>

                                </span>
                            <?php else: ?>
                                <span style="color:#94a3b8;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-secondary" style="font-size:11.5px;padding:4px 9px;">
                                <i class="bi bi-person"></i> User
                            </span>
                        </td>
                        
                        <td>
                            <?php if(!empty($user->password_plain)): ?>
                                <div style="display:inline-flex;align-items:center;gap:6px;background:#f8fafc;border:1px solid #cbd5e1;padding:4px 10px;border-radius:8px;">
                                    <span id="pass-text-<?php echo e($user->id); ?>" style="font-family:Consolas,monospace;font-weight:700;letter-spacing:1px;font-size:13px;color:#1e293b;" data-plain="<?php echo e($user->password_plain); ?>">••••••••</span>
                                    <button type="button" onclick="togglePassVisibility('<?php echo e($user->id); ?>')" title="Lihat / Sembunyikan Password" style="background:none;border:none;cursor:pointer;color:#005baa;padding:2px 4px;border-radius:4px;display:inline-flex;align-items:center;font-size:14px;">
                                        <i class="bi bi-eye" id="pass-icon-<?php echo e($user->id); ?>"></i>
                                    </button>
                                    <button type="button" onclick="copyPassword('<?php echo e($user->password_plain); ?>', this)" title="Salin Password" style="background:none;border:none;cursor:pointer;color:#64748b;padding:2px 4px;border-radius:4px;display:inline-flex;align-items:center;font-size:13px;">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <span style="color:#94a3b8;font-size:12px;"><i>kpwbisulut</i></span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;">
                            <div class="action-group" style="justify-content:center;">
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn-secondary btn-sm" title="Edit Akun User">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus User', message: 'Apakah Anda yakin ingin menghapus user <strong><?php echo e($user->nama_unit ?? $user->username); ?></strong> (<?php echo e($user->username); ?>)?', type: 'danger', confirmText: 'Ya, Hapus' })">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-danger btn-sm" title="Hapus User">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state" style="padding:40px 20px;">
                                <i class="bi bi-people" style="font-size:36px;color:#94a3b8;"></i>
                                <p style="margin-top:8px;color:#64748b;">Belum ada data user unit kerja. <a href="<?php echo e(route('admin.users.create')); ?>" style="color:#005baa;font-weight:700;">Tambah user sekarang</a></p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
        <div style="padding:16px 24px;border-top:1px solid #f1f5f9;background:#f8fafc;">
            <?php echo e($users->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>

<script>
function togglePassVisibility(userId) {
    const textEl = document.getElementById('pass-text-' + userId);
    const iconEl = document.getElementById('pass-icon-' + userId);
    if (!textEl || !iconEl) return;

    const plain = textEl.getAttribute('data-plain');
    if (textEl.innerText === '••••••••') {
        textEl.innerText = plain;
        iconEl.classList.replace('bi-eye', 'bi-eye-slash');
        iconEl.style.color = '#dc2626';
    } else {
        textEl.innerText = '••••••••';
        iconEl.classList.replace('bi-eye-slash', 'bi-eye');
        iconEl.style.color = '#005baa';
    }
}

function copyPassword(text, btn) {
    if (!navigator.clipboard) {
        const temp = document.createElement('input');
        temp.value = text;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
    } else {
        navigator.clipboard.writeText(text);
    }

    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check2" style="color:#059669;font-weight:700;"></i>';
    btn.setAttribute('title', 'Tersalin!');
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.setAttribute('title', 'Salin Password');
    }, 1500);
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/users/index.blade.php ENDPATH**/ ?>