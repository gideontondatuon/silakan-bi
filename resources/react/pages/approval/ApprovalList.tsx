import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { adminService, ApprovalListData } from '../../services/adminService';
import { bookingService } from '../../services/bookingService';
import { Pemesanan, Ruangan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const ApprovalList: React.FC = () => {
    const [data, setData] = useState<ApprovalListData | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [tab, setTab] = useState<'pending' | 'disetujui' | 'selesai' | 'semua'>('pending');
    const [searchQuery, setSearchQuery] = useState('');
    const [ruanganFilter, setRuanganFilter] = useState('');
    const [tanggalFilter, setTanggalFilter] = useState('');
    const [page, setPage] = useState(1);
    const [ruanganList, setRuanganList] = useState<Ruangan[]>([]);

    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal states
    const [earlyFinishTarget, setEarlyFinishTarget] = useState<Pemesanan | null>(null);
    const [isFinishingEarly, setIsFinishingEarly] = useState(false);

    const [deleteTarget, setDeleteTarget] = useState<Pemesanan | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    // Load active rooms for filter dropdown
    useEffect(() => {
        bookingService.getRuanganList(false).then((res) => {
            if (res.status === 'success') {
                setRuanganList(res.data);
            }
        });
    }, []);

    const loadData = async (targetTab = tab, targetPage = page) => {
        setIsLoading(true);
        try {
            const res = await adminService.getApprovalList({
                tab: targetTab,
                q: searchQuery.trim() || undefined,
                ruangan_id: ruanganFilter || undefined,
                tanggal: tanggalFilter || undefined,
                page: targetPage,
            });
            if (res.status === 'success') {
                setData(res.data);
            }
        } catch (err: any) {
            console.error('Failed to load approval list:', err);
        } finally {
            setIsLoading(false);
        }
    };

    // Load data when tab or page changes
    useEffect(() => {
        loadData(tab, page);
    }, [tab, page]);

    const handleTabChange = (newTab: 'pending' | 'disetujui' | 'selesai' | 'semua') => {
        setTab(newTab);
        setPage(1);
    };

    const handleFilterSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setPage(1);
        loadData(tab, 1);
    };

    const handleResetFilter = () => {
        setSearchQuery('');
        setRuanganFilter('');
        setTanggalFilter('');
        setPage(1);
        // Force reload with empty filters
        setIsLoading(true);
        adminService.getApprovalList({
            tab,
            page: 1,
        }).then((res) => {
            if (res.status === 'success') {
                setData(res.data);
            }
        }).finally(() => {
            setIsLoading(false);
        });
    };

    const handleEarlyFinishSubmit = async () => {
        if (!earlyFinishTarget) return;
        setIsFinishingEarly(true);
        try {
            const res = await adminService.selesaiAwal(earlyFinishTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Kegiatan berhasil diselesaikan lebih awal.' });
            setEarlyFinishTarget(null);
            loadData(tab, page);
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyelesaikan kegiatan.' });
        } finally {
            setIsFinishingEarly(false);
        }
    };

    const handleDeleteSubmit = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteBooking(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil dihapus.' });
            setDeleteTarget(null);
            loadData(tab, page);
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menghapus pemesanan.' });
        } finally {
            setIsDeleting(false);
        }
    };

    const formatTanggal = (dateStr: string) => {
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            return d.toLocaleDateString('id-ID', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            });
        } catch {
            return dateStr;
        }
    };

    const canBeFinishedEarly = (item: Pemesanan): boolean => {
        const status = typeof item.status === 'object' ? (item.status as any).value : item.status;
        if (status !== 'Disetujui') return false;

        try {
            const now = new Date();
            const witaDate = new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Makassar' }).format(now);
            const witaTime = new Intl.DateTimeFormat('en-GB', { timeZone: 'Asia/Makassar', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' }).format(now);

            const itemDate = item.tanggal_kegiatan ? item.tanggal_kegiatan.substring(0, 10) : '';
            const itemStart = item.waktu_mulai ? (item.waktu_mulai.length === 5 ? item.waktu_mulai + ':00' : item.waktu_mulai) : '00:00:00';
            const itemEnd = item.waktu_selesai ? (item.waktu_selesai.length === 5 ? item.waktu_selesai + ':00' : item.waktu_selesai) : '23:59:59';

            return itemDate === witaDate && witaTime >= itemStart && witaTime <= itemEnd;
        } catch {
            return false;
        }
    };

    const renderStatusBadge = (item: Pemesanan) => {
        const statusVal = typeof item.status === 'object' ? (item.status as any).value : item.status;
        if (statusVal === 'Selesai' || item.is_finished) {
            return (
                <span className="badge" style={{ fontSize: '11px', padding: '4px 10px', background: '#f1f5f9', color: '#475569', border: '1px solid #cbd5e1', fontWeight: 600 }}>
                    <i className="bi bi-check2-all"></i> Selesai
                </span>
            );
        }
        if (statusVal === 'Disetujui') {
            return (
                <span className="badge badge-success" style={{ fontSize: '11px', padding: '4px 10px' }}>
                    <i className="bi bi-check-circle-fill"></i> Disetujui
                </span>
            );
        }
        if (statusVal === 'Pending') {
            return (
                <span className="badge badge-warning" style={{ fontSize: '11px', padding: '4px 10px' }}>
                    <i className="bi bi-clock-history"></i> Pending
                </span>
            );
        }
        if (statusVal === 'Ditolak') {
            return (
                <span className="badge badge-danger" style={{ fontSize: '11px', padding: '4px 10px' }}>
                    <i className="bi bi-x-circle-fill"></i> Ditolak
                </span>
            );
        }
        return (
            <span className="badge badge-secondary" style={{ fontSize: '11px', padding: '4px 10px' }}>
                <i className="bi bi-dash-circle"></i> Cancel
            </span>
        );
    };

    const countPending = data?.counts?.pending ?? 0;
    const countDisetujui = data?.counts?.disetujui ?? 0;
    const countSelesai = data?.counts?.selesai ?? 0;
    const countSemua = data?.counts?.semua ?? data?.items?.total ?? 0;

    const hasActiveFilter = Boolean(searchQuery || ruanganFilter || tanggalFilter);
    const items = data?.items?.data || [];

    return (
        <div>
            {/* Header matching Blade */}
            <div className="dashboard-header" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '16px', marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className="bi bi-calendar-check-fill" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Manajemen Pemesanan Ruangan
                    </h1>
                    <p>Kelola, verifikasi, serta hapus/batalkan pengajuan pemesanan ruangan kantor KPwBI Prov. Sulut.</p>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px', flexWrap: 'wrap' }}>
                    <Link
                        to="/admin/approval/create"
                        className="btn-primary"
                        style={{ padding: '9px 18px', borderRadius: '10px', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '8px', boxShadow: '0 4px 12px rgba(0,91,170,0.25)', textDecoration: 'none' }}
                    >
                        <i className="bi bi-calendar-plus-fill"></i> Tambah Rapat
                    </Link>
                    <span className={`badge ${countPending > 0 ? 'badge-warning' : 'badge-success'}`} style={{ fontSize: '13px', padding: '8px 16px' }}>
                        <i className="bi bi-clock-history"></i> {countPending} Menunggu Approval
                    </span>
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Tab Switcher matching Blade */}
            <div style={{ display: 'flex', gap: '10px', marginBottom: '20px', borderBottom: '2px solid #e2e8f0', paddingBottom: '2px', flexWrap: 'wrap' }}>
                <button
                    type="button"
                    onClick={() => handleTabChange('pending')}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
                        textDecoration: 'none',
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: '8px',
                        transition: 'all .2s',
                        border: tab === 'pending' ? 'none' : '1px solid #e2e8f0',
                        borderBottom: 'none',
                        background: tab === 'pending' ? '#005baa' : '#f8fafc',
                        color: tab === 'pending' ? '#ffffff' : '#64748b',
                        boxShadow: tab === 'pending' ? '0 4px 12px rgba(0,91,170,0.25)' : 'none',
                        cursor: 'pointer',
                    }}
                >
                    <i className="bi bi-hourglass-split"></i>
                    Menunggu Approval
                    <span style={{ background: tab === 'pending' ? 'rgba(255,255,255,0.25)' : '#f59e0b', color: '#fff', padding: '2px 8px', borderRadius: '9999px', fontSize: '11px' }}>
                        {countPending}
                    </span>
                </button>

                <button
                    type="button"
                    onClick={() => handleTabChange('disetujui')}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
                        textDecoration: 'none',
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: '8px',
                        transition: 'all .2s',
                        border: tab === 'disetujui' ? 'none' : '1px solid #e2e8f0',
                        borderBottom: 'none',
                        background: tab === 'disetujui' ? '#005baa' : '#f8fafc',
                        color: tab === 'disetujui' ? '#ffffff' : '#64748b',
                        boxShadow: tab === 'disetujui' ? '0 4px 12px rgba(0,91,170,0.25)' : 'none',
                        cursor: 'pointer',
                    }}
                >
                    <i className="bi bi-check-circle-fill"></i>
                    Disetujui / Aktif
                    <span style={{ background: tab === 'disetujui' ? 'rgba(255,255,255,0.25)' : '#059669', color: '#fff', padding: '2px 8px', borderRadius: '9999px', fontSize: '11px' }}>
                        {countDisetujui}
                    </span>
                </button>

                <button
                    type="button"
                    onClick={() => handleTabChange('selesai')}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
                        textDecoration: 'none',
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: '8px',
                        transition: 'all .2s',
                        border: tab === 'selesai' ? 'none' : '1px solid #e2e8f0',
                        borderBottom: 'none',
                        background: tab === 'selesai' ? '#005baa' : '#f8fafc',
                        color: tab === 'selesai' ? '#ffffff' : '#64748b',
                        boxShadow: tab === 'selesai' ? '0 4px 12px rgba(0,91,170,0.25)' : 'none',
                        cursor: 'pointer',
                    }}
                >
                    <i className="bi bi-check2-all"></i>
                    Selesai
                    <span style={{ background: tab === 'selesai' ? 'rgba(255,255,255,0.25)' : '#475569', color: '#fff', padding: '2px 8px', borderRadius: '9999px', fontSize: '11px' }}>
                        {countSelesai}
                    </span>
                </button>

                <button
                    type="button"
                    onClick={() => handleTabChange('semua')}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
                        textDecoration: 'none',
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: '8px',
                        transition: 'all .2s',
                        border: tab === 'semua' ? 'none' : '1px solid #e2e8f0',
                        borderBottom: 'none',
                        background: tab === 'semua' ? '#005baa' : '#f8fafc',
                        color: tab === 'semua' ? '#ffffff' : '#64748b',
                        boxShadow: tab === 'semua' ? '0 4px 12px rgba(0,91,170,0.25)' : 'none',
                        cursor: 'pointer',
                    }}
                >
                    <i className="bi bi-collection-fill"></i>
                    Semua Pemesanan
                    <span style={{ background: tab === 'semua' ? 'rgba(255,255,255,0.25)' : '#64748b', color: '#fff', padding: '2px 8px', borderRadius: '9999px', fontSize: '11px' }}>
                        {countSemua}
                    </span>
                </button>
            </div>

            {/* Filter Card matching Blade 1:1 */}
            <div className="dashboard-section" style={{ marginBottom: '20px', padding: '16px 20px', background: '#ffffff', borderRadius: '12px', border: '1px solid #e2e8f0' }}>
                <form onSubmit={handleFilterSubmit} style={{ display: 'flex', alignItems: 'center', gap: '12px', flexWrap: 'wrap' }}>
                    {/* Search Input */}
                    <div style={{ flex: 2, minWidth: '220px', position: 'relative' }}>
                        <input
                            type="text"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            placeholder="Cari kode, judul kegiatan, PIC, unit..."
                            style={{
                                width: '100%',
                                padding: '9px 12px 9px 36px',
                                border: '1px solid #cbd5e1',
                                borderRadius: '8px',
                                fontSize: '13px',
                                outline: 'none',
                                background: '#ffffff',
                                color: '#1e293b',
                                boxSizing: 'border-box',
                            }}
                        />
                        <i className="bi bi-search" style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)', color: '#94a3b8', pointerEvents: 'none' }}></i>
                    </div>

                    {/* Room Filter */}
                    <div style={{ flex: 1, minWidth: '180px' }}>
                        <select
                            value={ruanganFilter}
                            onChange={(e) => setRuanganFilter(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1px solid #cbd5e1',
                                borderRadius: '8px',
                                fontSize: '13px',
                                background: 'white',
                                outline: 'none',
                                color: '#1e293b',
                                boxSizing: 'border-box',
                            }}
                        >
                            <option value="">-- Semua Ruangan --</option>
                            {ruanganList.map((r) => (
                                <option key={r.id} value={r.id}>
                                    {r.nama_ruangan}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Date Filter */}
                    <div style={{ minWidth: '150px' }}>
                        <input
                            type="date"
                            value={tanggalFilter}
                            onChange={(e) => setTanggalFilter(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '8px 12px',
                                border: '1px solid #cbd5e1',
                                borderRadius: '8px',
                                fontSize: '13px',
                                outline: 'none',
                                background: '#ffffff',
                                color: '#1e293b',
                                boxSizing: 'border-box',
                            }}
                        />
                    </div>

                    {/* Submit & Reset */}
                    <button
                        type="submit"
                        className="btn-primary"
                        style={{ padding: '9px 16px', borderRadius: '8px', fontSize: '13px', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '6px', cursor: 'pointer' }}
                    >
                        <i className="bi bi-filter"></i> Terapkan
                    </button>

                    {hasActiveFilter && (
                        <button
                            type="button"
                            onClick={handleResetFilter}
                            className="btn-secondary"
                            style={{ padding: '9px 14px', borderRadius: '8px', fontSize: '13px', display: 'inline-flex', alignItems: 'center', gap: '6px', cursor: 'pointer' }}
                            title="Reset Filter"
                        >
                            <i className="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    )}
                </form>
            </div>

            {/* Data Table Section matching Blade 1:1 */}
            <div className="dashboard-section" style={{ padding: 0, overflow: 'hidden', borderRadius: '14px', border: '1px solid #e2e8f0', background: '#ffffff' }}>
                <div className="table-wrapper">
                    <table className="data-table" style={{ width: '100%', borderCollapse: 'collapse' }}>
                        <thead>
                            <tr style={{ background: '#f8fafc', borderBottom: '1px solid #e2e8f0', textAlign: 'left', fontSize: '12px', color: '#475569', textTransform: 'uppercase' }}>
                                <th style={{ padding: '14px 18px' }}>Kode &amp; Tanggal</th>
                                <th style={{ padding: '14px 18px' }}>Kegiatan / PIC</th>
                                <th style={{ padding: '14px 18px' }}>Ruangan &amp; Layout</th>
                                <th style={{ padding: '14px 18px' }}>Waktu (WITA)</th>
                                <th style={{ padding: '14px 18px' }}>Pemohon / Unit</th>
                                <th style={{ padding: '14px 18px' }}>Status</th>
                                <th style={{ padding: '14px 18px', textAlign: 'center' }}>Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody>
                            {isLoading ? (
                                <tr>
                                    <td colSpan={7} style={{ textAlign: 'center', padding: '48px 24px' }}>
                                        <LoadingSpinner message="Memuat pengajuan pemesanan..." />
                                    </td>
                                </tr>
                            ) : items.length > 0 ? (
                                items.map((item) => (
                                    <tr key={item.id} style={{ borderBottom: '1px solid #f1f5f9', fontSize: '13px' }}>
                                        <td style={{ padding: '14px 18px' }}>
                                            <strong style={{ color: '#003b73', fontFamily: 'monospace', fontSize: '12.5px', display: 'block' }}>
                                                {item.kode_pemesanan}
                                            </strong>
                                            <small style={{ color: '#64748b', fontWeight: 600 }}>
                                                <i className="bi bi-calendar3"></i> {formatTanggal(item.tanggal_kegiatan)}
                                            </small>
                                        </td>
                                        <td style={{ padding: '14px 18px' }}>
                                            <strong style={{ color: '#0f172a', fontSize: '13.5px', display: 'block' }}>
                                                {item.judul_kegiatan}
                                            </strong>
                                            <small style={{ color: '#64748b' }}>
                                                <i className="bi bi-person"></i> PIC: {item.pic_kegiatan} ({item.jenis_pic ?? '-'})
                                            </small>
                                            {item.file_disposisi && (
                                                <div style={{ marginTop: '3px' }}>
                                                    <span className="badge badge-info" style={{ fontSize: '10px', padding: '2px 6px' }}>
                                                        <i className="bi bi-paperclip"></i> Ada Disposisi
                                                    </span>
                                                </div>
                                            )}
                                        </td>
                                        <td style={{ padding: '14px 18px' }}>
                                            <strong style={{ color: '#005baa', display: 'block' }}>
                                                {item.ruangan?.nama_ruangan}
                                            </strong>
                                            <small style={{ color: '#64748b' }}>
                                                {item.layout?.nama_layout ?? '-'} &bull; {item.jumlah_tamu} Tamu
                                            </small>
                                        </td>
                                        <td style={{ padding: '14px 18px', whiteSpace: 'nowrap', color: '#334155', fontWeight: 600 }}>
                                            <i className="bi bi-clock"></i> {item.waktu_mulai?.substring(0, 5)} – {item.waktu_selesai?.substring(0, 5)}
                                        </td>
                                        <td style={{ padding: '14px 18px' }}>
                                            <strong style={{ color: '#003b73', display: 'block' }}>
                                                {item.user?.name ?? 'User (Dihapus)'}
                                            </strong>
                                            <small style={{ color: '#64748b' }}>
                                                {item.user?.nama_unit ?? '-'}
                                            </small>
                                        </td>
                                        <td style={{ padding: '14px 18px' }}>
                                            {renderStatusBadge(item)}
                                        </td>
                                        <td style={{ padding: '14px 18px', textAlign: 'center' }}>
                                            <div style={{ display: 'inline-flex', alignItems: 'center', gap: '6px', flexWrap: 'wrap', justifyContent: 'center' }}>
                                                {/* Review / Detail */}
                                                <Link
                                                    to={`/admin/approval/${item.id}`}
                                                    className="btn-primary btn-sm"
                                                    style={{ padding: '6px 12px', fontSize: '12px', textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '4px' }}
                                                    title="Verifikasi / Detail"
                                                >
                                                    <i className="bi bi-pencil-square"></i> Periksa
                                                </Link>

                                                {/* Early Release if Live Today */}
                                                {canBeFinishedEarly(item) && (
                                                    <button
                                                        type="button"
                                                        onClick={() => setEarlyFinishTarget(item)}
                                                        className="btn-sm"
                                                        style={{ background: '#059669', color: '#fff', border: 'none', borderRadius: '8px', padding: '6px 10px', fontSize: '12px', fontWeight: 700, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: '4px' }}
                                                        title="Rapat usai lebih cepat"
                                                    >
                                                        <i className="bi bi-check2-circle"></i> Selesai Awal
                                                    </button>
                                                )}

                                                {/* Delete Booking */}
                                                <button
                                                    type="button"
                                                    onClick={() => setDeleteTarget(item)}
                                                    className="btn-danger btn-sm"
                                                    style={{ padding: '6px 9px', fontSize: '12px', cursor: 'pointer' }}
                                                    title="Hapus Permanen"
                                                >
                                                    <i className="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={7} style={{ textAlign: 'center', padding: '48px 24px' }}>
                                        <div style={{ maxWidth: '320px', margin: '0 auto' }}>
                                            <div style={{ width: '56px', height: '56px', borderRadius: '50%', background: '#f1f5f9', color: '#94a3b8', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 14px auto', fontSize: '24px' }}>
                                                <i className="bi bi-calendar-x"></i>
                                            </div>
                                            <h4 style={{ margin: '0 0 6px 0', color: '#0f172a', fontSize: '16px', fontWeight: 700 }}>
                                                Tidak ada data pemesanan ditemukan
                                            </h4>
                                            <p style={{ margin: 0, color: '#64748b', fontSize: '13px', lineHeight: 1.5 }}>
                                                {tab === 'pending'
                                                    ? 'Tidak ada pengajuan pemesanan yang menunggu approval saat ini.'
                                                    : tab === 'disetujui'
                                                    ? 'Belum ada data pemesanan yang berstatus disetujui sesuai filter yang dipilih.'
                                                    : 'Belum ada data pemesanan di dalam sistem.'}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination matching Blade */}
                {data?.items && data.items.last_page > 1 && (
                    <div style={{ padding: '16px 24px', borderTop: '1px solid #f1f5f9', background: '#f8fafc', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '12px' }}>
                        <small style={{ color: '#64748b' }}>
                            Menampilkan {data.items.from || 0}–{data.items.to || 0} dari total {data.items.total} data
                        </small>
                        <div style={{ display: 'flex', gap: '8px' }}>
                            <button
                                type="button"
                                className="btn-secondary"
                                style={{ padding: '6px 14px', fontSize: '12px' }}
                                disabled={page <= 1}
                                onClick={() => setPage((p) => Math.max(1, p - 1))}
                            >
                                &larr; Sebelumnya
                            </button>
                            <span style={{ padding: '6px 12px', fontSize: '13px', fontWeight: 600, color: '#334155' }}>
                                {page} / {data.items.last_page}
                            </span>
                            <button
                                type="button"
                                className="btn-secondary"
                                style={{ padding: '6px 14px', fontSize: '12px' }}
                                disabled={page >= data.items.last_page}
                                onClick={() => setPage((p) => p + 1)}
                            >
                                Selanjutnya &rarr;
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* Modal Early Release */}
            <Modal
                isOpen={!!earlyFinishTarget}
                onClose={() => setEarlyFinishTarget(null)}
                title="Selesaikan Rapat Lebih Awal"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setEarlyFinishTarget(null)} disabled={isFinishingEarly}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#059669', borderColor: '#059669' }} onClick={handleEarlyFinishSubmit} disabled={isFinishingEarly}>
                            {isFinishingEarly ? 'Memproses...' : 'Ya, Selesaikan Sekarang'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah rapat di ruangan <strong>{earlyFinishTarget?.ruangan?.nama_ruangan}</strong> telah selesai lebih cepat dan ruangan siap dibebaskan?
                </p>
            </Modal>

            {/* Modal Delete */}
            <Modal
                isOpen={!!deleteTarget}
                onClose={() => setDeleteTarget(null)}
                title="Hapus Pemesanan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setDeleteTarget(null)} disabled={isDeleting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDeleteSubmit} disabled={isDeleting}>
                            {isDeleting ? 'Menghapus...' : 'Ya, Hapus Data'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah Anda yakin ingin menghapus data pemesanan <strong>{deleteTarget?.kode_pemesanan}</strong> ({deleteTarget?.judul_kegiatan}) secara permanen dari sistem?
                </p>
            </Modal>
        </div>
    );
};
