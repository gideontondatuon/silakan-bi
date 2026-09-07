import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { adminService, AdminDashboardData } from '../../services/adminService';
import { StatCard } from '../../components/common/StatCard';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { Pemesanan } from '../../types';

export const AdminDashboard: React.FC = () => {
    const { user } = useAuth();
    const [data, setData] = useState<AdminDashboardData | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    // Modals
    const [deleteTarget, setDeleteTarget] = useState<Pemesanan | null>(null);
    const [selesaiTarget, setSelesaiTarget] = useState<Pemesanan | null>(null);
    const [actionLoading, setActionLoading] = useState(false);

    // Live countdowns
    const [countdowns, setCountdowns] = useState<{ [id: number]: string }>({});

    const loadData = async () => {
        try {
            const res = await adminService.getAdminDashboard();
            if (res.status === 'success') {
                setData(res.data);
            }
        } catch (e) {
            console.error('Error loading admin dashboard:', e);
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
        const interval = setInterval(loadData, 15000);
        return () => clearInterval(interval);
    }, []);

    // Countdown updater
    useEffect(() => {
        if (!data?.kegiatan_berlangsung || data.kegiatan_berlangsung.length === 0) return;

        const updateCountdowns = () => {
            const now = new Date().getTime();
            const newCountdowns: { [id: number]: string } = {};

            data.kegiatan_berlangsung.forEach(live => {
                if (!live.tanggal_kegiatan || !live.waktu_selesai) return;
                const dateStr = live.tanggal_kegiatan.split('T')[0];
                const endTime = new Date(`${dateStr}T${live.waktu_selesai}`).getTime();
                const diff = endTime - now;

                if (diff <= 0) {
                    newCountdowns[live.id] = 'Selesai';
                } else {
                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    if (hours > 0) {
                        newCountdowns[live.id] = `Sisa ${hours}j ${minutes}m ${seconds}s`;
                    } else {
                        newCountdowns[live.id] = `Sisa ${minutes}m ${seconds}s`;
                    }
                }
            });

            setCountdowns(newCountdowns);
        };

        updateCountdowns();
        const timer = setInterval(updateCountdowns, 1000);
        return () => clearInterval(timer);
    }, [data?.kegiatan_berlangsung]);

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setActionLoading(true);
        try {
            await adminService.deleteBooking(deleteTarget.id);
            setDeleteTarget(null);
            loadData();
        } catch (e) {
            console.error('Failed to delete booking:', e);
        } finally {
            setActionLoading(false);
        }
    };

    const handleSelesaiAwal = async () => {
        if (!selesaiTarget) return;
        setActionLoading(true);
        try {
            await adminService.selesaiAwal(selesaiTarget.id);
            setSelesaiTarget(null);
            loadData();
        } catch (e) {
            console.error('Failed to finish early:', e);
        } finally {
            setActionLoading(false);
        }
    };

    if (isLoading && !data) {
        return <LoadingSpinner message="Memuat Dashboard Admin..." />;
    }

    // Format Indonesian Dates matching Blade:
    // now()->translatedFormat('l, d F Y') e.g. "Senin, 07 September 2026"
    // now()->translatedFormat('d F Y') e.g. "07 September 2026"
    const now = new Date();
    const fullDate = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
    const shortDate = now.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });

    const formatEventDate = (dateStr?: string) => {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    };

    // Calculate current time in WITA for Agenda Hari Ini card status
    const currentHours = now.getHours().toString().padStart(2, '0');
    const currentMins = now.getMinutes().toString().padStart(2, '0');
    const currentTimeStr = `${currentHours}:${currentMins}:00`;

    return (
        <div>
            {/* Page Header (Exact Blade Line 4-18) */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-grid-fill" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Dashboard Admin
                    </h1>
                    <p>
                        Selamat datang kembali, <strong>{user?.name || 'Administrator'}</strong> &mdash; {fullDate}
                    </p>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px', flexWrap: 'wrap' }}>
                    <Link
                        to="/pemesanan/create"
                        className="btn-primary"
                        style={{
                            padding: '9px 18px',
                            borderRadius: '10px',
                            fontWeight: 700,
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '8px',
                            boxShadow: '0 4px 12px rgba(0,91,170,0.25)',
                        }}
                    >
                        <i className="bi bi-calendar-plus-fill"></i> Tambah Rapat
                    </Link>
                    <div className="dashboard-date">
                        <i className="bi bi-calendar3"></i> {shortDate}
                    </div>
                </div>
            </div>

            {/* LIVE Banner (Exact Blade Line 22-71) */}
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
                                <div className="live-card-room" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '8px' }}>
                                    <strong style={{ fontSize: '15px', color: '#fef08a' }}>
                                        <i className="bi bi-building"></i> {live.ruangan?.nama_ruangan}
                                    </strong>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '10px', flexWrap: 'wrap' }}>
                                        <span className="live-card-time">
                                            <i className="bi bi-clock-history"></i> {live.waktu_mulai} – {live.waktu_selesai} WITA
                                        </span>
                                        <span className="live-countdown-badge">
                                            <i className="bi bi-hourglass-split" style={{ animation: 'spinHourglass 2.5s infinite linear', color: '#fef08a' }}></i>
                                            <span className="countdown-value">{countdowns[live.id] || 'Hitung sisa...'}</span>
                                        </span>
                                    </div>
                                </div>
                                <div className="live-card-title" style={{ fontSize: '16px', fontWeight: 700, color: '#ffffff', margin: '8px 0 10px 0' }}>
                                    {live.judul_kegiatan}
                                </div>
                                <div className="live-card-pic" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '16px', flexWrap: 'wrap', fontSize: '12.5px', color: 'rgba(255,255,255,0.9)', paddingTop: '10px', borderTop: '1px solid rgba(255,255,255,0.15)' }}>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '16px', flexWrap: 'wrap' }}>
                                        <span>
                                            <i className="bi bi-people-fill" style={{ color: '#93c5fd', marginRight: '4px' }}></i> Unit: <strong style={{ color: '#ffffff' }}>{live.user?.nama_unit || live.user?.name}</strong>
                                        </span>
                                        {live.pic_kegiatan && (
                                            <span>
                                                <i className="bi bi-person-badge-fill" style={{ color: '#93c5fd', marginRight: '4px' }}></i> PIC: <strong style={{ color: '#ffffff' }}>{live.pic_kegiatan}</strong>
                                            </span>
                                        )}
                                        {live.layout && (
                                            <span>
                                                <i className="bi bi-grid-3x3-gap-fill" style={{ color: '#93c5fd', marginRight: '4px' }}></i> Layout: <strong style={{ color: '#ffffff' }}>{live.layout.nama_layout}</strong>
                                            </span>
                                        )}
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => setSelesaiTarget(live)}
                                        className="btn-sm"
                                        style={{ background: '#059669', color: '#fff', border: 'none', borderRadius: '8px', padding: '5px 12px', fontSize: '11.5px', fontWeight: 700, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: '5px' }}
                                    >
                                        <i className="bi bi-check2-circle"></i> Selesaikan Rapat
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* Stat Cards (Exact Blade Line 75-82: 6 Cards) */}
            <div className="stat-grid">
                <StatCard
                    title="Total Ruangan"
                    value={data?.stats.total_ruangan ?? 0}
                    icon="building"
                    color="blue"
                />
                <StatCard
                    title="Total Pemesanan"
                    value={data?.stats.total_pemesanan ?? 0}
                    icon="calendar"
                    color="teal"
                />
                <StatCard
                    title="Menunggu Approval"
                    value={data?.stats.waiting_approval ?? 0}
                    icon="clock-history"
                    color="yellow"
                />
                <StatCard
                    title="Disetujui"
                    value={data?.stats.disetujui ?? 0}
                    icon="check-circle"
                    color="green"
                />
                <StatCard
                    title="Ditolak"
                    value={data?.stats.ditolak ?? 0}
                    icon="x-circle"
                    color="red"
                />
                <StatCard
                    title="Pemesanan Bulan Ini"
                    value={data?.stats.pemesanan_bulan_ini ?? 0}
                    icon="graph-up"
                    color="purple"
                />
            </div>

            {/* Agenda Mendatang (Exact Blade Line 86-154) */}
            <div className="dashboard-section">
                <div className="section-header">
                    <h2><i className="bi bi-calendar-event-fill"></i> Agenda &amp; Kegiatan Mendatang</h2>
                    <Link to="/kalender">
                        <i className="bi bi-calendar3"></i> Kalender
                    </Link>
                </div>
                <div className="table-wrapper">
                    <table className="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal &amp; Waktu</th>
                                <th>Kegiatan</th>
                                <th>Ruangan &amp; Layout</th>
                                <th>Pemohon / Unit</th>
                                <th>Status</th>
                                <th style={{ textAlign: 'center' }}>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {data?.agenda_mendatang && data.agenda_mendatang.length > 0 ? (
                                data.agenda_mendatang.map((item) => (
                                    <tr key={item.id}>
                                        <td>
                                            <strong style={{ color: '#003b73' }}>{formatEventDate(item.tanggal_kegiatan)}</strong><br />
                                            <small style={{ color: '#64748b' }}>
                                                <i className="bi bi-clock"></i> {item.waktu_mulai} – {item.waktu_selesai} WITA
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{item.judul_kegiatan}</strong><br />
                                            <small style={{ color: '#64748b' }}>
                                                PIC: {item.pic_kegiatan} ({item.jenis_pic || 'Organik'})
                                            </small>
                                        </td>
                                        <td>
                                            <span style={{ fontWeight: 700, color: '#005baa' }}>{item.ruangan?.nama_ruangan}</span><br />
                                            <small style={{ color: '#64748b' }}>
                                                {item.layout?.nama_layout ?? '-'} &middot; {item.jumlah_tamu} Tamu
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{item.user?.name}</strong><br />
                                            <small style={{ color: '#64748b' }}>{item.user?.nama_unit ?? '-'}</small>
                                        </td>
                                        <td>
                                            <span className="badge badge-success">
                                                <i className="bi bi-check-circle"></i> Disetujui
                                            </span>
                                        </td>
                                        <td style={{ textAlign: 'center' }}>
                                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '6px' }}>
                                                <Link
                                                    to={`/admin/approval/${item.id}`}
                                                    className="btn-primary btn-sm"
                                                    style={{ padding: '4px 8px', fontSize: '11.5px' }}
                                                    title="Detail Pemesanan"
                                                >
                                                    <i className="bi bi-eye"></i>
                                                </Link>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeleteTarget(item)}
                                                    className="btn-danger btn-sm"
                                                    style={{ padding: '4px 8px', fontSize: '11.5px', background: '#dc2626', color: 'white', border: 'none', borderRadius: '6px', cursor: 'pointer' }}
                                                    title="Hapus Pemesanan"
                                                >
                                                    <i className="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={6}>
                                        <div className="empty-state">
                                            <i className="bi bi-calendar-x"></i>
                                            <p>Belum ada agenda kegiatan mendatang yang disetujui.</p>
                                        </div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Two-Column Grid 1: Waiting Approval & Agenda Hari Ini (Exact Blade Line 157-242) */}
            <div className="dashboard-grid">
                {/* Waiting Approval */}
                <div className="dashboard-section">
                    <div className="section-header">
                        <h2><i className="bi bi-clock-history"></i> Waiting Approval</h2>
                        <Link to="/admin/approval">
                            <i className="bi bi-arrow-right"></i> Semua
                        </Link>
                    </div>
                    <div className="table-wrapper">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Kegiatan</th>
                                    <th>User</th>
                                    <th>Ruangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data?.waiting_list && data.waiting_list.length > 0 ? (
                                    data.waiting_list.map((item) => (
                                        <tr key={item.id}>
                                            <td>
                                                <span className="badge badge-secondary">{item.kode_pemesanan}</span>
                                            </td>
                                            <td>{item.judul_kegiatan}</td>
                                            <td>{item.user?.name}</td>
                                            <td>{item.ruangan?.nama_ruangan}</td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={4}>
                                            <div className="empty-state">
                                                <i className="bi bi-inbox"></i>
                                                <p>Tidak ada pemesanan menunggu.</p>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Agenda Hari Ini */}
                <div className="dashboard-section">
                    <div className="section-header">
                        <h2><i className="bi bi-sun"></i> Agenda Hari Ini</h2>
                    </div>
                    {data?.kegiatan_hari_ini && data.kegiatan_hari_ini.length > 0 ? (
                        data.kegiatan_hari_ini.map((item) => {
                            const isSelesai = currentTimeStr > item.waktu_selesai;
                            const isBerlangsung = currentTimeStr >= item.waktu_mulai && currentTimeStr <= item.waktu_selesai;

                            return (
                                <div
                                    key={item.id}
                                    className="agenda-card"
                                    style={{
                                        padding: '14px 16px',
                                        borderRadius: '12px',
                                        border: '1px solid #e2e8f0',
                                        marginBottom: '12px',
                                        background: isSelesai ? '#f8fafc' : '#ffffff',
                                        boxShadow: '0 2px 8px rgba(0,0,0,0.03)',
                                        opacity: isSelesai ? '0.72' : '1',
                                    }}
                                >
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '8px', marginBottom: '6px', flexWrap: 'wrap' }}>
                                        <strong style={{ color: '#003b73', fontSize: '13px' }}>
                                            <i className="bi bi-building"></i> {item.ruangan?.nama_ruangan}
                                        </strong>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '6px', flexWrap: 'wrap' }}>
                                            {isSelesai ? (
                                                <span style={{ fontSize: '11px', padding: '3px 9px', borderRadius: '6px', background: '#f1f5f9', color: '#64748b', border: '1px solid #cbd5e1', fontWeight: 600 }}>
                                                    <i className="bi bi-check-circle-fill"></i> Selesai
                                                </span>
                                            ) : isBerlangsung ? (
                                                <span style={{ fontSize: '11px', padding: '3px 9px', borderRadius: '6px', background: '#dcfce7', color: '#15803d', border: '1px solid #86efac', fontWeight: 600 }}>
                                                    <i className="bi bi-broadcast"></i> Berlangsung
                                                </span>
                                            ) : null}
                                            <span className="badge badge-info" style={{ fontSize: '11px', padding: '3px 9px', borderRadius: '6px', background: '#e0f2fe', color: '#0369a1', border: '1px solid #bae6fd', fontWeight: 600 }}>
                                                <i className="bi bi-people-fill"></i> {item.user?.nama_unit ?? item.user?.name}
                                            </span>
                                        </div>
                                    </div>
                                    <p style={{ margin: '0 0 6px 0', fontWeight: 700, color: isSelesai ? '#94a3b8' : '#0f172a', fontSize: '13.5px', lineHeight: 1.35 }}>
                                        {item.judul_kegiatan}
                                    </p>
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', fontSize: '12px', color: '#64748b', flexWrap: 'wrap', gap: '6px' }}>
                                        <span style={{ color: isSelesai ? '#94a3b8' : '#005baa', fontWeight: 600 }}>
                                            <i className="bi bi-clock"></i> {item.waktu_mulai} – {item.waktu_selesai} WITA
                                        </span>
                                        {item.pic_kegiatan && (
                                            <span style={{ color: '#475569', fontWeight: 500 }}>
                                                <i className="bi bi-person-fill"></i> PIC: {item.pic_kegiatan}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            );
                        })
                    ) : (
                        <div className="empty-state" style={{ padding: '30px 24px' }}>
                            <i className="bi bi-calendar-check"></i>
                            <p>Tidak ada agenda hari ini.</p>
                        </div>
                    )}
                </div>
            </div>

            {/* Two-Column Grid 2: Ruangan Terpopuler & Aktivitas Terbaru (Exact Blade Line 245-281) */}
            <div className="dashboard-grid">
                {/* Ruangan Terpopuler */}
                <div className="dashboard-section">
                    <div className="section-header">
                        <h2><i className="bi bi-trophy"></i> Ruangan Terpopuler</h2>
                    </div>
                    {data?.ruangan_terpopuler && data.ruangan_terpopuler.length > 0 ? (
                        data.ruangan_terpopuler.map((item, index) => (
                            <div key={item.ruangan_id || index} className="ranking-item">
                                <span>
                                    <span className="ranking-rank">{index + 1}</span>
                                    {item.ruangan?.nama_ruangan || `Ruangan ${item.ruangan_id}`}
                                </span>
                                <strong>{item.total} booking</strong>
                            </div>
                        ))
                    ) : (
                        <div className="empty-state" style={{ padding: '30px 24px' }}>
                            <i className="bi bi-building-dash"></i>
                            <p>Belum ada data pemakaian ruangan.</p>
                        </div>
                    )}
                </div>

                {/* Aktivitas Terbaru */}
                <div className="dashboard-section">
                    <div className="section-header">
                        <h2><i className="bi bi-activity"></i> Aktivitas Terbaru</h2>
                    </div>
                    {data?.aktivitas_terbaru && data.aktivitas_terbaru.length > 0 ? (
                        data.aktivitas_terbaru.map((item) => (
                            <div key={item.id} className="activity-item">
                                <div className="activity-dot"></div>
                                <div>
                                    <strong>{item.user?.name}</strong>
                                    membuat pemesanan ruangan<br />
                                    <small><i className="bi bi-hash"></i> {item.kode_pemesanan}</small>
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="empty-state" style={{ padding: '30px 24px' }}>
                            <i className="bi bi-clock-history"></i>
                            <p>Belum ada aktivitas terbaru.</p>
                        </div>
                    )}
                </div>
            </div>

            {/* Visual Analytics & Chart Section (Exact Blade Line 282-310) */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '20px', marginTop: '24px', marginBottom: '24px' }}>
                {/* Chart 1: Tren Pemesanan */}
                <div className="dashboard-section" style={{ marginBottom: 0, padding: '20px', background: '#ffffff', borderRadius: '14px', border: '1px solid #e2e8f0', boxShadow: '0 4px 16px rgba(0,0,0,0.02)' }}>
                    <div className="section-header" style={{ marginBottom: '16px', borderBottom: '1px solid #f1f5f9', paddingBottom: '10px' }}>
                        <h2 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', display: 'flex', alignItems: 'center', gap: '8px' }}>
                            <i className="bi bi-graph-up-arrow" style={{ color: '#005baa' }}></i> Tren Pemesanan (6 Bulan)
                        </h2>
                    </div>
                    <div style={{ position: 'relative', height: '220px', display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', padding: '10px 20px 30px' }}>
                        {data?.charts.monthly.labels && data.charts.monthly.labels.length > 0 ? (
                            (() => {
                                const labels = data.charts.monthly.labels;
                                const values = data.charts.monthly.data;
                                const maxVal = Math.max(...values, 5);

                                return labels.map((label, idx) => {
                                    const val = values[idx] || 0;
                                    const heightPct = Math.max(Math.round((val / maxVal) * 150), 6);

                                    return (
                                        <div key={label} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '8px', flex: 1 }}>
                                            <span style={{ fontSize: '11px', fontWeight: 700, color: '#003b73' }}>{val}</span>
                                            <div
                                                style={{
                                                    width: '28px',
                                                    height: `${heightPct}px`,
                                                    background: 'linear-gradient(180deg, #005baa 0%, #38bdf8 100%)',
                                                    borderRadius: '6px 6px 0 0',
                                                    boxShadow: '0 4px 12px rgba(0,91,170,0.2)',
                                                    transition: 'all 0.3s ease',
                                                }}
                                            />
                                            <span style={{ fontSize: '11px', color: '#64748b', whiteSpace: 'nowrap' }}>{label}</span>
                                        </div>
                                    );
                                });
                            })()
                        ) : (
                            <div style={{ width: '100%', textAlign: 'center', color: '#94a3b8', fontSize: '13px' }}>Belum ada data tren bulanan.</div>
                        )}
                    </div>
                </div>

                {/* Chart 2: Pemakaian per Unit Kerja */}
                <div className="dashboard-section" style={{ marginBottom: 0, padding: '20px', background: '#ffffff', borderRadius: '14px', border: '1px solid #e2e8f0', boxShadow: '0 4px 16px rgba(0,0,0,0.02)' }}>
                    <div className="section-header" style={{ marginBottom: '16px', borderBottom: '1px solid #f1f5f9', paddingBottom: '10px' }}>
                        <h2 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', display: 'flex', alignItems: 'center', gap: '8px' }}>
                            <i className="bi bi-pie-chart-fill" style={{ color: '#005baa' }}></i> Pemakaian per Unit Kerja
                        </h2>
                    </div>
                    <div style={{ position: 'relative', height: '220px', display: 'flex', flexDirection: 'column', justifyContent: 'center', gap: '10px', padding: '0 10px' }}>
                        {data?.charts.unit_distribution.labels && data.charts.unit_distribution.labels.length > 0 ? (
                            data.charts.unit_distribution.labels.map((unitName, idx) => {
                                const val = data.charts.unit_distribution.data[idx] || 0;
                                const colors = ['#005baa', '#0284c7', '#0ea5e9', '#38bdf8', '#7dd3fc'];
                                const barColor = colors[idx % colors.length];

                                return (
                                    <div key={unitName} style={{ display: 'flex', flexDirection: 'column', gap: '4px' }}>
                                        <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '12px', fontWeight: 600, color: '#334155' }}>
                                            <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', maxWidth: '80%' }}>
                                                <i className="bi bi-circle-fill" style={{ color: barColor, fontSize: '8px', marginRight: '6px' }}></i>
                                                {unitName}
                                            </span>
                                            <span style={{ fontWeight: 700, color: '#003b73' }}>{val} booking</span>
                                        </div>
                                        <div style={{ height: '7px', background: '#f1f5f9', borderRadius: '4px', overflow: 'hidden' }}>
                                            <div style={{ width: `${Math.min(val * 20, 100)}%`, height: '100%', background: barColor, borderRadius: '4px' }} />
                                        </div>
                                    </div>
                                );
                            })
                        ) : (
                            <div style={{ textAlign: 'center', color: '#94a3b8', fontSize: '13px' }}>Belum ada data pemakaian unit.</div>
                        )}
                    </div>
                </div>
            </div>

            {/* Modal Confirm Selesai Awal */}
            <Modal
                isOpen={!!selesaiTarget}
                onClose={() => setSelesaiTarget(null)}
                title="Selesaikan Rapat Lebih Awal"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setSelesaiTarget(null)} disabled={actionLoading}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" onClick={handleSelesaiAwal} disabled={actionLoading}>
                            {actionLoading ? 'Memproses...' : 'Ya, Selesaikan Sekarang'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah rapat di ruangan <strong>{selesaiTarget?.ruangan?.nama_ruangan}</strong> telah selesai lebih cepat dan siap dibebaskan?
                </p>
            </Modal>

            {/* Modal Confirm Delete */}
            <Modal
                isOpen={!!deleteTarget}
                onClose={() => setDeleteTarget(null)}
                title="Hapus Pemesanan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setDeleteTarget(null)} disabled={actionLoading}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDelete} disabled={actionLoading}>
                            {actionLoading ? 'Menghapus...' : 'Ya, Hapus Data'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah Anda yakin ingin menghapus data pemesanan <strong>{deleteTarget?.kode_pemesanan}</strong> ({deleteTarget?.judul_kegiatan}) secara permanen?
                </p>
            </Modal>
        </div>
    );
};
