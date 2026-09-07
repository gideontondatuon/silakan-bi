import React, { useEffect, useState } from 'react';
import { bookingService } from '../../services/bookingService';
import { Ruangan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';

export const KalenderPage: React.FC = () => {
    const [ruanganList, setRuanganList] = useState<Ruangan[]>([]);
    const [selectedRuanganId, setSelectedRuanganId] = useState('');
    const [events, setEvents] = useState<any[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    // Current calendar month view state
    const [currentDate, setCurrentDate] = useState(new Date());
    const [selectedEvent, setSelectedEvent] = useState<any | null>(null);

    useEffect(() => {
        bookingService.getRuanganList(true).then((res) => {
            if (res.status === 'success') setRuanganList(res.data);
        });
    }, []);

    const loadEvents = async () => {
        setIsLoading(true);
        try {
            const data = await bookingService.getKalenderEvents(selectedRuanganId || undefined);
            setEvents(data);
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadEvents();
    }, [selectedRuanganId]);

    // Calendar grid calculations
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    const firstDayIndex = new Date(year, month, 1).getDay(); // 0 = Sun, 1 = Mon ...
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    const dayHeaders = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    const prevMonth = () => {
        setCurrentDate(new Date(year, month - 1, 1));
    };

    const nextMonth = () => {
        setCurrentDate(new Date(year, month + 1, 1));
    };

    const goToday = () => {
        setCurrentDate(new Date());
    };

    // Filter events for specific day
    const getEventsForDay = (day: number) => {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return events.filter((e) => e.start.startsWith(dateStr));
    };

    const isToday = (day: number) => {
        const today = new Date();
        return (
            today.getDate() === day &&
            today.getMonth() === month &&
            today.getFullYear() === year
        );
    };

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className="bi bi-calendar3" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Kalender Pemakaian Ruangan
                    </h1>
                    <p>Jadwal seluruh kegiatan rapat dan hari libur nasional di KPwBI Sulut</p>
                </div>
            </div>

            {/* Filter Bar & Controls */}
            <div
                style={{
                    background: '#fff',
                    borderRadius: '16px',
                    padding: '16px 24px',
                    border: '1px solid #e2e8f0',
                    marginBottom: '20px',
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    flexWrap: 'wrap',
                    gap: '14px',
                }}
            >
                {/* Navigation controls */}
                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <button type="button" className="btn-secondary" onClick={prevMonth} style={{ padding: '6px 12px' }}>
                        <i className="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" className="btn-secondary" onClick={goToday} style={{ padding: '6px 12px', fontSize: '13px' }}>
                        Hari Ini
                    </button>
                    <button type="button" className="btn-secondary" onClick={nextMonth} style={{ padding: '6px 12px' }}>
                        <i className="bi bi-chevron-right"></i>
                    </button>
                    <h2 style={{ fontSize: '18px', fontWeight: 800, color: '#003b73', margin: '0 0 0 10px' }}>
                        {monthNames[month]} {year}
                    </h2>
                </div>

                {/* Filter Room */}
                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <label style={{ fontSize: '13px', fontWeight: 600, color: '#475569' }}>Filter Ruangan:</label>
                    <select
                        className="login-input"
                        value={selectedRuanganId}
                        onChange={(e) => setSelectedRuanganId(e.target.value)}
                        style={{ height: '38px', minWidth: '180px', fontSize: '13px' }}
                    >
                        <option value="">Semua Ruangan</option>
                        {ruanganList.map((r) => (
                            <option key={r.id} value={r.id}>
                                {r.nama_ruangan}
                            </option>
                        ))}
                    </select>
                </div>
            </div>

            {/* Calendar Grid View */}
            <div
                style={{
                    background: '#fff',
                    borderRadius: '16px',
                    border: '1px solid #e2e8f0',
                    overflow: 'hidden',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.03)',
                }}
            >
                {isLoading ? (
                    <LoadingSpinner message="Memuat agenda kalender..." height="400px" />
                ) : (
                    <div style={{ display: 'flex', flexDirection: 'column' }}>
                        {/* Day Header */}
                        <div
                            style={{
                                display: 'grid',
                                gridTemplateColumns: 'repeat(7, 1fr)',
                                background: '#f8fafc',
                                borderBottom: '1px solid #e2e8f0',
                                textAlign: 'center',
                                fontWeight: 700,
                                fontSize: '13px',
                                color: '#475569',
                                padding: '12px 0',
                            }}
                        >
                            {dayHeaders.map((dh, idx) => (
                                <div key={dh} style={{ color: idx === 0 ? '#ef4444' : '#475569' }}>
                                    {dh}
                                </div>
                            ))}
                        </div>

                        {/* Days Grid */}
                        <div
                            style={{
                                display: 'grid',
                                gridTemplateColumns: 'repeat(7, 1fr)',
                                gridAutoRows: 'minmax(110px, auto)',
                            }}
                        >
                            {/* Empty days before 1st of month */}
                            {Array.from({ length: firstDayIndex }).map((_, idx) => (
                                <div
                                    key={`empty-${idx}`}
                                    style={{
                                        background: '#fafafa',
                                        borderRight: '1px solid #f1f5f9',
                                        borderBottom: '1px solid #f1f5f9',
                                    }}
                                />
                            ))}

                            {/* Month Days */}
                            {Array.from({ length: daysInMonth }).map((_, idx) => {
                                const dayNum = idx + 1;
                                const dayEvents = getEventsForDay(dayNum);
                                const today = isToday(dayNum);

                                return (
                                    <div
                                        key={`day-${dayNum}`}
                                        style={{
                                            borderRight: '1px solid #f1f5f9',
                                            borderBottom: '1px solid #f1f5f9',
                                            padding: '8px',
                                            background: today ? '#f0f9ff' : '#ffffff',
                                            display: 'flex',
                                            flexDirection: 'column',
                                            gap: '4px',
                                        }}
                                    >
                                        <div
                                            style={{
                                                display: 'flex',
                                                justifyContent: 'space-between',
                                                alignItems: 'center',
                                                marginBottom: '4px',
                                            }}
                                        >
                                            <span
                                                style={{
                                                    fontSize: '12px',
                                                    fontWeight: today ? 800 : 600,
                                                    color: today ? '#0284c7' : '#334155',
                                                    width: today ? '22px' : 'auto',
                                                    height: today ? '22px' : 'auto',
                                                    borderRadius: '50%',
                                                    display: 'inline-flex',
                                                    alignItems: 'center',
                                                    justifyContent: 'center',
                                                    background: today ? '#bae6fd' : 'transparent',
                                                }}
                                            >
                                                {dayNum}
                                            </span>
                                        </div>

                                        {/* Events list */}
                                        <div style={{ display: 'flex', flexDirection: 'column', gap: '3px', overflowY: 'auto' }}>
                                            {dayEvents.slice(0, 3).map((ev) => (
                                                <div
                                                    key={ev.id}
                                                    onClick={() => setSelectedEvent(ev)}
                                                    style={{
                                                        fontSize: '11px',
                                                        padding: '3px 6px',
                                                        borderRadius: '4px',
                                                        background: ev.backgroundColor || '#005baa',
                                                        color: '#ffffff',
                                                        cursor: 'pointer',
                                                        whiteSpace: 'nowrap',
                                                        overflow: 'hidden',
                                                        textOverflow: 'ellipsis',
                                                        fontWeight: 600,
                                                    }}
                                                    title={ev.title}
                                                >
                                                    {ev.title}
                                                </div>
                                            ))}
                                            {dayEvents.length > 3 && (
                                                <span style={{ fontSize: '10.5px', color: '#64748b', fontWeight: 600 }}>
                                                    +{dayEvents.length - 3} lainnya
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                )}
            </div>

            {/* Event Detail Modal */}
            <Modal
                isOpen={!!selectedEvent}
                onClose={() => setSelectedEvent(null)}
                title={selectedEvent?.type === 'holiday' ? 'Hari Libur / Cuti Bersama' : 'Detail Agenda Rapat'}
                footer={
                    <button type="button" className="btn-secondary" onClick={() => setSelectedEvent(null)}>
                        Tutup
                    </button>
                }
            >
                {selectedEvent?.type === 'holiday' ? (
                    <div>
                        <h4 style={{ color: '#003b73', fontSize: '16px', marginBottom: '8px' }}>
                            {selectedEvent.title}
                        </h4>
                        <p style={{ color: '#64748b', fontSize: '13.5px' }}>
                            Tanggal: <strong>{selectedEvent.extendedProps?.tanggal}</strong>
                        </p>
                    </div>
                ) : (
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '10px', fontSize: '13.5px' }}>
                        <div>
                            <span style={{ color: '#64748b', fontSize: '12px', display: 'block' }}>Judul Agenda:</span>
                            <strong style={{ fontSize: '15px', color: '#003b73' }}>
                                {selectedEvent?.extendedProps?.judul}
                            </strong>
                        </div>
                        <div>
                            <span style={{ color: '#64748b', fontSize: '12px', display: 'block' }}>Ruangan:</span>
                            <strong>{selectedEvent?.extendedProps?.ruangan}</strong> ({selectedEvent?.extendedProps?.lokasi})
                        </div>
                        <div>
                            <span style={{ color: '#64748b', fontSize: '12px', display: 'block' }}>Waktu Pelaksanaan:</span>
                            <strong style={{ color: '#005baa' }}>{selectedEvent?.extendedProps?.waktu}</strong>
                            <div style={{ fontSize: '12px', color: '#64748b' }}>{selectedEvent?.extendedProps?.tanggal}</div>
                        </div>
                        <div>
                            <span style={{ color: '#64748b', fontSize: '12px', display: 'block' }}>Unit Pemohon & PIC:</span>
                            <strong>{selectedEvent?.extendedProps?.unit}</strong> &mdash; {selectedEvent?.extendedProps?.pic}
                        </div>
                        <div>
                            <span style={{ color: '#64748b', fontSize: '12px', display: 'block' }}>Peserta:</span>
                            <strong>{selectedEvent?.extendedProps?.tamu} Orang</strong>
                        </div>
                    </div>
                )}
            </Modal>
        </div>
    );
};
