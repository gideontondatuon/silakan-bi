<x-app-layout>

{{-- Page Header --}}
<div class="dashboard-header">
    <div>
        <h1><i class="bi bi-grid-fill" style="color:#005baa;margin-right:8px;"></i>Dashboard Admin</h1>
        <p>Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> &mdash; {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('admin.approval.create') }}" class="btn-primary" style="padding:9px 18px;border-radius:10px;font-weight:700;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(0,91,170,0.25);">
            <i class="bi bi-calendar-plus-fill"></i> Tambah Rapat
        </a>
        <div class="dashboard-date">
            <i class="bi bi-calendar3"></i>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>
</div>


{{-- LIVE Banner --}}
@if($kegiatanBerlangsung->count() > 0)
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
            <div class="live-card-pic" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;font-size:12.5px;color:rgba(255,255,255,0.9);padding-top:10px;border-top:1px solid rgba(255,255,255,0.15);">
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                    <span><i class="bi bi-people-fill" style="color:#93c5fd;margin-right:4px;"></i> Unit: <strong style="color:#ffffff;">{{ $live->user->nama_unit ?? $live->user->name }}</strong></span>
                    @if($live->pic_kegiatan)
                        <span><i class="bi bi-person-badge-fill" style="color:#93c5fd;margin-right:4px;"></i> PIC: <strong style="color:#ffffff;">{{ $live->pic_kegiatan }}</strong></span>
                    @endif
                    @if($live->layout)
                        <span><i class="bi bi-grid-3x3-gap-fill" style="color:#93c5fd;margin-right:4px;"></i> Layout: <strong style="color:#ffffff;">{{ $live->layout->nama_layout }}</strong></span>
                    @endif
                </div>
                <form action="{{ route('admin.approval.selesai-awal', $live) }}" method="POST" onsubmit="return submitFormWithConfirm(this, { title: 'Selesaikan Rapat Lebih Awal', message: 'Apakah rapat di ruangan <strong>{{ $live->ruangan->nama_ruangan }}</strong> telah selesai lebih cepat dan siap dibebaskan?', type: 'primary', confirmText: 'Ya, Selesaikan Sekarang' })" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-sm" style="background:#059669;color:#fff;border:none;border-radius:8px;padding:5px 12px;font-size:11.5px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
                        <i class="bi bi-check2-circle"></i> Selesaikan Rapat
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif


{{-- Stat Cards --}}
<div class="stat-grid">
    <x-stat-card title="Total Ruangan"       :value="$totalRuangan"      icon="building"      color="blue"   />
    <x-stat-card title="Total Pemesanan"     :value="$totalPemesanan"    icon="calendar"      color="teal"   />
    <x-stat-card title="Menunggu Approval"   :value="$waitingApproval"   icon="clock-history" color="yellow" />
    <x-stat-card title="Disetujui"           :value="$disetujui"         icon="check-circle"  color="green"  />
    <x-stat-card title="Ditolak"             :value="$ditolak"           icon="x-circle"      color="red"    />
    <x-stat-card title="Pemesanan Bulan Ini" :value="$pemesananBulanIni" icon="graph-up"      color="purple" />
</div>


{{-- Agenda Mendatang --}}
<div class="dashboard-section">
    <div class="section-header">
        <h2><i class="bi bi-calendar-event-fill"></i> Agenda &amp; Kegiatan Mendatang</h2>
        <a href="{{ route('kalender.index') }}">
            <i class="bi bi-calendar3"></i> Kalender
        </a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Kegiatan</th>
                    <th>Ruangan &amp; Layout</th>
                    <th>Pemohon / Unit</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agendaMendatang as $item)
                <tr>
                    <td>
                        <strong style="color:#003b73;">{{ $item->tanggal_kegiatan->isoFormat('ddd, D MMM YYYY') }}</strong><br>
                        <small style="color:#64748b;"><i class="bi bi-clock"></i> {{ $item->waktu_mulai }} – {{ $item->waktu_selesai }} WITA</small>
                    </td>
                    <td>
                        <strong>{{ $item->judul_kegiatan }}</strong><br>
                        <small style="color:#64748b;">PIC: {{ $item->pic_kegiatan }} ({{ $item->jenis_pic }})</small>
                    </td>
                    <td>
                        <span style="font-weight:700;color:#005baa;">{{ $item->ruangan->nama_ruangan }}</span><br>
                        <small style="color:#64748b;">{{ $item->layout?->nama_layout ?? '-' }} &middot; {{ $item->jumlah_tamu }} Tamu</small>
                    </td>
                    <td>
                        <strong>{{ $item->user->name }}</strong><br>
                        <small style="color:#64748b;">{{ $item->user->nama_unit ?? '-' }}</small>
                    </td>
                    <td><span class="badge badge-success"><i class="bi bi-check-circle"></i> Disetujui</span></td>
                    <td style="text-align:center;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                            <a href="{{ route('admin.approval.show', $item) }}" class="btn-primary btn-sm" style="padding:4px 8px;font-size:11.5px;" title="Detail Pemesanan">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.approval.destroy', $item) }}" onsubmit="return submitFormWithConfirm(this, { title: 'Hapus Pemesanan', message: 'Apakah Anda yakin ingin menghapus data pemesanan <strong>{{ $item->kode_pemesanan }}</strong> ({{ $item->judul_kegiatan }}) secara permanen?', type: 'danger', confirmText: 'Ya, Hapus Data' });" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm" style="padding:4px 8px;font-size:11.5px;background:#dc2626;color:white;border:none;border-radius:6px;cursor:pointer;" title="Hapus Pemesanan">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p>Belum ada agenda kegiatan mendatang yang disetujui.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- Two-column grid --}}
<div class="dashboard-grid">

    {{-- Waiting Approval --}}
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-clock-history"></i> Waiting Approval</h2>
            <a href="{{ route('admin.approval.index') }}">
                <i class="bi bi-arrow-right"></i> Semua
            </a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Kegiatan</th>
                        <th>User</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($waitingList as $item)
                    <tr>
                        <td><span class="badge badge-secondary">{{ $item->kode_pemesanan }}</span></td>
                        <td>{{ $item->judul_kegiatan }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->ruangan->nama_ruangan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>Tidak ada pemesanan menunggu.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Agenda Hari Ini --}}
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-sun"></i> Agenda Hari Ini</h2>
        </div>
        @forelse($kegiatanHariIni as $item)
        @php
            $isSelesai = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') > $item->waktu_selesai;
            $isBerlangsung = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') >= $item->waktu_mulai
                          && \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') <= $item->waktu_selesai;
        @endphp
        <div class="agenda-card" style="padding:14px 16px;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:12px;background:{{ $isSelesai ? '#f8fafc' : '#ffffff' }};box-shadow:0 2px 8px rgba(0,0,0,0.03);opacity:{{ $isSelesai ? '0.72' : '1' }};">
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
        <div class="empty-state" style="padding:30px 24px;">
            <i class="bi bi-calendar-check"></i>
            <p>Tidak ada agenda hari ini.</p>
        </div>
        @endforelse
    </div>

</div>


{{-- Second two-column grid --}}
<div class="dashboard-grid">

    {{-- Ruangan Terpopuler --}}
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-trophy"></i> Ruangan Terpopuler</h2>
        </div>
        @foreach($ruanganTerpopuler as $index => $item)
        <div class="ranking-item">
            <span>
                <span class="ranking-rank">{{ $index + 1 }}</span>
                {{ $item->ruangan->nama_ruangan }}
            </span>
            <strong>{{ $item->total }} booking</strong>
        </div>
        @endforeach
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="bi bi-activity"></i> Aktivitas Terbaru</h2>
        </div>
        @foreach($aktivitasTerbaru as $item)
        <div class="activity-item">
            <div class="activity-dot"></div>
            <div>
                <strong>{{ $item->user->name }}</strong>
                membuat pemesanan ruangan<br>
                <small><i class="bi bi-hash"></i> {{ $item->kode_pemesanan }}</small>
            </div>
        </div>
        @endforeach
    </div>

</div>

{{-- Visual Analytics & Chart Section --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:20px;margin-top:24px;margin-bottom:24px;">
    
    {{-- Chart 1: Tren Pemesanan --}}
    <div class="dashboard-section" style="margin-bottom:0;padding:20px;background:#ffffff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
        <div class="section-header" style="margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">
            <h2 style="font-size:15px;font-weight:700;color:#003b73;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-graph-up-arrow" style="color:#005baa;"></i> Tren Pemesanan (6 Bulan)
            </h2>
        </div>
        <div style="position:relative;height:220px;">
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>

    {{-- Chart 2: Pemakaian per Unit Kerja --}}
    <div class="dashboard-section" style="margin-bottom:0;padding:20px;background:#ffffff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.02);">
        <div class="section-header" style="margin-bottom:16px;border-bottom:1px solid #f1f5f9;padding-bottom:10px;">
            <h2 style="font-size:15px;font-weight:700;color:#003b73;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-pie-chart-fill" style="color:#005baa;"></i> Pemakaian per Unit Kerja
            </h2>
        </div>
        <div style="position:relative;height:220px;">
            <canvas id="unitDistributionChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Monthly Trend Chart
    const ctxMonthly = document.getElementById('monthlyTrendChart');
    if (ctxMonthly) {
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartMonthlyLabels) !!},
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: {!! json_encode($chartMonthlyData) !!},
                    borderColor: '#005baa',
                    backgroundColor: 'rgba(0, 91, 170, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#003b73'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Unit Distribution Chart
    const ctxUnit = document.getElementById('unitDistributionChart');
    if (ctxUnit) {
        new Chart(ctxUnit, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartUnitLabels) !!},
                datasets: [{
                    data: {!! json_encode($chartUnitData) !!},
                    backgroundColor: [
                        '#005baa', '#0284c7', '#0ea5e9', '#38bdf8', '#7dd3fc'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }
});
</script>

</x-app-layout>