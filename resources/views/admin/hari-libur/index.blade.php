<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-calendar2-week" style="color:#005baa;margin-right:8px;"></i>Kelola Hari Libur &amp; Cuti Bersama</h1>
        <p>Manajemen tanggal merah, hari libur nasional, cuti bersama, dan akhir pekan sistem SILAKAN.</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <form method="POST" action="{{ route('admin.hari-libur.sync') }}" style="display:inline-block;" onsubmit="return submitFormWithConfirm(this, { title: 'Sinkronisasi Hari Libur', message: 'Sinkronkan data hari libur nasional &amp; cuti bersama tahun <strong>{{ date('Y') }}</strong> dari API resmi?', type: 'primary', confirmText: 'Ya, Sinkronkan' })">
            @csrf
            <input type="hidden" name="tahun" value="{{ date('Y') }}">
            <button type="submit" class="btn-primary">
                <i class="bi bi-cloud-download"></i> Sync API Libur &amp; Cuti {{ date('Y') }}
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('success') }}
</div>
@endif

<div class="dashboard-grid">

    {{-- Form Tambah Manual --}}
    <div class="dashboard-section" style="margin-bottom:0;">
        <div class="section-header">
            <h2><i class="bi bi-plus-circle"></i> Tambah Hari Libur / Cuti Bersama</h2>
        </div>
        <div style="padding:24px;">
            <form method="POST" action="{{ route('admin.hari-libur.store') }}">
                @csrf

                <div class="form-group">
                    <label class="required">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" required>
                    @error('tanggal') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="required">Nama / Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Cuti Bersama Idul Fitri" required>
                    @error('keterangan') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="required">Kategori Hari Libur</label>
                    <select name="kategori" required>
                        <option value="libur_nasional" {{ old('kategori') == 'libur_nasional' ? 'selected' : '' }}>Hari Libur Nasional</option>
                        <option value="cuti_bersama"  {{ old('kategori') == 'cuti_bersama'  ? 'selected' : '' }}>Cuti Bersama</option>
                        <option value="internal"      {{ old('kategori') == 'internal'      ? 'selected' : '' }}>Libur Internal / Khusus BI</option>
                    </select>
                    @error('kategori') <span class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-action" style="margin-top:12px;">
                    <button type="submit" class="btn-primary" style="width:100%;">
                        <i class="bi bi-check-lg"></i> Simpan Data Libur
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Tabel Hari Libur & Cuti Bersama --}}
    <div class="dashboard-section" style="margin-bottom:0;">
        <div class="section-header">
            <h2><i class="bi bi-calendar-event"></i> Daftar Libur &amp; Cuti Bersama</h2>
            <form method="GET" action="{{ route('admin.hari-libur.index') }}" style="display:flex;align-items:center;gap:8px;">
                <select name="kategori" onchange="this.form.submit()" style="padding:4px 10px;font-size:12px;border-radius:6px;border:1px solid #cbd5e1;">
                    <option value="">Semua Kategori</option>
                    <option value="libur_nasional" {{ request('kategori') == 'libur_nasional' ? 'selected' : '' }}>Libur Nasional</option>
                    <option value="cuti_bersama"  {{ request('kategori') == 'cuti_bersama'  ? 'selected' : '' }}>Cuti Bersama</option>
                    <option value="internal"      {{ request('kategori') == 'internal'      ? 'selected' : '' }}>Internal BI</option>
                </select>

                <select name="tahun" onchange="this.form.submit()" style="padding:4px 10px;font-size:12px;border-radius:6px;border:1px solid #cbd5e1;">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $th)
                        <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>Tahun {{ $th }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th style="width:60px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hariLibur as $index => $item)
                    <tr>
                        <td style="color:#94a3b8;font-size:12px;">{{ $hariLibur->firstItem() + $index }}</td>
                        <td>
                            <strong style="color:#003b73;">{{ $item->tanggal->isoFormat('ddd, D MMM YYYY') }}</strong>
                        </td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            @if($item->kategori == 'cuti_bersama')
                                <span class="badge badge-warning" style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;"><i class="bi bi-umbrella-fill"></i> Cuti Bersama</span>
                            @elseif($item->kategori == 'internal')
                                <span class="badge badge-info"><i class="bi bi-building"></i> Internal BI</span>
                            @else
                                <span class="badge badge-danger"><i class="bi bi-flag-fill"></i> Libur Nasional</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.hari-libur.destroy', $item) }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Hari Libur', message: 'Apakah Anda yakin ingin menghapus data libur <strong>{{ $item->keterangan }}</strong>?', type: 'danger', confirmText: 'Ya, Hapus' })">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p>Belum ada data hari libur / cuti bersama. Klik "Sync API Libur & Cuti" di atas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hariLibur->hasPages())
        <div style="padding:16px 24px;border-top:1px solid #f1f5f9;">
            {{ $hariLibur->links() }}
        </div>
        @endif
    </div>

</div>

</x-app-layout>
