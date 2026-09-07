import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { adminService, AdminDashboardData } from '../../services/adminService';
import { StatCard } from '../../components/common/StatCard';
import { Badge } from '../../components/common/Badge';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const AdminDashboard: React.FC = () => {
    const [data, setData] = useState<AdminDashboardData | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    const loadData = async () => {
        try {
            const res = await adminService.getAdminDashboard();
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
        const interval = setInterval(loadData, 15000);
        return () => clearInterval(interval);
    }, []);

    if (isLoading && !data) {
        return <LoadingSpinner message="Memuat Dashboard Administrator..." />;
    }

    const todayDate = new Date().toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-shield-check" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Dashboard Administrator
                    </h1>
                    <p>Ringkasan operasional dan pemantauan layanan ruangan rapat KPwBI Sulut</p>
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

            {/* Stat Cards Grid */}
            <div className="stat-grid">
                <StatCard
                    title="Menunggu Persetujuan"
                    value={data?.stats.waiting_approval ?? 0}
                    icon="clock-history"
                    variant="yellow"
                    subtitle="Perlu diverifikasi"
                />
                <StatCard
                    title="Kegiatan Disetujui"
                    value={data?.stats.disetujui ?? 0}
                    icon="check-circle"
                    variant="green"
                />
                <StatCard
                    title="Pemesanan Bulan Ini"
                    value={data?.stats.pemesanan_bulan_ini ?? 0}
                    icon="calendar-month"
                    variant="blue"
                />
                <StatCard
                    title="Total Ruangan"
                    value={data?.stats.total_ruangan ?? 0}
                    icon="building"
                    variant="purple"
                    subtitle="Tersedia di sistem"
                />
            </div>

            {/* Quick Actions Admin */}
            <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', margin: '24px 0' }}>
                <Link to="/admin/approval" className="btn-primary">
                    <i className="bi bi-check-circle-fill" style={{ fontSize: '16px' }}></i> Kelola Persetujuan
                </Link>
                <Link to="/admin/approval/create" className="btn-secondary">
                    <i className="bi bi-calendar-plus" style={{ fontSize: '16px' }}></i> Booking Mandiri Admin
                </Link>
                <Link to="/admin/ruangan" className="btn-secondary">
                    <i className="bi bi-building" style={{ fontSize: '16px' }}></i> Master Ruangan
                </Link>
                <Link to="/admin/laporan" className="btn-secondary">
                    <i className="bi bi-file-earmark-bar-graph" style={{ fontSize: '16px' }}></i> Laporan & Rekap
                </Link>
            </div>

            {/* Main Admin Content Layout */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(450px, 1fr))', gap: '24px' }}>
                {/* Waiting Approval Section */}
                <div className="dashboard-section" style={{ background: '#fff', borderRadius: '16px', padding: '24px', border: '1px solid #e2e8f0', boxShadow: '0 4px 12px rgba(0,0,0,0.04)' }}>
                    <div className="section-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
                        <h2 style={{ fontSize: '16px', fontWeight: 700, color: '#003b73', margin: 0 }}>
                            <i className="bi bi-hourglass-split" style={{ color: '#d97706', marginRight: '8px' }}></i>
                            Menunggu Persetujuan Admin
                        </h2>
                        <Link to="/admin/approval" className="see-all-link" style={{ fontSize: '13px', fontWeight: 600, color: '#005baa', textDecoration: 'none' }}>
                            Semua Pengajuan &rarr;
                        </Link>
                    </div>

                    {data?.waiting_list && data.waiting_list.length > 0 ? (
                        <div className="table-responsive">
                            <table className="data-table">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Pemohon</th>
                                        <th>Ruangan</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.waiting_list.map((item) => (
                                        <tr key={item.id}>
                                            <td>
                                                <strong style={{ color: '#003b73' }}>{item.kode_pemesanan}</strong>
                                            </td>
                                            <td>
                                                <div>{item.user?.nama_unit || item.user?.name}</div>
                                                <small style={{ color: '#64748b' }}>PIC: {item.pic_kegiatan}</small>
                                            </td>
                                            <td>{item.ruangan?.nama_ruangan}</td>
                                            <td>
                                                <div>{item.tanggal_kegiatan}</div>
                                                <small style={{ color: '#64748b' }}>
                                                    {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)}
                                                </small>
                                            </td>
                                            <td>
                                                <Link to={`/admin/approval/${item.id}`} className="btn-table-action" style={{ textDecoration: 'none' }}>
                                                    Verifikasi
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <div className="empty-state" style={{ padding: '32px', textAlign: 'center' }}>
                            <i className="bi bi-check-all" style={{ fontSize: '36px', color: '#10b981' }}></i>
                            <p style={{ marginTop: '8px', color: '#64748b', fontSize: '13.5px' }}>
                                Tidak ada pengajuan pemesanan yang menunggu verifikasi saat ini.
                            </p>
                        </div>
                    )}
                </div>

                {/* Kegiatan Live & Rapat Hari Ini */}
                <div className="dashboard-section" style={{ background: '#fff', borderRadius: '16px', padding: '24px', border: '1px solid #e2e8f0', boxShadow: '0 4px 12px rgba(0,0,0,0.04)' }}>
                    <div className="section-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
                        <h2 style={{ fontSize: '16px', fontWeight: 700, color: '#003b73', margin: 0 }}>
                            <i className="bi bi-calendar-event" style={{ color: '#005baa', marginRight: '8px' }}></i>
                            Jadwal Rapat Hari Ini
                        </h2>
                        <Link to="/admin/kegiatan-berlangsung" className="see-all-link" style={{ fontSize: '13px', fontWeight: 600, color: '#005baa', textDecoration: 'none' }}>
                            Live Monitor &rarr;
                        </Link>
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
                                        <span style={{ fontSize: '12px', fontWeight: 600, color: '#0284c7' }}>
                                            {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)} WITA
                                        </span>
                                    </div>
                                    <p style={{ margin: '6px 0 4px 0', fontSize: '13px', color: '#334155', fontWeight: 600 }}>
                                        {item.judul_kegiatan}
                                    </p>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '12px', color: '#64748b' }}>
                                        <span>Unit: {item.user?.nama_unit || item.user?.name}</span>
                                        <span>PIC: {item.pic_kegiatan}</span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="empty-state" style={{ padding: '32px', textAlign: 'center' }}>
                            <i className="bi bi-calendar-check" style={{ fontSize: '36px', color: '#94a3b8' }}></i>
                            <p style={{ marginTop: '8px', color: '#64748b', fontSize: '13.5px' }}>
                                Tidak ada agenda ruangan untuk hari ini.
                            </p>
                        </div>
                    )}
                </div>
            </div>

            {/* Analytics Row: Ruangan Populer & Unit Distribution */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(450px, 1fr))', gap: '24px', marginTop: '24px' }}>
                <div style={{ background: '#fff', borderRadius: '16px', padding: '24px', border: '1px solid #e2e8f0' }}>
                    <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', marginBottom: '16px' }}>
                        <i className="bi bi-bar-chart" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Ruangan Paling Sering Digunakan
                    </h3>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
                        {data?.charts.popular_rooms.labels.map((room, idx) => {
                            const count = data.charts.popular_rooms.data[idx];
                            const max = Math.max(...data.charts.popular_rooms.data, 1);
                            const pct = Math.round((count / max) * 100);
                            return (
                                <div key={room}>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '13px', fontWeight: 600, marginBottom: '4px' }}>
                                        <span>{room}</span>
                                        <span style={{ color: '#005baa' }}>{count} Kali</span>
                                    </div>
                                    <div style={{ height: '8px', background: '#e2e8f0', borderRadius: '4px', overflow: 'hidden' }}>
                                        <div style={{ width: `${pct}%`, height: '100%', background: 'linear-gradient(90deg, #005baa, #0284c7)', borderRadius: '4px' }} />
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>

                <div style={{ background: '#fff', borderRadius: '16px', padding: '24px', border: '1px solid #e2e8f0' }}>
                    <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', marginBottom: '16px' }}>
                        <i className="bi bi-pie-chart" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Distribusi Penggunaan per Unit Kerja
                    </h3>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
                        {data?.charts.unit_distribution.labels.map((unit, idx) => {
                            const count = data.charts.unit_distribution.data[idx];
                            const max = Math.max(...data.charts.unit_distribution.data, 1);
                            const pct = Math.round((count / max) * 100);
                            return (
                                <div key={unit}>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '13px', fontWeight: 600, marginBottom: '4px' }}>
                                        <span>{unit}</span>
                                        <span style={{ color: '#059669' }}>{count} Kegiatan</span>
                                    </div>
                                    <div style={{ height: '8px', background: '#e2e8f0', borderRadius: '4px', overflow: 'hidden' }}>
                                        <div style={{ width: `${pct}%`, height: '100%', background: 'linear-gradient(90deg, #059669, #10b981)', borderRadius: '4px' }} />
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </div>
        </div>
    );
};
