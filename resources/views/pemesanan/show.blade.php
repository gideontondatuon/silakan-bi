<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-file-earmark-text" style="color:#005baa;margin-right:8px;"></i>Detail Pemesanan</h1>
        <p>Informasi lengkap pengajuan penggunaan ruangan.</p>
    </div>
    <a href="{{ route('pemesanan.index') }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="detail-container">

    {{-- Info Utama --}}
    <div class="detail-card">
        <div class="detail-title">
            <i class="bi bi-info-circle"></i>
            Informasi Umum
        </div>
        <div class="detail-grid">
            <div>
                <label>Kode Pemesanan</label>
                <p style="font-family:monospace;font-weight:700;color:#005baa;">{{ $pemesanan->kode_pemesanan }}</p>
            </div>
            <div>
                <label>Status</label>
                <p>
                    @if($pemesanan->status->value == 'Pending')
                        <span class="badge badge-warning"><i class="bi bi-clock"></i> Menunggu Approval</span>
                    @elseif($pemesanan->status->value == 'Disetujui')
                        <span class="badge badge-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                    @elseif($pemesanan->status->value == 'Ditolak')
                        <span class="badge badge-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                    @else
                        <span class="badge badge-secondary"><i class="bi bi-dash-circle"></i> Dibatalkan</span>
                    @endif
                </p>
            </div>
            <div>
                <label>Judul Kegiatan</label>
                <p>{{ $pemesanan->judul_kegiatan }}</p>
            </div>
            <div>
                <label>Ruangan</label>
                <p style="color:#005baa;font-weight:700;">{{ $pemesanan->ruangan->nama_ruangan }}</p>
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

    {{-- Catatan Admin --}}
    @if($pemesanan->catatan_admin)
    <div class="detail-card">
        <div class="detail-title" style="color:#047857;"><i class="bi bi-chat-left-text-fill"></i> Catatan Admin</div>
        <div style="padding:16px 22px;background:#ecfdf5;border-radius:10px;border:1px solid #a7f3d0;color:#047857;">
            <p style="font-size:13.5px;font-weight:600;margin:0;">{{ $pemesanan->catatan_admin }}</p>
        </div>
    </div>
    @endif

    {{-- Alasan Penolakan --}}
    @if($pemesanan->alasan_penolakan)
    <div class="detail-card">
        <div class="detail-title" style="color:#be123c;"><i class="bi bi-exclamation-octagon-fill"></i> Alasan Penolakan</div>
        <div style="padding:16px 22px;background:#fff1f2;border-radius:10px;border:1px solid #fecdd3;color:#be123c;">
            <p style="font-size:13.5px;font-weight:600;margin:0;">{{ $pemesanan->alasan_penolakan }}</p>
        </div>
    </div>
    @endif

    {{-- Catatan User --}}
    @if($pemesanan->catatan_user)
    <div class="detail-card">
        <div class="detail-title"><i class="bi bi-sticky"></i> Catatan Pemohon</div>
        <div style="padding:16px 22px;">
            <p style="color:#334155;font-size:13.5px;">{{ $pemesanan->catatan_user }}</p>
        </div>
    </div>
    @endif

    {{-- Lembar Disposisi --}}
    @if($pemesanan->file_disposisi)
    <div class="detail-card">
        <div class="detail-title"><i class="bi bi-file-earmark-text"></i> Lembar Disposisi</div>
        <div style="padding:16px 22px;">
            <a href="{{ asset('storage/' . $pemesanan->file_disposisi) }}" target="_blank" class="btn-primary">
                <i class="bi bi-file-earmark-pdf"></i> Lihat / Unduh Lembar Disposisi
            </a>
        </div>
    </div>
    @endif

</div>

</x-app-layout>