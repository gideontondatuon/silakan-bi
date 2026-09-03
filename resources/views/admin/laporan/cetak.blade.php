<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Pemesanan Ruangan — SILAKAN KPwBI Prov. Sulut</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ==================== PAGE SETUP ==================== */
        @page {
            size: A4 landscape;
            margin: 14mm 12mm 16mm 12mm;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', Arial, sans-serif;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ==================== SCREEN WRAPPER ==================== */
        .print-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
            background: #ffffff;
        }

        /* ==================== ACTION BAR (SCREEN ONLY) ==================== */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 24px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .action-bar-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-bar-info i {
            font-size: 18px;
            color: #005baa;
        }

        .action-bar-info span {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        .action-bar-info strong {
            color: #003b73;
        }

        .btn-print {
            background: linear-gradient(135deg, #005baa, #003b73);
            color: white;
            padding: 10px 22px;
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 91, 170, 0.3);
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #003b73, #002d5a);
        }

        /* ==================== KOP SURAT HEADER ==================== */
        .kop-header {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #003b73;
            margin-bottom: 4px;
            position: relative;
        }

        .kop-logo {
            height: 54px;
            width: auto;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
        }

        .kop-instansi-name {
            font-size: 14.5pt;
            font-weight: 800;
            color: #003b73;
            letter-spacing: 0.5px;
            margin: 0 0 1px 0;
        }

        .kop-instansi-sub {
            font-size: 9.5pt;
            font-weight: 500;
            color: #475569;
            margin: 0;
        }

        .kop-gold-bar {
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #b8972a, #d4ac3a, #b8972a);
        }

        /* ==================== REPORT TITLE ==================== */
        .report-title-block {
            text-align: center;
            padding: 14px 0 12px;
            border-bottom: 1px dashed #cbd5e1;
            margin-bottom: 14px;
        }

        .report-doc-label {
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #005baa;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 3px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 8px;
        }

        .report-main-title {
            font-size: 16pt;
            font-weight: 800;
            color: #003b73;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }

        .report-sub-title {
            font-size: 10pt;
            color: #475569;
            font-weight: 500;
            margin: 0;
        }

        /* ==================== META INFO STRIP ==================== */
        .meta-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #003b73, #005baa);
            border-radius: 9px;
            padding: 10px 18px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 7px;
            color: rgba(255,255,255,.9);
            font-size: 9pt;
        }

        .meta-item i {
            font-size: 11pt;
            color: rgba(255,255,255,.65);
        }

        .meta-item strong {
            color: #ffffff;
        }

        /* ==================== SUMMARY CARDS ==================== */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 18px;
        }

        .summary-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .summary-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .summary-card-icon.blue   { background: #dbeafe; color: #1d4ed8; }
        .summary-card-icon.green  { background: #dcfce7; color: #16a34a; }
        .summary-card-icon.red    { background: #fee2e2; color: #dc2626; }
        .summary-card-icon.purple { background: #ede9fe; color: #7c3aed; }

        .summary-card small {
            display: block;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #64748b;
            margin-bottom: 1px;
        }

        .summary-card strong {
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        /* ==================== DATA TABLE ==================== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .data-table thead tr {
            background: linear-gradient(135deg, #003b73, #005baa);
        }

        .data-table thead th {
            padding: 9px 10px;
            text-align: left;
            font-size: 8pt;
            font-weight: 700;
            color: rgba(255,255,255,.85);
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
            border: 1px solid rgba(255,255,255,.15);
        }

        .data-table tbody td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 9pt;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 2px solid #003b73;
        }

        .kode-badge {
            display: inline-block;
            background: #f0f9ff;
            color: #005baa;
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 8pt;
            font-weight: 700;
            font-family: Consolas, 'Courier New', monospace;
            border: 1px solid #bae6fd;
        }

        /* STATUS BADGES */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 8pt;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-selesai   { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .status-disetujui { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .status-pending   { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .status-ditolak   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .status-cancel    { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        .jenis-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 600;
        }

        .jenis-organik    { background: #dbeafe; color: #1d4ed8; }
        .jenis-non-organik { background: #f3e8ff; color: #6d28d9; }

        /* ==================== FOOTER / SIGNATURE ==================== */
        .report-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            margin-top: 10px;
        }

        .footer-left {
            font-size: 8.5pt;
            color: #64748b;
            line-height: 1.7;
        }

        .footer-left strong {
            color: #003b73;
        }

        .signature-block {
            text-align: center;
        }

        .signature-block p {
            font-size: 9pt;
            color: #334155;
            margin: 0 0 2px 0;
        }

        .signature-space {
            height: 55px;
            border-bottom: 1.5px solid #334155;
            width: 200px;
            margin: 8px auto;
        }

        .signature-name {
            font-size: 9pt;
            font-weight: 700;
            color: #003b73;
            margin: 0;
        }

        .signature-role {
            font-size: 8pt;
            color: #64748b;
            margin: 1px 0 0 0;
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-row td {
            padding: 40px 20px;
            text-align: center;
            color: #64748b;
            font-style: italic;
        }

        /* ==================== PRINT MEDIA ==================== */
        @media print {
            .action-bar, .no-print { display: none !important; }

            .print-wrapper {
                max-width: 100%;
                padding: 0;
            }

            body { font-size: 8.5pt; }

            .kop-logo { height: 48px; }
            .kop-instansi-name { font-size: 13pt; }
            .report-main-title { font-size: 14pt; }
            .summary-grid { gap: 8px; margin-bottom: 12px; }
            .summary-card { padding: 9px 12px; }
            .summary-card strong { font-size: 14pt; }
            .data-table thead th { font-size: 7.5pt; padding: 8px 9px; }
            .data-table tbody td { padding: 7px 9px; font-size: 8.5pt; }
        }

        /* ==================== BOOTSTRAP ICONS (CDN) ==================== */
    </style>

    {{-- Bootstrap Icons for screen preview --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="print-wrapper">

    {{-- ========== ACTION BAR (Screen Only) ========== --}}
    <div class="action-bar no-print">
        <div class="action-bar-info">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>
            <span>Pratinjau Laporan Siap Cetak &bull; <strong>{{ $pemesanan->count() }} Record</strong> ditemukan</span>
        </div>
        <button class="btn-print" onclick="window.print()">
            <i class="bi bi-printer-fill"></i> Cetak / Simpan PDF
        </button>
    </div>

    {{-- ========== KOP SURAT ========== --}}
    <div class="kop-header">
        <img src="{{ asset('images/logo-bi4.png') }}" alt="Logo Bank Indonesia" class="kop-logo">
        <div class="kop-text">
            <p class="kop-instansi-name">BANK INDONESIA</p>
            <p class="kop-instansi-sub">Provinsi Sulawesi Utara</p>
            <p class="kop-instansi-sub" style="margin-top:2px;">Jl. 17 Agustus, Manado, Sulawesi Utara</p>
        </div>
        <div class="kop-gold-bar"></div>
    </div>

    {{-- ========== JUDUL LAPORAN ========== --}}
    <div class="report-title-block">
        <div class="report-doc-label">Dokumen Internal</div>
        <h1 class="report-main-title">LAPORAN REKAPITULASI PEMESANAN RUANGAN RAPAT</h1>
        <p class="report-sub-title">
            SILAKAN &mdash; Sistem Informasi Layanan Kantor &bull; KPwBI Provinsi Sulawesi Utara
        </p>
    </div>

    {{-- ========== META INFO STRIP ========== --}}
    <div class="meta-strip">
        <div class="meta-item">
            <i class="bi bi-calendar3-range"></i>
            <span>Periode:&nbsp;
                <strong>
                    {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->translatedFormat('d F Y') : 'Semua Tanggal' }}
                    @if(request('tanggal_selesai')) &ndash; {{ \Carbon\Carbon::parse(request('tanggal_selesai'))->translatedFormat('d F Y') }} @endif
                </strong>
            </span>
        </div>
        <div class="meta-item">
            <i class="bi bi-building"></i>
            <span>Ruangan: <strong>{{ request('ruangan_id') ? $pemesanan->first()?->ruangan?->nama_ruangan ?? 'Spesifik' : 'Semua Ruangan' }}</strong></span>
        </div>
        <div class="meta-item">
            <i class="bi bi-filter-circle"></i>
            <span>Status: <strong>{{ request('status') ?? 'Semua Status' }}</strong></span>
        </div>
        <div class="meta-item">
            <i class="bi bi-clock-history"></i>
            <span>Dicetak: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WITA</strong></span>
        </div>
        <div class="meta-item">
            <i class="bi bi-person-badge"></i>
            <span>Oleh: <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong></span>
        </div>
    </div>

    {{-- ========== SUMMARY CARDS ========== --}}
    @php
        $total       = $pemesanan->count();
        $disetujui   = $pemesanan->filter(fn($i) => (is_object($i->status) ? $i->status->value : $i->status) === 'Disetujui')->count();
        $ditolak     = $pemesanan->filter(fn($i) => (is_object($i->status) ? $i->status->value : $i->status) === 'Ditolak')->count();
        $pending     = $pemesanan->filter(fn($i) => (is_object($i->status) ? $i->status->value : $i->status) === 'Pending')->count();
        $approvalRate = $total > 0 ? round(($disetujui / $total) * 100) : 0;
    @endphp
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-icon blue"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div>
                <small>Total Pemesanan</small>
                <strong>{{ $total }}</strong>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-card-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <small>Disetujui</small>
                <strong style="color:#16a34a;">{{ $disetujui }}</strong>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-card-icon red"><i class="bi bi-x-circle-fill"></i></div>
            <div>
                <small>Ditolak / Cancel</small>
                <strong style="color:#dc2626;">{{ $ditolak }}</strong>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-card-icon purple"><i class="bi bi-bar-chart-fill"></i></div>
            <div>
                <small>Tingkat Persetujuan</small>
                <strong style="color:#7c3aed;">{{ $approvalRate }}%</strong>
                <div style="margin-top:4px;height:4px;background:#ede9fe;border-radius:9px;overflow:hidden;">
                    <div style="height:100%;width:{{ $approvalRate }}%;background:#7c3aed;border-radius:9px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== DATA TABLE ========== --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:32px;text-align:center;">No</th>
                <th>Kode Pemesanan</th>
                <th style="white-space:nowrap;">Tanggal Kegiatan</th>
                <th style="white-space:nowrap;">Waktu (WITA)</th>
                <th>Ruangan</th>
                <th>Judul Kegiatan</th>
                <th>Unit Kerja / Pemohon</th>
                <th>PIC Kegiatan</th>
                <th>Jenis PIC</th>
                <th style="text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesanan as $index => $item)
            @php
                $statusVal = is_object($item->status) ? $item->status->value : $item->status;
                $statusClass = match($statusVal) {
                    'Selesai'   => 'status-selesai',
                    'Disetujui' => 'status-disetujui',
                    'Pending'   => 'status-pending',
                    'Ditolak'   => 'status-ditolak',
                    default     => 'status-cancel',
                };
                $statusIcon = match($statusVal) {
                    'Selesai'   => '✓✓',
                    'Disetujui' => '✓',
                    'Pending'   => '⏳',
                    'Ditolak'   => '✗',
                    default     => '—',
                };
            @endphp
            <tr>
                <td style="text-align:center;color:#94a3b8;font-weight:600;">{{ $index + 1 }}</td>
                <td>
                    <span class="kode-badge">{{ $item->kode_pemesanan }}</span>
                </td>
                <td style="white-space:nowrap;font-weight:600;color:#334155;">
                    {{ $item->tanggal_kegiatan ? $item->tanggal_kegiatan->translatedFormat('d M Y') : '-' }}
                </td>
                <td style="white-space:nowrap;font-weight:600;color:#475569;">
                    {{ $item->waktu_mulai }} &ndash; {{ $item->waktu_selesai }}
                </td>
                <td style="font-weight:700;color:#1e293b;">
                    {{ $item->ruangan->nama_ruangan ?? '-' }}
                    @if($item->layout)
                        <div style="font-size:7.5pt;color:#64748b;font-weight:400;margin-top:1px;">{{ $item->layout->nama_layout }}</div>
                    @endif
                </td>
                <td style="font-weight:600;color:#0f172a;max-width:200px;">
                    {{ $item->judul_kegiatan }}
                </td>
                <td style="font-weight:700;color:#005baa;">
                    {{ $item->user?->nama_unit ?? $item->user?->name ?? '—' }}
                </td>
                <td style="color:#334155;">
                    {{ $item->pic_kegiatan }}
                </td>
                <td>
                    @if($item->jenis_pic)
                        <span class="jenis-badge {{ $item->jenis_pic === 'Organik' ? 'jenis-organik' : 'jenis-non-organik' }}">
                            {{ $item->jenis_pic }}
                        </span>
                    @else
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusIcon }} {{ $statusVal }}
                    </span>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="10">Tidak ada data pemesanan ruangan yang sesuai dengan filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ========== TOTAL ROW ========== --}}
    <div style="display:flex;justify-content:space-between;align-items:center;font-size:9pt;color:#475569;margin-bottom:24px;">
        <div style="display:flex;gap:16px;">
            <span>Total: <strong style="color:#003b73;">{{ $total }} pemesanan</strong></span>
            <span>Disetujui: <strong style="color:#16a34a;">{{ $disetujui }}</strong></span>
            <span>Ditolak: <strong style="color:#dc2626;">{{ $ditolak }}</strong></span>
            <span>Pending: <strong style="color:#92400e;">{{ $pending }}</strong></span>
        </div>
        <div style="font-size:8.5pt;color:#94a3b8;">
            &ast; Data bersumber langsung dari database SILAKAN per tanggal cetak
        </div>
    </div>

    {{-- ========== FOOTER / TANDA TANGAN ========== --}}
    <div class="report-footer">
        <div class="footer-left">
            <strong>SILAKAN — Sistem Informasi Layanan Kantor</strong><br>
            Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara<br>
        </div>

        <div class="signature-block">
            <p>Manado, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="font-size:8.5pt;color:#64748b;">Mengetahui, Administrator SILAKAN</p>
            <div class="signature-space"></div>
            <p class="signature-name">Administrator SILAKAN</p>
        </div>
    </div>

</div>

<script>
    // Hapus font CDN jika tidak ada internet saat cetak
    document.querySelector('link[href*="googleapis"]')?.setAttribute('media', 'screen');
</script>

</body>
</html>
