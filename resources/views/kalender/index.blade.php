<x-app-layout>

<div class="dashboard-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <h1><i class="bi bi-calendar3" style="color:#005baa;margin-right:8px;"></i>Kalender Ruangan</h1>
        <p>Monitoring jadwal penggunaan ruangan kantor secara visual dan terpusat.</p>
    </div>

    {{-- Room Filter Dropdown --}}
    <div style="display:flex;align-items:center;gap:10px;background:#ffffff;padding:8px 16px;border-radius:12px;border:1px solid #cbd5e1;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <i class="bi bi-funnel-fill" style="color:#005baa;font-size:16px;"></i>
        <label for="filter-ruangan" style="font-size:13px;font-weight:700;color:#334155;white-space:nowrap;margin:0;">Filter Ruangan:</label>
        <select id="filter-ruangan" style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#003b73;background:#f8fafc;outline:none;cursor:pointer;">
            <option value="">-- Seluruh Ruangan Rapat --</option>
            @foreach($ruangans as $r)
                <option value="{{ $r->id }}">{{ $r->nama_ruangan }} ({{ $r->kapasitas }} Org)</option>
            @endforeach
        </select>
    </div>
</div>

<div class="calendar-summary">
    <div class="calendar-stat">
        <div class="calendar-stat-icon">
            <i class="bi bi-building"></i>
        </div>
        <div>
            <span>Total Ruangan</span>
            <strong>{{ \App\Models\Ruangan::count() }}</strong>
        </div>
    </div>

    <div class="calendar-stat">
        <div class="calendar-stat-icon">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div>
            <span>Jadwal Aktif</span>
            <strong>{{ \App\Models\Pemesanan::approved()->count() }}</strong>
        </div>
    </div>

    <div class="calendar-stat">
        <div class="calendar-stat-icon">
            <i class="bi bi-calendar-event"></i>
        </div>
        <div>
            <span>Akan Datang</span>
            <strong>{{ \App\Models\Pemesanan::approved()->upcoming()->count() }}</strong>
        </div>
    </div>
</div>

<div class="calendar-layout">
    <div class="calendar-main">
        <div id="calendar"></div>
    </div>

    <div class="calendar-sidebar" style="overflow:hidden;box-sizing:border-box;">
        <h3><i class="bi bi-calendar-event" style="color:#005baa;margin-right:6px;"></i> Jadwal yang Akan Datang</h3>

        @php
            $upcomingSchedule = \App\Models\Pemesanan::with(['ruangan', 'layout', 'user'])
                ->approved()
                ->upcoming()
                ->orderBy('tanggal_kegiatan', 'asc')
                ->orderBy('waktu_mulai', 'asc')
                ->take(10)
                ->get();
        @endphp

        @if($upcomingSchedule->count())
            @foreach($upcomingSchedule as $item)
            <div style="margin-bottom:12px;padding:12px 14px;border-radius:12px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 2px 6px rgba(0,0,0,0.03);box-sizing:border-box;max-width:100%;overflow:hidden;transition:all .2s;">
                {{-- Header: Ruangan & Tanggal Badge --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:6px;margin-bottom:8px;">
                    <span style="font-size:11.5px;font-weight:700;color:#005baa;background:#e0f2fe;padding:3px 8px;border-radius:6px;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <i class="bi bi-door-open-fill"></i> {{ $item->ruangan->nama_ruangan ?? '-' }}
                    </span>
                    <span style="font-size:11px;font-weight:700;color:#0369a1;background:#f0f9ff;border:1px solid #bae6fd;padding:2px 8px;border-radius:6px;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
                        <i class="bi bi-calendar3"></i> {{ $item->tanggal_kegiatan ? $item->tanggal_kegiatan->translatedFormat('d M Y') : '-' }}
                    </span>
                </div>

                {{-- Judul Kegiatan --}}
                <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:8px;line-height:1.35;word-break:break-word;">
                    {{ $item->judul_kegiatan }}
                </div>

                {{-- Footer Info: Waktu & PIC --}}
                <div style="display:flex;flex-direction:column;gap:4px;font-size:11.5px;color:#64748b;padding-top:8px;border-top:1px dashed #e2e8f0;">
                    <div style="display:flex;align-items:center;gap:6px;color:#334155;font-weight:600;">
                        <i class="bi bi-clock-fill" style="color:#0284c7;font-size:12px;"></i>
                        <span>{{ $item->waktu_mulai }} – {{ $item->waktu_selesai }} WITA</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;color:#64748b;overflow:hidden;">
                        <i class="bi bi-person-fill" style="color:#64748b;font-size:12px;flex-shrink:0;"></i>
                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->pic_kegiatan }} ({{ $item->user?->nama_unit ?? 'Unit' }})</span>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-schedule">
                <i class="bi bi-calendar-x"></i>
                <p>Tidak ada jadwal penggunaan ruangan yang akan datang.</p>
            </div>
        @endif
    </div>
</div>

{{-- Enhanced Event Modal --}}
<div id="modalKegiatan" class="custom-modal-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.65);backdrop-filter:blur(6px);z-index:99999;align-items:center;justify-content:center;padding:16px;">
    <div class="custom-modal-box" style="background:#fff;width:100%;max-width:540px;border-radius:16px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);overflow:hidden;animation:modalScaleIn .2s ease-out;">
        <div style="padding:18px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;background:#f8fafc;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div id="modalEventIcon" style="width:38px;height:38px;border-radius:50%;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:18px;">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div>
                    <h3 id="modalEventTitle" style="margin:0;font-size:16px;font-weight:700;color:#0f172a;">Rincian Jadwal</h3>
                    <p id="modalEventSubtitle" style="margin:2px 0 0;font-size:12px;color:#64748b;">Sistem SILAKAN Bank Indonesia</p>
                </div>
            </div>
            <button type="button" onclick="closeCalendarModal()" style="background:none;border:none;font-size:22px;color:#64748b;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <div id="detailKegiatan" style="padding:22px 24px;">
        </div>

        <div style="padding:14px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:flex-end;gap:10px;">
            <button type="button" class="btn-secondary" onclick="closeCalendarModal()" style="padding:8px 18px;font-size:13px;border-radius:8px;">
                Tutup
            </button>
            <a id="modalBtnDetail" href="#" class="btn-primary" style="padding:8px 18px;font-size:13px;border-radius:8px;display:none;">
                <i class="bi bi-box-arrow-up-right"></i> Buka Detail Lengkap
            </a>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<style>
.fc-day-sat, .fc-day-sun {
    background-color: rgba(254, 242, 242, 0.45) !important;
}
.fc-day-sat .fc-daygrid-day-number, .fc-day-sun .fc-daygrid-day-number {
    color: #dc2626 !important;
    font-weight: 800;
}
.fc-col-header-cell.fc-day-sat, .fc-col-header-cell.fc-day-sun {
    background-color: #fee2e2 !important;
    color: #991b1b !important;
}
.fc-event {
    cursor: pointer;
    border-radius: 6px !important;
    padding: 2px 5px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    transition: transform 0.15s ease;
}
.fc-event:hover {
    transform: scale(1.02);
    filter: brightness(1.08);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
let calendar = null;

function closeCalendarModal() {
    const m = document.getElementById('modalKegiatan');
    if (m) m.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function(){
    const calendarEl = document.getElementById('calendar');
    const filterSelect = document.getElementById('filter-ruangan');

    function getEventsUrl() {
        const roomId = filterSelect ? filterSelect.value : '';
        const base = "{{ route('kalender.events') }}";
        return roomId ? `${base}?ruangan_id=${roomId}` : base;
    }

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 720,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu'
        },
        events: getEventsUrl(),
        eventDidMount: function(info) {
            if (info.event.extendedProps.is_nasional !== undefined) {
                info.el.style.fontWeight = '700';
            }
        },
        eventClick: function(info) {
            const props = info.event.extendedProps;
            const modal = document.getElementById('modalKegiatan');
            const titleEl = document.getElementById('modalEventTitle');
            const iconEl = document.getElementById('modalEventIcon');
            const bodyEl = document.getElementById('detailKegiatan');
            const btnDetail = document.getElementById('modalBtnDetail');

            if (!modal) return;

            if (props.kategori !== undefined || props.is_nasional !== undefined) {
                // Holiday Event
                const isCuti = props.kategori === 'cuti_bersama';
                const isInternal = props.kategori === 'internal';
                const labelText = props.kategori_label || (isCuti ? 'Cuti Bersama' : 'Hari Libur Nasional');
                const icon = isCuti ? '🏖️' : (isInternal ? '🏛️' : '🚩');

                iconEl.style.background = isCuti ? '#fef3c7' : (isInternal ? '#e0f2fe' : '#fee2e2');
                iconEl.style.color = isCuti ? '#d97706' : (isInternal ? '#0284c7' : '#dc2626');
                iconEl.innerHTML = icon;

                titleEl.textContent = labelText;
                btnDetail.style.display = 'none';

                bodyEl.innerHTML = `
                    <div style="padding:14px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:14px;">
                        <span style="font-size:11.5px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Keterangan Hari Libur</span>
                        <h4 style="margin:4px 0 0;font-size:16px;color:#003b73;font-weight:800;">${props.keterangan}</h4>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:13px;">
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Tanggal</span>
                            <strong style="color:#0f172a;">${props.tanggal || info.event.startStr}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Status</span>
                            <span class="badge ${isCuti ? 'badge-warning' : (isInternal ? 'badge-info' : 'badge-danger')}">${labelText}</span>
                        </div>
                    </div>
                `;
            } else {
                // Meeting Booking Event
                iconEl.style.background = '#e0f2fe';
                iconEl.style.color = '#0284c7';
                iconEl.innerHTML = '<i class="bi bi-calendar-check-fill"></i>';

                titleEl.textContent = props.ruangan || 'Jadwal Ruangan';

                const waHtml = props.no_wa_pic
                    ? `<a href="https://wa.me/${props.no_wa_pic.replace(/[^0-9]/g,'')}" target="_blank" style="color:#16a34a;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px;"><i class="bi bi-whatsapp"></i> ${props.no_wa_pic}</a>`
                    : '<span style="color:#94a3b8;">-</span>';

                bodyEl.innerHTML = `
                    <div style="padding:14px;background:#f0f9ff;border-radius:12px;border:1px solid #bae6fd;margin-bottom:14px;">
                        <span style="font-size:11.5px;color:#0369a1;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Agenda / Kegiatan</span>
                        <h4 style="margin:4px 0 0;font-size:15.5px;color:#003b73;font-weight:800;">${props.judul || info.event.title}</h4>
                        <span style="display:inline-block;margin-top:6px;font-family:monospace;font-size:11px;color:#0284c7;font-weight:700;">Kode: ${props.kode_pemesanan || '-'}</span>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:13px;background:#f8fafc;padding:14px;border-radius:12px;border:1px solid #e2e8f0;">
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Ruangan</span>
                            <strong style="color:#005baa;">${props.ruangan}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Layout</span>
                            <strong style="color:#0f172a;">${props.layout}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Tanggal</span>
                            <strong style="color:#0f172a;">${props.tanggal || info.event.startStr}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Waktu (WITA)</span>
                            <strong style="color:#0f172a;"><i class="bi bi-clock"></i> ${props.waktu}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">PIC Kegiatan</span>
                            <strong style="color:#0f172a;">${props.pic}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">No. WhatsApp PIC</span>
                            ${waHtml}
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Unit Kerja</span>
                            <strong style="color:#0f172a;">${props.unit}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b;font-size:11.5px;display:block;">Jumlah Tamu</span>
                            <strong style="color:#0f172a;">${props.tamu || '-'} Orang</strong>
                        </div>
                    </div>
                `;

                if (props.booking_id) {
                    btnDetail.href = `{{ url('/pemesanan') }}/${props.booking_id}`;
                    btnDetail.style.display = 'inline-flex';
                } else {
                    btnDetail.style.display = 'none';
                }
            }

            modal.style.display = 'flex';
        }
    });

    calendar.render();

    // Filter change handler
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            calendar.removeAllEventSources();
            calendar.addEventSource(getEventsUrl());
        });
    }
});
</script>
@endpush
</x-app-layout>