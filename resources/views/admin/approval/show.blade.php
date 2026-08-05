<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-file-earmark-check" style="color:#005baa;margin-right:8px;"></i>Detail Pemesanan</h1>
        <p>Review informasi pengajuan penggunaan ruangan sebelum persetujuan.</p>
    </div>
    <a href="{{ route('admin.approval.index') }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="detail-container">

    {{-- Info Pemohon --}}
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-person-circle"></i>
            Informasi Pemohon
        </div>
        <div class="detail-grid">
            <div>
                <label>Nama Pemohon</label>
                <p>{{ $pemesanan->user->name }}</p>
            </div>
            <div>
                <label>Unit Kerja</label>
                <p>{{ $pemesanan->user->nama_unit ?? '-' }}</p>
            </div>
            <div>
                <label>Kode Pemesanan</label>
                <p style="font-family:monospace;font-weight:700;color:#005baa;">{{ $pemesanan->kode_pemesanan }}</p>
            </div>
            <div>
                <label>Status</label>
                <p><span class="badge badge-warning"><i class="bi bi-clock-history"></i> {{ $pemesanan->status->label() }}</span></p>
            </div>
        </div>
    </div>

    {{-- Info Kegiatan --}}
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-calendar-event"></i>
            Informasi Kegiatan
        </div>
        <div class="detail-grid">
            <div>
                <label>Judul Kegiatan</label>
                <p>{{ $pemesanan->judul_kegiatan }}</p>
            </div>
            <div>
                <label>PIC Kegiatan</label>
                <p>{{ $pemesanan->pic_kegiatan }} {{ $pemesanan->no_wa_pic ? '('.$pemesanan->no_wa_pic.')' : '' }}</p>
            </div>
            <div>
                <label>Jenis PIC</label>
                <p>{{ $pemesanan->jenis_pic }}</p>
            </div>
            <div>
                <label>Jumlah Tamu</label>
                <p>{{ $pemesanan->jumlah_tamu }} orang</p>
            </div>
            <div>
                <label>Tanggal Kegiatan</label>
                <p>{{ $pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div>
                <label>Waktu</label>
                <p>{{ $pemesanan->waktu_mulai }} – {{ $pemesanan->waktu_selesai }} WITA</p>
            </div>
        </div>
    </div>

    {{-- Info Ruangan --}}
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-building"></i>
            Informasi Ruangan
        </div>
        <div class="detail-grid">
            <div>
                <label>Ruangan</label>
                <p style="color:#005baa;font-weight:700;">{{ $pemesanan->ruangan->nama_ruangan }}</p>
            </div>
            <div>
                <label>Layout</label>
                <p>{{ $pemesanan->layout?->nama_layout ?? '-' }}</p>
            </div>
        </div>

        @if($pemesanan->keterangan_layout)
        <div class="layout-note">
            <label>Keterangan Layout</label>
            <p>{{ $pemesanan->keterangan_layout }}</p>
        </div>
        @endif
    </div>

    {{-- Lembar Disposisi --}}
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-file-earmark-text"></i>
            Lembar Disposisi
        </div>
        <div style="padding:16px 22px;">
            @if($pemesanan->file_disposisi)
            <div style="display:flex;align-items:center;justify-content:space-between;background:#f0f9ff;padding:16px;border-radius:10px;border:1px solid #bae6fd;flex-wrap:wrap;gap:10px;">
                <div>
                    <strong style="color:#003b73;font-size:14px;display:flex;align-items:center;gap:7px;">
                        <i class="bi bi-file-earmark-pdf" style="color:#005baa;"></i>
                        File Disposisi Terlampir
                    </strong>
                    <small style="color:#64748b;">Pengguna telah mengunggah berkas lembar disposisi.</small>
                </div>
                <a href="{{ asset('storage/' . $pemesanan->file_disposisi) }}" target="_blank" class="btn-primary btn-sm">
                    <i class="bi bi-download"></i> Buka / Unduh
                </a>
            </div>
            @else
            <div style="background:#fffbeb;border:1px solid #fde68a;padding:14px 16px;border-radius:10px;color:#b45309;font-size:13.5px;display:flex;align-items:center;gap:9px;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Pengguna tidak mengunggah berkas lembar disposisi.
            </div>
            @endif
        </div>
    </div>

</div>


{{-- Action Bar --}}
<div class="dashboard-section" style="margin-top:0;">
    <div style="padding:20px 24px;">
        <div class="approval-action" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <a href="{{ route('admin.approval.index') }}" class="btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div style="display:flex;align-items:center;gap:12px;">
                <button type="button" class="btn-danger" id="btn-trigger-reject" style="padding:10px 24px;font-weight:600;display:inline-flex;align-items:center;gap:8px;">
                    <i class="bi bi-x-circle-fill"></i> Tolak Pemesanan
                </button>

                <button type="button" class="btn-success" id="btn-trigger-approve" style="padding:10px 24px;font-weight:600;display:inline-flex;align-items:center;gap:8px;background:#059669;color:#fff;border:none;border-radius:10px;cursor:pointer;">
                    <i class="bi bi-check-circle-fill"></i> Setujui Pemesanan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL REJECT (TOLAK) --}}
<div id="custom-modal-reject" class="custom-modal-overlay" style="display:none;">
    <div class="custom-modal-box">
        <div class="custom-modal-header" style="border-bottom:1px solid #fee2e2;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:#fff5f5;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#fef2f2;border:1px solid #fecdd3;display:flex;align-items:center;justify-content:center;color:#e11d48;font-size:20px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h3 style="margin:0;font-size:17px;font-weight:700;color:#991b1b;">Konfirmasi Penolakan Pemesanan</h3>
                    <p style="margin:2px 0 0;font-size:12.5px;color:#9f1239;">Kode: <strong style="font-family:monospace;">{{ $pemesanan->kode_pemesanan }}</strong> &bull; PIC: <strong>{{ $pemesanan->pic_kegiatan }}</strong></p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeRejectModal()">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.approval.reject', $pemesanan) }}" id="form-confirm-reject">
            @csrf
            <div class="custom-modal-body" style="padding:24px;">
                <div style="background:#fef2f2;border:1px solid #fecdd3;padding:12px 16px;border-radius:10px;color:#9f1239;font-size:13px;margin-bottom:18px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bi bi-info-circle-fill" style="margin-top:2px;font-size:16px;"></i>
                    <div>
                        Pemberitahuan penolakan beserta alasan di bawah ini akan <strong>langsung dikirimkan secara otomatis via WhatsApp</strong> ke nomor PIC <strong>{{ $pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? 'Pemohon') }}</strong>.
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

{{-- MODAL APPROVE (SETUJUI) --}}
<div id="custom-modal-approve" class="custom-modal-overlay" style="display:none;">
    <div class="custom-modal-box">
        <div class="custom-modal-header" style="border-bottom:1px solid #d1fae5;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:#ecfdf5;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#d1fae5;border:1px solid #a7f3d0;display:flex;align-items:center;justify-content:center;color:#059669;font-size:20px;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <h3 style="margin:0;font-size:17px;font-weight:700;color:#065f46;">Konfirmasi Persetujuan Pemesanan</h3>
                    <p style="margin:2px 0 0;font-size:12.5px;color:#047857;">Kode: <strong style="font-family:monospace;">{{ $pemesanan->kode_pemesanan }}</strong> &bull; PIC: <strong>{{ $pemesanan->pic_kegiatan }}</strong></p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeApproveModal()">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.approval.approve', $pemesanan) }}" id="form-confirm-approve">
            @csrf
            <div class="custom-modal-body" style="padding:24px;">
                <div style="background:#ecfdf5;border:1px solid #a7f3d0;padding:14px 16px;border-radius:10px;color:#047857;font-size:13.5px;margin-bottom:18px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bi bi-whatsapp" style="margin-top:2px;font-size:18px;color:#25d366;"></i>
                    <div>
                        Pesan notifikasi persetujuan resmi beserta jadwal ruangan akan <strong>otomatis dikirimkan via WhatsApp</strong> ke nomor PIC <strong>{{ $pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? 'Pemohon') }}</strong>.
                    </div>
                </div>

                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;font-size:13px;color:#334155;margin-bottom:18px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Ruangan</span>
                            <strong style="color:#005baa;">{{ $pemesanan->ruangan->nama_ruangan }}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Layout</span>
                            <strong>{{ $pemesanan->layout?->nama_layout ?? '-' }}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Tanggal Kegiatan</span>
                            <strong>{{ $pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY') }}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Waktu</span>
                            <strong>{{ $pemesanan->waktu_mulai }} – {{ $pemesanan->waktu_selesai }} WITA</strong>
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

</x-app-layout>