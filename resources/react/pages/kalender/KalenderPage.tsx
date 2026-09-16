import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { bookingService, KalenderIndexData } from '../../services/bookingService';
import { useAuth } from '../../context/AuthContext';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';

export const KalenderPage: React.FC = () => {
    const { user } = useAuth();
    const isAdmin = user?.role === 'admin';

    const [indexData, setIndexData] = useState<KalenderIndexData | null>(null);
    const [selectedRuanganId, setSelectedRuanganId] = useState('');
    const [events, setEvents] = useState<any[]>([]);
    const [isLoading, setIsLoading] = useState(true);

    // Current calendar month / week view state
    const [currentDate, setCurrentDate] = useState(new Date());
    const [calendarView, setCalendarView] = useState<'month' | 'week'>('month');
    const [selectedEvent, setSelectedEvent] = useState<any | null>(null);
    const [hoveredEventData, setHoveredEventData] = useState<{
        event: any;
        rect: DOMRect;
    } | null>(null);

    // Dismiss hover tooltip on window scroll
    useEffect(() => {
        const handleScroll = () => setHoveredEventData(null);
        window.addEventListener('scroll', handleScroll, true);
        return () => window.removeEventListener('scroll', handleScroll, true);
    }, []);

    // Load initial index data (rooms, summary stats, upcoming bookings)
    useEffect(() => {
        bookingService.getKalenderIndex().then((res) => {
            if (res.status === 'success' && res.data) {
                setIndexData(res.data);
            }
        });
    }, []);

    // Load calendar events
    const loadEvents = async () => {
        setIsLoading(true);
        try {
            const data = await bookingService.getKalenderEvents(selectedRuanganId || undefined);
            setEvents(Array.isArray(data) ? data : []);
        } catch (e) {
            console.error('Failed to load calendar events:', e);
            setEvents([]);
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

    const firstDayIndex = new Date(year, month, 1).getDay(); // 0 = Min, 1 = Sen ...
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    const dayHeaders = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    const prevMonth = () => {
        if (calendarView === 'month') {
            setCurrentDate(new Date(year, month - 1, 1));
        } else {
            const prev = new Date(currentDate);
            prev.setDate(prev.getDate() - 7);
            setCurrentDate(prev);
        }
    };

    const nextMonth = () => {
        if (calendarView === 'month') {
            setCurrentDate(new Date(year, month + 1, 1));
        } else {
            const next = new Date(currentDate);
            next.setDate(next.getDate() + 7);
            setCurrentDate(next);
        }
    };

    const goToday = () => {
        setCurrentDate(new Date());
    };

    // Filter events for specific day (YYYY-MM-DD)
    const getEventsForDate = (dateStr: string) => {
        if (!Array.isArray(events)) return [];
        return events.filter((e) => {
            if (!e || !e.start) return false;
            return e.start.startsWith(dateStr);
        });
    };

    const getEventsForDay = (day: number) => {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return getEventsForDate(dateStr);
    };

    const isToday = (day: number) => {
        const today = new Date();
        return (
            today.getDate() === day &&
            today.getMonth() === month &&
            today.getFullYear() === year
        );
    };

    // Calculate start of current week for week view
    const getWeekDays = () => {
        const curr = new Date(currentDate);
        const dayOfWeek = curr.getDay(); // 0 = Sun
        const sunday = new Date(curr);
        sunday.setDate(curr.getDate() - dayOfWeek);

        const days: Date[] = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(sunday);
            d.setDate(sunday.getDate() + i);
            days.push(d);
        }
        return days;
    };

    const ruanganList = indexData?.ruangan || [];
    const stats = indexData?.stats || {
        total_ruangan: ruanganList.length,
        jadwal_aktif: 0,
        akan_datang: 0,
    };
    const upcomingSchedule = indexData?.upcoming || [];

    return (
        <div>
            {/* Header matching Blade 1:1 */}
            <div
                className="dashboard-header"
                style={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    flexWrap: 'wrap',
                    gap: '12px',
                    marginBottom: '10px',
                }}
            >
                <div>
                    <h1 style={{ fontSize: '18px', margin: 0, display: 'flex', alignItems: 'center' }}>
                        <i className="bi bi-calendar3" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Kalender Ruangan
                    </h1>
                    <p style={{ margin: '2px 0 0', fontSize: '12px', color: '#64748b' }}>Monitoring jadwal penggunaan ruangan kantor secara visual dan terpusat.</p>
                </div>

                {/* Room Filter Dropdown */}
                <div
                    style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '8px',
                        background: '#ffffff',
                        padding: '4px 12px',
                        borderRadius: '10px',
                        border: '1px solid #cbd5e1',
                        boxShadow: '0 1px 4px rgba(0,0,0,0.03)',
                        flexWrap: 'wrap',
                        maxWidth: '100%',
                    }}
                >
                    <i className="bi bi-funnel-fill" style={{ color: '#005baa', fontSize: '13px' }}></i>
                    <label
                        htmlFor="filter-ruangan"
                        style={{ fontSize: '12px', fontWeight: 700, color: '#334155', whiteSpace: 'nowrap', margin: 0 }}
                    >
                        Filter Ruangan:
                    </label>
                    <select
                        id="filter-ruangan"
                        value={selectedRuanganId}
                        onChange={(e) => setSelectedRuanganId(e.target.value)}
                        style={{
                            padding: '4px 10px',
                            border: '1px solid #e2e8f0',
                            borderRadius: '6px',
                            fontSize: '12.5px',
                            fontWeight: 600,
                            color: '#003b73',
                            background: '#f8fafc',
                            outline: 'none',
                            cursor: 'pointer',
                            flex: 1,
                            minWidth: '150px',
                        }}
                    >
                        <option value="">-- Seluruh Ruangan Rapat --</option>
                        {ruanganList.map((r) => (
                            <option key={r.id} value={r.id}>
                                {r.nama_ruangan} ({r.kapasitas} Org)
                            </option>
                        ))}
                    </select>
                </div>
            </div>

            {/* Calendar Summary Stats matching Blade 1:1 - Compact Mode */}
            <div className="calendar-summary" style={{ marginBottom: '10px', gap: '10px', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(170px, 1fr))' }}>
                <div className="calendar-stat" style={{ padding: '8px 14px', gap: '10px', borderRadius: '10px' }}>
                    <div className="calendar-stat-icon" style={{ width: '32px', height: '32px', fontSize: '15px', borderRadius: '8px' }}>
                        <i className="bi bi-building"></i>
                    </div>
                    <div>
                        <span style={{ fontSize: '10.5px' }}>Total Ruangan</span>
                        <strong style={{ fontSize: '17px', marginTop: '1px' }}>{stats.total_ruangan}</strong>
                    </div>
                </div>

                <div className="calendar-stat" style={{ padding: '8px 14px', gap: '10px', borderRadius: '10px' }}>
                    <div className="calendar-stat-icon" style={{ width: '32px', height: '32px', fontSize: '15px', borderRadius: '8px' }}>
                        <i className="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <span style={{ fontSize: '10.5px' }}>Jadwal Aktif</span>
                        <strong style={{ fontSize: '17px', marginTop: '1px' }}>{stats.jadwal_aktif}</strong>
                    </div>
                </div>

                <div className="calendar-stat" style={{ padding: '8px 14px', gap: '10px', borderRadius: '10px' }}>
                    <div className="calendar-stat-icon" style={{ width: '32px', height: '32px', fontSize: '15px', borderRadius: '8px' }}>
                        <i className="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <span style={{ fontSize: '10.5px' }}>Akan Datang</span>
                        <strong style={{ fontSize: '17px', marginTop: '1px' }}>{stats.akan_datang}</strong>
                    </div>
                </div>
            </div>

            {/* Calendar Layout: Main + Sidebar matching Blade 1:1 */}
            <div className="calendar-layout" style={{ gap: '16px' }}>
                {/* Main Calendar View */}
                <div className="calendar-main" style={{ padding: '14px 16px', borderRadius: '12px' }}>
                    {/* Toolbar */}
                    <div
                        style={{
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'center',
                            flexWrap: 'wrap',
                            gap: '10px',
                            marginBottom: '10px',
                        }}
                    >
                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                            <button
                                type="button"
                                className="btn-secondary"
                                onClick={prevMonth}
                                style={{ padding: '6px 12px', borderRadius: '8px' }}
                                title="Sebelumnya"
                            >
                                <i className="bi bi-chevron-left"></i>
                            </button>
                            <button
                                type="button"
                                className="btn-secondary"
                                onClick={goToday}
                                style={{ padding: '6px 14px', fontSize: '13px', borderRadius: '8px' }}
                            >
                                Hari Ini
                            </button>
                            <button
                                type="button"
                                className="btn-secondary"
                                onClick={nextMonth}
                                style={{ padding: '6px 12px', borderRadius: '8px' }}
                                title="Selanjutnya"
                            >
                                <i className="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <h2 style={{ fontSize: '18px', fontWeight: 800, color: '#003b73', margin: 0 }}>
                            {monthNames[month]} {year}
                        </h2>

                        <div style={{ display: 'flex', gap: '4px', background: '#f1f5f9', padding: '3px', borderRadius: '8px' }}>
                            <button
                                type="button"
                                onClick={() => setCalendarView('month')}
                                style={{
                                    border: 'none',
                                    padding: '5px 14px',
                                    borderRadius: '6px',
                                    fontSize: '12.5px',
                                    fontWeight: 700,
                                    cursor: 'pointer',
                                    background: calendarView === 'month' ? '#005baa' : 'transparent',
                                    color: calendarView === 'month' ? '#ffffff' : '#475569',
                                    transition: 'all .2s',
                                }}
                            >
                                Bulan
                            </button>
                            <button
                                type="button"
                                onClick={() => setCalendarView('week')}
                                style={{
                                    border: 'none',
                                    padding: '5px 14px',
                                    borderRadius: '6px',
                                    fontSize: '12.5px',
                                    fontWeight: 700,
                                    cursor: 'pointer',
                                    background: calendarView === 'week' ? '#005baa' : 'transparent',
                                    color: calendarView === 'week' ? '#ffffff' : '#475569',
                                    transition: 'all .2s',
                                }}
                            >
                                Minggu
                            </button>
                        </div>
                    </div>

                    {isLoading ? (
                        <LoadingSpinner message="Memuat agenda kalender..." height="400px" />
                    ) : calendarView === 'month' ? (
                        /* Month View */
                        <div style={{ border: '1px solid #e2e8f0', borderRadius: '12px', overflowX: 'auto', WebkitOverflowScrolling: 'touch' }}>
                            <div style={{ minWidth: '680px' }}>
                                {/* Day Header */}
                                <div
                                    style={{
                                        display: 'grid',
                                        gridTemplateColumns: 'repeat(7, 1fr)',
                                        background: '#f8fafc',
                                        borderBottom: '1px solid #e2e8f0',
                                        textAlign: 'center',
                                        fontWeight: 700,
                                        fontSize: '11.5px',
                                        color: '#475569',
                                    }}
                                >
                                    {dayHeaders.map((dh, idx) => {
                                        const isWeekend = idx === 0 || idx === 6;
                                        return (
                                            <div
                                                key={dh}
                                                style={{
                                                    padding: '6px 0',
                                                    background: isWeekend ? '#fee2e2' : '#f8fafc',
                                                    color: isWeekend ? '#991b1b' : '#475569',
                                                    borderRight: idx < 6 ? '1px solid #e2e8f0' : 'none',
                                                }}
                                            >
                                                {dh}
                                            </div>
                                        );
                                    })}
                                </div>

                                {/* Days Grid */}
                                <div
                                    style={{
                                        display: 'grid',
                                        gridTemplateColumns: 'repeat(7, 1fr)',
                                        gridAutoRows: 'minmax(58px, auto)',
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
                                        const dayOfWeek = (firstDayIndex + idx) % 7;
                                        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

                                        return (
                                            <div
                                                key={`day-${dayNum}`}
                                                style={{
                                                    borderRight: dayOfWeek === 6 ? 'none' : '1px solid #f1f5f9',
                                                    borderBottom: '1px solid #f1f5f9',
                                                    padding: '3px 5px',
                                                    background: today
                                                        ? '#f0f9ff'
                                                        : isWeekend
                                                        ? 'rgba(254, 242, 242, 0.45)'
                                                        : '#ffffff',
                                                    display: 'flex',
                                                    flexDirection: 'column',
                                                    gap: '2px',
                                                    minHeight: '58px',
                                                    boxSizing: 'border-box',
                                                }}
                                            >
                                                <div
                                                    style={{
                                                        display: 'flex',
                                                        justifyContent: 'space-between',
                                                        alignItems: 'center',
                                                        lineHeight: 1,
                                                    }}
                                                >
                                                    <span
                                                        style={{
                                                            fontSize: '11.5px',
                                                            fontWeight: today ? 800 : 700,
                                                            color: today ? '#0284c7' : isWeekend ? '#dc2626' : '#1e293b',
                                                            display: 'inline-flex',
                                                            alignItems: 'center',
                                                            justifyContent: 'center',
                                                            width: today ? '20px' : 'auto',
                                                            height: today ? '20px' : 'auto',
                                                            borderRadius: today ? '50%' : '0',
                                                            background: today ? '#e0f2fe' : 'transparent',
                                                        }}
                                                    >
                                                        {dayNum}
                                                    </span>
                                                    {dayEvents.some((e) => e.type === 'holiday') && (
                                                        <span
                                                            style={{
                                                                width: '5px',
                                                                height: '5px',
                                                                borderRadius: '50%',
                                                                background: '#ef4444',
                                                                display: 'inline-block',
                                                            }}
                                                            title="Hari Libur"
                                                        />
                                                    )}
                                                </div>

                                                {/* Event Badges: Cukup Jamnya Saja */}
                                                <div style={{ display: 'flex', flexDirection: 'column', gap: '2px' }}>
                                                    {dayEvents.slice(0, 3).map((ev) => {
                                                        const isBooking = ev.type === 'booking';
                                                        const timeDisplay = isBooking
                                                            ? (ev.extendedProps?.waktu || (ev.start ? ev.start.substring(11, 16) : 'Jadwal'))
                                                            : (ev.extendedProps?.kategori_label || 'Libur');

                                                        return (
                                                            <div
                                                                key={ev.id}
                                                                onClick={() => setSelectedEvent(ev)}
                                                                onMouseEnter={(e) => {
                                                                    setHoveredEventData({
                                                                        event: ev,
                                                                        rect: e.currentTarget.getBoundingClientRect(),
                                                                    });
                                                                }}
                                                                onMouseLeave={() => setHoveredEventData(null)}
                                                                style={{
                                                                    fontSize: '10px',
                                                                    padding: '1.5px 5px',
                                                                    borderRadius: '4px',
                                                                    background: ev.color || '#005baa',
                                                                    color: '#ffffff',
                                                                    cursor: 'pointer',
                                                                    whiteSpace: 'nowrap',
                                                                    overflow: 'hidden',
                                                                    textOverflow: 'ellipsis',
                                                                    fontWeight: 600,
                                                                    display: 'flex',
                                                                    alignItems: 'center',
                                                                    gap: '3px',
                                                                    lineHeight: 1.3,
                                                                    transition: 'transform 0.1s ease, filter 0.1s ease',
                                                                }}
                                                                title={`${ev.extendedProps?.judul || ev.title} (${timeDisplay})`}
                                                            >
                                                                <i
                                                                    className={isBooking ? 'bi bi-clock-fill' : 'bi bi-flag-fill'}
                                                                    style={{ fontSize: '8.5px', opacity: 0.9, flexShrink: 0 }}
                                                                ></i>
                                                                <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                                                    {timeDisplay}
                                                                </span>
                                                            </div>
                                                        );
                                                    })}
                                                    {dayEvents.length > 3 && (
                                                        <span
                                                            style={{
                                                                fontSize: '9.5px',
                                                                color: '#0284c7',
                                                                fontWeight: 700,
                                                                paddingLeft: '2px',
                                                                cursor: 'pointer',
                                                            }}
                                                            onClick={() => setSelectedEvent(dayEvents[3])}
                                                        >
                                                            +{dayEvents.length - 3} lainnya
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    ) : (
                        /* Week View */
                        <div style={{ border: '1px solid #e2e8f0', borderRadius: '12px', overflowX: 'auto', WebkitOverflowScrolling: 'touch' }}>
                            <div style={{ minWidth: '680px' }}>
                                <div
                                    style={{
                                        display: 'grid',
                                        gridTemplateColumns: 'repeat(7, 1fr)',
                                        background: '#f8fafc',
                                        borderBottom: '1px solid #e2e8f0',
                                        textAlign: 'center',
                                    }}
                                >
                                    {getWeekDays().map((d, idx) => {
                                        const isWeekend = idx === 0 || idx === 6;
                                        const isDayToday =
                                            d.getDate() === new Date().getDate() &&
                                            d.getMonth() === new Date().getMonth() &&
                                            d.getFullYear() === new Date().getFullYear();

                                        return (
                                            <div
                                                key={d.toISOString()}
                                                style={{
                                                    padding: '12px 6px',
                                                    background: isWeekend ? '#fee2e2' : isDayToday ? '#e0f2fe' : '#f8fafc',
                                                    color: isWeekend ? '#991b1b' : isDayToday ? '#0369a1' : '#334155',
                                                    borderRight: idx < 6 ? '1px solid #e2e8f0' : 'none',
                                                }}
                                            >
                                                <div style={{ fontSize: '12px', fontWeight: 700, textTransform: 'uppercase' }}>
                                                    {dayHeaders[idx]}
                                                </div>
                                                <div style={{ fontSize: '16px', fontWeight: 800, marginTop: '2px' }}>
                                                    {d.getDate()}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>

                                <div
                                    style={{
                                        display: 'grid',
                                        gridTemplateColumns: 'repeat(7, 1fr)',
                                        minHeight: '380px',
                                    }}
                                >
                                    {getWeekDays().map((d, idx) => {
                                        const dateStr = d.toISOString().split('T')[0];
                                        const dayEvents = getEventsForDate(dateStr);
                                        const isWeekend = idx === 0 || idx === 6;

                                        return (
                                            <div
                                                key={d.toISOString()}
                                                style={{
                                                    borderRight: idx < 6 ? '1px solid #f1f5f9' : 'none',
                                                    padding: '10px 8px',
                                                    background: isWeekend ? 'rgba(254, 242, 242, 0.45)' : '#ffffff',
                                                    display: 'flex',
                                                    flexDirection: 'column',
                                                    gap: '6px',
                                                }}
                                            >
                                                {dayEvents.length > 0 ? (
                                                    dayEvents.map((ev) => (
                                                        <div
                                                            key={ev.id}
                                                            onClick={() => setSelectedEvent(ev)}
                                                            onMouseEnter={(e) => {
                                                                setHoveredEventData({
                                                                    event: ev,
                                                                    rect: e.currentTarget.getBoundingClientRect(),
                                                                });
                                                            }}
                                                            onMouseLeave={() => setHoveredEventData(null)}
                                                            style={{
                                                                fontSize: '11.5px',
                                                                padding: '6px 8px',
                                                                borderRadius: '6px',
                                                                background: ev.color,
                                                                color: '#ffffff',
                                                                cursor: 'pointer',
                                                                boxShadow: '0 1px 3px rgba(0,0,0,0.1)',
                                                            }}
                                                            title={ev.title}
                                                        >
                                                            <div style={{ fontWeight: 700, marginBottom: '2px' }}>{ev.title}</div>
                                                            <div style={{ fontSize: '10.5px', opacity: 0.9 }}>
                                                                {ev.type === 'holiday'
                                                                    ? 'Hari Libur Nasional'
                                                                    : `${ev.raw?.waktu_mulai?.substring(0, 5)} - ${ev.raw?.waktu_selesai?.substring(0, 5)}`}
                                                            </div>
                                                        </div>
                                                    ))
                                                ) : (
                                                    <div style={{ color: '#cbd5e1', fontSize: '11px', textAlign: 'center', marginTop: '20px' }}>
                                                        Tidak ada agenda
                                                    </div>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    )}
                </div>

                {/* Sidebar: Jadwal yang Akan Datang - Compact & Always visible at 100% */}
                <div
                    className="calendar-sidebar"
                    style={{
                        maxHeight: 'calc(100vh - 120px)',
                        display: 'flex',
                        flexDirection: 'column',
                        padding: '14px',
                        position: 'sticky',
                        top: '14px',
                        borderRadius: '12px',
                        background: '#ffffff',
                        border: '1px solid #e2e8f0',
                        boxShadow: '0 1px 4px rgba(0,0,0,0.04)',
                        boxSizing: 'border-box',
                        overflow: 'hidden',
                    }}
                >
                    <div
                        style={{
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'space-between',
                            marginBottom: '10px',
                            paddingBottom: '8px',
                            borderBottom: '2px solid #e0f2fe',
                        }}
                    >
                        <h3 style={{ margin: 0, padding: 0, border: 'none', fontSize: '13.5px', fontWeight: 800, color: '#003b73', display: 'flex', alignItems: 'center', gap: '6px' }}>
                            <i className="bi bi-calendar-event" style={{ color: '#005baa' }}></i>
                            Jadwal yang Akan Datang
                        </h3>
                        <span
                            style={{
                                fontSize: '11px',
                                fontWeight: 700,
                                color: '#005baa',
                                background: '#e0f2fe',
                                padding: '2px 8px',
                                borderRadius: '10px',
                            }}
                        >
                            {upcomingSchedule.length}
                        </span>
                    </div>

                    <div
                        style={{
                            overflowY: 'auto',
                            flex: 1,
                            paddingRight: '3px',
                            display: 'flex',
                            flexDirection: 'column',
                            gap: '8px',
                        }}
                    >
                        {upcomingSchedule.length > 0 ? (
                            upcomingSchedule.map((item) => (
                                <div
                                    key={item.id}
                                    style={{
                                        padding: '9px 11px',
                                        borderRadius: '10px',
                                        background: '#ffffff',
                                        border: '1px solid #e2e8f0',
                                        boxShadow: '0 1px 3px rgba(0,0,0,0.02)',
                                        boxSizing: 'border-box',
                                        maxWidth: '100%',
                                        transition: 'all .2s',
                                    }}
                                >
                                    {/* Header: Ruangan & Tanggal Badge */}
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '6px', marginBottom: '6px' }}>
                                        <span
                                            style={{
                                                fontSize: '10.5px',
                                                fontWeight: 700,
                                                color: '#005baa',
                                                background: '#e0f2fe',
                                                padding: '2px 6px',
                                                borderRadius: '5px',
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                gap: '4px',
                                                whiteSpace: 'nowrap',
                                                overflow: 'hidden',
                                                textOverflow: 'ellipsis',
                                                maxWidth: '140px',
                                            }}
                                        >
                                            <i className="bi bi-door-open-fill"></i> {item.nama_ruangan}
                                        </span>
                                        <span
                                            style={{
                                                fontSize: '10.5px',
                                                fontWeight: 700,
                                                color: '#0369a1',
                                                background: '#f0f9ff',
                                                border: '1px solid #bae6fd',
                                                padding: '1px 6px',
                                                borderRadius: '5px',
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                gap: '3px',
                                                whiteSpace: 'nowrap',
                                            }}
                                        >
                                            <i className="bi bi-calendar3"></i> {item.tanggal_kegiatan}
                                        </span>
                                    </div>

                                    {/* Judul Kegiatan */}
                                    <div style={{ fontSize: '12px', fontWeight: 700, color: '#0f172a', marginBottom: '6px', lineHeight: 1.3, wordBreak: 'break-word' }}>
                                        {item.judul_kegiatan}
                                    </div>

                                    {/* Footer Info: Waktu & PIC */}
                                    <div style={{ display: 'flex', flexDirection: 'column', gap: '3px', fontSize: '11px', color: '#64748b', paddingTop: '6px', borderTop: '1px dashed #e2e8f0' }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '5px', color: '#334155', fontWeight: 600 }}>
                                            <i className="bi bi-clock-fill" style={{ color: '#0284c7', fontSize: '11px' }}></i>
                                            <span>{item.waktu_mulai} – {item.waktu_selesai} WITA</span>
                                        </div>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '5px', color: '#64748b', overflow: 'hidden' }}>
                                            <i className="bi bi-person-fill" style={{ color: '#64748b', fontSize: '11px', flexShrink: 0 }}></i>
                                            <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                                {item.pic_kegiatan} ({item.nama_unit})
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="empty-schedule" style={{ padding: '20px 10px' }}>
                                <i className="bi bi-calendar-x" style={{ fontSize: '28px', marginBottom: '6px' }}></i>
                                <p style={{ fontSize: '12px' }}>Tidak ada jadwal yang akan datang.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Hover Tooltip Popover (Muncul Otomatis saat Kursor Digeser ke Penanda Jam) */}
            {hoveredEventData && (() => {
                const ev = hoveredEventData.event;
                const rect = hoveredEventData.rect;
                const isBooking = ev.type === 'booking';

                const tooltipWidth = 310;
                let left = rect.left + rect.width / 2 - tooltipWidth / 2;
                if (left < 10) left = 10;
                if (left + tooltipWidth > window.innerWidth - 10) {
                    left = window.innerWidth - tooltipWidth - 10;
                }

                const tooltipEstHeight = isBooking ? 180 : 115;
                const fitsBelow = rect.bottom + tooltipEstHeight + 10 < window.innerHeight;
                const top = fitsBelow ? rect.bottom + 6 : Math.max(10, rect.top - tooltipEstHeight - 6);

                return (
                    <div
                        style={{
                            position: 'fixed',
                            top: `${top}px`,
                            left: `${left}px`,
                            width: `${tooltipWidth}px`,
                            background: '#ffffff',
                            borderRadius: '12px',
                            boxShadow: '0 12px 30px rgba(15, 23, 42, 0.2), 0 2px 8px rgba(0, 91, 170, 0.08)',
                            border: '1px solid #cbd5e1',
                            borderLeft: `5px solid ${ev.color || '#005baa'}`,
                            padding: '12px 14px',
                            zIndex: 99999,
                            pointerEvents: 'none',
                            boxSizing: 'border-box',
                            animation: 'fadeIn 0.1s ease-out',
                        }}
                    >
                        {isBooking ? (
                            <>
                                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '6px', marginBottom: '6px' }}>
                                    <span
                                        style={{
                                            fontSize: '10px',
                                            fontWeight: 800,
                                            textTransform: 'uppercase',
                                            padding: '2px 6px',
                                            borderRadius: '4px',
                                            background: ev.extendedProps?.status === 'Approved' ? '#dcfce7' : '#fef3c7',
                                            color: ev.extendedProps?.status === 'Approved' ? '#15803d' : '#b45309',
                                            letterSpacing: '0.4px',
                                        }}
                                    >
                                        {ev.extendedProps?.status || 'Disetujui'}
                                    </span>
                                    <span
                                        style={{
                                            fontSize: '10.5px',
                                            fontWeight: 700,
                                            color: '#005baa',
                                            background: '#e0f2fe',
                                            padding: '2px 6px',
                                            borderRadius: '4px',
                                            display: 'inline-flex',
                                            alignItems: 'center',
                                            gap: '4px',
                                            maxWidth: '180px',
                                            overflow: 'hidden',
                                            textOverflow: 'ellipsis',
                                            whiteSpace: 'nowrap',
                                        }}
                                    >
                                        <i className="bi bi-door-open-fill"></i> {ev.extendedProps?.ruangan || '-'}
                                    </span>
                                </div>

                                <div style={{ fontSize: '13px', fontWeight: 800, color: '#0f172a', marginBottom: '6px', lineHeight: 1.35 }}>
                                    {ev.extendedProps?.judul || ev.title}
                                </div>

                                <div style={{ display: 'flex', flexDirection: 'column', gap: '4px', fontSize: '11px', color: '#475569', borderTop: '1px dashed #e2e8f0', paddingTop: '6px' }}>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '5px', color: '#0284c7', fontWeight: 700 }}>
                                        <i className="bi bi-clock-fill" style={{ fontSize: '11px' }}></i>
                                        <span>{ev.extendedProps?.waktu || 'Waktu kegiatan'} WITA</span>
                                    </div>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '5px' }}>
                                        <i className="bi bi-person-fill" style={{ color: '#64748b' }}></i>
                                        <span>PIC: <strong>{ev.extendedProps?.pic || '-'}</strong> ({ev.extendedProps?.unit || '-'})</span>
                                    </div>
                                    {ev.extendedProps?.layout && (
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '5px', color: '#64748b' }}>
                                            <i className="bi bi-grid-fill" style={{ color: '#64748b' }}></i>
                                            <span>Layout: {ev.extendedProps?.layout} {ev.extendedProps?.jumlah_tamu ? `• ${ev.extendedProps.jumlah_tamu} Tamu` : ''}</span>
                                        </div>
                                    )}
                                </div>

                                <div style={{ marginTop: '6px', fontSize: '9.5px', color: '#94a3b8', fontStyle: 'italic', textAlign: 'right' }}>
                                    Klik untuk buka rincian lengkap ↗
                                </div>
                            </>
                        ) : (
                            <>
                                <div style={{ display: 'flex', alignItems: 'center', gap: '6px', marginBottom: '4px' }}>
                                    <span style={{ fontSize: '12px' }}>🚩</span>
                                    <span style={{ fontSize: '10.5px', fontWeight: 700, color: ev.color, textTransform: 'uppercase' }}>
                                        {ev.extendedProps?.kategori_label || 'Hari Libur'}
                                    </span>
                                </div>
                                <div style={{ fontSize: '13px', fontWeight: 800, color: '#0f172a', marginBottom: '4px' }}>
                                    {ev.extendedProps?.keterangan || ev.title}
                                </div>
                                <div style={{ fontSize: '11px', color: '#64748b' }}>
                                    <i className="bi bi-calendar3" style={{ marginRight: '4px' }}></i>
                                    {ev.extendedProps?.tanggal || ev.start}
                                </div>
                            </>
                        )}
                    </div>
                );
            })()}

            {/* Event Detail Modal matching Blade 1:1 */}
            {selectedEvent && (
                <div
                    style={{
                        position: 'fixed',
                        top: 0,
                        left: 0,
                        width: '100%',
                        height: '100%',
                        background: 'rgba(15,23,42,0.65)',
                        backdropFilter: 'blur(6px)',
                        zIndex: 99999,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        padding: '16px',
                    }}
                    onClick={() => setSelectedEvent(null)}
                >
                    <div
                        style={{
                            background: '#fff',
                            width: '100%',
                            maxWidth: '540px',
                            borderRadius: '16px',
                            boxShadow: '0 25px 50px -12px rgba(0,0,0,0.25)',
                            overflow: 'hidden',
                        }}
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Modal Header */}
                        <div
                            style={{
                                padding: '18px 24px',
                                borderBottom: '1px solid #f1f5f9',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                                background: '#f8fafc',
                            }}
                        >
                            <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                                <div
                                    style={{
                                        width: '38px',
                                        height: '38px',
                                        borderRadius: '50%',
                                        background:
                                            selectedEvent.type === 'holiday'
                                                ? selectedEvent.extendedProps?.kategori === 'cuti_bersama'
                                                    ? '#fef3c7'
                                                    : selectedEvent.extendedProps?.kategori === 'internal'
                                                    ? '#e0f2fe'
                                                    : '#fee2e2'
                                                : '#e0f2fe',
                                        color:
                                            selectedEvent.type === 'holiday'
                                                ? selectedEvent.extendedProps?.kategori === 'cuti_bersama'
                                                    ? '#d97706'
                                                    : selectedEvent.extendedProps?.kategori === 'internal'
                                                    ? '#0284c7'
                                                    : '#dc2626'
                                                : '#0284c7',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        fontSize: '18px',
                                    }}
                                >
                                    {selectedEvent.type === 'holiday' ? (
                                        selectedEvent.extendedProps?.kategori === 'cuti_bersama' ? (
                                            '🏖️'
                                        ) : selectedEvent.extendedProps?.kategori === 'internal' ? (
                                            '🏛️'
                                        ) : (
                                            '🚩'
                                        )
                                    ) : (
                                        <i className="bi bi-calendar-check-fill"></i>
                                    )}
                                </div>
                                <div>
                                    <h3 style={{ margin: 0, fontSize: '16px', fontWeight: 700, color: '#0f172a' }}>
                                        {selectedEvent.type === 'holiday'
                                            ? selectedEvent.extendedProps?.kategori_label ||
                                              (selectedEvent.extendedProps?.kategori === 'cuti_bersama'
                                                  ? 'Cuti Bersama'
                                                  : 'Hari Libur Nasional')
                                            : selectedEvent.extendedProps?.ruangan || 'Rincian Jadwal'}
                                    </h3>
                                    <p style={{ margin: '2px 0 0', fontSize: '12px', color: '#64748b' }}>
                                        Sistem SILAKAN Kantor Perwakilan
                                    </p>
                                </div>
                            </div>
                            <button
                                type="button"
                                onClick={() => setSelectedEvent(null)}
                                style={{ background: 'none', border: 'none', fontSize: '24px', color: '#64748b', cursor: 'pointer', lineHeight: 1 }}
                            >
                                &times;
                            </button>
                        </div>

                        {/* Modal Body */}
                        <div style={{ padding: '22px 24px' }}>
                            {selectedEvent.type === 'holiday' ? (
                                <>
                                    <div
                                        style={{
                                            padding: '14px',
                                            background: '#f8fafc',
                                            borderRadius: '12px',
                                            border: '1px solid #e2e8f0',
                                            marginBottom: '14px',
                                        }}
                                    >
                                        <span style={{ fontSize: '11.5px', color: '#64748b', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                                            Keterangan Hari Libur
                                        </span>
                                        <h4 style={{ margin: '4px 0 0', fontSize: '16px', color: '#003b73', fontWeight: 800 }}>
                                            {selectedEvent.extendedProps?.keterangan || selectedEvent.title}
                                        </h4>
                                    </div>
                                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px', fontSize: '13px' }}>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Tanggal</span>
                                            <strong style={{ color: '#0f172a' }}>
                                                {selectedEvent.extendedProps?.tanggal || selectedEvent.start}
                                            </strong>
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Status</span>
                                            <span
                                                className={`badge ${
                                                    selectedEvent.extendedProps?.kategori === 'cuti_bersama'
                                                        ? 'badge-warning'
                                                        : selectedEvent.extendedProps?.kategori === 'internal'
                                                        ? 'badge-info'
                                                        : 'badge-danger'
                                                }`}
                                            >
                                                {selectedEvent.extendedProps?.kategori_label || 'Libur'}
                                            </span>
                                        </div>
                                    </div>
                                </>
                            ) : (
                                <>
                                    <div
                                        style={{
                                            padding: '14px',
                                            background: '#f0f9ff',
                                            borderRadius: '12px',
                                            border: '1px solid #bae6fd',
                                            marginBottom: '14px',
                                        }}
                                    >
                                        <span style={{ fontSize: '11.5px', color: '#0369a1', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                                            Agenda / Kegiatan
                                        </span>
                                        <h4 style={{ margin: '4px 0 0', fontSize: '15.5px', color: '#003b73', fontWeight: 800 }}>
                                            {selectedEvent.extendedProps?.judul || selectedEvent.title}
                                        </h4>
                                        <span style={{ display: 'inline-block', marginTop: '6px', fontFamily: 'monospace', fontSize: '11px', color: '#0284c7', fontWeight: 700 }}>
                                            Kode: {selectedEvent.extendedProps?.kode_pemesanan || '-'}
                                        </span>
                                    </div>

                                    <div
                                        style={{
                                            display: 'grid',
                                            gridTemplateColumns: '1fr 1fr',
                                            gap: '12px',
                                            fontSize: '13px',
                                            background: '#f8fafc',
                                            padding: '14px',
                                            borderRadius: '12px',
                                            border: '1px solid #e2e8f0',
                                        }}
                                    >
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Ruangan</span>
                                            <strong style={{ color: '#005baa' }}>{selectedEvent.extendedProps?.ruangan}</strong>
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Layout</span>
                                            <strong style={{ color: '#0f172a' }}>{selectedEvent.extendedProps?.layout || '-'}</strong>
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Tanggal</span>
                                            <strong style={{ color: '#0f172a' }}>{selectedEvent.extendedProps?.tanggal || selectedEvent.start}</strong>
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Waktu (WITA)</span>
                                            <strong style={{ color: '#0f172a' }}>
                                                <i className="bi bi-clock"></i> {selectedEvent.extendedProps?.waktu}
                                            </strong>
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>PIC Kegiatan</span>
                                            <strong style={{ color: '#0f172a' }}>{selectedEvent.extendedProps?.pic}</strong>
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>No. WhatsApp PIC</span>
                                            {selectedEvent.extendedProps?.no_wa_pic && selectedEvent.extendedProps?.no_wa_pic !== '-' ? (
                                                <a
                                                    href={`https://wa.me/${selectedEvent.extendedProps.no_wa_pic.replace(/[^0-9]/g, '')}`}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    style={{ color: '#16a34a', fontWeight: 700, textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '4px' }}
                                                >
                                                    <i className="bi bi-whatsapp"></i> {selectedEvent.extendedProps.no_wa_pic}
                                                </a>
                                            ) : (
                                                <span style={{ color: '#94a3b8' }}>-</span>
                                            )}
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Unit Kerja</span>
                                            <strong style={{ color: '#0f172a' }}>{selectedEvent.extendedProps?.unit}</strong>
                                        </div>
                                        <div>
                                            <span style={{ color: '#64748b', fontSize: '11.5px', display: 'block' }}>Jumlah Tamu</span>
                                            <strong style={{ color: '#0f172a' }}>{selectedEvent.extendedProps?.tamu || '-'} Orang</strong>
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>

                        {/* Modal Footer */}
                        <div
                            style={{
                                padding: '14px 24px',
                                background: '#f8fafc',
                                borderTop: '1px solid #e2e8f0',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'flex-end',
                                gap: '10px',
                            }}
                        >
                            <button
                                type="button"
                                className="btn-secondary"
                                onClick={() => setSelectedEvent(null)}
                                style={{ padding: '8px 18px', fontSize: '13px', borderRadius: '8px' }}
                            >
                                Tutup
                            </button>
                            {selectedEvent.extendedProps?.booking_id && (
                                <Link
                                    to={isAdmin ? `/admin/approval/${selectedEvent.extendedProps.booking_id}` : `/pemesanan/${selectedEvent.extendedProps.booking_id}`}
                                    className="btn-primary"
                                    style={{ padding: '8px 18px', fontSize: '13px', borderRadius: '8px', textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '6px' }}
                                >
                                    <i className="bi bi-box-arrow-up-right"></i> Buka Detail Lengkap
                                </Link>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};
