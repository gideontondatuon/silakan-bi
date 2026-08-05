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

<div class="dashboard-header" style="margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:800;color:#003b73;display:flex;align-items:center;gap:10px;">
            <i class="bi bi-shield-check" style="color:#005baa;"></i> Audit Log System &amp; Aktivitas User
        </h1>
        <p style="color:#64748b;font-size:13.5px;margin-top:4px;">
            Jejak aktivitas keamanan, perizinan, persetujuan, dan perubahan data pada sistem SILAKAN.
        </p>
    </div>
    <span class="badge badge-info" style="font-size:13px;padding:8px 16px;">
        <i class="bi bi-list-columns-reverse"></i> <?php echo e($auditLog->total()); ?> Log Tercatat
    </span>
</div>


<div class="dashboard-section" style="margin-bottom:24px;padding:20px 24px;background:#ffffff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
    <form method="GET" action="<?php echo e(route('admin.audit-log.index')); ?>" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:16px;align-items:end;">
        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="<?php echo e(request('tanggal_mulai')); ?>" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;">
        </div>

        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="<?php echo e(request('tanggal_selesai')); ?>" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;">
        </div>

        <div>
            <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Modul Aktivitas</label>
            <select name="modul" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:white;">
                <option value="">-- Semua Modul --</option>
                <option value="Approval" <?php echo e(request('modul') === 'Approval' ? 'selected' : ''); ?>>Approval</option>
                <option value="Pemesanan" <?php echo e(request('modul') === 'Pemesanan' ? 'selected' : ''); ?>>Pemesanan</option>
                <option value="Master Data" <?php echo e(request('modul') === 'Master Data' ? 'selected' : ''); ?>>Master Data</option>
                <option value="User" <?php echo e(request('modul') === 'User' ? 'selected' : ''); ?>>User & Auth</option>
            </select>
        </div>

        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn-primary" style="flex:1;padding:9px 14px;border-radius:8px;font-size:13px;font-weight:700;">
                <i class="bi bi-filter"></i> Filter Log
            </button>
            <a href="<?php echo e(route('admin.audit-log.index')); ?>" class="btn-secondary" style="padding:9px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>


<div class="dashboard-section" style="padding:0;overflow:hidden;border-radius:14px;border:1px solid #e2e8f0;background:#ffffff;">
    <div class="table-wrapper">
        <table class="data-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;text-align:left;font-size:12px;color:#475569;text-transform:uppercase;">
                    <th style="padding:14px 18px;">Waktu &amp; IP</th>
                    <th style="padding:14px 18px;">Pelaku (User/Admin)</th>
                    <th style="padding:14px 18px;">Aktivitas</th>
                    <th style="padding:14px 18px;">Modul</th>
                    <th style="padding:14px 18px;">Keterangan Rinci</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $auditLog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom:1px solid #f1f5f9;font-size:13px;">
                    <td style="padding:14px 18px;white-space:nowrap;">
                        <strong style="color:#0f172a;display:block;"><?php echo e($log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-'); ?> WITA</strong>
                        <small style="color:#64748b;"><i class="bi bi-hdd-network"></i> IP: <?php echo e($log->ip_address ?? '127.0.0.1'); ?></small>
                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#003b73;display:block;"><?php echo e($log->user?->name ?? 'Sistem'); ?></strong>
                        <small style="color:#64748b;"><?php echo e($log->user?->nama_unit ?? $log->user?->role?->label() ?? 'System'); ?></small>
                    </td>
                    <td style="padding:14px 18px;">
                        <?php
                            $aksiText = $log->aksi ?? $log->aktivitas ?? '-';
                            $aksiLower = strtolower($aksiText);
                            $bg = '#e0f2fe'; $color = '#0369a1'; $border = '#bae6fd';
                            if (str_contains($aksiLower, 'tambah') || str_contains($aksiLower, 'approve') || str_contains($aksiLower, 'setuju') || str_contains($aksiLower, 'simpan') || str_contains($aksiLower, 'create')) {
                                $bg = '#dcfce7'; $color = '#15803d'; $border = '#bbf7d0';
                            } elseif (str_contains($aksiLower, 'hapus') || str_contains($aksiLower, 'reject') || str_contains($aksiLower, 'batal') || str_contains($aksiLower, 'delete')) {
                                $bg = '#fee2e2'; $color = '#b91c1c'; $border = '#fecaca';
                            } elseif (str_contains($aksiLower, 'edit') || str_contains($aksiLower, 'update') || str_contains($aksiLower, 'ubah')) {
                                $bg = '#fef3c7'; $color = '#b45309'; $border = '#fde68a';
                            }
                        ?>
                        <span class="badge" style="font-size:11.5px;padding:4px 9px;background:<?php echo e($bg); ?>;color:<?php echo e($color); ?>;border:1px solid <?php echo e($border); ?>;font-weight:700;border-radius:6px;display:inline-block;">
                            <?php echo e($aksiText); ?>

                        </span>
                    </td>
                    <td style="padding:14px 18px;font-weight:600;color:#334155;">
                        <?php echo e($log->modul ?? '-'); ?>

                    </td>
                    <td style="padding:14px 18px;color:#475569;line-height:1.4;">
                        <?php echo e($log->keterangan ?? $log->deskripsi ?? ($aksiText !== '-' ? $aksiText . ' pada modul ' . ($log->modul ?? 'Sistem') : '-')); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="padding:40px 20px;text-align:center;color:#64748b;">
                        <i class="bi bi-shield-x" style="font-size:32px;color:#94a3b8;display:block;margin-bottom:8px;"></i>
                        Belum ada rekaman audit log yang sesuai.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($auditLog->hasPages()): ?>
    <div style="padding:16px 20px;border-top:1px solid #e2e8f0;background:#f8fafc;">
        <?php echo e($auditLog->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/audit-log/index.blade.php ENDPATH**/ ?>