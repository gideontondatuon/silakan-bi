import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { adminService, ApprovalListData } from '../../services/adminService';
import { Pemesanan, Ruangan } from '../../types';
import { bookingService } from '../../services/bookingService';
import { Badge } from '../../components/common/Badge';
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

    // Modal state for Approve
    const [approveTarget, setApproveTarget] = useState<Pemesanan | null>(null);
    const [catatanAdmin, setCatatanAdmin] = useState('');
    const [isApproving, setIsApproving] = useState(false);

    // Modal state for Reject
    const [rejectTarget, setRejectTarget] = useState<Pemesanan | null>(null);
    const [alasanPenolakan, setAlasanPenolakan] = useState('');
    const [isRejecting, setIsRejecting] = useState(false);

    // Modal state for Delete
    const [deleteTarget, setDeleteTarget] = useState<Pemesanan | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    // Load active rooms for filter
    useEffect(() => {
        bookingService.getRuanganList(false).then((res) => {
            if (res.status === 'success') setRuanganList(res.data);
        });
    }, []);

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await adminService.getApprovalList({
                tab: tab === 'semua' ? undefined : tab,
                q: searchQuery || undefined,
                ruangan_id: ruanganFilter || undefined,
                tanggal: tanggalFilter || undefined,
                page,
            });
            if (res.status === 'success') {
                setData(res.data);
            }
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, [tab, ruanganFilter, tanggalFilter, page]);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        setPage(1);
        loadData();
    };

    const handleApproveSubmit = async () => {
        if (!approveTarget) return;
        setIsApproving(true);
        try {
            const res = await adminService.approve(approveTarget.id, catatanAdmin);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil disetujui.' });
            setApproveTarget(null);
            setCatatanAdmin('');
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyetujui pemesanan.' });
        } finally {
            setIsApproving(false);
        }
    };

    const handleRejectSubmit = async () => {
        if (!rejectTarget) return;
        if (!alasanPenolakan.trim()) {
            alert('Harap isi alasan penolakan.');
            return;
        }
        setIsRejecting(true);
        try {
            const res = await adminService.reject(rejectTarget.id, alasanPenolakan);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil ditolak.' });
            setRejectTarget(null);
            setAlasanPenolakan('');
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menolak pemesanan.' });
        } finally {
            setIsRejecting(false);
        }
    };

    const handleDeleteSubmit = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteBooking(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil dihapus.' });
            setDeleteTarget(null);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menghapus pemesanan.' });
        } finally {
            setIsDeleting(false);
        }
    };

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
                        to="/pemesanan/create"
                        className="btn-primary"
                        style={{ padding: '9px 18px', borderRadius: '10px', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '8px', boxShadow: '0 4px 12px rgba(0,91,170,0.25)' }}
                    >
                        <i className="bi bi-calendar-plus-fill"></i> Tambah Rapat
                    </Link>
                    <span className={`badge ${(data?.counts?.pending || 0) > 0 ? 'badge-warning' : 'badge-success'}`} style={{ fontSize: '13px', padding: '8px 16px' }}>
                        <i className="bi bi-clock-history"></i> {data?.counts?.pending || 0} Menunggu Approval
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

            {/* Filter Tabs matching Blade */}
            <div style={{ display: 'flex', gap: '10px', marginBottom: '20px', borderBottom: '2px solid #e2e8f0', paddingBottom: '2px', flexWrap: 'wrap' }}>
                <button
                    type="button"
                    onClick={() => { setTab('pending'); setPage(1); }}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
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
                        {data?.counts?.pending || 0}
                    </span>
                </button>

                <button
                    type="button"
                    onClick={() => { setTab('disetujui'); setPage(1); }}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
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
                        {data?.counts?.disetujui || 0}
                    </span>
                </button>

                <button
                    type="button"
                    onClick={() => { setTab('selesai'); setPage(1); }}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
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
                        {data?.counts?.selesai || 0}
                    </span>
                </button>

                <button
                    type="button"
                    onClick={() => { setTab('semua'); setPage(1); }}
                    style={{
                        padding: '10px 20px',
                        borderRadius: '10px 10px 0 0',
                        fontWeight: 700,
                        fontSize: '13.5px',
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
                        {data?.items?.total || 0}
                    </span>
                </button>
            </div>

            {/* Filter Bar */}
            <div style={{ background: '#fff', borderRadius: '12px', padding: '16px 20px', border: '1px solid #e2e8f0', marginBottom: '20px', display: 'flex', gap: '14px', flexWrap: 'wrap', alignItems: 'center', justifyContent: 'space-between' }}>
                <form onSubmit={handleSearch} style={{ display: 'flex', gap: '8px', flex: '1 1 280px', maxWidth: '400px' }}>
                    <input
                        type="text"
                        className="login-input"
                        placeholder="Cari kode tiket, judul kegiatan, pemohon..."
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        style={{ height: '38px', fontSize: '13px' }}
                    />
                    <button type="submit" className="btn-primary" style={{ padding: '0 14px', height: '38px', flexShrink: 0 }}>
                        <i className="bi bi-search"></i>
                    </button>
                </form>

                <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap' }}>
                    <select
                        className="login-input"
                        value={ruanganFilter}
                        onChange={(e) => { setRuanganFilter(e.target.value); setPage(1); }}
                        style={{ height: '38px', fontSize: '13px', minWidth: '160px' }}
                    >
                        <option value="">Semua Ruangan</option>
                        {ruanganList.map((r) => (
                            <option key={r.id} value={r.id}>
                                {r.nama_ruangan}
                            </option>
                        ))}
                    </select>

                    <input
                        type="date"
                        className="login-input"
                        value={tanggalFilter}
                        onChange={(e) => { setTanggalFilter(e.target.value); setPage(1); }}
                        style={{ height: '38px', fontSize: '13px' }}
                    />
                </div>
            </div>

            {/* Table */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                {isLoading ? (
                    <LoadingSpinner message="Memuat pengajuan pemesanan..." />
                ) : data && data.items.data.length > 0 ? (
                    <>
                        <div className="table-responsive">
                            <table className="data-table">
                                <thead>
                                    <tr>
                                        <th>Kode Tiket</th>
                                        <th>Unit & Pemohon</th>
                                        <th>Ruangan & Layout</th>
                                        <th>Kegiatan</th>
                                        <th>Jadwal Rapat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.items.data.map((item) => (
                                        <tr key={item.id}>
                                            <td>
                                                <strong style={{ color: '#003b73' }}>{item.kode_pemesanan}</strong>
                                                <div style={{ fontSize: '11px', color: '#94a3b8' }}>
                                                    {new Date(item.created_at).toLocaleDateString('id-ID')}
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{item.user?.nama_unit || item.user?.name}</strong>
                                                <div style={{ fontSize: '12px', color: '#64748b' }}>
                                                    PIC: {item.pic_kegiatan} ({item.jenis_pic})
                                                </div>
                                            </td>
                                            <td>
                                                <div>{item.ruangan?.nama_ruangan}</div>
                                                <small style={{ color: '#64748b' }}>Layout: {item.layout?.nama_layout || 'Standar'}</small>
                                            </td>
                                            <td>
                                                <span style={{ fontWeight: 600, color: '#1e293b' }}>{item.judul_kegiatan}</span>
                                                <div style={{ fontSize: '11.5px', color: '#64748b' }}>{item.jumlah_tamu} Peserta</div>
                                            </td>
                                            <td>
                                                <div>{item.tanggal_kegiatan}</div>
                                                <small style={{ color: '#0284c7', fontWeight: 600 }}>
                                                    {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)} WITA
                                                </small>
                                            </td>
                                            <td>
                                                <Badge status={item.status} />
                                            </td>
                                            <td>
                                                <div style={{ display: 'flex', gap: '6px', alignItems: 'center' }}>
                                                    <Link
                                                        to={`/admin/approval/${item.id}`}
                                                        className="btn-table-action"
                                                        title="Lihat Detail Lengkap"
                                                        style={{ textDecoration: 'none' }}
                                                    >
                                                        Detail
                                                    </Link>

                                                    {item.status === 'Pending' && (
                                                        <>
                                                            <button
                                                                type="button"
                                                                onClick={() => { setApproveTarget(item); setCatatanAdmin(''); }}
                                                                style={{ background: '#ecfdf5', color: '#059669', border: '1px solid #a7f3d0', borderRadius: '6px', padding: '4px 8px', fontSize: '12px', fontWeight: 700, cursor: 'pointer' }}
                                                                title="Setujui"
                                                            >
                                                                <i className="bi bi-check-lg"></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                onClick={() => { setRejectTarget(item); setAlasanPenolakan(''); }}
                                                                style={{ background: '#fef2f2', color: '#dc2626', border: '1px solid #fecdd3', borderRadius: '6px', padding: '4px 8px', fontSize: '12px', fontWeight: 700, cursor: 'pointer' }}
                                                                title="Tolak"
                                                            >
                                                                <i className="bi bi-x-lg"></i>
                                                            </button>
                                                        </>
                                                    )}

                                                    <button
                                                        type="button"
                                                        onClick={() => setDeleteTarget(item)}
                                                        style={{ background: '#f1f5f9', color: '#94a3b8', border: '1px solid #cbd5e1', borderRadius: '6px', padding: '4px 8px', fontSize: '12px', cursor: 'pointer' }}
                                                        title="Hapus Pemesanan"
                                                    >
                                                        <i className="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Pagination */}
                        {data.items.last_page > 1 && (
                            <div style={{ padding: '16px 24px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderTop: '1px solid #e2e8f0', background: '#f8fafc' }}>
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
                                    <span style={{ padding: '6px 12px', fontSize: '13px', fontWeight: 600 }}>
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
                    </>
                ) : (
                    <div className="empty-state" style={{ padding: '48px', textAlign: 'center' }}>
                        <i className="bi bi-inbox" style={{ fontSize: '40px', color: '#94a3b8' }}></i>
                        <h4 style={{ marginTop: '12px', color: '#334155' }}>Tidak Ada Pengajuan Pemesanan</h4>
                        <p style={{ color: '#64748b', fontSize: '13px' }}>Tidak ada data pada tab status atau kriteria filter yang dipilih.</p>
                    </div>
                )}
            </div>

            {/* Modal Approve */}
            <Modal
                isOpen={!!approveTarget}
                onClose={() => setApproveTarget(null)}
                title="Persetujuan Pemesanan Ruangan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setApproveTarget(null)} disabled={isApproving}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#059669', borderColor: '#059669' }} onClick={handleApproveSubmit} disabled={isApproving}>
                            {isApproving ? 'Menyetujui...' : 'Ya, Setujui Pemesanan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', marginBottom: '14px' }}>
                    Setujui pengajuan pemesanan <strong>{approveTarget?.kode_pemesanan}</strong> untuk kegiatan <em>{approveTarget?.judul_kegiatan}</em>?
                </p>
                <div className="form-group">
                    <label style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600, color: '#334155' }}>
                        Catatan Admin (Opsional):
                    </label>
                    <textarea
                        className="login-input"
                        rows={2}
                        placeholder="Contoh: Disetujui dengan catatan kunci ruangan dapat diambil di pos satpam."
                        value={catatanAdmin}
                        onChange={(e) => setCatatanAdmin(e.target.value)}
                    />
                </div>
            </Modal>

            {/* Modal Reject */}
            <Modal
                isOpen={!!rejectTarget}
                onClose={() => setRejectTarget(null)}
                title="Penolakan Pemesanan Ruangan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setRejectTarget(null)} disabled={isRejecting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleRejectSubmit} disabled={isRejecting}>
                            {isRejecting ? 'Menolak...' : 'Tolak Pengajuan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', marginBottom: '14px' }}>
                    Tolak pengajuan pemesanan <strong>{rejectTarget?.kode_pemesanan}</strong> ({rejectTarget?.judul_kegiatan})?
                </p>
                <div className="form-group">
                    <label className="required" style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600, color: '#334155' }}>
                        Alasan Penolakan (Wajib Diisi):
                    </label>
                    <textarea
                        className="login-input"
                        rows={3}
                        placeholder="Tuliskan alasan penolakan agar pemohon dapat mengetahuinya..."
                        value={alasanPenolakan}
                        onChange={(e) => setAlasanPenolakan(e.target.value)}
                        required
                    />
                </div>
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
                            {isDeleting ? 'Menghapus...' : 'Hapus Permanen'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah Anda yakin ingin menghapus pemesanan <strong>{deleteTarget?.kode_pemesanan}</strong> ({deleteTarget?.judul_kegiatan}) secara permanen?
                </p>
            </Modal>
        </div>
    );
};
