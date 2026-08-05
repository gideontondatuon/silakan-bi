<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-building" style="color:#005baa;margin-right:8px;"></i>Data Ruangan</h1>
        <p>Kelola data ruangan yang tersedia pada sistem SILAKAN.</p>
    </div>
    <a href="{{ route('admin.ruangan.create') }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Ruangan
    </a>
</div>


<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Nama Ruangan</th>
                    <th>Lokasi</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ruangans as $i => $ruangan)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $ruangans->firstItem() + $i }}</td>
                    <td>
                        <strong style="color:#003b73;">{{ $ruangan->nama_ruangan }}</strong>
                    </td>
                    <td>
                        <span style="display:flex;align-items:center;gap:6px;color:#475569;">
                            <i class="bi bi-geo-alt" style="color:#005baa;"></i>
                            {{ $ruangan->lokasi }}
                        </span>
                    </td>
                    <td>
                        <span style="display:flex;align-items:center;gap:5px;">
                            <i class="bi bi-people" style="color:#005baa;font-size:13px;"></i>
                            {{ $ruangan->kapasitas }} Orang
                        </span>
                    </td>
                    <td>
                        @if($ruangan->status == 'aktif')
                            <span class="badge badge-success"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Aktif</span>
                        @elseif($ruangan->status == 'perawatan')
                            <span class="badge badge-warning"><i class="bi bi-tools" style="font-size:10px;"></i> Perawatan</span>
                        @else
                            <span class="badge badge-danger"><i class="bi bi-x-circle" style="font-size:10px;"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.ruangan.edit', $ruangan) }}" class="btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.ruangan.destroy', $ruangan) }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Ruangan', message: 'Apakah Anda yakin ingin menghapus data ruangan <strong>{{ $ruangan->nama_ruangan }}</strong>?', type: 'danger', confirmText: 'Ya, Hapus' })">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-building"></i>
                            <p>Belum ada data ruangan. <a href="{{ route('admin.ruangan.create') }}">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ruangans->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        {{ $ruangans->links() }}
    </div>
    @endif
</div>

</x-app-layout>