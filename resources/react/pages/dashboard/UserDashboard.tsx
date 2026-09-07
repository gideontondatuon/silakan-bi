import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { bookingService, UserDashboardData } from '../../services/bookingService';
import { StatCard } from '../../components/common/StatCard';
import { Badge } from '../../components/common/Badge';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const UserDashboard: React.FC = () => {
    const { user } = useAuth();
    const [data, setData] = useState<UserDashboardData | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal Selesai Awal confirmation
    const [selectedLive, setSelectedLive] = useState<any | null>(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const loadData = async () => {
        try {
            const res = await bookingService.getUserDashboard();
            if (res.status === 'success') {
                setData(res.data);
            }
        } catch (e) {
            // Ignore error
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
        const interval = setInterval(loadData, 15000); // refresh every 15s
        return () => clearInterval(interval);
    }, []);

    const handleSelesaiAwal = async () => {
        if (!selectedLive) return;
        setIsSubmitting(true);
        try {
            const res = await bookingService.selesaiAwal(selectedLive.id);
            setAlertMessage({ type: 'success', text: res.message || 'Kegiatan berhasil diselesaikan lebih awal.' });
            setSelectedLive(null);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyelesaikan rapat.' });
        } finally {
            setIsSubmitting(false);
        }
    };

    if (isLoading && !data) {
        return <LoadingSpinner message="Memuat Dashboard..." />;
    }

    const todayDate = new Date().toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });

    return (
        <div>
            {/* Dashboard Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-grid-fill" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Dashboard SILAKAN
                    </h1>
                    <p>
                        Selamat datang kembali, <strong>{user?.name || user?.username}</strong>
                        {user?.nama_unit && <span> &mdash; {user.nama_unit}</span>}
                    </p>
                </div>
                <div className="dashboard-date">
                    <i className="bi bi-calendar3"></i>
                    {todayDate}
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Live Banner */}
            {data?.kegiatan_berlangsung && data.kegiatan_berlangsung.length > 0 && (
                <div className="live-banner">
                    <div className="live-banner-header">
                        <div className="live-banner-title">
                            <span className="live-indicator-dot"></span>
                            Kegiatan Sedang Berlangsung — Live Saat Ini
                        </div>
                        <span className="live-count">
                            <i className="bi bi-building"></i> {data.kegiatan_berlangsung.length} Ruangan Terpakai
                        </span>
                    </div>

                    <div className="live-cards-grid">
                        {data.kegiatan_berlangsung.map((live) => (
                            <div key={live.id} className="live-card">
                                <div
                                    className="live-card-room"
                                    style={{
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'space-between',
                                        flexWrap: 'wrap',
                                        gap: '8px',
                                    }}
                                >
                                    <strong style={{ fontSize: '15px', color: '#fef08a' }}>
                                        <i className="bi bi-building"></i> {live.ruangan?.nama_ruangan}
                                    </strong>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '10px', flexWrap: 'wrap' }}>
                                        <span className="live-card-time">
                                            <i className="bi bi-clock-history"></i> {live.waktu_mulai?.substring(0, 5)} –{' '}
                                            {live.waktu_selesai?.substring(0, 5)} WITA
                                        </span>
                                    </div>
                                </div>

                                <div
                                    className="live-card-title"
                                    style={{ fontSize: '16px', fontWeight: 700, color: '#ffffff', margin: '8px 0 10px 0' }}
                                >
                                    {live.judul_kegiatan}
                                </div>

                                <div
                                    className="live-card-pic"
                                    style={{
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'space-between',
                                        gap: '16px',
                                        flexWrap: 'wrap',
                                        fontSize: '12.5px',
                                        color: 'rgba(255,255,255,0.9)',
                                        paddingTop: '10px',
                                        borderTop: '1px solid rgba(255,255,255,0.15)',
                                    }}
                                >
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '16px', flexWrap: 'wrap' }}>
                                        <span>
                                            <i className="bi bi-people-fill" style={{ color: '#93c5fd', marginRight: '4px' }}></i>{' '}
                                            Unit:{' '}
                                            <strong style={{ color: '#ffffff' }}>
                                                {live.user?.nama_unit || live.user?.name}
                                            </strong>
                                        </span>
                                        {live.pic_kegiatan && (
                                            <span>
                                                <i className="bi bi-person-badge-fill" style={{ color: '#93c5fd', marginRight: '4px' }}></i>{' '}
                                                PIC: <strong style={{ color: '#ffffff' }}>{live.pic_kegiatan}</strong>
                                            </span>
                                        )}
                                        {live.layout && (
                                            <span>
                                                <i className="bi bi-grid-3x3-gap-fill" style={{ color: '#93c5fd', marginRight: '4px' }}></i>{' '}
                                                Layout: <strong style={{ color: '#ffffff' }}>{live.layout.nama_layout}</strong>
                                            </span>
                                        )}
                                    </div>

                                    {live.user_id === user?.id && (
                                        <button
                                            type="button"
                                            onClick={() => setSelectedLive(live)}
                                            className="btn-sm"
                                            style={{
                                                background: '#059669',
                                                color: '#fff',
                                                border: 'none',
                                                borderRadius: '8px',
                                                padding: '5px 12px',
                                                fontSize: '11.5px',
                                                fontWeight: 700,
                                                cursor: 'pointer',
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                gap: '5px',
                                            }}
                                        >
                                            <i className="bi bi-check2-circle"></i> Selesaikan Rapat
                                        </button>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* Quick Actions */}
            <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', marginBottom: '24px' }}>
                <Link to="/pemesanan/create" className="btn-primary">
                    <i className="bi bi-plus-circle-fill" style={{ fontSize: '16px' }}></i> Buat Pemesanan Ruangan
                </Link>
                <Link to="/kalender" className="btn-secondary">
                    <i className="bi bi-calendar-range" style={{ fontSize: '16px' }}></i> Kalender Ruangan
                </Link>
                <Link to="/pemesanan" className="btn-secondary">
                    <i className="bi bi-journal-text" style={{ fontSize: '16px' }}></i> Riwayat Pemesanan
                </Link>
            </div>

            {/* Stat Cards */}
            <div className="stat-grid">
                <StatCard
                    title="Total Pemesanan"
                    value={data?.stats.total ?? 0}
                    icon="calendar-check"
                    variant="blue"
                />
                <StatCard
                    title="Menunggu Approval"
                    value={data?.stats.pending ?? 0}
                    icon="clock-history"
                    variant="yellow"
                />
                <StatCard
                    title="Disetujui"
                    value={data?.stats.approved ?? 0}
                    icon="check-circle"
                    variant="green"
                />
                <StatCard
                    title="Kegiatan Mendatang"
                    value={data?.stats.upcoming ?? 0}
                    icon="calendar-event"
                    variant="purple"
                />
            </div>

            {/* Main Content Grid */}
            <div className="dashboard-user-layout" style={{ marginTop: '24px' }}>
                {/* Pemesanan Terbaru */}
                <div className="dashboard-section">
                    <div className="section-header">
                        <h2>
                            <i className="bi bi-clock-history" style={{ color: '#005baa', marginRight: '8px' }}></i>
                            Pemesanan Terbaru Anda
                        </h2>
                        <Link to="/pemesanan" className="see-all-link">
                            Lihat Semua &rarr;
                        </Link>
                    </div>

                    {data?.pemesanan_terbaru && data.pemesanan_terbaru.length > 0 ? (
                        <div className="table-responsive">
                            <table className="data-table">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Ruangan</th>
                                        <th>Kegiatan</th>
                                        <th>Tanggal & Jam</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.pemesanan_terbaru.map((item) => (
                                        <tr key={item.id}>
                                            <td>
                                                <strong style={{ color: '#003b73' }}>{item.kode_pemesanan}</strong>
                                            </td>
                                            <td>{item.ruangan?.nama_ruangan || '-'}</td>
                                            <td>
                                                <span style={{ fontWeight: 600 }}>{item.judul_kegiatan}</span>
                                            </td>
                                            <td>
                                                <div>{item.tanggal_kegiatan}</div>
                                                <small style={{ color: '#64748b' }}>
                                                    {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)} WITA
                                                </small>
                                            </td>
                                            <td>
                                                <Badge status={item.status} />
                                            </td>
                                            <td>
                                                <Link
                                                    to={`/pemesanan/${item.id}`}
                                                    className="btn-table-action"
                                                    title="Lihat Detail"
                                                >
                                                    <i className="bi bi-eye"></i> Detail
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <div className="empty-state" style={{ padding: '32px', textAlign: 'center' }}>
                            <i className="bi bi-inbox" style={{ fontSize: '32px', color: '#94a3b8' }}></i>
                            <p style={{ marginTop: '8px', color: '#64748b' }}>Belum ada pengajuan pemesanan ruangan.</p>
                        </div>
                    )}
                </div>

                {/* Agenda Hari Ini */}
                <div className="dashboard-section">
                    <div className="section-header">
                        <h2>
                            <i className="bi bi-calendar-event" style={{ color: '#005baa', marginRight: '8px' }}></i>
                            Agenda Rapat Hari Ini
                        </h2>
                    </div>

                    {data?.kegiatan_hari_ini && data.kegiatan_hari_ini.length > 0 ? (
                        <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                            {data.kegiatan_hari_ini.map((item) => (
                                <div
                                    key={item.id}
                                    style={{
                                        padding: '14px 16px',
                                        borderRadius: '10px',
                                        background: '#f8fafc',
                                        border: '1px solid #e2e8f0',
                                        borderLeft: '4px solid #005baa',
                                    }}
                                >
                                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                        <strong style={{ fontSize: '14px', color: '#003b73' }}>
                                            {item.ruangan?.nama_ruangan}
                                        </strong>
                                        <small style={{ color: '#64748b', fontWeight: 600 }}>
                                            <i className="bi bi-clock"></i> {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)} WITA
                                        </small>
                                    </div>
                                    <p style={{ margin: '6px 0 4px 0', fontSize: '13px', color: '#334155', fontWeight: 500 }}>
                                        {item.judul_kegiatan}
                                    </p>
                                    <small style={{ color: '#64748b', fontSize: '11.5px' }}>
                                        Unit: {item.user?.nama_unit || item.user?.name}
                                    </small>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="empty-state" style={{ padding: '32px', textAlign: 'center' }}>
                            <i className="bi bi-calendar-x" style={{ fontSize: '32px', color: '#94a3b8' }}></i>
                            <p style={{ marginTop: '8px', color: '#64748b' }}>Tidak ada agenda rapat untuk hari ini.</p>
                        </div>
                    )}
                </div>
            </div>

            {/* Modal Selesaikan Rapat Lebih Awal */}
            <Modal
                isOpen={!!selectedLive}
                onClose={() => setSelectedLive(null)}
                title="Selesaikan Rapat Lebih Awal"
                footer={
                    <>
                        <button
                            type="button"
                            className="btn-secondary"
                            onClick={() => setSelectedLive(null)}
                            disabled={isSubmitting}
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            className="btn-primary"
                            style={{ background: '#059669', borderColor: '#059669' }}
                            onClick={handleSelesaiAwal}
                            disabled={isSubmitting}
                        >
                            {isSubmitting ? 'Menyimpan...' : 'Ya, Selesaikan Sekarang'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah rapat <strong>{selectedLive?.judul_kegiatan}</strong> di ruangan{' '}
                    <strong>{selectedLive?.ruangan?.nama_ruangan}</strong> telah selesai lebih cepat dan ruangan siap dibebaskan?
                </p>
            </Modal>
        </div>
    );
};
