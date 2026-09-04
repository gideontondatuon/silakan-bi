<x-app-layout>

<div class="dashboard-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px;">
    <div>
        <h1><i class="bi bi-calendar-check-fill" style="color:#005baa;margin-right:8px;"></i>Manajemen Pemesanan Ruangan</h1>
        <p>Kelola, verifikasi, serta hapus/batalkan pengajuan pemesanan ruangan kantor KPwBI Prov. Sulut.</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('admin.approval.create') }}" class="btn-primary" style="padding:9px 18px;border-radius:10px;font-weight:700;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(0,91,170,0.25);">
            <i class="bi bi-calendar-plus-fill"></i> Tambah Rapat
        </a>
        <span class="badge {{ $countPending > 0 ? 'badge-warning' : 'badge-success' }}" style="font-size:13px;padding:8px 16px;">
            <i class="bi bi-clock-history"></i> {{ $countPending }} Menunggu Approval
        </span>
    </div>
</div>

<div id="live-approval-container">
{{-- Tab Switcher --}}
<div style="display:flex;gap:10px;margin-bottom:20px;border-bottom:2px solid #e2e8f0;padding-bottom:2px;flex-wrap:wrap;">
    <a href="{{ route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'pending'])) }}" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;{{ $tab === 'pending' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;' }}">
        <i class="bi bi-hourglass-split"></i>
        Menunggu Approval
        <span style="background:{{ $tab === 'pending' ? 'rgba(255,255,255,0.25)' : '#f59e0b' }};color:{{ $tab === 'pending' ? '#fff' : '#fff' }};padding:2px 8px;border-radius:9999px;font-size:11px;">
            {{ $countPending }}
        </span>
    </a>

    <a href="{{ route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'disetujui'])) }}" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;{{ $tab === 'disetujui' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;' }}">
        <i class="bi bi-check-circle-fill"></i>
        Disetujui / Aktif
        <span style="background:{{ $tab === 'disetujui' ? 'rgba(255,255,255,0.25)' : '#059669' }};color:#fff;padding:2px 8px;border-radius:9999px;font-size:11px;">
            {{ $countDisetujui }}
        </span>
    </a>

    <a href="{{ route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'selesai'])) }}" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;{{ $tab === 'selesai' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;' }}">
        <i class="bi bi-check2-all"></i>
        Selesai
        <span style="background:{{ $tab === 'selesai' ? 'rgba(255,255,255,0.25)' : '#475569' }};color:#fff;padding:2px 8px;border-radius:9999px;font-size:11px;">
            {{ $countSelesai }}
        </span>
    </a>

    <a href="{{ route('admin.approval.index', array_merge(request()->except('page'), ['tab' => 'semua'])) }}" 
       style="padding:10px 20px;border-radius:10px 10px 0 0;font-weight:700;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;{{ $tab === 'semua' ? 'background:#005baa;color:#ffffff;box-shadow:0 4px 12px rgba(0,91,170,0.25);' : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-bottom:none;' }}">
        <i class="bi bi-collection-fill"></i>
        Semua Pemesanan
        <span style="background:{{ $tab === 'semua' ? 'rgba(255,255,255,0.25)' : '#64748b' }};color:#fff;padding:2px 8px;border-radius:9999px;font-size:11px;">
            {{ $countSemua }}
        </span>
    </a>
</div>

{{-- Filter Card --}}
<div class="dashboard-section" style="margin-bottom:20px;padding:16px 20px;background:#ffffff;border-radius:12px;border:1px solid #e2e8f0;">
    <form method="GET" action="{{ route('admin.approval.index') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <input type="hidden" name="tab" value="{{ $tab }}">

        {{-- Search Input --}}
        <div style="flex:2;min-width:220px;position:relative;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, judul kegiatan, PIC, unit..." style="width:100%;padding:9px 12px 9px 36px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;outline:none;">
            <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
        </div>

        {{-- Room Filter --}}
        <div style="flex:1;min-width:180px;">
            <select name="ruangan_id" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:white;outline:none;">
                <option value="">-- Semua Ruangan --</option>
                @foreach($ruangans as $r)
                    <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>{{ $r->nama_ruangan }}</option>
                @endforeach
            </select>
        </div>

        {{-- Date Filter --}}
        <div style="min-width:150px;">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;outline:none;">
        </div>

        {{-- Submit & Reset --}}
        <button type="submit" class="btn-primary" style="padding:9px 16px;border-radius:8px;font-size:13px;font-weight:700;">
            <i class="bi bi-filter"></i> Terapkan
        </button>

        @if(request()->hasAny(['q', 'ruangan_id', 'tanggal']))
        <a href="{{ route('admin.approval.index', ['tab' => $tab]) }}" class="btn-secondary" style="padding:9px 14px;border-radius:8px;font-size:13px;text-decoration:none;" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- Data Table --}}
<div class="dashboard-section" style="padding:0;overflow:hidden;border-radius:14px;border:1px solid #e2e8f0;background:#ffffff;">
    <div class="table-wrapper">
        <table class="data-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;text-align:left;font-size:12px;color:#475569;text-transform:uppercase;">
                    <th style="padding:14px 18px;">Kode & Tanggal</th>
                    <th style="padding:14px 18px;">Kegiatan / PIC</th>
                    <th style="padding:14px 18px;">Ruangan &amp; Layout</th>
                    <th style="padding:14px 18px;">Waktu (WITA)</th>
                    <th style="padding:14px 18px;">Pemohon / Unit</th>
                    <th style="padding:14px 18px;">Status</th>
                    <th style="padding:14px 18px;text-align:center;">Aksi Manajemen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemesanan as $item)
                @php
                    $statusVal = is_object($item->status) ? $item->status->value : $item->status;
                    $isLiveToday = $item->canBeFinishedEarly();
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9;font-size:13px;">
                    <td style="padding:14px 18px;">
                        <strong style="color:#003b73;font-family:monospace;font-size:12.5px;display:block;">
                            {{ $item->kode_pemesanan }}
                        </strong>
                        <small style="color:#64748b;font-weight:600;"><i class="bi bi-calendar3"></i> {{ $item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY') }}</small>
                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#0f172a;font-size:13.5px;display:block;">{{ $item->judul_kegiatan }}</strong>
                        <small style="color:#64748b;"><i class="bi bi-person"></i> PIC: {{ $item->pic_kegiatan }} ({{ $item->jenis_pic ?? '-' }})</small>
                        @if($item->file_disposisi)
                        <br><span class="badge badge-info" style="margin-top:3px;font-size:10px;padding:2px 6px;">
                            <i class="bi bi-paperclip"></i> Ada Disposisi
                        </span>
                        @endif
                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#005baa;display:block;">{{ $item->ruangan->nama_ruangan }}</strong>
                        <small style="color:#64748b;">{{ $item->layout?->nama_layout ?? '-' }} &bull; {{ $item->jumlah_tamu }} Tamu</small>
                    </td>
                    <td style="padding:14px 18px;white-space:nowrap;color:#334155;font-weight:600;">
                        <i class="bi bi-clock"></i> {{ $item->waktu_mulai }} – {{ $item->waktu_selesai }}
                    </td>
                    <td style="padding:14px 18px;">
                        <strong style="color:#003b73;display:block;">{{ $item->user?->name ?? 'User (Dihapus)' }}</strong>
                        <small style="color:#64748b;">{{ $item->user?->nama_unit ?? '-' }}</small>
                    </td>
                    <td style="padding:14px 18px;">
                        @if($statusVal === 'Selesai' || $item->is_finished)
                            <span class="badge" style="font-size:11px;padding:4px 10px;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;font-weight:600;">
                                <i class="bi bi-check2-all"></i> Selesai
                            </span>
                        @elseif($statusVal === 'Disetujui')
                            <span class="badge badge-success" style="font-size:11px;padding:4px 10px;">
                                <i class="bi bi-check-circle-fill"></i> Disetujui
                            </span>
                        @elseif($statusVal === 'Pending')
                            <span class="badge badge-warning" style="font-size:11px;padding:4px 10px;">
                                <i class="bi bi-clock-history"></i> Pending
                            </span>
                        @elseif($statusVal === 'Ditolak')
                            <span class="badge badge-danger" style="font-size:11px;padding:4px 10px;">
                                <i class="bi bi-x-circle-fill"></i> Ditolak
                            </span>
                        @else
                            <span class="badge badge-secondary" style="font-size:11px;padding:4px 10px;">
                                <i class="bi bi-dash-circle"></i> Cancel
                            </span>
                        @endif
                    </td>
                    <td style="padding:14px 18px;text-align:center;">
                        <div style="display:inline-flex;align-items:center;gap:6px;flex-wrap:wrap;justify-content:center;">
                            {{-- Review / Detail --}}
                            <a href="{{ route('admin.approval.show', $item) }}" class="btn-primary btn-sm" style="padding:6px 12px;font-size:12px;" title="Verifikasi / Detail">
                                <i class="bi bi-pencil-square"></i> Periksa
                            </a>

                            {{-- Early Release if Live Today --}}
                            @if($isLiveToday)
                            <form action="{{ route('admin.approval.selesai-awal', $item) }}" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Selesaikan Rapat Lebih Awal', message: 'Apakah rapat di ruangan <strong>{{ $item->ruangan->nama_ruangan }}</strong> telah selesai lebih cepat dan ruangan siap dibebaskan?', type: 'primary', confirmText: 'Ya, Selesaikan Sekarang' })" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn-sm" style="background:#059669;color:#fff;border:none;border-radius:8px;padding:6px 10px;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;" title="Rapat usai lebih cepat">
                                    <i class="bi bi-check2-circle"></i> Selesai Awal
                                </button>
                            </form>
                            @endif

                            {{-- Delete Booking Form --}}
                            <form method="POST" action="{{ route('admin.approval.destroy', $item) }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Pemesanan', message: 'Apakah Anda yakin ingin menghapus data pemesanan <strong>{{ $item->kode_pemesanan }}</strong> ({{ $item->judul_kegiatan }}) secara permanen dari sistem?', type: 'danger', confirmText: 'Ya, Hapus Data' });" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm" style="padding:6px 9px;font-size:12px;" title="Hapus Permanen">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px 24px;">
                        <div style="max-width:320px;margin:0 auto;">
                            <div style="width:56px;height:56px;border-radius:50%;background:#f1f5f9;color:#94a3b8;display:flex;align-items:center;justify-content:center;margin:0 auto 14px auto;font-size:24px;">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <h4 style="margin:0 0 6px 0;color:#0f172a;font-size:16px;">Tidak ada data pemesanan ditemukan</h4>
                            <p style="margin:0;color:#64748b;font-size:13px;">
                                @if($tab === 'pending')
                                    Tidak ada pengajuan pemesanan yang menunggu approval saat ini.
                                @elseif($tab === 'disetujui')
                                    Belum ada data pemesanan yang berstatus disetujui sesuai filter yang dipilih.
                                @else
                                    Belum ada data pemesanan di dalam sistem.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pemesanan->hasPages())
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;background:#f8fafc;">
        {{ $pemesanan->links() }}
    </div>
    @endif
</div>
</div>

</x-app-layout>