import React, { useEffect, useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { LayoutRuangan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const LayoutList: React.FC = () => {
    const location = useLocation();
    const [layouts, setLayouts] = useState<LayoutRuangan[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal Delete
    const [deleteTarget, setDeleteTarget] = useState<LayoutRuangan | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    // Pagination
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [totalItems, setTotalItems] = useState(0);
    const [perPage] = useState(10);

    const loadData = async (page = 1) => {
        setIsLoading(true);
        try {
            const res = await adminService.getLayoutList({ page, per_page: perPage });
            if (res.status === 'success') {
                if (Array.isArray(res.data)) {
                    setLayouts(res.data);
                    setTotalItems(res.data.length);
                    setTotalPages(1);
                    setCurrentPage(1);
                } else if (res.data?.data) {
                    setLayouts(res.data.data);
                    setCurrentPage(res.data.current_page || 1);
                    setTotalPages(res.data.last_page || 1);
                    setTotalItems(res.data.total || res.data.data.length);
                }
            }
        } catch {
            setAlertMessage({ type: 'error', text: 'Gagal memuat data layout ruangan.' });
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        if (location.state?.flashMessage) {
            setAlertMessage({ type: 'success', text: location.state.flashMessage });
            window.history.replaceState({}, document.title);
        }
        loadData(currentPage);
    }, [currentPage]);

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteLayout(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Layout berhasil dihapus.' });
            setDeleteTarget(null);
            loadData(currentPage);
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal menghapus layout ruangan.',
            });
            setDeleteTarget(null);
        } finally {
            setIsDeleting(false);
        }
    };

    return (
        <div>
            {/* Page Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-layout-text-sidebar-reverse" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Data Layout Ruangan
                    </h1>
                    <p>Kelola konfigurasi layout dan kapasitas ruangan kantor.</p>
                </div>
                <Link to="/admin/layout/create" className="btn-primary">
                    <i className="bi bi-plus-lg"></i> Tambah Layout
                </Link>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Table Section */}
            <div className="dashboard-section">
                <div className="table-wrapper">
                    {isLoading ? (
                        <LoadingSpinner message="Memuat data layout ruangan..." />
                    ) : (
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th style={{ width: '40px' }}>#</th>
                                    <th>Nama Layout</th>
                                    <th>Terdapat di Ruangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {layouts.length > 0 ? (
                                    layouts.map((item, idx) => (
                                        <tr key={item.id}>
                                            <td style={{ color: '#94a3b8', fontSize: '12px' }}>
                                                {(currentPage - 1) * perPage + idx + 1}
                                            </td>
                                            <td>
                                                <span style={{ display: 'flex', alignItems: 'center', gap: '9px' }}>
                                                    <span
                                                        style={{
                                                            width: '34px',
                                                            height: '34px',
                                                            borderRadius: '8px',
                                                            background: 'linear-gradient(135deg, #e8f1fb, #cce0f5)',
                                                            display: 'flex',
                                                            alignItems: 'center',
                                                            justifyContent: 'center',
                                                            color: '#005baa',
                                                            fontSize: '15px',
                                                            flexShrink: 0,
                                                        }}
                                                    >
                                                        <i className="bi bi-layout-wtf"></i>
                                                    </span>
                                                    <strong style={{ color: '#003b73' }}>{item.nama_layout}</strong>
                                                </span>
                                            </td>
                                            <td>
                                                <div style={{ display: 'flex', flexWrap: 'wrap', gap: '6px' }}>
                                                    {item.ruangans && item.ruangans.length > 0 ? (
                                                        item.ruangans.map((r) => (
                                                            <span key={r.id} className="badge badge-secondary" style={{ fontSize: '11px' }}>
                                                                <i className="bi bi-building"></i> {r.nama_ruangan}
                                                            </span>
                                                        ))
                                                    ) : item.ruangan ? (
                                                        <span className="badge badge-secondary" style={{ fontSize: '11px' }}>
                                                            <i className="bi bi-building"></i> {item.ruangan.nama_ruangan}
                                                        </span>
                                                    ) : (
                                                        <span style={{ color: '#94a3b8', fontStyle: 'italic', fontSize: '12px' }}>
                                                            Belum ditautkan ke ruangan
                                                        </span>
                                                    )}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="action-group">
                                                    <Link to={`/admin/layout/${item.id}/edit`} className="btn-secondary btn-sm">
                                                        <i className="bi bi-pencil"></i> Edit
                                                    </Link>
                                                    <button
                                                        type="button"
                                                        className="btn-danger btn-sm"
                                                        onClick={() => setDeleteTarget(item)}
                                                    >
                                                        <i className="bi bi-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={4}>
                                            <div className="empty-state">
                                                <i className="bi bi-layout-text-sidebar"></i>
                                                <p>
                                                    Belum ada data layout ruangan.{' '}
                                                    <Link to="/admin/layout/create">Tambah sekarang</Link>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    )}
                </div>

                {totalPages > 1 && (
                    <div
                        style={{
                            padding: '16px 24px',
                            borderTop: '1px solid #f1f5f9',
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'center',
                            flexWrap: 'wrap',
                            gap: '12px',
                        }}
                    >
                        <div style={{ fontSize: '13px', color: '#64748b' }}>
                            Menampilkan {(currentPage - 1) * perPage + 1} - {Math.min(currentPage * perPage, totalItems)} dari {totalItems} layout
                        </div>
                        <div style={{ display: 'flex', gap: '6px' }}>
                            <button
                                type="button"
                                className="btn-secondary btn-sm"
                                disabled={currentPage <= 1}
                                onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
                            >
                                <i className="bi bi-chevron-left"></i> Sebelumnya
                            </button>
                            {Array.from({ length: totalPages }, (_, idx) => idx + 1).map((pg) => (
                                <button
                                    key={pg}
                                    type="button"
                                    className={`btn-sm ${pg === currentPage ? 'btn-primary' : 'btn-secondary'}`}
                                    onClick={() => setCurrentPage(pg)}
                                >
                                    {pg}
                                </button>
                            ))}
                            <button
                                type="button"
                                className="btn-secondary btn-sm"
                                disabled={currentPage >= totalPages}
                                onClick={() => setCurrentPage((p) => Math.min(totalPages, p + 1))}
                            >
                                Selanjutnya <i className="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* Custom Modal Delete Layout (1:1 with Blade submitFormWithConfirm) */}
            {deleteTarget && (
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
                    onClick={() => !isDeleting && setDeleteTarget(null)}
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
                                    background: '#fee2e2',
                                    color: '#dc2626',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    fontSize: '20px',
                                    flexShrink: 0,
                                    border: '1px solid #fecdd3',
                                }}
                            >
                                <i className="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div>
                                <h3 style={{ margin: 0, fontSize: '16px', fontWeight: 700, color: '#0f172a' }}>
                                    Hapus Layout
                                </h3>
                                <p style={{ margin: '2px 0 0', fontSize: '12px', color: '#64748b' }}>
                                    Sistem Informasi SILAKAN BI
                                </p>
                            </div>
                        </div>

                        <div style={{ padding: '22px 24px' }}>
                            <p style={{ margin: 0, fontSize: '13.5px', color: '#334155', lineHeight: 1.5 }}>
                                Apakah Anda yakin ingin menghapus layout{' '}
                                <strong>{deleteTarget.nama_layout}</strong>?
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
                                onClick={() => setDeleteTarget(null)}
                                disabled={isDeleting}
                                style={{ padding: '9px 18px', fontSize: '13px', borderRadius: '10px' }}
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                onClick={handleDelete}
                                disabled={isDeleting}
                                style={{
                                    background: '#dc2626',
                                    color: '#fff',
                                    padding: '9px 20px',
                                    fontSize: '13px',
                                    fontWeight: 600,
                                    borderRadius: '10px',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                    border: 'none',
                                    cursor: isDeleting ? 'not-allowed' : 'pointer',
                                    opacity: isDeleting ? 0.7 : 1,
                                }}
                            >
                                <i className="bi bi-trash"></i>
                                {isDeleting ? 'Menghapus...' : 'Ya, Hapus'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};
