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
                        <strong style="color:#003b73;">{{ $item->user->name }}</strong><br>
                        <small style="color:#64748b;">{{ $item->user->nama_unit ?? '-' }}</small>
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
                        <strong style="color:#005baa;">{{ $item->ruangan->nama_ruangan }}</strong><br>
                        <small style="color:#64748b;">{{ $item->layout?->nama_layout ?? '-' }}</small>
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

{{-- Pengajuan Reschedule --}}
@if(isset($rescheduleList) && $rescheduleList->count() > 0)
<div class="dashboard-section" style="margin-top:32px;">
    <div class="section-header" style="margin-bottom:16px;">
        <h2 style="color:#003b73;font-size:16px;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-calendar-event" style="color:#f59e0b;"></i> Pengajuan Perubahan Jadwal (Reschedule)
            <span class="badge badge-warning" style="font-size:11px;padding:3px 8px;">{{ $rescheduleList->count() }} Baru</span>
        </h2>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pemohon</th>
                    <th>Jadwal Lama</th>
                    <th>Jadwal Baru Diahjukan</th>
                    <th>Alasan Reschedule</th>
                    <th>Aksi Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rescheduleList as $res)
                <tr>
                    <td><strong style="color:#003b73;">{{ $res->kode_pemesanan }}</strong></td>
                    <td>
                        <strong>{{ $res->user->name }}</strong><br>
                        <small style="color:#64748b;">{{ $res->user->nama_unit ?? '-' }}</small>
                    </td>
                    <td>
                        <span style="color:#dc2626;font-weight:600;"><i class="bi bi-calendar-x"></i> {{ $res->tanggal_kegiatan->format('d/m/Y') }}</span><br>
                        <small style="color:#64748b;">{{ $res->waktu_mulai }} - {{ $res->waktu_selesai }}</small>
                    </td>
                    <td>
                        <span style="color:#16a34a;font-weight:700;"><i class="bi bi-calendar-check-fill"></i> {{ \Carbon\Carbon::parse($res->reschedule_tanggal)->format('d/m/Y') }}</span><br>
                        <strong style="color:#0284c7;">{{ $res->reschedule_waktu_mulai }} - {{ $res->reschedule_waktu_selesai }} WITA</strong>
                    </td>
                    <td style="max-width:240px;font-size:12.5px;color:#475569;">
                        "{{ $res->reschedule_alasan }}"
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <form action="{{ route('admin.approval.reschedule.approve', $res) }}" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Setujui Reschedule', message: 'Apakah Anda yakin ingin menyetujui perubahan jadwal untuk <strong>{{ $res->kode_pemesanan }}</strong>?', type: 'info', confirmText: 'Ya, Setujui' })">
                                @csrf
                                <button type="submit" class="btn-success btn-sm" style="background:#16a34a;color:white;border:none;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">
                                    <i class="bi bi-check-circle-fill"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.approval.reschedule.reject', $res) }}" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Tolak Reschedule', message: 'Apakah Anda yakin ingin menolak pengajuan reschedule ini?', type: 'danger', confirmText: 'Tolak' })">
                                @csrf
                                <button type="submit" class="btn-danger btn-sm" style="padding:6px 12px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">
                                    <i class="bi bi-x-circle-fill"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

</x-app-layout>