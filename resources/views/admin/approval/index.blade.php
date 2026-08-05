<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-clock-history" style="color:#f59e0b;margin-right:8px;"></i>Waiting List Pemesanan</h1>
        <p>Kelola dan verifikasi pengajuan penggunaan ruangan sebelum proses persetujuan.</p>
    </div>
    <span class="badge badge-warning" style="font-size:13px;padding:8px 16px;">
        <i class="bi bi-clock-history"></i> {{ $pemesanan->total() }} Pengajuan Menunggu
    </span>
</div>


<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pemohon</th>
                    <th>Kegiatan</th>
                    <th>Ruangan</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemesanan as $item)
                <tr>
                    <td>
                        <span class="badge badge-secondary" style="font-family:monospace;font-size:11px;">
                            {{ $item->kode_pemesanan }}
                        </span>
                    </td>
                    <td>
                        <strong style="color:#003b73;">{{ $item->user?->name ?? 'User (Dihapus)' }}</strong><br>
                        <small style="color:#64748b;">{{ $item->user?->nama_unit ?? '-' }}</small>
                    </td>
                    <td>
                        <strong>{{ $item->judul_kegiatan }}</strong><br>
                        <small style="color:#64748b;">PIC: {{ $item->pic_kegiatan }}</small>
                        @if($item->file_disposisi)
                        <br><span class="badge badge-info" style="margin-top:4px;font-size:10.5px;">
                            <i class="bi bi-paperclip"></i> Ada Disposisi
                        </span>
                        @endif
                    </td>
                    <td>
                        <strong style="color:#005baa;">{{ $item->ruangan->nama_ruangan }}</strong>
                        @if($item->layout)
                            <br><small style="color:#64748b;">{{ $item->layout->nama_layout }}</small>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY') }}</strong><br>
                        <small style="color:#64748b;"><i class="bi bi-clock"></i> {{ $item->waktu_mulai }} – {{ $item->waktu_selesai }}</small>
                    </td>
                    <td>
                        <span class="badge badge-warning">
                            <i class="bi bi-clock-history"></i>
                            {{ $item->status->label() }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.approval.show', $item) }}" class="btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Tidak ada pengajuan pemesanan yang perlu direview.</p>
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