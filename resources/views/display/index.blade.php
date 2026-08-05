<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DISPLAY LAYAR MONITOR — SILAKAN Bank Indonesia KPwBI Prov. Sulut</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #031326;
            background: linear-gradient(135deg, #020c1b 0%, #001f3f 50%, #003b73 100%);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar Header */
        .display-header {
            min-height: 90px;
            background: rgba(0, 31, 63, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 2px solid rgba(255, 255, 255, 0.12);
            padding: 16px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-brand img {
            height: 52px;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
        }

        .header-brand-text h1 {
            font-size: 24px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 2px;
            line-height: 1;
        }

        .header-brand-text p {
            font-size: 13px;
            color: #93c5fd;
            margin-top: 4px;
            font-weight: 600;
        }

        .header-clock {
            text-align: right;
        }

        .clock-time {
            font-size: 30px;
            font-weight: 900;
            color: #fef08a;
            letter-spacing: 1px;
            font-family: monospace;
            text-shadow: 0 0 12px rgba(254, 240, 138, 0.4);
        }

        .clock-date {
            font-size: 13.5px;
            color: #e2e8f0;
            font-weight: 600;
            margin-top: 2px;
        }

        /* Main Display Layout */
        .display-container {
            flex: 1;
            padding: 28px 36px;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 28px;
        }

        .display-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }

        .card-header-title {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        /* Live Indicator */
        .live-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ef4444;
            animation: livePulse 1.8s infinite;
        }

        @keyframes livePulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        @keyframes spinHourglass {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Live Cards Grid */
        .live-item {
            background: linear-gradient(135deg, rgba(0, 91, 170, 0.4), rgba(0, 59, 115, 0.6));
            border: 1px solid rgba(147, 197, 253, 0.3);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .live-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .live-room {
            font-size: 17px;
            font-weight: 800;
            color: #fef08a;
        }

        .live-time-badge {
            background: #ef4444;
            color: white;
            font-size: 13px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .live-countdown {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(254, 240, 138, 0.4);
            color: #fef08a;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 700;
            font-family: monospace;
        }

        .live-title {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .live-meta {
            display: flex;
            gap: 16px;
            font-size: 13.5px;
            color: #cbd5e1;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-wrap: wrap;
        }

        /* Today Table (Desktop) */
        .today-table {
            width: 100%;
            border-collapse: collapse;
        }

        .today-table th {
            text-align: left;
            padding: 12px 14px;
            font-size: 12.5px;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .today-table td {
            padding: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 14px;
        }

        .mobile-schedule-view {
            display: none;
        }

        /* Footer Bar */
        .display-footer {
            min-height: 50px;
            background: rgba(2, 12, 27, 0.9);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
            color: #94a3b8;
        }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .display-header {
                padding: 16px 20px;
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .header-brand {
                flex-direction: column;
                gap: 8px;
            }

            .header-clock {
                text-align: center;
            }

            .clock-time {
                font-size: 24px;
            }

            .display-container {
                padding: 16px 20px;
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .display-footer {
                padding: 14px 20px;
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .desktop-schedule-view {
                display: none !important;
            }

            .mobile-schedule-view {
                display: block !important;
            }

            .header-brand img {
                height: 44px;
            }

            .header-brand-text h1 {
                font-size: 20px;
            }

            .header-brand-text p {
                font-size: 11.5px;
            }

            .clock-time {
                font-size: 22px;
            }

            .clock-date {
                font-size: 12px;
            }

            .display-card {
                padding: 18px;
                border-radius: 16px;
            }

            .card-header-title {
                font-size: 16px;
                margin-bottom: 14px;
            }

            .live-item {
                padding: 14px;
                border-radius: 14px;
            }

            .live-title {
                font-size: 15.5px;
            }

            .live-meta {
                flex-direction: column;
                gap: 6px;
                font-size: 12.5px;
            }
        }
    </style>
</head>
<body>

    {{-- Top Header --}}
    <header class="display-header">
        <div class="header-brand">
            <img src="{{ asset('images/logo-bi4.png') }}" alt="Bank Indonesia Logo">
            <div class="header-brand-text">
                <h1>SILAKAN</h1>
                <p>Sistem Informasi Layanan Kantor — KPwBI Prov. Sulut</p>
            </div>
        </div>
        <div class="header-clock">
            <div class="clock-time" id="clock-time">--:--:-- WITA</div>
            <div class="clock-date" id="clock-date">-- --- ----</div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="display-container">

        {{-- Left: Live Currently Active --}}
        <div class="display-card">
            <div class="card-header-title">
                <span class="live-dot"></span>
                <span>Kegiatan Sedang Berlangsung (LIVE)</span>
            </div>

            <div id="live-container" style="flex:1;overflow-y:auto;">
                @forelse($kegiatanLive as $live)
                <div class="live-item">
                    <div class="live-item-header">
                        <span class="live-room"><i class="bi bi-building"></i> {{ $live->ruangan->nama_ruangan }}</span>
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <span class="live-time-badge">
                                <i class="bi bi-clock"></i> {{ $live->waktu_mulai }} - {{ $live->waktu_selesai }}
                            </span>
                            <span class="live-countdown" data-end-time="{{ $live->tanggal_kegiatan->format('Y-m-d') }}T{{ $live->waktu_selesai }}">
                                <i class="bi bi-hourglass-split" style="animation:spinHourglass 2.5s infinite linear;"></i>
                                <span class="countdown-val">Sisa ...</span>
                            </span>
                        </div>
                    </div>
                    <div class="live-title">{{ $live->judul_kegiatan }}</div>
                    <div class="live-meta">
                        <span><i class="bi bi-people-fill" style="color:#60a5fa;"></i> {{ $live->user->nama_unit ?? $live->user->name }}</span>
                        <span><i class="bi bi-person-fill" style="color:#60a5fa;"></i> PIC: {{ $live->pic_kegiatan }}</span>
                    </div>
                </div>
                @empty
                <div style="padding:40px 20px;text-align:center;color:#94a3b8;">
                    <i class="bi bi-calendar-check" style="font-size:48px;display:block;margin-bottom:12px;color:#64748b;"></i>
                    <p style="font-size:15px;font-weight:600;">Saat ini tidak ada kegiatan rapat yang berlangsung.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Right: Today's Full Schedule --}}
        <div class="display-card">
            <div class="card-header-title">
                <i class="bi bi-calendar-week" style="color:#60a5fa;"></i>
                <span>Jadwal Agenda Rapat Hari Ini</span>
            </div>

            {{-- Desktop Table View --}}
            <div class="desktop-schedule-view" style="flex:1;overflow-y:auto;">
                <table class="today-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Ruangan</th>
                            <th>Kegiatan</th>
                            <th>Unit Penyelenggara</th>
                        </tr>
                    </thead>
                    <tbody id="today-tbody">
                        @forelse($kegiatanHariIni as $today)
                        <tr>
                            <td style="font-weight:700;color:#fef08a;white-space:nowrap;">
                                <i class="bi bi-clock"></i> {{ $today->waktu_mulai }} - {{ $today->waktu_selesai }}
                            </td>
                            <td style="font-weight:700;color:#93c5fd;">
                                {{ $today->ruangan->nama_ruangan }}
                            </td>
                            <td>
                                <strong>{{ $today->judul_kegiatan }}</strong>
                            </td>
                            <td style="color:#cbd5e1;">
                                {{ $today->user->nama_unit ?? $today->user->name }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:40px;color:#94a3b8;">
                                Tidak ada agenda rapat untuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="mobile-schedule-view">
                @forelse($kegiatanHariIni as $today)
                <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:14px;padding:14px 16px;margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:6px;">
                        <span style="font-weight:800;color:#fef08a;font-size:13px;"><i class="bi bi-clock"></i> {{ $today->waktu_mulai }} - {{ $today->waktu_selesai }} WITA</span>
                        <span style="font-weight:700;color:#93c5fd;font-size:12px;background:rgba(147,197,253,0.15);padding:3px 10px;border-radius:6px;border:1px solid rgba(147,197,253,0.25);">
                            <i class="bi bi-building"></i> {{ $today->ruangan->nama_ruangan }}
                        </span>
                    </div>
                    <div style="font-weight:800;font-size:15px;color:#ffffff;margin-bottom:6px;line-height:1.35;">{{ $today->judul_kegiatan }}</div>
                    <div style="font-size:12.5px;color:#cbd5e1;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-people-fill" style="color:#60a5fa;"></i> {{ $today->user->nama_unit ?? $today->user->name }}
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:30px 14px;color:#94a3b8;font-size:14px;">
                    Tidak ada agenda rapat untuk hari ini.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <footer class="display-footer">
        <div>
            <i class="bi bi-broadcast" style="color:#ef4444;margin-right:6px;"></i> Mode Layar TV Lobby Kiosk &mdash; Bank Indonesia KPwBI Prov. Sulut
        </div>
        <div id="last-update">
            Terakhir Diperbarui: {{ now()->translatedFormat('H:i:s') }} WITA
        </div>
    </footer>

    <script>
    // Real-time Clock
    function updateClock() {
        const now = new Date();
        const hours   = String(now.getHours()).padStart(2,'0');
        const minutes = String(now.getMinutes()).padStart(2,'0');
        const seconds = String(now.getSeconds()).padStart(2,'0');
        document.getElementById('clock-time').textContent = `${hours}:${minutes}:${seconds} WITA`;

        const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        document.getElementById('clock-date').textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Live Countdown Timer Ticks
    function updateCountdowns() {
        const now = new Date().getTime();
        document.querySelectorAll('.live-countdown').forEach(el => {
            const endTimeStr = el.getAttribute('data-end-time');
            if (!endTimeStr) return;
            const endTime = new Date(endTimeStr).getTime();
            const diff = endTime - now;

            const valEl = el.querySelector('.countdown-val');
            if (!valEl) return;

            if (diff <= 0) {
                valEl.textContent = 'Selesai';
                return;
            }

            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            const hours   = Math.floor(diff / (1000 * 60 * 60));

            if (hours > 0) {
                valEl.textContent = `Sisa ${hours}j ${minutes}m ${seconds}s`;
            } else {
                valEl.textContent = `Sisa ${minutes}m ${seconds}s`;
            }
        });
    }
    updateCountdowns();
    setInterval(updateCountdowns, 1000);

    // AJAX Background Polling (Every 15 Seconds)
    function fetchDisplayData() {
        fetch('/api/display-data')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('last-update').textContent = `Terakhir Diperbarui: ${new Date().toLocaleTimeString('id-ID')} WITA`;
                }
            })
            .catch(err => console.log('Display refresh exception', err));
    }
    setInterval(fetchDisplayData, 15000);
    </script>
</body>
</html>
