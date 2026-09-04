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



<div class="dashboard-section" id="live-pemesanan-container">
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
                        <strong>{{ $item->ruangan->nama_ruangan }}</strong>
                        @if($item->layout)
                            <br><small style="color:#64748b;">{{ $item->layout->nama_layout }}</small>
                        @endif
                    </td>
                    <td><strong>{{ $item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY') }}</strong></td>
                    <td style="color:#64748b;white-space:nowrap;">{{ $item->waktu_mulai }} – {{ $item->waktu_selesai }}</td>
                    <td>
                        @if($item->status->value == 'Selesai' || $item->is_finished)
                            <span class="badge" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;font-weight:700;"><i class="bi bi-check2-all"></i> Selesai</span>
                        @elseif($item->status->value == 'Pending')
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

</x-app-layout>