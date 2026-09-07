import React, { useEffect, useState } from 'react';
import api from '../../services/api';

interface LiveItem {
    id: number;
    kode: string;
    ruangan: string;
    judul: string;
    unit: string;
    pic: string;
    waktu: string;
    end_time: string;
}

interface TodayItem {
    id: number;
    kode: string;
    ruangan: string;
    judul: string;
    unit: string;
    pic: string;
    waktu: string;
    status: string;
}

export const KioskDisplay: React.FC = () => {
    const [currentTime, setCurrentTime] = useState<string>('--:--:-- WITA');
    const [currentDate, setCurrentDate] = useState<string>('-- --- ----');
    const [liveData, setLiveData] = useState<LiveItem[]>([]);
    const [todayData, setTodayData] = useState<TodayItem[]>([]);
    const [lastUpdate, setLastUpdate] = useState<string>('');
    const [countdowns, setCountdowns] = useState<{ [id: number]: string }>({});

    // Clock updater (every 1 second)
    useEffect(() => {
        const updateClock = () => {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            setCurrentTime(`${hours}:${minutes}:${seconds} WITA`);

            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            setCurrentDate(`${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`);
        };

        updateClock();
        const clockTimer = setInterval(updateClock, 1000);
        return () => clearInterval(clockTimer);
    }, []);

    // Countdown updater (every 1 second)
    useEffect(() => {
        const updateCountdowns = () => {
            const now = new Date().getTime();
            const newCountdowns: { [id: number]: string } = {};

            liveData.forEach(item => {
                if (!item.end_time) return;
                const endTime = new Date(item.end_time).getTime();
                const diff = endTime - now;

                if (diff <= 0) {
                    newCountdowns[item.id] = 'Selesai';
                } else {
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    const hours = Math.floor(diff / (1000 * 60 * 60));

                    if (hours > 0) {
                        newCountdowns[item.id] = `Sisa ${hours}j ${minutes}m ${seconds}s`;
                    } else {
                        newCountdowns[item.id] = `Sisa ${minutes}m ${seconds}s`;
                    }
                }
            });

            setCountdowns(newCountdowns);
        };

        updateCountdowns();
        const countdownTimer = setInterval(updateCountdowns, 1000);
        return () => clearInterval(countdownTimer);
    }, [liveData]);

    // Data Fetcher
    const fetchDisplayData = async () => {
        try {
            const res = await api.get('/display-data');
            if (res.data?.status === 'success') {
                setLiveData(res.data.live || []);
                setTodayData(res.data.today || []);
                setLastUpdate(new Date().toLocaleTimeString('id-ID') + ' WITA');
            }
        } catch (err) {
            console.error('Kiosk data fetch error:', err);
        }
    };

    useEffect(() => {
        fetchDisplayData();
        const pollTimer = setInterval(fetchDisplayData, 15000);
        return () => clearInterval(pollTimer);
    }, []);

    return (
        <div style={{
            fontFamily: "'Segoe UI', system-ui, -apple-system, sans-serif",
            background: 'linear-gradient(135deg, #020c1b 0%, #001f3f 50%, #003b73 100%)',
            color: '#ffffff',
            minHeight: '100vh',
            display: 'flex',
            flexDirection: 'column',
            overflowX: 'hidden'
        }}>
            <style>{`
                @keyframes livePulse {
                    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
                    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
                    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
                }
                @keyframes spinHourglass {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
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
                @media (max-width: 992px) {
                    .display-header {
                        padding: 16px 20px !important;
                        flex-direction: column !important;
                        gap: 12px !important;
                        text-align: center !important;
                    }
                    .header-brand {
                        flex-direction: column !important;
                        gap: 8px !important;
                    }
                    .header-clock {
                        text-align: center !important;
                    }
                    .display-container {
                        padding: 16px 20px !important;
                        grid-template-columns: 1fr !important;
                        gap: 20px !important;
                    }
                    .display-footer {
                        padding: 14px 20px !important;
                        flex-direction: column !important;
                        gap: 8px !important;
                        text-align: center !important;
                    }
                }
                @media (max-width: 768px) {
                    .desktop-schedule-view {
                        display: none !important;
                    }
                    .mobile-schedule-view {
                        display: block !important;
                    }
                }
            `}</style>

            {/* Top Header */}
            <header className="display-header" style={{
                minHeight: '90px',
                background: 'rgba(0, 31, 63, 0.85)',
                backdropFilter: 'blur(16px)',
                borderBottom: '2px solid rgba(255, 255, 255, 0.12)',
                padding: '16px 36px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                boxShadow: '0 8px 32px rgba(0, 0, 0, 0.4)'
            }}>
                <div className="header-brand" style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
                    <img src="/images/logo-bi4.png" alt="Bank Indonesia Logo" style={{ height: '52px', filter: 'drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3))' }} />
                    <div className="header-brand-text">
                        <h1 style={{ fontSize: '24px', fontWeight: 900, color: '#ffffff', letterSpacing: '2px', lineHeight: 1, margin: 0 }}>SILAKAN</h1>
                        <p style={{ fontSize: '13px', color: '#93c5fd', marginTop: '4px', fontWeight: 600, margin: 0 }}>Sistem Informasi Layanan Kantor — KPwBI Prov. Sulut</p>
                    </div>
                </div>
                <div className="header-clock" style={{ textAlign: 'right' }}>
                    <div className="clock-time" style={{ fontSize: '30px', fontWeight: 900, color: '#fef08a', letterSpacing: '1px', fontFamily: 'monospace', textShadow: '0 0 12px rgba(254, 240, 138, 0.4)' }}>
                        {currentTime}
                    </div>
                    <div className="clock-date" style={{ fontSize: '13.5px', color: '#e2e8f0', fontWeight: 600, marginTop: '2px' }}>
                        {currentDate}
                    </div>
                </div>
            </header>

            {/* Main Content */}
            <div className="display-container" style={{
                flex: 1,
                padding: '28px 36px',
                display: 'grid',
                gridTemplateColumns: '1fr 1.3fr',
                gap: '28px'
            }}>
                {/* Left: Live Currently Active */}
                <div style={{
                    background: 'rgba(255, 255, 255, 0.05)',
                    backdropFilter: 'blur(16px)',
                    border: '1px solid rgba(255, 255, 255, 0.12)',
                    borderRadius: '20px',
                    padding: '24px',
                    boxShadow: '0 16px 40px rgba(0, 0, 0, 0.3)',
                    display: 'flex',
                    flexDirection: 'column'
                }}>
                    <div style={{
                        fontSize: '18px',
                        fontWeight: 800,
                        color: '#ffffff',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '12px',
                        paddingBottom: '16px',
                        borderBottom: '1px solid rgba(255, 255, 255, 0.1)',
                        marginBottom: '20px'
                    }}>
                        <span style={{ width: '12px', height: '12px', borderRadius: '50%', background: '#ef4444', animation: 'livePulse 1.8s infinite', display: 'inline-block' }}></span>
                        <span>Kegiatan Sedang Berlangsung (LIVE)</span>
                    </div>

                    <div style={{ flex: 1, overflowY: 'auto' }}>
                        {liveData.length > 0 ? (
                            liveData.map(live => (
                                <div key={live.id} style={{
                                    background: 'linear-gradient(135deg, rgba(0, 91, 170, 0.4), rgba(0, 59, 115, 0.6))',
                                    border: '1px solid rgba(147, 197, 253, 0.3)',
                                    borderRadius: '16px',
                                    padding: '20px',
                                    marginBottom: '16px',
                                    boxShadow: '0 8px 24px rgba(0, 0, 0, 0.2)'
                                }}>
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '10px', flexWrap: 'wrap', gap: '8px' }}>
                                        <span style={{ fontSize: '17px', fontWeight: 800, color: '#fef08a' }}>
                                            <i className="bi bi-building"></i> {live.ruangan}
                                        </span>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }}>
                                            <span style={{
                                                background: '#ef4444',
                                                color: 'white',
                                                fontSize: '13px',
                                                fontWeight: 700,
                                                padding: '5px 12px',
                                                borderRadius: '8px',
                                                display: 'flex',
                                                alignItems: 'center',
                                                gap: '6px'
                                            }}>
                                                <i className="bi bi-clock"></i> {live.waktu}
                                            </span>
                                            <span style={{
                                                background: 'rgba(15, 23, 42, 0.7)',
                                                border: '1px solid rgba(254, 240, 138, 0.4)',
                                                color: '#fef08a',
                                                padding: '4px 10px',
                                                borderRadius: '6px',
                                                fontSize: '12.5px',
                                                fontWeight: 700,
                                                fontFamily: 'monospace'
                                            }}>
                                                <i className="bi bi-hourglass-split" style={{ animation: 'spinHourglass 2.5s infinite linear', display: 'inline-block', marginRight: '4px' }}></i>
                                                <span>{countdowns[live.id] || 'Menghitung...'}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div style={{ fontSize: '18px', fontWeight: 800, color: '#ffffff', marginBottom: '12px', lineHeight: 1.3 }}>
                                        {live.judul}
                                    </div>
                                    <div style={{ display: 'flex', gap: '16px', fontSize: '13.5px', color: '#cbd5e1', paddingTop: '10px', borderTop: '1px solid rgba(255, 255, 255, 0.1)', flexWrap: 'wrap' }}>
                                        <span><i className="bi bi-people-fill" style={{ color: '#60a5fa' }}></i> {live.unit}</span>
                                        <span><i className="bi bi-person-fill" style={{ color: '#60a5fa' }}></i> PIC: {live.pic}</span>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div style={{ padding: '40px 20px', textAlign: 'center', color: '#94a3b8' }}>
                                <i className="bi bi-calendar-check" style={{ fontSize: '48px', display: 'block', marginBottom: '12px', color: '#64748b' }}></i>
                                <p style={{ fontSize: '15px', fontWeight: 600, margin: 0 }}>Saat ini tidak ada kegiatan rapat yang berlangsung.</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Right: Today's Full Schedule */}
                <div style={{
                    background: 'rgba(255, 255, 255, 0.05)',
                    backdropFilter: 'blur(16px)',
                    border: '1px solid rgba(255, 255, 255, 0.12)',
                    borderRadius: '20px',
                    padding: '24px',
                    boxShadow: '0 16px 40px rgba(0, 0, 0, 0.3)',
                    display: 'flex',
                    flexDirection: 'column'
                }}>
                    <div style={{
                        fontSize: '18px',
                        fontWeight: 800,
                        color: '#ffffff',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '12px',
                        paddingBottom: '16px',
                        borderBottom: '1px solid rgba(255, 255, 255, 0.1)',
                        marginBottom: '20px'
                    }}>
                        <i className="bi bi-calendar-week" style={{ color: '#60a5fa' }}></i>
                        <span>Jadwal Agenda Rapat Hari Ini</span>
                    </div>

                    {/* Desktop Table View */}
                    <div className="desktop-schedule-view" style={{ flex: 1, overflowY: 'auto' }}>
                        <table className="today-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Ruangan</th>
                                    <th>Kegiatan</th>
                                    <th>Unit Penyelenggara</th>
                                </tr>
                            </thead>
                            <tbody>
                                {todayData.length > 0 ? (
                                    todayData.map(today => (
                                        <tr key={today.id}>
                                            <td style={{ fontWeight: 700, color: '#fef08a', whiteSpace: 'nowrap' }}>
                                                <i className="bi bi-clock"></i> {today.waktu}
                                            </td>
                                            <td style={{ fontWeight: 700, color: '#93c5fd' }}>
                                                {today.ruangan}
                                            </td>
                                            <td>
                                                <strong>{today.judul}</strong>
                                            </td>
                                            <td style={{ color: '#cbd5e1' }}>
                                                {today.unit}
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={4} style={{ textAlign: 'center', padding: '40px', color: '#94a3b8' }}>
                                            Tidak ada agenda rapat untuk hari ini.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Mobile Cards View */}
                    <div className="mobile-schedule-view">
                        {todayData.length > 0 ? (
                            todayData.map(today => (
                                <div key={today.id} style={{
                                    background: 'rgba(255,255,255,0.06)',
                                    border: '1px solid rgba(255,255,255,0.12)',
                                    borderRadius: '14px',
                                    padding: '14px 16px',
                                    marginBottom: '12px'
                                }}>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px', flexWrap: 'wrap', gap: '6px' }}>
                                        <span style={{ fontWeight: 800, color: '#fef08a', fontSize: '13px' }}>
                                            <i className="bi bi-clock"></i> {today.waktu}
                                        </span>
                                        <span style={{
                                            fontWeight: 700,
                                            color: '#93c5fd',
                                            fontSize: '12px',
                                            background: 'rgba(147,197,253,0.15)',
                                            padding: '3px 10px',
                                            borderRadius: '6px',
                                            border: '1px solid rgba(147,197,253,0.25)'
                                        }}>
                                            <i className="bi bi-building"></i> {today.ruangan}
                                        </span>
                                    </div>
                                    <div style={{ fontWeight: 800, fontSize: '15px', color: '#ffffff', marginBottom: '6px', lineHeight: 1.35 }}>
                                        {today.judul}
                                    </div>
                                    <div style={{ fontSize: '12.5px', color: '#cbd5e1', display: 'flex', alignItems: 'center', gap: '6px' }}>
                                        <i className="bi bi-people-fill" style={{ color: '#60a5fa' }}></i> {today.unit}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div style={{ textAlign: 'center', padding: '30px 14px', color: '#94a3b8', fontSize: '14px' }}>
                                Tidak ada agenda rapat untuk hari ini.
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Footer */}
            <footer className="display-footer" style={{
                minHeight: '50px',
                background: 'rgba(2, 12, 27, 0.9)',
                borderTop: '1px solid rgba(255, 255, 255, 0.1)',
                padding: '12px 36px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                fontSize: '12.5px',
                color: '#94a3b8'
            }}>
                <div>
                    <i className="bi bi-broadcast" style={{ color: '#ef4444', marginRight: '6px' }}></i> Mode Layar TV Lobby Kiosk &mdash; Bank Indonesia KPwBI Prov. Sulut
                </div>
                <div>
                    Terakhir Diperbarui: {lastUpdate || '--:--:-- WITA'}
                </div>
            </footer>
        </div>
    );
};
