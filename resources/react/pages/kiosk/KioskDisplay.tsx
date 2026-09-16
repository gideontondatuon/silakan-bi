import React, { useEffect, useState } from 'react';
import { useParams, useSearchParams } from 'react-router-dom';
import api from '../../services/api';

interface LiveItem {
    id: number;
    kode: string;
    ruangan: string;
    judul: string;
    unit: string;
    pic: string;
    jenis_kegiatan?: string;
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
    jenis_kegiatan?: string;
    waktu: string;
    status: string;
}

type DisplayMode = 'internal' | 'eksternal' | 'all';

export const KioskDisplay: React.FC = () => {
    const { type: routeType } = useParams<{ type?: string }>();
    const [searchParams, setSearchParams] = useSearchParams();

    // Determine initial mode from route / URL query
    const getInitialMode = (): DisplayMode => {
        const raw = (routeType || searchParams.get('mode') || searchParams.get('type') || '').toLowerCase();
        if (raw === 'eksternal' || raw === 'external') return 'eksternal';
        if (raw === 'all' || raw === 'semua') return 'all';
        return 'internal';
    };

    const [activeMode, setActiveMode] = useState<DisplayMode>(getInitialMode);
    const [currentTime, setCurrentTime] = useState<string>('--:--:-- WITA');
    const [currentDate, setCurrentDate] = useState<string>('-- --- ----');
    const [liveData, setLiveData] = useState<LiveItem[]>([]);
    const [todayData, setTodayData] = useState<TodayItem[]>([]);
    const [lastUpdate, setLastUpdate] = useState<string>('');
    const [countdowns, setCountdowns] = useState<{ [id: number]: string }>({});
    const [isFullscreen, setIsFullscreen] = useState<boolean>(false);

    // Sync active mode if route param changes
    useEffect(() => {
        if (routeType) {
            const lower = routeType.toLowerCase();
            if (lower === 'eksternal' || lower === 'external') setActiveMode('eksternal');
            else if (lower === 'all' || lower === 'semua') setActiveMode('all');
            else if (lower === 'internal') setActiveMode('internal');
        }
    }, [routeType]);

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

    // Auto-Rotate logic if ?auto=true in URL
    const autoRotate = searchParams.get('auto') === 'true' || searchParams.get('auto') === '1';
    useEffect(() => {
        if (!autoRotate) return;
        const timer = setInterval(() => {
            setActiveMode(current => (current === 'internal' ? 'eksternal' : 'internal'));
        }, 20000);
        return () => clearInterval(timer);
    }, [autoRotate]);

    // Data Fetcher
    const fetchDisplayData = async () => {
        try {
            const params = activeMode !== 'all' ? { jenis: activeMode } : {};
            const res = await api.get('/display-data', { params });
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
    }, [activeMode]);

    // Fullscreen toggle with vendor prefixes
    const toggleFullscreen = () => {
        const doc = document as any;
        const docEl = document.documentElement as any;

        const isFs = !!(doc.fullscreenElement || doc.webkitFullscreenElement || doc.mozFullScreenElement || doc.msFullscreenElement);

        if (!isFs) {
            if (docEl.requestFullscreen) {
                docEl.requestFullscreen().catch(() => {});
            } else if (docEl.webkitRequestFullscreen) {
                docEl.webkitRequestFullscreen();
            } else if (docEl.mozRequestFullScreen) {
                docEl.mozRequestFullScreen();
            } else if (docEl.msRequestFullscreen) {
                docEl.msRequestFullscreen();
            }
        } else {
            if (doc.exitFullscreen) {
                doc.exitFullscreen().catch(() => {});
            } else if (doc.webkitExitFullscreen) {
                doc.webkitExitFullscreen();
            } else if (doc.mozCancelFullScreen) {
                doc.mozCancelFullScreen();
            } else if (doc.msExitFullscreen) {
                doc.msExitFullscreen();
            }
        }
    };

    // Fullscreen event listener & keyboard shortcut ('F' key)
    useEffect(() => {
        const handleFullscreenChange = () => {
            const doc = document as any;
            setIsFullscreen(!!(doc.fullscreenElement || doc.webkitFullscreenElement || doc.mozFullScreenElement || doc.msFullscreenElement));
        };

        const handleKeyDown = (e: KeyboardEvent) => {
            if (['input', 'textarea'].includes((document.activeElement?.tagName || '').toLowerCase())) {
                return;
            }
            if (e.key === 'f' || e.key === 'F') {
                toggleFullscreen();
            }
        };

        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);
        window.addEventListener('keydown', handleKeyDown);

        return () => {
            document.removeEventListener('fullscreenchange', handleFullscreenChange);
            document.removeEventListener('webkitfullscreenchange', handleFullscreenChange);
            document.removeEventListener('mozfullscreenchange', handleFullscreenChange);
            document.removeEventListener('MSFullscreenChange', handleFullscreenChange);
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, []);

    // Filter data according to activeMode
    const internalLive = liveData.filter(i => (i.jenis_kegiatan || 'Internal').toLowerCase() === 'internal');
    const eksternalLive = liveData.filter(i => (i.jenis_kegiatan || '').toLowerCase() === 'eksternal');

    const internalToday = todayData.filter(i => (i.jenis_kegiatan || 'Internal').toLowerCase() === 'internal');
    const eksternalToday = todayData.filter(i => (i.jenis_kegiatan || '').toLowerCase() === 'eksternal');

    const displayedLive = activeMode === 'all'
        ? liveData
        : activeMode === 'eksternal'
        ? eksternalLive
        : internalLive;

    const displayedToday = activeMode === 'all'
        ? todayData
        : activeMode === 'eksternal'
        ? eksternalToday
        : internalToday;

    const isEksternalTheme = activeMode === 'eksternal';

    return (
        <div style={{
            fontFamily: "'Segoe UI', system-ui, -apple-system, sans-serif",
            background: isEksternalTheme
                ? 'linear-gradient(135deg, #090c10 0%, #17120a 35%, #001f3f 100%)'
                : 'linear-gradient(135deg, #020c1b 0%, #001f3f 50%, #003b73 100%)',
            color: '#ffffff',
            minHeight: '100vh',
            display: 'flex',
            flexDirection: 'column',
            overflowX: 'hidden',
            transition: 'background 0.5s ease',
        }}>
            <style>{`
                @keyframes livePulseRed {
                    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
                    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
                    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
                }
                @keyframes livePulseGold {
                    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
                    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
                    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
                }
                @keyframes spinHourglass {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                @keyframes shimmer {
                    0% { opacity: 0.6; }
                    50% { opacity: 1; }
                    100% { opacity: 0.6; }
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
                .kiosk-tab-btn {
                    border: none;
                    outline: none;
                    background: transparent;
                    color: rgba(255, 255, 255, 0.75);
                    font-size: 13.5px;
                    font-weight: 700;
                    padding: 8px 18px;
                    border-radius: 12px;
                    cursor: pointer;
                    display: inline-flex;
                    alignItems: center;
                    gap: 8px;
                    transition: all 0.25s ease;
                }
                .kiosk-tab-btn:hover {
                    color: #ffffff;
                    background: rgba(255, 255, 255, 0.08);
                }
                .kiosk-tab-btn.active-internal {
                    background: linear-gradient(135deg, #005baa 0%, #0284c7 100%);
                    color: #ffffff;
                    box-shadow: 0 4px 14px rgba(0, 91, 170, 0.45);
                }
                .kiosk-tab-btn.active-eksternal {
                    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
                    color: #ffffff;
                    box-shadow: 0 4px 14px rgba(217, 119, 6, 0.45);
                }
                .kiosk-tab-btn.active-all {
                    background: linear-gradient(135deg, #334155 0%, #475569 100%);
                    color: #ffffff;
                    box-shadow: 0 4px 14px rgba(71, 85, 105, 0.4);
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
                    .kiosk-control-bar {
                        padding: 12px 20px !important;
                        flex-direction: column !important;
                        gap: 12px !important;
                    }
                    .kiosk-tab-group {
                        width: 100%;
                        justify-content: center;
                        flex-wrap: wrap;
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
                minHeight: '88px',
                background: 'rgba(0, 26, 53, 0.88)',
                backdropFilter: 'blur(16px)',
                borderBottom: '2px solid rgba(255, 255, 255, 0.12)',
                padding: '14px 36px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                boxShadow: '0 8px 32px rgba(0, 0, 0, 0.4)',
                position: 'relative',
                zIndex: 10,
            }}>
                <div className="header-brand" style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
                    <img src="/images/SILAKAN.png" alt="SILAKAN" style={{ height: '50px', objectFit: 'contain', filter: 'drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3))' }} />
                    <div className="header-brand-text">
                        <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                            <h1 style={{ fontSize: '24px', fontWeight: 900, color: '#ffffff', letterSpacing: '2px', lineHeight: 1, margin: 0 }}>SILAKAN</h1>
                            <span style={{
                                fontSize: '11px',
                                fontWeight: 800,
                                background: isEksternalTheme ? 'linear-gradient(135deg, #d97706, #f59e0b)' : 'linear-gradient(135deg, #005baa, #0284c7)',
                                color: '#ffffff',
                                padding: '3px 9px',
                                borderRadius: '6px',
                                letterSpacing: '0.05em',
                                textTransform: 'uppercase',
                                boxShadow: '0 2px 8px rgba(0,0,0,0.25)',
                            }}>
                                {activeMode === 'eksternal' ? 'Eksternal' : (activeMode === 'internal' ? 'Internal' : 'Kiosk Monitoring Semua')}
                            </span>
                        </div>
                        <p style={{ fontSize: '12.5px', color: '#93c5fd', marginTop: '4px', fontWeight: 600, margin: 0 }}>
                            Sistem Informasi Layanan Kantor — Kantor Perwakilan Prov. Sulawesi Utara
                        </p>
                    </div>
                </div>

                <div className="display-header-right" style={{ display: 'flex', alignItems: 'center', gap: '20px' }}>
                    {/* Fullscreen Button */}
                    <button
                        type="button"
                        onClick={toggleFullscreen}
                        title={isFullscreen ? 'Keluar dari Layar Penuh (ESC / Tombol F)' : 'Tampilkan Layar Penuh (F11 / Tombol F)'}
                        style={{
                            background: isFullscreen ? 'rgba(56, 189, 248, 0.2)' : 'rgba(255, 255, 255, 0.08)',
                            border: '1px solid ' + (isFullscreen ? 'rgba(56, 189, 248, 0.45)' : 'rgba(255, 255, 255, 0.18)'),
                            color: isFullscreen ? '#7dd3fc' : '#ffffff',
                            padding: '9px 16px',
                            borderRadius: '12px',
                            fontSize: '13px',
                            fontWeight: 700,
                            cursor: 'pointer',
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '8px',
                            transition: 'all 0.2s ease',
                            backdropFilter: 'blur(8px)',
                            boxShadow: isFullscreen ? '0 0 16px rgba(56, 189, 248, 0.3)' : 'none',
                        }}
                        onMouseEnter={(e) => {
                            e.currentTarget.style.background = isFullscreen ? 'rgba(56, 189, 248, 0.3)' : 'rgba(255, 255, 255, 0.18)';
                            e.currentTarget.style.transform = 'translateY(-1px)';
                        }}
                        onMouseLeave={(e) => {
                            e.currentTarget.style.background = isFullscreen ? 'rgba(56, 189, 248, 0.2)' : 'rgba(255, 255, 255, 0.08)';
                            e.currentTarget.style.transform = 'translateY(0)';
                        }}
                    >
                        <i className={`bi ${isFullscreen ? 'bi-fullscreen-exit' : 'bi-arrows-fullscreen'}`} style={{ fontSize: '15px' }} />
                        <span>{isFullscreen ? 'Kecilkan' : 'Layar Penuh'}</span>
                    </button>

                    <div className="header-clock" style={{ textAlign: 'right' }}>
                        <div className="clock-time" style={{ fontSize: '28px', fontWeight: 900, color: '#fef08a', letterSpacing: '1px', fontFamily: 'monospace', textShadow: '0 0 12px rgba(254, 240, 138, 0.4)' }}>
                            {currentTime}
                        </div>
                        <div className="clock-date" style={{ fontSize: '13px', color: '#e2e8f0', fontWeight: 600, marginTop: '2px' }}>
                            {currentDate}
                        </div>
                    </div>
                </div>
            </header>

            {/* Main Content */}
            <div className="display-container" style={{
                flex: 1,
                padding: '24px 36px',
                display: 'grid',
                gridTemplateColumns: '1fr 1.3fr',
                gap: '24px',
            }}>
                {/* Left Column: Live Currently Active */}
                <div style={{
                    background: 'rgba(255, 255, 255, 0.05)',
                    backdropFilter: 'blur(16px)',
                    border: '1px solid rgba(255, 255, 255, 0.12)',
                    borderRadius: '20px',
                    padding: '24px',
                    boxShadow: '0 16px 40px rgba(0, 0, 0, 0.3)',
                    display: 'flex',
                    flexDirection: 'column',
                }}>
                    <div style={{
                        fontSize: '18px',
                        fontWeight: 800,
                        color: '#ffffff',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        paddingBottom: '16px',
                        borderBottom: '1px solid rgba(255, 255, 255, 0.1)',
                        marginBottom: '20px',
                        flexWrap: 'wrap',
                        gap: '8px',
                    }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                            <span style={{
                                width: '12px',
                                height: '12px',
                                borderRadius: '50%',
                                background: isEksternalTheme ? '#f59e0b' : '#ef4444',
                                animation: isEksternalTheme ? 'livePulseGold 1.8s infinite' : 'livePulseRed 1.8s infinite',
                                display: 'inline-block',
                            }}></span>
                            <span>
                                {activeMode === 'eksternal'
                                    ? 'Rapat Eksternal Sedang Berlangsung (LIVE)'
                                    : (activeMode === 'internal'
                                        ? 'Rapat Internal Sedang Berlangsung (LIVE)'
                                        : 'Semua Rapat Berlangsung (LIVE)')}
                            </span>
                        </div>
                        <span style={{
                            fontSize: '12px',
                            fontWeight: 700,
                            padding: '3px 10px',
                            borderRadius: '12px',
                            background: isEksternalTheme ? 'rgba(245, 158, 11, 0.2)' : 'rgba(239, 68, 68, 0.2)',
                            color: isEksternalTheme ? '#fbbf24' : '#f87171',
                            border: '1px solid ' + (isEksternalTheme ? 'rgba(245, 158, 11, 0.3)' : 'rgba(239, 68, 68, 0.3)'),
                        }}>
                            {displayedLive.length} Kegiatan Aktif
                        </span>
                    </div>

                    <div style={{ flex: 1, overflowY: 'auto' }}>
                        {displayedLive.length > 0 ? (
                            displayedLive.map(live => {
                                const isItemEksternal = (live.jenis_kegiatan || '').toLowerCase() === 'eksternal';
                                return (
                                    <div key={live.id} style={{
                                        background: isItemEksternal
                                            ? 'linear-gradient(135deg, rgba(217, 119, 6, 0.35), rgba(180, 83, 9, 0.55))'
                                            : 'linear-gradient(135deg, rgba(0, 91, 170, 0.4), rgba(0, 59, 115, 0.6))',
                                        border: isItemEksternal
                                            ? '1px solid rgba(251, 191, 36, 0.4)'
                                            : '1px solid rgba(147, 197, 253, 0.3)',
                                        borderRadius: '16px',
                                        padding: '20px',
                                        marginBottom: '16px',
                                        boxShadow: '0 8px 24px rgba(0, 0, 0, 0.2)',
                                        transition: 'transform 0.2s ease',
                                    }}>
                                        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '10px', flexWrap: 'wrap', gap: '8px' }}>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                                <span style={{ fontSize: '17px', fontWeight: 800, color: '#fef08a' }}>
                                                    <i className="bi bi-door-open-fill" style={{ marginRight: '4px' }}></i> {live.ruangan}
                                                </span>
                                            </div>

                                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }}>
                                                <span style={{
                                                    background: isItemEksternal ? '#d97706' : '#ef4444',
                                                    color: 'white',
                                                    fontSize: '13px',
                                                    fontWeight: 700,
                                                    padding: '5px 12px',
                                                    borderRadius: '8px',
                                                    display: 'flex',
                                                    alignItems: 'center',
                                                    gap: '6px',
                                                }}>
                                                    <i className="bi bi-clock"></i> {live.waktu}
                                                </span>
                                                <span style={{
                                                    background: 'rgba(15, 23, 42, 0.75)',
                                                    border: '1px solid ' + (isItemEksternal ? 'rgba(251, 191, 36, 0.5)' : 'rgba(254, 240, 138, 0.4)'),
                                                    color: '#fef08a',
                                                    padding: '4px 10px',
                                                    borderRadius: '6px',
                                                    fontSize: '12.5px',
                                                    fontWeight: 700,
                                                    fontFamily: 'monospace',
                                                }}>
                                                    <i className="bi bi-hourglass-split" style={{ animation: 'spinHourglass 2.5s infinite linear', display: 'inline-block', marginRight: '4px' }}></i>
                                                    <span>{countdowns[live.id] || 'Menghitung...'}</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div style={{ fontSize: '18px', fontWeight: 800, color: '#ffffff', marginBottom: '12px', lineHeight: 1.35 }}>
                                            {live.judul}
                                        </div>

                                        <div style={{ display: 'flex', gap: '16px', fontSize: '13.5px', color: '#cbd5e1', paddingTop: '10px', borderTop: '1px solid rgba(255, 255, 255, 0.1)', flexWrap: 'wrap' }}>
                                            <span><i className="bi bi-people-fill" style={{ color: '#60a5fa', marginRight: '4px' }}></i> {live.unit}</span>
                                            <span><i className="bi bi-person-badge-fill" style={{ color: '#60a5fa', marginRight: '4px' }}></i> PIC: {live.pic}</span>
                                        </div>
                                    </div>
                                );
                            })
                        ) : (
                            <div style={{ padding: '60px 20px', textAlign: 'center', color: '#94a3b8' }}>
                                <i className={`bi ${isEksternalTheme ? 'bi-calendar-event' : 'bi-calendar-check'}`} style={{ fontSize: '48px', display: 'block', marginBottom: '12px', color: isEksternalTheme ? '#d97706' : '#64748b' }}></i>
                                <p style={{ fontSize: '15px', fontWeight: 700, margin: 0, color: '#ffffff' }}>
                                    {activeMode === 'eksternal'
                                        ? 'Saat ini tidak ada kegiatan rapat eksternal yang berlangsung.'
                                        : (activeMode === 'internal'
                                            ? 'Saat ini tidak ada kegiatan rapat internal yang berlangsung.'
                                            : 'Saat ini tidak ada kegiatan rapat yang berlangsung.')}
                                </p>
                                <p style={{ fontSize: '13px', color: '#94a3b8', marginTop: '4px' }}>
                                    Ruangan dalam status siap digunakan atau menunggu jadwal berikutnya.
                                </p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Right Column: Today's Schedule */}
                <div style={{
                    background: 'rgba(255, 255, 255, 0.05)',
                    backdropFilter: 'blur(16px)',
                    border: '1px solid rgba(255, 255, 255, 0.12)',
                    borderRadius: '20px',
                    padding: '24px',
                    boxShadow: '0 16px 40px rgba(0, 0, 0, 0.3)',
                    display: 'flex',
                    flexDirection: 'column',
                }}>
                    <div style={{
                        fontSize: '18px',
                        fontWeight: 800,
                        color: '#ffffff',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        paddingBottom: '16px',
                        borderBottom: '1px solid rgba(255, 255, 255, 0.1)',
                        marginBottom: '20px',
                        flexWrap: 'wrap',
                        gap: '8px',
                    }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                            <i className="bi bi-calendar-week-fill" style={{ color: isEksternalTheme ? '#fbbf24' : '#60a5fa' }}></i>
                            <span>
                                {activeMode === 'eksternal'
                                    ? 'Jadwal Rapat Eksternal Hari Ini'
                                    : (activeMode === 'internal'
                                        ? 'Jadwal Rapat Internal Hari Ini'
                                        : 'Seluruh Agenda Rapat Hari Ini')}
                            </span>
                        </div>
                        <span style={{
                            fontSize: '12px',
                            fontWeight: 700,
                            padding: '3px 10px',
                            borderRadius: '12px',
                            background: 'rgba(255, 255, 255, 0.1)',
                            color: '#cbd5e1',
                        }}>
                            {displayedToday.length} Agenda Terjadwal
                        </span>
                    </div>

                    {/* Desktop Table View */}
                    <div className="desktop-schedule-view" style={{ flex: 1, overflowY: 'auto' }}>
                        <table className="today-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Ruangan</th>
                                    <th>Kegiatan</th>
                                    <th>Penyelenggara / PIC</th>
                                </tr>
                            </thead>
                            <tbody>
                                {displayedToday.length > 0 ? (
                                    displayedToday.map(today => {
                                        return (
                                            <tr key={today.id}>
                                                <td style={{ fontWeight: 700, color: '#fef08a', whiteSpace: 'nowrap' }}>
                                                    <i className="bi bi-clock" style={{ marginRight: '4px' }}></i> {today.waktu}
                                                </td>
                                                <td style={{ fontWeight: 700, color: '#93c5fd', whiteSpace: 'nowrap' }}>
                                                    <i className="bi bi-door-closed" style={{ marginRight: '3px' }}></i> {today.ruangan}
                                                </td>
                                                <td>
                                                    <strong style={{ color: '#ffffff', display: 'block', lineHeight: 1.3 }}>
                                                        {today.judul}
                                                    </strong>
                                                </td>
                                                <td style={{ color: '#cbd5e1', fontSize: '13px' }}>
                                                    <div><i className="bi bi-people" style={{ marginRight: '4px', color: '#94a3b8' }}></i> {today.unit}</div>
                                                    <div style={{ fontSize: '11.5px', color: '#94a3b8' }}>PIC: {today.pic}</div>
                                                </td>
                                            </tr>
                                        );
                                    })
                                ) : (
                                    <tr>
                                        <td colSpan={4} style={{ textAlign: 'center', padding: '50px 20px', color: '#94a3b8' }}>
                                            <i className="bi bi-inbox" style={{ fontSize: '36px', display: 'block', marginBottom: '8px', opacity: 0.5 }}></i>
                                            {activeMode === 'eksternal'
                                                ? 'Tidak ada agenda rapat eksternal untuk hari ini.'
                                                : (activeMode === 'internal'
                                                    ? 'Tidak ada agenda rapat internal untuk hari ini.'
                                                    : 'Tidak ada agenda rapat untuk hari ini.')}
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Mobile Cards View */}
                    <div className="mobile-schedule-view">
                        {displayedToday.length > 0 ? (
                            displayedToday.map(today => {
                                const isItemEksternal = (today.jenis_kegiatan || '').toLowerCase() === 'eksternal';
                                return (
                                    <div key={today.id} style={{
                                        background: 'rgba(255,255,255,0.06)',
                                        border: '1px solid ' + (isItemEksternal ? 'rgba(245,158,11,0.3)' : 'rgba(255,255,255,0.12)'),
                                        borderRadius: '14px',
                                        padding: '14px 16px',
                                        marginBottom: '12px',
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
                                                padding: '2px 8px',
                                                borderRadius: '6px',
                                            }}>
                                                {today.ruangan}
                                            </span>
                                        </div>
                                        <div style={{ fontWeight: 800, fontSize: '15px', color: '#ffffff', marginBottom: '6px', lineHeight: 1.35 }}>
                                            {today.judul}
                                        </div>
                                        <div style={{ fontSize: '12px', color: '#cbd5e1', display: 'flex', alignItems: 'center', gap: '10px' }}>
                                            <span><i className="bi bi-people-fill" style={{ color: '#60a5fa' }}></i> {today.unit}</span>
                                            <span><i className="bi bi-person-fill" style={{ color: '#60a5fa' }}></i> {today.pic}</span>
                                        </div>
                                    </div>
                                );
                            })
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
                minHeight: '48px',
                background: 'rgba(2, 12, 27, 0.95)',
                borderTop: '1px solid rgba(255, 255, 255, 0.1)',
                padding: '10px 36px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                fontSize: '12.5px',
                color: '#94a3b8',
            }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <i className="bi bi-broadcast" style={{ color: isEksternalTheme ? '#f59e0b' : '#ef4444' }}></i>
                    <span>
                        Mode TV Lobby Display &mdash; Kantor Perwakilan Provinsi Sulawesi Utara
                    </span>
                    <span style={{ opacity: 0.5 }}>|</span>
                    <span style={{ color: isEksternalTheme ? '#fbbf24' : '#93c5fd', fontWeight: 600 }}>
                        {activeMode === 'eksternal' ? 'Tampilan Agenda Eksternal' : (activeMode === 'internal' ? 'Tampilan Agenda Internal' : 'Tampilan Semua Agenda')}
                    </span>
                </div>
                <div>
                    Terakhir Disinkronkan: <strong style={{ color: '#ffffff' }}>{lastUpdate || '--:--:-- WITA'}</strong>
                </div>
            </footer>
        </div>
    );
};
