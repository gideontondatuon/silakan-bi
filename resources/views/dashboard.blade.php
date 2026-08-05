<x-app-layout>

<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-grid-fill" style="color:#005baa;margin-right:8px;"></i>Dashboard SILAKAN</h1>
        <p>Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>
            @if(auth()->user()->nama_unit) &mdash; {{ auth()->user()->nama_unit }} @endif
        </p>
    </div>
    <div class="dashboard-date">
        <i class="bi bi-calendar3"></i>
        {{ now()->translatedFormat('d F Y') }}
    </div>
</div>


{{-- Live Banner --}}
@if(isset($kegiatanBerlangsung) && $kegiatanBerlangsung->count() > 0)
<div class="live-banner">
    <div class="live-banner-header">
        <div class="live-banner-title">
            <span class="live-indicator-dot"></span>
            Kegiatan Sedang Berlangsung — Live Saat Ini
        </div>
        <span class="live-count">
            <i class="bi bi-building"></i> {{ $kegiatanBerlangsung->count() }} Ruangan Terpakai
        </span>
    </div>
    <div class="live-cards-grid">
        @foreach($kegiatanBerlangsung as $live)
        <div class="live-card">
            <div class="live-card-room" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <strong style="font-size:15px;color:#fef08a;"><i class="bi bi-building"></i> {{ $live->ruangan->nama_ruangan }}</strong>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span class="live-card-time">
                        <i class="bi bi-clock-history"></i>
                        {{ $live->waktu_mulai }} – {{ $live->waktu_selesai }} WITA
                    </span>
                    <span class="live-countdown-badge" data-end-time="{{ $live->tanggal_kegiatan->format('Y-m-d') }}T{{ $live->waktu_selesai }}">
                        <i class="bi bi-hourglass-split" style="animation:spinHourglass 2.5s infinite linear;color:#fef08a;"></i>
                        <span class="countdown-value">Hitung sisa...</span>
                    </span>
                </div>
            </div>
            <div class="live-card-title" style="font-size:16px;font-weight:700;color:#ffffff;margin:8px 0 10px 0;">{{ $live->judul_kegiatan }}</div>
            <div class="live-card-pic" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;font-size:12.5px;color:rgba(255,255,255,0.9);padding-top:10px;border-top:1px solid rgba(255,255,255,0.15);">
                <span><i class="bi bi-people-fill" style="color:#93c5fd;margin-right:4px;"></i> Unit Penyelenggara: <strong style="color:#ffffff;">{{ $live->user->nama_unit ?? $live->user->name }}</strong></span>
                @if($live->pic_kegiatan)
                    <span><i class="bi bi-person-badge-fill" style="color:#93c5fd;margin-right:4px;"></i> PIC: <strong style="color:#ffffff;">{{ $live->pic_kegiatan }}</strong></span>
                @endif
                @if($live->layout)
                    <span><i class="bi bi-grid-3x3-gap-fill" style="color:#93c5fd;margin-right:4px;"></i> Layout: <strong style="color:#ffffff;">{{ $live->layout->nama_layout }}</strong></span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif


{{-- Quick Actions --}}
<div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px;">
    <a href="{{ route('pemesanan.create') }}" class="btn-primary">
        <i class="bi bi-plus-circle-fill" style="font-size:16px;"></i> Buat Pemesanan Ruangan
    </a>
    <a href="{{ route('kalender.index') }}" class="btn-secondary">
        <i class="bi bi-calendar-range" style="font-size:16px;"></i> Kalender Ruangan
    </a>
    <a href="{{ route('pemesanan.index') }}" class="btn-secondary">
        <i class="bi bi-journal-text" style="font-size:16px;"></i> Riwayat Pemesanan
    </a>
</div>


{{-- Stat Cards --}}
<div class="stat-grid">
    <x-stat-card title="Total Pemesanan"      :value="$totalPemesanan"   icon="calendar-check"  color="blue"   />
    <x-stat-card title="Menunggu Approval"    :value="$pendingPemesanan" icon="clock-history"   color="yellow" />
    <x-stat-card title="Disetujui"            :value="$approvedPemesanan" icon="check-circle"   color="green"  />
    <x-stat-card title="Kegiatan Mendatang"   :value="$upcomingPemesanan" icon="calendar-event" color="purple" />
</div>


{{-- Main Content Grid --}}
<div class="dashboard-user-layout">

    {{-- Pemesanan Terbaru --}}
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-clock-history"></i> Pemesanan Terbaru Saya</h2>
            <a href="{{ route('pemesanan.index') }}">
                <i class="bi bi-arrow-right"></i> Lihat Semua
            </a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Kegiatan</th>
                        <th>Ruangan</th>
                        <th>Tanggal &amp; Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemesananTerbaru as $item)
                    <tr>
                        <td><span style="font-family:monospace;font-size:11.5px;color:#005baa;font-weight:700;">{{ $item->kode_pemesanan }}</span></td>
                        <td><strong>{{ $item->judul_kegiatan }}</strong></td>
                        <td>
                            {{ $item->ruangan->nama_ruangan }}<br>
                            <small style="color:#64748b;">{{ $item->layout?->nama_layout ?? '-' }}</small>
                        </td>
                        <td>
                            {{ $item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY') }}<br>
                            <small style="color:#64748b;"><i class="bi bi-clock"></i> {{ $item->waktu_mulai }} – {{ $item->waktu_selesai }}</small>
                        </td>
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
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>Belum ada pengajuan pemesanan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Agenda Hari Ini --}}
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="bi bi-calendar-day"></i> Agenda Hari Ini</h2>
            </div>
            @forelse($kegiatanHariIni as $item)
            @php
                $isSelesai = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') > $item->waktu_selesai;
                $isBerlangsung = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') >= $item->waktu_mulai
                              && \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') <= $item->waktu_selesai;
            @endphp
            <div class="agenda-card" style="padding:14px 16px;border-radius:12px;border:1px solid {{ $isSelesai ? '#e2e8f0' : '#e2e8f0' }};margin-bottom:12px;background:{{ $isSelesai ? '#f8fafc' : '#ffffff' }};box-shadow:0 2px 8px rgba(0,0,0,0.03);opacity:{{ $isSelesai ? '0.72' : '1' }};">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px;flex-wrap:wrap;">
                    <strong style="color:#003b73;font-size:13px;"><i class="bi bi-building"></i> {{ $item->ruangan->nama_ruangan }}</strong>
                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                        @if($isSelesai)
                            <span style="font-size:11px;padding:3px 9px;border-radius:6px;background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;font-weight:600;"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                        @elseif($isBerlangsung)
                            <span style="font-size:11px;padding:3px 9px;border-radius:6px;background:#dcfce7;color:#15803d;border:1px solid #86efac;font-weight:600;"><i class="bi bi-broadcast"></i> Berlangsung</span>
                        @endif
                        <span class="badge badge-info" style="font-size:11px;padding:3px 9px;border-radius:6px;background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;font-weight:600;">
                            <i class="bi bi-people-fill"></i> {{ $item->user->nama_unit ?? $item->user->name }}
                        </span>
                    </div>
                </div>
                <p style="margin:0 0 6px 0;font-weight:700;color:{{ $isSelesai ? '#94a3b8' : '#0f172a' }};font-size:13.5px;line-height:1.35;">{{ $item->judul_kegiatan }}</p>
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;color:#64748b;flex-wrap:wrap;gap:6px;">
                    <span style="color:{{ $isSelesai ? '#94a3b8' : '#005baa' }};font-weight:600;"><i class="bi bi-clock"></i> {{ $item->waktu_mulai }} – {{ $item->waktu_selesai }} WITA</span>
                    @if($item->pic_kegiatan)
                        <span style="color:#475569;font-weight:500;"><i class="bi bi-person-fill"></i> PIC: {{ $item->pic_kegiatan }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:24px 20px;">
                <i class="bi bi-calendar-x"></i>
                <p>Tidak ada agenda hari ini.</p>
            </div>
            @endforelse
        </div>

        {{-- Panduan Pemesanan --}}
        <div class="dashboard-section" style="background:linear-gradient(135deg,#005baa,#003b73);color:white;border:none;">
            <div style="padding:20px;">
                <h2 style="color:white;font-size:14px;display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                    <i class="bi bi-info-circle"></i> Panduan Pemesanan
                </h2>
                <div style="display:flex;flex-direction:column;gap:12px;font-size:13px;">
                    @foreach([
                        ['1', 'Pilih ruangan & tata letak (layout) sesuai kebutuhan acara.'],
                        ['2', 'Isi formulir pengajuan pemesanan secara lengkap.'],
                        ['3', 'Tunggu verifikasi & persetujuan dari Administrator.'],
                    ] as $step)
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div style="background:rgba(255,255,255,0.2);width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">{{ $step[0] }}</div>
                        <div style="padding-top:3px;">{{ $step[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>

</x-app-layout>