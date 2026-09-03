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


<div style="margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
                <div style="width:42px;height:42px;background:linear-gradient(135deg,#005baa,#003b73);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-file-earmark-bar-graph-fill" style="color:#ffffff;font-size:20px;"></i>
                </div>
                <div>
                    <h1 style="font-size:21px;font-weight:800;color:#003b73;margin:0;line-height:1.2;">Laporan &amp; Rekapitulasi Pemesanan</h1>
                    <p style="color:#64748b;font-size:13px;margin:2px 0 0;">Rekapitulasi pemesanan ruangan rapat KPwBI Prov. Sulawesi Utara</p>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="<?php echo e(route('admin.laporan.export-excel', request()->query())); ?>"
               style="background:linear-gradient(135deg,#059669,#047857);color:white;border:none;display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;font-weight:700;font-size:13px;text-decoration:none;box-shadow:0 4px 12px rgba(5,150,105,0.3);transition:all .2s;">
                <i class="bi bi-file-earmark-excel-fill"></i> Ekspor Excel (.xlsx)
            </a>
            <a href="<?php echo e(route('admin.laporan.cetak', request()->query())); ?>" target="_blank"
               style="background:linear-gradient(135deg,#005baa,#003b73);color:white;border:none;display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;font-weight:700;font-size:13px;text-decoration:none;box-shadow:0 4px 12px rgba(0,91,170,0.3);transition:all .2s;">
                <i class="bi bi-printer-fill"></i> Cetak / Pratinjau PDF
            </a>
        </div>
    </div>
</div>



<div style="background:#ffffff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,0,0,0.04);padding:22px 24px;margin-bottom:24px;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
        <i class="bi bi-funnel-fill" style="color:#005baa;font-size:15px;"></i>
        <span style="font-weight:700;font-size:13.5px;color:#003b73;">Filter &amp; Pencarian Data</span>
        <?php if(request()->hasAny(['tanggal_mulai','tanggal_selesai','ruangan_id','status','jenis_pic','user_id'])): ?>
            <span style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;margin-left:auto;">Filter Aktif</span>
        <?php endif; ?>
    </div>
    <form method="GET" action="<?php echo e(route('admin.laporan.index')); ?>" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(175px, 1fr));gap:14px;align-items:end;">

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="<?php echo e(request('tanggal_mulai')); ?>"
                   style="width:100%;padding:9px 12px;border:1.5px solid #cbd5e1;border-radius:9px;font-size:13px;color:#1e293b;outline:none;transition:border .2s;" onfocus="this.style.borderColor='#005baa'" onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="<?php echo e(request('tanggal_selesai')); ?>"
                   style="width:100%;padding:9px 12px;border:1.5px solid #cbd5e1;border-radius:9px;font-size:13px;color:#1e293b;outline:none;" onfocus="this.style.borderColor='#005baa'" onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">Ruangan</label>
            <select name="ruangan_id" style="width:100%;padding:9px 12px;border:1.5px solid #cbd5e1;border-radius:9px;font-size:13px;background:white;color:#1e293b;outline:none;" onfocus="this.style.borderColor='#005baa'" onblur="this.style.borderColor='#cbd5e1'">
                <option value="">— Semua Ruangan —</option>
                <?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r->id); ?>" <?php echo e(request('ruangan_id') == $r->id ? 'selected' : ''); ?>><?php echo e($r->nama_ruangan); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">Status</label>
            <select name="status" style="width:100%;padding:9px 12px;border:1.5px solid #cbd5e1;border-radius:9px;font-size:13px;background:white;color:#1e293b;outline:none;" onfocus="this.style.borderColor='#005baa'" onblur="this.style.borderColor='#cbd5e1'">
                <option value="">— Semua Status —</option>
                <option value="Disetujui" <?php echo e(request('status') === 'Disetujui' ? 'selected' : ''); ?>>✅ Disetujui</option>
                <option value="Selesai"   <?php echo e(request('status') === 'Selesai'   ? 'selected' : ''); ?>>🏁 Selesai</option>
                <option value="Pending"   <?php echo e(request('status') === 'Pending'   ? 'selected' : ''); ?>>⏳ Pending</option>
                <option value="Ditolak"   <?php echo e(request('status') === 'Ditolak'   ? 'selected' : ''); ?>>❌ Ditolak</option>
                <option value="Cancel"    <?php echo e(request('status') === 'Cancel'    ? 'selected' : ''); ?>>⬜ Cancel</option>
            </select>
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">Jenis PIC</label>
            <select name="jenis_pic" style="width:100%;padding:9px 12px;border:1.5px solid #cbd5e1;border-radius:9px;font-size:13px;background:white;color:#1e293b;outline:none;" onfocus="this.style.borderColor='#005baa'" onblur="this.style.borderColor='#cbd5e1'">
                <option value="">— Semua Jenis —</option>
                <option value="Organik"     <?php echo e(request('jenis_pic') === 'Organik'     ? 'selected' : ''); ?>>Pegawai Organik</option>
                <option value="Non Organik" <?php echo e(request('jenis_pic') === 'Non Organik' ? 'selected' : ''); ?>>Pegawai Non-Organik</option>
            </select>
        </div>

        <div>
            <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">Unit Kerja</label>
            <select name="user_id" style="width:100%;padding:9px 12px;border:1.5px solid #cbd5e1;border-radius:9px;font-size:13px;background:white;color:#1e293b;outline:none;" onfocus="this.style.borderColor='#005baa'" onblur="this.style.borderColor='#cbd5e1'">
                <option value="">— Semua Unit —</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->nama_unit ?? $u->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="display:flex;gap:8px;">
            <button type="submit"
                    style="flex:1;padding:9px 14px;border-radius:9px;font-size:13px;font-weight:700;background:linear-gradient(135deg,#005baa,#003b73);color:white;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="<?php echo e(route('admin.laporan.index')); ?>"
               title="Reset Filter"
               style="padding:9px 13px;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;background:#f1f5f9;color:#475569;border:1.5px solid #e2e8f0;">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>

    </form>
</div>



<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(190px,1fr));gap:16px;margin-bottom:24px;">

    
    <div style="background:#ffffff;padding:20px;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,0,0,0.04);display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#e0f2fe,#bae6fd);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-file-earmark-text-fill" style="font-size:22px;color:#0284c7;"></i>
        </div>
        <div>
            <div style="font-size:11.5px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px;">Total Pemesanan</div>
            <div style="font-size:22px;font-weight:800;color:#0f172a;line-height:1;"><?php echo e($totalPemesanan); ?></div>
        </div>
    </div>

    
    <div style="background:#ffffff;padding:20px;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,0,0,0.04);display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-check-circle-fill" style="font-size:22px;color:#16a34a;"></i>
        </div>
        <div>
            <div style="font-size:11.5px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px;">Disetujui</div>
            <div style="font-size:22px;font-weight:800;color:#16a34a;line-height:1;"><?php echo e($totalDisetujui); ?></div>
        </div>
    </div>

    
    <div style="background:#ffffff;padding:20px;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,0,0,0.04);display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#fee2e2,#fecaca);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-x-circle-fill" style="font-size:22px;color:#dc2626;"></i>
        </div>
        <div>
            <div style="font-size:11.5px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px;">Ditolak</div>
            <div style="font-size:22px;font-weight:800;color:#dc2626;line-height:1;"><?php echo e($totalDitolak); ?></div>
        </div>
    </div>

    
    <div style="background:#ffffff;padding:20px;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,0,0,0.04);display:flex;align-items:center;gap:16px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-bar-chart-fill" style="font-size:22px;color:#7c3aed;"></i>
        </div>
        <div>
            <div style="font-size:11.5px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px;">Tingkat Persetujuan</div>
            <?php $rate = $totalPemesanan > 0 ? round(($totalDisetujui / $totalPemesanan) * 100) : 0; ?>
            <div style="font-size:22px;font-weight:800;color:#7c3aed;line-height:1;"><?php echo e($rate); ?>%</div>
            <div style="margin-top:6px;height:4px;background:#ede9fe;border-radius:9px;overflow:hidden;">
                <div style="height:100%;width:<?php echo e($rate); ?>%;background:linear-gradient(90deg,#7c3aed,#a78bfa);border-radius:9px;"></div>
            </div>
        </div>
    </div>

</div>



<div style="background:#ffffff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,0,0,0.04);overflow:hidden;">

    
    <div style="padding:16px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <i class="bi bi-table" style="color:#005baa;font-size:15px;"></i>
            <span style="font-weight:700;font-size:13.5px;color:#003b73;">Data Rekap Pemesanan</span>
            <span style="background:#eff6ff;color:#005baa;border:1px solid #bfdbfe;padding:2px 8px;border-radius:20px;font-size:11.5px;font-weight:700;">
                <?php echo e($pemesanan->total()); ?> Record
            </span>
        </div>
        <div style="font-size:12.5px;color:#64748b;">
            Halaman <?php echo e($pemesanan->currentPage()); ?> dari <?php echo e($pemesanan->lastPage()); ?>

        </div>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:900px;">
            <thead>
                <tr style="background:linear-gradient(135deg,#003b73,#005baa);">
                    <th style="padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;white-space:nowrap;width:40px;">#</th>
                    <th style="padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;white-space:nowrap;">Kode / Tanggal</th>
                    <th style="padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;white-space:nowrap;">Ruangan</th>
                    <th style="padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;">Judul Kegiatan</th>
                    <th style="padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;white-space:nowrap;">Unit / PIC</th>
                    <th style="padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;white-space:nowrap;">Waktu</th>
                    <th style="padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;white-space:nowrap;">Status</th>
                    <th style="padding:13px 16px;text-align:center;font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.6px;white-space:nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pemesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $val = is_object($item->status) ? $item->status->value : $item->status; ?>
                <tr style="border-bottom:1px solid #f1f5f9;transition:background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">

                    <td style="padding:14px 16px;color:#94a3b8;font-weight:600;font-size:12.5px;">
                        <?php echo e($pemesanan->firstItem() + $index); ?>

                    </td>

                    <td style="padding:14px 16px;">
                        <code style="background:#f0f9ff;color:#005baa;padding:3px 7px;border-radius:5px;font-size:11.5px;font-weight:700;font-family:Consolas,monospace;"><?php echo e($item->kode_pemesanan); ?></code>
                        <div style="margin-top:5px;display:flex;align-items:center;gap:4px;color:#64748b;font-size:12px;">
                            <i class="bi bi-calendar3"></i>
                            <?php echo e($item->tanggal_kegiatan ? $item->tanggal_kegiatan->translatedFormat('d M Y') : '-'); ?>

                        </div>
                    </td>

                    <td style="padding:14px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;background:linear-gradient(135deg,#e0f2fe,#bae6fd);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-door-open-fill" style="color:#0284c7;font-size:13px;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;color:#1e293b;font-size:13px;"><?php echo e($item->ruangan->nama_ruangan ?? '-'); ?></div>
                                <?php if($item->layout): ?>
                                    <div style="font-size:11px;color:#64748b;margin-top:1px;"><i class="bi bi-grid-3x3-gap"></i> <?php echo e($item->layout->nama_layout); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>

                    <td style="padding:14px 16px;max-width:240px;">
                        <div style="font-weight:700;color:#0f172a;font-size:13px;white-space:normal;line-height:1.35;"><?php echo e($item->judul_kegiatan); ?></div>
                    </td>

                    <td style="padding:14px 16px;">
                        <div style="font-weight:700;color:#005baa;font-size:13px;"><?php echo e($item->user->nama_unit ?? $item->user->name); ?></div>
                        <div style="display:flex;align-items:center;gap:4px;color:#64748b;font-size:12px;margin-top:2px;">
                            <i class="bi bi-person-fill"></i> <?php echo e($item->pic_kegiatan); ?>

                        </div>
                        <?php if($item->jenis_pic): ?>
                            <span style="display:inline-block;margin-top:4px;font-size:10.5px;padding:2px 7px;border-radius:4px;
                                <?php echo e($item->jenis_pic === 'Organik' ? 'background:#dbeafe;color:#1d4ed8;' : 'background:#f3e8ff;color:#6d28d9;'); ?>

                                font-weight:600;">
                                <?php echo e($item->jenis_pic); ?>

                            </span>
                        <?php endif; ?>
                    </td>

                    <td style="padding:14px 16px;white-space:nowrap;">
                        <div style="display:flex;align-items:center;gap:5px;font-weight:600;color:#1e293b;font-size:13px;">
                            <i class="bi bi-clock-fill" style="color:#0284c7;"></i>
                            <?php echo e($item->waktu_mulai); ?>

                        </div>
                        <div style="margin-top:2px;font-size:12px;color:#64748b;padding-left:18px;">
                            s/d <?php echo e($item->waktu_selesai); ?> WITA
                        </div>
                    </td>

                    <td style="padding:14px 16px;">
                        <?php if($val === 'Selesai' || $item->is_finished): ?>
                            <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:8px;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;font-size:11.5px;font-weight:700;white-space:nowrap;">
                                <i class="bi bi-check2-all"></i> Selesai
                            </span>
                        <?php elseif($val === 'Disetujui'): ?>
                            <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:8px;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;font-size:11.5px;font-weight:700;white-space:nowrap;">
                                <i class="bi bi-check-circle-fill"></i> Disetujui
                            </span>
                        <?php elseif($val === 'Pending'): ?>
                            <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:8px;background:#fffbeb;color:#92400e;border:1px solid #fde68a;font-size:11.5px;font-weight:700;white-space:nowrap;">
                                <i class="bi bi-clock-fill"></i> Pending
                            </span>
                        <?php elseif($val === 'Ditolak'): ?>
                            <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:8px;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;font-size:11.5px;font-weight:700;white-space:nowrap;">
                                <i class="bi bi-x-circle-fill"></i> Ditolak
                            </span>
                        <?php else: ?>
                            <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:8px;background:#f8fafc;color:#475569;border:1px solid #cbd5e1;font-size:11.5px;font-weight:700;white-space:nowrap;">
                                <i class="bi bi-dash-circle"></i> Cancel
                            </span>
                        <?php endif; ?>
                    </td>

                    <td style="padding:14px 16px;text-align:center;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                            <a href="<?php echo e(route('admin.approval.show', $item)); ?>"
                               title="Lihat Detail"
                               style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#eff6ff;color:#005baa;border:1px solid #bfdbfe;border-radius:8px;font-size:14px;text-decoration:none;transition:all .2s;"
                               onmouseover="this.style.background='#005baa';this.style.color='white'"
                               onmouseout="this.style.background='#eff6ff';this.style.color='#005baa'">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.approval.destroy', $item)); ?>"
                                  onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Pemesanan', message: 'Hapus data <strong><?php echo e($item->kode_pemesanan); ?></strong> secara permanen?', type: 'danger', confirmText: 'Ya, Hapus Data' });">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" title="Hapus"
                                        style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:8px;font-size:14px;cursor:pointer;transition:all .2s;"
                                        onmouseover="this.style.background='#dc2626';this.style.color='white'"
                                        onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="padding:56px 20px;text-align:center;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                            <div style="width:64px;height:64px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-search" style="font-size:26px;color:#94a3b8;"></i>
                            </div>
                            <div style="font-weight:700;color:#475569;font-size:14px;">Tidak ada data ditemukan</div>
                            <div style="color:#94a3b8;font-size:13px;">Coba ubah filter pencarian Anda.</div>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($pemesanan->hasPages()): ?>
    <div style="padding:16px 22px;border-top:1px solid #f1f5f9;background:#fafbfc;">
        <?php echo e($pemesanan->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/laporan/index.blade.php ENDPATH**/ ?>