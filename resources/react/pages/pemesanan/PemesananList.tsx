import React, { useEffect, useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { bookingService } from '../../services/bookingService';
import { PaginatedData, Pemesanan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const PemesananList: React.FC = () => {
    const location = useLocation();
    const [data, setData] = useState<PaginatedData<Pemesanan> | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [page, setPage] = useState(1);
    const [flashSuccess, setFlashSuccess] = useState<string | null>(
        location.state?.flashSuccess || null
    );

    // Cancel modal state
    const [cancelTarget, setCancelTarget] = useState<Pemesanan | null>(null);
    const [isCancelling, setIsCancelling] = useState(false);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    const loadData = async (targetPage = page) => {
        setIsLoading(true);
        try {
            const res = await bookingService.getPemesananList({
                page: targetPage,
            });
            if (res.status === 'success') {
                setData(res.data);
            }
        } catch (e) {
            console.error('Failed to load bookings:', e);
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData(page);
    }, [page]);

    const handleCancelSubmit = async () => {
        if (!cancelTarget) return;
        setIsCancelling(true);
        try {
            const res = await bookingService.cancelPemesanan(cancelTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Pengajuan pemesanan berhasil dibatalkan.' });
            setCancelTarget(null);
            loadData(page);
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal membatalkan pengajuan pemesanan.' });
        } finally {
            setIsCancelling(false);
        }
    };

    const formatTanggal = (dateStr?: string) => {
        if (!dateStr) return '-';
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

    const renderStatusBadge = (item: Pemesanan) => {
        const statusVal = typeof item.status === 'object' ? (item.status as any).value : item.status;
        if (statusVal === 'Selesai' || item.is_finished) {
            return (
                <span className="badge" style={{ background: '#f1f5f9', color: '#475569', border: '1px solid #cbd5e1', fontWeight: 700 }}>
                    <i className="bi bi-check2-all"></i> Selesai
                </span>
            );
        }
        if (statusVal === 'Pending') {
            return (
                <span className="badge badge-warning">
                    <i className="bi bi-clock"></i> Pending
                </span>
            );
        }
        if (statusVal === 'Disetujui') {
            return (
                <span className="badge badge-success">
                    <i className="bi bi-check-circle"></i> Disetujui
                </span>
            );
        }
        if (statusVal === 'Ditolak') {
            return (
                <span className="badge badge-danger">
                    <i className="bi bi-x-circle"></i> Ditolak
                </span>
            );
        }
        return (
            <span className="badge badge-secondary">
                <i className="bi bi-dash-circle"></i> Cancel
            </span>
        );
    };

    const items = data?.data || [];

    return (
        <div>
            {/* Header matching Blade 1:1 */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-journal-text" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Pemesanan Saya
                    </h1>
                    <p>Daftar seluruh pengajuan penggunaan ruangan Anda.</p>
                </div>
                <Link to="/pemesanan/create" className="btn-primary" style={{ textDecoration: 'none' }}>
                    <i className="bi bi-plus-circle"></i> Buat Pemesanan
                </Link>
            </div>

            {flashSuccess && (
                <AlertBanner
                    type="success"
                    message={flashSuccess}
                    onClose={() => setFlashSuccess(null)}
                />
            )}

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Table Container matching Blade 1:1 */}
            <div className="dashboard-section" id="live-pemesanan-container">
                <div className="table-wrapper">
                    <table className="data-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Kegiatan</th>
                                <th>Ruangan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {isLoading ? (
                                <tr>
                                    <td colSpan={7} style={{ textAlign: 'center', padding: '48px 24px' }}>
                                        <LoadingSpinner message="Memuat daftar pemesanan..." />
                                    </td>
                                </tr>
                            ) : items.length > 0 ? (
                                items.map((item) => (
                                    <tr key={item.id}>
                                        <td>
                                            <span style={{ fontFamily: 'monospace', fontSize: '11.5px', color: '#005baa', fontWeight: 700 }}>
                                                {item.kode_pemesanan}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style={{ color: '#003b73' }}>{item.judul_kegiatan}</strong>
                                            {item.jenis_kegiatan && (
                                                <div style={{ marginTop: '3px' }}>
                                                    <span
                                                        style={{
                                                            fontSize: '10.5px',
                                                            fontWeight: 700,
                                                            padding: '2px 7px',
                                                            borderRadius: '5px',
                                                            background: item.jenis_kegiatan === 'Eksternal' ? '#fef3c7' : '#e0f2fe',
                                                            color: item.jenis_kegiatan === 'Eksternal' ? '#92400e' : '#0369a1',
                                                        }}
                                                    >
                                                        <i className={`bi ${item.jenis_kegiatan === 'Eksternal' ? 'bi-globe2' : 'bi-building'}`} style={{ marginRight: '3px' }}></i>
                                                        {item.jenis_kegiatan}
                                                    </span>
                                                </div>
                                            )}
                                        </td>
                                        <td>
                                            <strong>{item.ruangan?.nama_ruangan || '-'}</strong>
                                            {item.layout && (
                                                <>
                                                    <br />
                                                    <small style={{ color: '#64748b' }}>{item.layout.nama_layout}</small>
                                                </>
                                            )}
                                        </td>
                                        <td>
                                            <strong>{formatTanggal(item.tanggal_kegiatan)}</strong>
                                        </td>
                                        <td style={{ color: '#64748b', whiteSpace: 'nowrap' }}>
                                            {item.waktu_mulai?.substring(0, 5)} – {item.waktu_selesai?.substring(0, 5)}
                                        </td>
                                        <td>
                                            {renderStatusBadge(item)}
                                        </td>
                                        <td>
                                            <div className="action-group">
                                                <Link to={`/pemesanan/${item.id}`} className="btn-info btn-sm" style={{ textDecoration: 'none' }}>
                                                    <i className="bi bi-eye"></i> Detail
                                                </Link>
                                                {(typeof item.status === 'object' ? (item.status as any).value : item.status) === 'Pending' && (
                                                    <button
                                                        type="button"
                                                        className="btn-danger btn-sm"
                                                        onClick={() => setCancelTarget(item)}
                                                        title="Batalkan Pemesanan"
                                                    >
                                                        <i className="bi bi-x-circle"></i> Batalkan
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={7}>
                                        <div className="empty-state">
                                            <i className="bi bi-inbox"></i>
                                            <p>
                                                Belum ada pengajuan pemesanan.{' '}
                                                <Link to="/pemesanan/create">Buat sekarang</Link>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {data && data.last_page > 1 && (
                    <div style={{ padding: '16px 24px', borderTop: '1px solid #f1f5f9', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '12px' }}>
                        <small style={{ color: '#64748b' }}>
                            Menampilkan {data.from || 0}–{data.to || 0} dari total {data.total} data
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
                                {page} / {data.last_page}
                            </span>
                            <button
                                type="button"
                                className="btn-secondary"
                                style={{ padding: '6px 14px', fontSize: '12px' }}
                                disabled={page >= data.last_page}
                                onClick={() => setPage((p) => p + 1)}
                            >
                                Selanjutnya &rarr;
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* Cancel Confirmation Modal */}
            <Modal
                isOpen={!!cancelTarget}
                onClose={() => setCancelTarget(null)}
                title="Batalkan Pemesanan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setCancelTarget(null)} disabled={isCancelling}>
                            Tutup
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleCancelSubmit} disabled={isCancelling}>
                            {isCancelling ? 'Membatalkan...' : 'Ya, Batalkan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah Anda yakin ingin membatalkan pengajuan pemesanan <strong>{cancelTarget?.kode_pemesanan}</strong> ({cancelTarget?.judul_kegiatan})?
                </p>
            </Modal>
        </div>
    );
};
