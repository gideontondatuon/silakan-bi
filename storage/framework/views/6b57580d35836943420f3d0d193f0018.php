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
        <h1><i class="bi bi-file-earmark-check" style="color:#005baa;margin-right:8px;"></i>Detail Pemesanan</h1>
        <p>Review informasi pengajuan penggunaan ruangan sebelum persetujuan.</p>
    </div>
    <a href="<?php echo e(route('admin.approval.index')); ?>" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="detail-container">

    
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-person-circle"></i>
            Informasi Pemohon
        </div>
        <div class="detail-grid">
            <div>
                <label>Nama Pemohon</label>
                <p><?php echo e($pemesanan->user->name); ?></p>
            </div>
            <div>
                <label>Unit Kerja</label>
                <p><?php echo e($pemesanan->user->nama_unit ?? '-'); ?></p>
            </div>
            <div>
                <label>Kode Pemesanan</label>
                <p style="font-family:monospace;font-weight:700;color:#005baa;"><?php echo e($pemesanan->kode_pemesanan); ?></p>
            </div>
            <div>
                <label>Status</label>
                <p>
                    <?php if($pemesanan->status->value === 'Selesai' || $pemesanan->is_finished): ?>
                        <span class="badge" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;font-weight:700;"><i class="bi bi-check2-all"></i> Selesai</span>
                    <?php elseif($pemesanan->status->value === 'Disetujui'): ?>
                        <span class="badge badge-success"><i class="bi bi-check-circle-fill"></i> Disetujui</span>
                    <?php elseif($pemesanan->status->value === 'Pending'): ?>
                        <span class="badge badge-warning"><i class="bi bi-clock-history"></i> Pending</span>
                    <?php elseif($pemesanan->status->value === 'Ditolak'): ?>
                        <span class="badge badge-danger"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                    <?php else: ?>
                        <span class="badge badge-secondary"><i class="bi bi-dash-circle"></i> Dibatalkan</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-calendar-event"></i>
            Informasi Kegiatan
        </div>
        <div class="detail-grid">
            <div>
                <label>Judul Kegiatan</label>
                <p><?php echo e($pemesanan->judul_kegiatan); ?></p>
            </div>
            <div>
                <label>PIC Kegiatan</label>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:3px;">
                    <strong style="color:#0f172a;font-size:14px;"><?php echo e($pemesanan->pic_kegiatan); ?></strong>
                    <?php if($pemesanan->no_wa_pic): ?>
                        <?php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $pemesanan->no_wa_pic);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $waMsg = urlencode("Halo Bapak/Ibu {$pemesanan->pic_kegiatan}, kami dari Tim Pengelola Ruangan KPwBI Prov. Sulut mengonfirmasi terkait pengajuan pemesanan ruangan {$pemesanan->ruangan->nama_ruangan} untuk agenda \"{$pemesanan->judul_kegiatan}\" (Kode: {$pemesanan->kode_pemesanan}).");
                        ?>
                        <a href="https://wa.me/<?php echo e($cleanPhone); ?>?text=<?php echo e($waMsg); ?>" target="_blank" class="btn-sm" style="background:#16a34a;color:#fff;text-decoration:none;border-radius:8px;padding:4px 10px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:5px;box-shadow:0 2px 6px rgba(22,163,74,0.25);">
                            <i class="bi bi-whatsapp"></i> Chat WA PIC (<?php echo e($pemesanan->no_wa_pic); ?>)
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <label>Jenis PIC</label>
                <p><?php echo e($pemesanan->jenis_pic); ?></p>
            </div>
            <div>
                <label>Jumlah Tamu</label>
                <p><?php echo e($pemesanan->jumlah_tamu); ?> orang</p>
            </div>
            <div>
                <label>Tanggal Kegiatan</label>
                <p><?php echo e($pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY')); ?></p>
            </div>
            <div>
                <label>Waktu</label>
                <p><?php echo e($pemesanan->waktu_mulai); ?> – <?php echo e($pemesanan->waktu_selesai); ?> WITA</p>
            </div>
        </div>
    </div>

    
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-building"></i>
            Informasi Ruangan
        </div>
        <div class="detail-grid">
            <div>
                <label>Ruangan</label>
                <p style="color:#005baa;font-weight:700;"><?php echo e($pemesanan->ruangan->nama_ruangan); ?></p>
            </div>
            <div>
                <label>Layout</label>
                <p><?php echo e($pemesanan->layout?->nama_layout ?? '-'); ?></p>
            </div>
        </div>

        <?php if($pemesanan->keterangan_layout): ?>
        <div class="layout-note">
            <label>Keterangan Layout</label>
            <p><?php echo e($pemesanan->keterangan_layout); ?></p>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-file-earmark-text"></i>
            Lembar Disposisi
        </div>
        <div style="padding:16px 22px;">
            <?php if($pemesanan->file_disposisi): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;background:#f0f9ff;padding:16px;border-radius:10px;border:1px solid #bae6fd;flex-wrap:wrap;gap:10px;">
                <div>
                    <strong style="color:#003b73;font-size:14px;display:flex;align-items:center;gap:7px;">
                        <i class="bi bi-file-earmark-pdf" style="color:#005baa;"></i>
                        File Disposisi Terlampir
                    </strong>
                    <small style="color:#64748b;">Pengguna telah mengunggah berkas lembar disposisi.</small>
                </div>
                <a href="<?php echo e(asset('storage/' . $pemesanan->file_disposisi)); ?>" target="_blank" class="btn-primary btn-sm">
                    <i class="bi bi-download"></i> Buka / Unduh
                </a>
            </div>
            <?php else: ?>
            <div style="background:#fffbeb;border:1px solid #fde68a;padding:14px 16px;border-radius:10px;color:#b45309;font-size:13.5px;display:flex;align-items:center;gap:9px;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Pengguna tidak mengunggah berkas lembar disposisi.
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>



<?php
    $statusVal = is_object($pemesanan->status) ? $pemesanan->status->value : $pemesanan->status;
    $isLiveToday = $pemesanan->canBeFinishedEarly();
?>

<div class="dashboard-section" style="margin-top:0;">
    <div style="padding:20px 24px;">
        <div class="approval-action" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <a href="<?php echo e(route('admin.approval.index')); ?>" class="btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>

            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                
                <?php if($isLiveToday): ?>
                <form action="<?php echo e(route('admin.approval.selesai-awal', $pemesanan)); ?>" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Selesaikan Rapat Lebih Awal', message: 'Apakah rapat di ruangan <strong><?php echo e($pemesanan->ruangan->nama_ruangan); ?></strong> telah selesai lebih cepat dan siap dibebaskan?', type: 'primary', confirmText: 'Ya, Selesaikan Sekarang' });">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-sm" style="padding:10px 20px;font-weight:700;display:inline-flex;align-items:center;gap:8px;background:#059669;color:#fff;border:none;border-radius:10px;cursor:pointer;font-size:13.5px;">
                        <i class="bi bi-check2-circle"></i> Selesaikan Rapat Sekarang
                    </button>
                </form>
                <?php endif; ?>

                
                <form method="POST" action="<?php echo e(route('admin.approval.destroy', $pemesanan)); ?>" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Pemesanan Ruangan', message: 'Apakah Anda yakin ingin menghapus data pemesanan <strong><?php echo e($pemesanan->kode_pemesanan); ?></strong> (<?php echo e($pemesanan->judul_kegiatan); ?>) secara permanen dari sistem? Jadwal ruangan akan dibebaskan seketika.', type: 'danger', confirmText: 'Ya, Hapus Pemesanan' });">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn-secondary" style="padding:10px 20px;font-weight:700;display:inline-flex;align-items:center;gap:8px;background:#fee2e2;color:#dc2626;border:1px solid #fecdd3;border-radius:10px;cursor:pointer;font-size:13.5px;" title="Hapus pemesanan secara permanen">
                        <i class="bi bi-trash-fill"></i> Hapus Pemesanan
                    </button>
                </form>

                <?php if($statusVal === 'Pending'): ?>
                    <button type="button" class="btn-danger" id="btn-trigger-reject" style="padding:10px 24px;font-weight:700;display:inline-flex;align-items:center;gap:8px;border-radius:10px;font-size:13.5px;">
                        <i class="bi bi-x-circle-fill"></i> Tolak Pemesanan
                    </button>

                    <button type="button" class="btn-success" id="btn-trigger-approve" style="padding:10px 24px;font-weight:700;display:inline-flex;align-items:center;gap:8px;background:#059669;color:#fff;border:none;border-radius:10px;cursor:pointer;font-size:13.5px;">
                        <i class="bi bi-check-circle-fill"></i> Setujui Pemesanan
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div id="custom-modal-reject" class="custom-modal-overlay" style="display:none;">
    <div class="custom-modal-box">
        <div class="custom-modal-header" style="border-bottom:1px solid #fee2e2;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:#fff5f5;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#fef2f2;border:1px solid #fecdd3;display:flex;align-items:center;justify-content:center;color:#e11d48;font-size:20px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h3 style="margin:0;font-size:17px;font-weight:700;color:#991b1b;">Konfirmasi Penolakan Pemesanan</h3>
                    <p style="margin:2px 0 0;font-size:12.5px;color:#9f1239;">Kode: <strong style="font-family:monospace;"><?php echo e($pemesanan->kode_pemesanan); ?></strong> &bull; PIC: <strong><?php echo e($pemesanan->pic_kegiatan); ?></strong></p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeRejectModal()">&times;</button>
        </div>

        <form method="POST" action="<?php echo e(route('admin.approval.reject', $pemesanan)); ?>" id="form-confirm-reject">
            <?php echo csrf_field(); ?>
            <div class="custom-modal-body" style="padding:24px;">
                <div style="background:#fef2f2;border:1px solid #fecdd3;padding:12px 16px;border-radius:10px;color:#9f1239;font-size:13px;margin-bottom:18px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bi bi-info-circle-fill" style="margin-top:2px;font-size:16px;"></i>
                    <div>
                        Pemberitahuan penolakan beserta alasan di bawah ini akan <strong>langsung dikirimkan secara otomatis via WhatsApp</strong> ke nomor PIC <strong><?php echo e($pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? 'Pemohon')); ?></strong>.
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:block;font-weight:700;font-size:13.5px;color:#334155;margin-bottom:8px;" class="required">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" rows="4" style="width:100%;padding:12px 14px;border:1px solid #cbd5e1;border-radius:10px;font-family:inherit;font-size:13.5px;outline:none;transition:all .2s;resize:vertical;" placeholder="Tuliskan alasan penolakan secara jelas (misal: Ruangan digunakan untuk rapat internal pimpinan mendadak)..." required></textarea>
                    <span style="display:block;font-size:11.5px;color:#64748b;margin-top:6px;">Berikan alasan yang informatif agar pemohon dapat memilih jadwal/ruangan lain.</span>
                </div>
            </div>

            <div class="custom-modal-footer" style="padding:16px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:flex-end;gap:12px;border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
                <button type="button" class="btn-secondary" onclick="closeRejectModal()" style="padding:9px 18px;">
                    <i class="bi bi-x"></i> Batal
                </button>
                <button type="submit" class="btn-danger" style="padding:9px 20px;font-weight:600;display:inline-flex;align-items:center;gap:6px;background:#dc2626;color:#fff;border:none;border-radius:10px;cursor:pointer;">
                    <i class="bi bi-send-check-fill"></i> Kirim &amp; Tolak Pemesanan
                </button>
            </div>
        </form>
    </div>
</div>


<div id="custom-modal-approve" class="custom-modal-overlay" style="display:none;">
    <div class="custom-modal-box">
        <div class="custom-modal-header" style="border-bottom:1px solid #d1fae5;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:#ecfdf5;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#d1fae5;border:1px solid #a7f3d0;display:flex;align-items:center;justify-content:center;color:#059669;font-size:20px;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <h3 style="margin:0;font-size:17px;font-weight:700;color:#065f46;">Konfirmasi Persetujuan Pemesanan</h3>
                    <p style="margin:2px 0 0;font-size:12.5px;color:#047857;">Kode: <strong style="font-family:monospace;"><?php echo e($pemesanan->kode_pemesanan); ?></strong> &bull; PIC: <strong><?php echo e($pemesanan->pic_kegiatan); ?></strong></p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeApproveModal()">&times;</button>
        </div>

        <form method="POST" action="<?php echo e(route('admin.approval.approve', $pemesanan)); ?>" id="form-confirm-approve">
            <?php echo csrf_field(); ?>
            <div class="custom-modal-body" style="padding:24px;">
                <div style="background:#ecfdf5;border:1px solid #a7f3d0;padding:14px 16px;border-radius:10px;color:#047857;font-size:13.5px;margin-bottom:18px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bi bi-whatsapp" style="margin-top:2px;font-size:18px;color:#25d366;"></i>
                    <div>
                        Pesan notifikasi persetujuan resmi beserta jadwal ruangan akan <strong>otomatis dikirimkan via WhatsApp</strong> ke nomor PIC <strong><?php echo e($pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? 'Pemohon')); ?></strong>.
                    </div>
                </div>

                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;font-size:13px;color:#334155;margin-bottom:18px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Ruangan</span>
                            <strong style="color:#005baa;"><?php echo e($pemesanan->ruangan->nama_ruangan); ?></strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Layout</span>
                            <strong><?php echo e($pemesanan->layout?->nama_layout ?? '-'); ?></strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Tanggal Kegiatan</span>
                            <strong><?php echo e($pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY')); ?></strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Waktu</span>
                            <strong><?php echo e($pemesanan->waktu_mulai); ?> – <?php echo e($pemesanan->waktu_selesai); ?> WITA</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label style="display:block;font-weight:700;font-size:13.5px;color:#334155;margin-bottom:8px;">Catatan Tambahan Admin <small style="color:#64748b;font-weight:400;">(Opsional)</small></label>
                    <textarea name="catatan_admin" rows="3" style="width:100%;padding:12px 14px;border:1px solid #cbd5e1;border-radius:10px;font-family:inherit;font-size:13.5px;outline:none;transition:all .2s;resize:vertical;" placeholder="Tambahkan catatan khusus untuk pemohon jika ada (misal: Harap mengonfirmasi teknisi sound system H-1)..."></textarea>
                    <span style="display:block;font-size:11.5px;color:#64748b;margin-top:6px;">Catatan ini juga akan dikirimkan pada pesan WhatsApp ke PIC.</span>
                </div>
            </div>

            <div class="custom-modal-footer" style="padding:16px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:flex-end;gap:12px;border-bottom-left-radius:16px;border-bottom-right-radius:16px;">
                <button type="button" class="btn-secondary" onclick="closeApproveModal()" style="padding:9px 18px;">
                    <i class="bi bi-x"></i> Batal
                </button>
                <button type="submit" class="btn-success" style="padding:9px 22px;font-weight:600;display:inline-flex;align-items:center;gap:6px;background:#059669;color:#fff;border:none;border-radius:10px;cursor:pointer;">
                    <i class="bi bi-check-circle-fill"></i> Ya, Setujui Pemesanan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.custom-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeInModal 0.25s ease-out forwards;
}

.custom-modal-box {
    background: #ffffff;
    width: 100%;
    max-width: 540px;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    animation: scaleInModal 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.modal-close-btn {
    background: transparent;
    border: none;
    font-size: 24px;
    color: #64748b;
    cursor: pointer;
    line-height: 1;
    padding: 4px 8px;
    border-radius: 6px;
    transition: all 0.2s;
}

.modal-close-btn:hover {
    background: rgba(0, 0, 0, 0.06);
    color: #0f172a;
}

@keyframes fadeInModal {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleInModal {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}
</style>

<script>
const rejectModal = document.getElementById('custom-modal-reject');
const approveModal = document.getElementById('custom-modal-approve');

function openRejectModal() {
    if (rejectModal) rejectModal.style.display = 'flex';
}

function closeRejectModal() {
    if (rejectModal) rejectModal.style.display = 'none';
}

function openApproveModal() {
    if (approveModal) approveModal.style.display = 'flex';
}

function closeApproveModal() {
    if (approveModal) approveModal.style.display = 'none';
}

document.getElementById('btn-trigger-reject')?.addEventListener('click', openRejectModal);
document.getElementById('btn-trigger-approve')?.addEventListener('click', openApproveModal);

window.addEventListener('click', function(e) {
    if (e.target === rejectModal) closeRejectModal();
    if (e.target === approveModal) closeApproveModal();
});

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
        closeApproveModal();
    }
});
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
<?php endif; ?><?php /**PATH D:\Bank Indo\silakan\resources\views/admin/approval/show.blade.php ENDPATH**/ ?>