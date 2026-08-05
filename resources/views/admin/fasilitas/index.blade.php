<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-tools" style="color:#005baa;margin-right:8px;"></i>Data Fasilitas</h1>
        <p>Kelola fasilitas yang tersedia pada ruangan kantor.</p>
    </div>
    <a href="{{ route('admin.fasilitas.create') }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Fasilitas
    </a>
</div>

<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Nama Fasilitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fasilitas as $item)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $loop->iteration }}</td>
                    <td>
                        <span style="display:flex;align-items:center;gap:8px;">
                            <span style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#e8f1fb,#cce0f5);display:flex;align-items:center;justify-content:center;color:#005baa;font-size:14px;flex-shrink:0;">
                                <i class="bi bi-check2-square"></i>
                            </span>
                            <strong style="color:#003b73;">{{ $item->nama_fasilitas }}</strong>
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.fasilitas.edit', $item) }}" class="btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.fasilitas.destroy', $item) }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Fasilitas', message: 'Apakah Anda yakin ingin menghapus fasilitas ini?', type: 'danger', confirmText: 'Ya, Hapus' })">
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
                    <td colspan="3">
                        <div class="empty-state">
                            <i class="bi bi-tools"></i>
                            <p>Belum ada data fasilitas. <a href="{{ route('admin.fasilitas.create') }}">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($fasilitas->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        {{ $fasilitas->links() }}
    </div>
    @endif
</div>

</x-app-layout>