<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-layout-text-sidebar-reverse" style="color:#005baa;margin-right:8px;"></i>Data Layout Ruangan</h1>
        <p>Kelola konfigurasi layout dan kapasitas ruangan kantor.</p>
    </div>
    <a href="{{ route('admin.layout.create') }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Layout
    </a>
</div>

<div class="dashboard-section">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Nama Layout</th>
                    <th>Terdapat di Ruangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($layouts as $item)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $loop->iteration }}</td>
                    <td>
                        <span style="display:flex;align-items:center;gap:9px;">
                            <span style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#e8f1fb,#cce0f5);display:flex;align-items:center;justify-content:center;color:#005baa;font-size:15px;flex-shrink:0;">
                                <i class="bi bi-layout-wtf"></i>
                            </span>
                            <strong style="color:#003b73;">{{ $item->nama_layout }}</strong>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            @if(isset($item->ruangans) && $item->ruangans->count() > 0)
                                @foreach($item->ruangans as $r)
                                <span class="badge badge-secondary" style="font-size:11px;">
                                    <i class="bi bi-building"></i> {{ $r->nama_ruangan }}
                                </span>
                                @endforeach
                            @elseif(isset($item->ruangan) && $item->ruangan)
                                <span class="badge badge-secondary" style="font-size:11px;">
                                    <i class="bi bi-building"></i> {{ $item->ruangan->nama_ruangan }}
                                </span>
                            @else
                                <span style="color:#94a3b8;font-style:italic;font-size:12px;">Belum ditautkan ke ruangan</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.layout.edit', $item) }}" class="btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.layout.destroy', $item) }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Layout', message: 'Apakah Anda yakin ingin menghapus layout <strong>{{ $item->nama_layout }}</strong>?', type: 'danger', confirmText: 'Ya, Hapus' })">
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
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="bi bi-layout-text-sidebar"></i>
                            <p>Belum ada data layout ruangan. <a href="{{ route('admin.layout.create') }}">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($layouts->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
        {{ $layouts->links() }}
    </div>
    @endif
</div>

</x-app-layout>