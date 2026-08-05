<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Pemesanan Ruangan - SILAKAN Bank Indonesia</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #003b73;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header img {
            height: 48px;
        }
        .header-text {
            text-align: right;
        }
        .header-text h2 {
            margin: 0;
            font-size: 16px;
            color: #003b73;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 2px 0 0;
            font-size: 11px;
            color: #475569;
        }
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h1 {
            margin: 0;
            font-size: 18px;
            color: #003b73;
        }
        .report-title p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 11px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 11px;
            color: #64748b;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 60px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #005baa; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <div class="header">
        <img src="{{ asset('images/logo-bi4.png') }}" alt="Bank Indonesia Logo">
        <div class="header-text">
            <h2>BANK INDONESIA</h2>
            <p>Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara</p>
            <p>SILAKAN — Sistem Informasi Layanan Kantor</p>
        </div>
    </div>

    <div class="report-title">
        <h1>LAPORAN REKAPITULASI PEMESANAN RUANGAN</h1>
        <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }} WITA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Ruangan</th>
                <th>Judul Kegiatan</th>
                <th>Unit / Pemohon</th>
                <th>PIC</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesanan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->kode_pemesanan }}</strong></td>
                <td>{{ $item->tanggal_kegiatan ? $item->tanggal_kegiatan->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->waktu_mulai }} - {{ $item->waktu_selesai }} WITA</td>
                <td><strong>{{ $item->ruangan->nama_ruangan ?? '-' }}</strong></td>
                <td>{{ $item->judul_kegiatan }}</td>
                <td>{{ $item->user?->nama_unit ?? $item->user?->name ?? 'User' }}</td>
                <td>{{ $item->pic_kegiatan }}</td>
                <td>
                    @php
                        $val = is_object($item->status) ? $item->status->value : $item->status;
                    @endphp
                    <strong>{{ $val }}</strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px;">Tidak ada data pemesanan ruangan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div>
            Total Record: <strong>{{ $pemesanan->count() }} Data</strong>
        </div>
        <div class="signature">
            <p>Manado, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Administrator SILAKAN</p>
            <div class="signature-space"></div>
            <p><strong>KPwBI Prov. Sulut</strong></p>
        </div>
    </div>

    <script>
        // Auto open print dialog on page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                // window.print();
            }, 500);
        });
    </script>
</body>
</html>
