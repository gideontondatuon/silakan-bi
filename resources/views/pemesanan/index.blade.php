<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-journal-text" style="color:#005baa;margin-right:8px;"></i>Pemesanan Saya</h1>
        <p>Daftar seluruh pengajuan penggunaan ruangan Anda.</p>
    </div>
    <a href="{{ route('pemesanan.create') }}" class="btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Pemesanan
    </a>
</div>


@if(session('success'))
<div class="alert alert-success">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle-fill"></i>
    {{ session('error') }}
</div>
@endif


<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Kegiatan</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemesanan as $item)
                <tr>
                    <td><span style="font-family:monospace;font-size:11.5px;color:#005baa;font-weight:700;">{{ $item->kode_pemesanan }}</span></td>
                    <td><strong style="color:#003b73;">{{ $item->judul_kegiatan }}</strong></td>
                    <td>
                        <strong>{{ $item->ruangan->nama_ruangan }}</strong><br>
                        <small style="color:#64748b;">{{ $item->layout?->nama_layout ?? '-' }}</small>
                    </td>
                    <td><strong>{{ $item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY') }}</strong></td>
                    <td style="color:#64748b;white-space:nowrap;">{{ $item->waktu_mulai }} – {{ $item->waktu_selesai }}</td>
                    <td>
                        @if($item->status->value == 'Pending')
                            <span class="badge badge-warning"><i class="bi bi-clock"></i> Pending</span>
                        @elseif($item->status->value == 'Disetujui')
                            <span class="badge badge-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                        @elseif($item->status->value == 'Ditolak')
                            <span class="badge badge-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                        @else
                            <span class="badge badge-secondary"><i class="bi bi-dash-circle"></i> Cancel</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('pemesanan.show', $item) }}" class="btn-info btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            @if(in_array($item->status->value, ['Disetujui', 'Pending']) && $item->reschedule_status !== 'Pending')
                                <button type="button" class="btn-warning btn-sm" onclick="openRescheduleModal({{ $item->id }}, '{{ $item->kode_pemesanan }}', '{{ $item->tanggal_kegiatan->format('Y-m-d') }}', '{{ $item->waktu_mulai }}', '{{ $item->waktu_selesai }}')" style="background:#f59e0b;color:white;border:none;padding:5px 10px;border-radius:6px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:4px;cursor:pointer;" title="Ajukan Reschedule">
                                    <i class="bi bi-calendar-event"></i> Reschedule
                                </button>
                            @elseif($item->reschedule_status === 'Pending')
                                <span class="badge badge-warning" style="font-size:11px;padding:4px 8px;" title="Reschedule Menunggu Approval Admin"><i class="bi bi-clock-history"></i> Reschedule Pending</span>
                            @endif
                            @if($item->status->value == 'Pending')
                            <form action="{{ route('pemesanan.cancel', $item) }}" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Batalkan Pemesanan', message: 'Apakah Anda yakin ingin membatalkan pengajuan pemesanan <strong>{{ $item->kode_pemesanan }}</strong>?', type: 'warning', confirmText: 'Ya, Batalkan' })">
                                @csrf
                                <button type="submit" class="btn-danger btn-sm">
                                    <i class="bi bi-x-circle"></i> Batalkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada pengajuan pemesanan. <a href="{{ route('pemesanan.create') }}">Buat sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pemesanan->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        {{ $pemesanan->links() }}
    </div>
    @endif
</div>


{{-- Global Reschedule Modal --}}
<div id="rescheduleModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:99999;align-items:center;justify-content:center;">
    <div style="background:#ffffff;border-radius:16px;max-width:480px;width:90%;padding:24px;box-shadow:0 20px 40px rgba(0,0,0,0.2);animation:fadeInUp 0.3s ease;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;border-bottom:1px solid #e2e8f0;padding-bottom:12px;">
            <h3 style="margin:0;font-size:16px;color:#003b73;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-calendar-event-fill" style="color:#005baa;"></i> Ajukan Perubahan Jadwal
            </h3>
            <button type="button" onclick="closeRescheduleModal()" style="background:none;border:none;font-size:20px;color:#64748b;cursor:pointer;">&times;</button>
        </div>
        <form id="rescheduleForm" method="POST" action="">
            @csrf
            <p style="font-size:13px;color:#475569;margin-bottom:16px;">
                Mengajukan perubahan tanggal/jam rapat untuk kode: <strong id="rescheduleKode">-</strong>
            </p>
            
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Tanggal Baru</label>
                <input type="date" name="reschedule_tanggal" id="rescheduleTanggal" required style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Jam Mulai Baru</label>
                    <input type="time" name="reschedule_waktu_mulai" id="rescheduleWaktuMulai" required style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;">
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Jam Selesai Baru</label>
                    <input type="time" name="reschedule_waktu_selesai" id="rescheduleWaktuSelesai" required style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12.5px;font-weight:700;color:#334155;margin-bottom:6px;">Alasan Perubahan Jadwal</label>
                <textarea name="reschedule_alasan" rows="3" required placeholder="Masukkan alasan penyesuaian jadwal rapat..." style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;"></textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeRescheduleModal()" class="btn-secondary" style="padding:9px 16px;border-radius:8px;font-size:13px;">Batal</button>
                <button type="submit" class="btn-primary" style="padding:9px 18px;border-radius:8px;font-size:13px;font-weight:700;">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRescheduleModal(id, kode, tgl, mulai, selesai) {
    const modal = document.getElementById('rescheduleModal');
    const form = document.getElementById('rescheduleForm');
    document.getElementById('rescheduleKode').textContent = kode;
    document.getElementById('rescheduleTanggal').value = tgl;
    document.getElementById('rescheduleWaktuMulai').value = mulai;
    document.getElementById('rescheduleWaktuSelesai').value = selesai;
    form.action = `/pemesanan/${id}/reschedule`;
    modal.style.display = 'flex';
}
function closeRescheduleModal() {
    document.getElementById('rescheduleModal').style.display = 'none';
}
</script>

</x-app-layout>