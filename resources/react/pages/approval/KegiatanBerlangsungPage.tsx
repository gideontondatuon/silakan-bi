import React, { useEffect, useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { bookingService } from '../../services/bookingService';
import { adminService } from '../../services/adminService';
import { Pemesanan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const KegiatanBerlangsungPage: React.FC = () => {
    const { user } = useAuth();
    const [kegiatan, setKegiatan] = useState<Pemesanan[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [modalItem, setModalItem] = useState<Pemesanan | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
    const [, setCurrentTime] = useState<number>(Date.now());

    const loadData = async (silent = false) => {
        if (!silent) setIsLoading(true);
        try {
            const res = await bookingService.getKegiatanBerlangsung();
            if (res.status === 'success') {
                setKegiatan(res.data || []);
            }
        } catch {
            // Fallback to admin/user dashboard if needed
            try {
                if (user?.role === 'admin') {
                    const adminRes = await adminService.getAdminDashboard();
                    if (adminRes.status === 'success') {
                        setKegiatan(adminRes.data.kegiatan_berlangsung || []);
                    }
                } else {
                    const userRes = await bookingService.getUserDashboard();
                    if (userRes.status === 'success') {
                        setKegiatan(userRes.data.kegiatan_berlangsung || []);
                    }
                }
            } catch {
                // Ignore silent fallback error
            }
        } finally {
            if (!silent) setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
        // Live polling every 10 seconds
        const pollInterval = setInterval(() => {
            loadData(true);
        }, 10000);

        // Countdown timer tick every second
        const tickInterval = setInterval(() => {
            setCurrentTime(Date.now());
        }, 1000);

        return () => {
            clearInterval(pollInterval);
            clearInterval(tickInterval);
        };
    }, [user?.role]);

    const handleConfirmSelesaiAwal = async () => {
        if (!modalItem) return;
        setIsSubmitting(true);
        try {
            let res;
            if (user?.role === 'admin') {
                res = await adminService.selesaiAwal(modalItem.id);
            } else {
                res = await bookingService.selesaiAwal(modalItem.id);
            }
            setAlertMessage({
                type: 'success',
                text: res.message || `Rapat di ${modalItem.ruangan?.nama_ruangan || 'ruangan'} telah berhasil diselesaikan lebih awal.`,
            });
            setModalItem(null);
            loadData(true);
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal menyelesaikan rapat.',
            });
        } finally {
            setIsSubmitting(false);
        }
    };

    const getCountdown = (item: Pemesanan): { text: string; isFinished: boolean } => {
        if (!item.tanggal_kegiatan || !item.waktu_selesai) {
            return { text: 'Menghitung sisa waktu...', isFinished: false };
        }
        const datePart = item.tanggal_kegiatan.split('T')[0];
        const timePart = item.waktu_selesai.length === 5 ? `${item.waktu_selesai}:00` : item.waktu_selesai;
        const endTime = new Date(`${datePart}T${timePart}`);
        const now = new Date();
        const diffMs = endTime.getTime() - now.getTime();

        if (isNaN(diffMs)) {
            return { text: 'Menghitung sisa waktu...', isFinished: false };
        }

        if (diffMs <= 0) {
            return { text: 'Waktu Selesai', isFinished: true };
        }

        const totalSeconds = Math.floor(diffMs / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        let formatted = 'Sisa ';
        if (hours > 0) {
            formatted += `${hours}j ${minutes}m ${seconds}s`;
        } else if (minutes > 0) {
            formatted += `${minutes}m ${seconds}s`;
        } else {
            formatted += `${seconds}s`;
        }

        return { text: formatted, isFinished: false };
    };

    const formatTime = (t?: string) => (t ? t.substring(0, 5) : '--:--');

    return (
        <div>
            {/* Page Header Banner */}
            <div
                className="dashboard-header"
                style={{
                    background: 'linear-gradient(135deg, #003b73 0%, #005baa 100%)',
                    padding: '28px 32px',
                    borderRadius: '18px',
                    color: 'white',
                    marginBottom: '28px',
                    boxShadow: '0 10px 25px -5px rgba(0, 59, 115, 0.25)',
                    position: 'relative',
                    overflow: 'hidden',
                }}
            >
                <div
                    style={{
                        position: 'absolute',
                        right: '-20px',
                        top: '-30px',
                        fontSize: '160px',
                        color: 'rgba(255,255,255,0.05)',
                        pointerEvents: 'none',
                        lineHeight: 1,
                    }}
                >
                    <i className="bi bi-broadcast"></i>
                </div>
                <div
                    style={{
                        position: 'relative',
                        zIndex: 2,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        flexWrap: 'wrap',
                        gap: '16px',
                    }}
                >
                    <div>
                        <div
                            style={{
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '8px',
                                background: 'rgba(255,255,255,0.15)',
                                backdropFilter: 'blur(8px)',
                                WebkitBackdropFilter: 'blur(8px)',
                                padding: '4px 14px',
                                borderRadius: '20px',
                                fontSize: '12px',
                                fontWeight: 700,
                                textTransform: 'uppercase',
                                letterSpacing: '1px',
                                marginBottom: '10px',
                                border: '1px solid rgba(255,255,255,0.25)',
                            }}
                        >
                            <span
                                style={{
                                    width: '8px',
                                    height: '8px',
                                    background: '#ef4444',
                                    borderRadius: '50%',
                                    display: 'inline-block',
                                    boxShadow: '0 0 10px #ef4444',
                                    animation: 'pulseRed 1.5s infinite',
                                }}
                            ></span>
                            Live Monitoring
                        </div>
                        <h1
                            style={{
                                fontSize: '26px',
                                fontWeight: 800,
                                margin: '0 0 6px 0',
                                color: '#ffffff',
                                letterSpacing: '0.5px',
                            }}
                        >
                            Kegiatan Berlangsung
                        </h1>
                        <p
                            style={{
                                margin: 0,
                                fontSize: '13.5px',
                                color: 'rgba(255,255,255,0.85)',
                                fontWeight: 500,
                            }}
                        >
                            Pantau penggunaan ruangan rapat &amp; kantor yang sedang aktif saat ini secara real-time.
                        </p>
                    </div>
                    <div
                        style={{
                            background: 'rgba(255,255,255,0.15)',
                            backdropFilter: 'blur(10px)',
                            WebkitBackdropFilter: 'blur(10px)',
                            padding: '12px 20px',
                            borderRadius: '14px',
                            border: '1px solid rgba(255,255,255,0.2)',
                            textAlign: 'right',
                        }}
                    >
                        <div
                            style={{
                                fontSize: '11px',
                                fontWeight: 600,
                                textTransform: 'uppercase',
                                letterSpacing: '0.8px',
                                color: 'rgba(255,255,255,0.8)',
                            }}
                        >
                            Ruangan Terpakai
                        </div>
                        <div
                            style={{
                                fontSize: '22px',
                                fontWeight: 800,
                                color: '#ffffff',
                                marginTop: '2px',
                            }}
                        >
                            <i className="bi bi-door-open-fill" style={{ color: '#fef08a', marginRight: '6px' }}></i>
                            {kegiatan.length} Ruangan
                        </div>
                    </div>
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Dashboard Section */}
            <div className="dashboard-section" style={{ background: 'transparent', padding: 0, boxShadow: 'none', border: 'none' }}>
                {isLoading && kegiatan.length === 0 ? (
                    <LoadingSpinner message="Memuat kegiatan langsung..." />
                ) : kegiatan.length > 0 ? (
                    <div className="live-monitor-grid">
                        {kegiatan.map((item) => {
                            const countdown = getCountdown(item);
                            const canFinish = user?.role === 'admin' || (user?.id && item.user_id === user.id);

                            return (
                                <div key={item.id} className="live-monitor-card">
                                    <div className="live-monitor-card-header">
                                        <div className="live-monitor-room-badge">
                                            <i className="bi bi-building"></i>
                                            {item.ruangan?.nama_ruangan || 'Ruangan'}
                                        </div>
                                        <div className="live-monitor-status-pill">
                                            <span
                                                style={{
                                                    width: '7px',
                                                    height: '7px',
                                                    background: '#dc2626',
                                                    borderRadius: '50%',
                                                    animation: 'pulseRed 1.5s infinite',
                                                }}
                                            ></span>
                                            LIVE SAAT INI
                                        </div>
                                    </div>

                                    <div className="live-monitor-body">
                                        <h3 className="live-monitor-title">
                                            {item.judul_kegiatan}
                                        </h3>

                                        {/* Live Countdown Timer Badge */}
                                        <div style={{ marginBottom: '16px' }}>
                                            <div
                                                className="live-countdown-badge"
                                                style={{
                                                    width: '100%',
                                                    justifyContent: 'center',
                                                    padding: '10px 16px',
                                                    fontSize: '13px',
                                                    background: countdown.isFinished ? '#dc2626' : '#0f172a',
                                                    color: countdown.isFinished ? '#ffffff' : '#fef08a',
                                                    borderRadius: '12px',
                                                    border: countdown.isFinished
                                                        ? '1px solid #fecdd3'
                                                        : '1px solid rgba(254, 240, 138, 0.3)',
                                                    display: 'flex',
                                                    alignItems: 'center',
                                                    gap: '8px',
                                                }}
                                            >
                                                <i
                                                    className="bi bi-hourglass-split"
                                                    style={{
                                                        animation: 'spinHourglass 2.5s infinite linear',
                                                        fontSize: '15px',
                                                        color: countdown.isFinished ? '#ffffff' : '#fef08a',
                                                    }}
                                                ></i>
                                                <span
                                                    className="countdown-value"
                                                    style={{ fontWeight: 800, letterSpacing: '0.5px' }}
                                                >
                                                    {countdown.text}
                                                </span>
                                            </div>
                                        </div>

                                        <div className="live-monitor-meta">
                                            <div className="live-monitor-meta-item">
                                                <i className="bi bi-clock-history"></i>
                                                <span>
                                                    Waktu Rapat:{' '}
                                                    <strong style={{ color: '#0f172a' }}>
                                                        {formatTime(item.waktu_mulai)} – {formatTime(item.waktu_selesai)} WITA
                                                    </strong>
                                                </span>
                                            </div>
                                            <div className="live-monitor-meta-item">
                                                <i className="bi bi-person-badge-fill"></i>
                                                <span>
                                                    Pemohon / PIC:{' '}
                                                    <strong style={{ color: '#005baa' }}>
                                                        {item.pic_kegiatan || item.user?.name || 'User'}
                                                    </strong>{' '}
                                                    ({item.user?.nama_unit || item.user?.name || 'Unit'})
                                                </span>
                                            </div>
                                            <div className="live-monitor-meta-item">
                                                <i className="bi bi-grid-3x3-gap-fill"></i>
                                                <span>
                                                    Layout Ruangan:{' '}
                                                    <strong style={{ color: '#0f172a' }}>
                                                        {item.layout?.nama_layout || '-'}
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>

                                        {canFinish && (
                                            <div style={{ marginTop: '16px', textAlign: 'right' }}>
                                                <button
                                                    type="button"
                                                    className="btn-sm"
                                                    onClick={() => setModalItem(item)}
                                                    style={{
                                                        background: '#059669',
                                                        color: '#fff',
                                                        border: 'none',
                                                        borderRadius: '10px',
                                                        padding: '8px 16px',
                                                        fontSize: '12.5px',
                                                        fontWeight: 700,
                                                        cursor: 'pointer',
                                                        display: 'inline-flex',
                                                        alignItems: 'center',
                                                        gap: '6px',
                                                    }}
                                                >
                                                    <i className="bi bi-check2-circle"></i> Selesaikan Rapat Sekarang
                                                </button>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                ) : (
                    <div
                        className="empty-state"
                        style={{
                            background: '#ffffff',
                            padding: '50px 30px',
                            borderRadius: '18px',
                            border: '1px dashed #cbd5e1',
                            textAlign: 'center',
                        }}
                    >
                        <div
                            style={{
                                width: '70px',
                                height: '70px',
                                borderRadius: '50%',
                                background: '#f1f5f9',
                                color: '#64748b',
                                display: 'inline-flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                fontSize: '32px',
                                marginBottom: '16px',
                            }}
                        >
                            <i className="bi bi-calendar-x"></i>
                        </div>
                        <h3 style={{ fontSize: '18px', fontWeight: 700, color: '#0f172a', margin: '0 0 6px 0' }}>
                            Tidak Ada Kegiatan Berlangsung
                        </h3>
                        <p style={{ fontSize: '13.5px', color: '#64748b', margin: 0 }}>
                            Saat ini tidak ada ruangan rapat yang sedang terpakai.
                        </p>
                    </div>
                )}
            </div>

            {/* Custom Modal Konfirmasi Selesai Lebih Awal (1:1 with Blade custom-modal-overlay) */}
            {modalItem && (
                <div
                    className="custom-modal-overlay"
                    style={{
                        display: 'flex',
                        position: 'fixed',
                        top: 0,
                        left: 0,
                        width: '100%',
                        height: '100%',
                        background: 'rgba(15,23,42,0.6)',
                        backdropFilter: 'blur(5px)',
                        WebkitBackdropFilter: 'blur(5px)',
                        zIndex: 99999,
                        alignItems: 'center',
                        justifyContent: 'center',
                        padding: '16px',
                    }}
                    onClick={() => !isSubmitting && setModalItem(null)}
                >
                    <div
                        className="custom-modal-box"
                        style={{
                            background: '#fff',
                            width: '100%',
                            maxWidth: '440px',
                            borderRadius: '16px',
                            boxShadow: '0 25px 50px -12px rgba(0,0,0,0.25)',
                            overflow: 'hidden',
                            animation: 'modalScaleIn .2s cubic-bezier(0.16,1,0.3,1)',
                        }}
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div
                            style={{
                                padding: '20px 24px',
                                borderBottom: '1px solid #f1f5f9',
                                display: 'flex',
                                alignItems: 'center',
                                gap: '14px',
                                background: '#f8fafc',
                            }}
                        >
                            <div
                                style={{
                                    width: '42px',
                                    height: '42px',
                                    borderRadius: '50%',
                                    background: '#e0f2fe',
                                    borderColor: '#bae6fd',
                                    color: '#0284c7',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    fontSize: '20px',
                                    flexShrink: 0,
                                    border: '1px solid #bae6fd',
                                }}
                            >
                                <i className="bi bi-question-circle-fill"></i>
                            </div>
                            <div>
                                <h3 style={{ margin: 0, fontSize: '16px', fontWeight: 700, color: '#0f172a' }}>
                                    Selesaikan Rapat Lebih Awal
                                </h3>
                                <p style={{ margin: '2px 0 0', fontSize: '12px', color: '#64748b' }}>
                                    Sistem Informasi SILAKAN BI
                                </p>
                            </div>
                        </div>

                        <div style={{ padding: '22px 24px' }}>
                            <p style={{ margin: 0, fontSize: '13.5px', color: '#334155', lineHeight: 1.5 }}>
                                Apakah rapat di ruangan{' '}
                                <strong>{modalItem.ruangan?.nama_ruangan || 'Ruangan'}</strong> telah selesai lebih
                                cepat dan siap dibebaskan?
                            </p>
                        </div>

                        <div
                            style={{
                                padding: '16px 24px',
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
                                onClick={() => setModalItem(null)}
                                disabled={isSubmitting}
                                style={{ padding: '9px 18px', fontSize: '13px', borderRadius: '10px' }}
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                onClick={handleConfirmSelesaiAwal}
                                disabled={isSubmitting}
                                style={{
                                    background: '#005baa',
                                    color: '#fff',
                                    padding: '9px 20px',
                                    fontSize: '13px',
                                    fontWeight: 600,
                                    borderRadius: '10px',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                    border: 'none',
                                    cursor: isSubmitting ? 'not-allowed' : 'pointer',
                                    opacity: isSubmitting ? 0.7 : 1,
                                }}
                            >
                                <i className="bi bi-check-lg"></i>
                                {isSubmitting ? 'Menyimpan...' : 'Ya, Selesaikan Sekarang'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};
