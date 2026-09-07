import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { LayoutRuangan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const LayoutList: React.FC = () => {
    const [layouts, setLayouts] = useState<LayoutRuangan[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal Create / Edit
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingLayout, setEditingLayout] = useState<LayoutRuangan | null>(null);
    const [namaLayout, setNamaLayout] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Modal Delete
    const [deleteTarget, setDeleteTarget] = useState<LayoutRuangan | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await adminService.getLayoutList();
            if (res.status === 'success') {
                const data = Array.isArray(res.data) ? res.data : res.data.data;
                setLayouts(data || []);
            }
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, []);

    const openCreate = () => {
        setEditingLayout(null);
        setNamaLayout('');
        setIsModalOpen(true);
    };

    const openEdit = (l: LayoutRuangan) => {
        setEditingLayout(l);
        setNamaLayout(l.nama_layout);
        setIsModalOpen(true);
    };

    const handleFormSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        try {
            if (editingLayout) {
                const res = await adminService.updateLayout(editingLayout.id, { nama_layout: namaLayout });
                setAlertMessage({ type: 'success', text: res.message || 'Layout berhasil diperbarui.' });
            } else {
                const res = await adminService.createLayout({ nama_layout: namaLayout });
                setAlertMessage({ type: 'success', text: res.message || 'Layout baru berhasil ditambahkan.' });
            }
            setIsModalOpen(false);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyimpan layout.' });
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteLayout(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Layout berhasil dihapus.' });
            setDeleteTarget(null);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menghapus layout.' });
        } finally {
            setIsDeleting(false);
        }
    };

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className="bi bi-layout-text-sidebar-reverse" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Master Data Layout Ruangan
                    </h1>
                    <p>Kelola tipe susunan meja dan kursi rapat (Classroom, U-Shape, Theater, Round Table, dll.)</p>
                </div>
                <div>
                    <button type="button" className="btn-primary" onClick={openCreate}>
                        <i className="bi bi-plus-circle-fill"></i> Tambah Layout Baru
                    </button>
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Table */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                {isLoading ? (
                    <LoadingSpinner message="Memuat master data layout..." />
                ) : (
                    <div className="table-responsive">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Tipe Layout</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {layouts.map((item, idx) => (
                                    <tr key={item.id}>
                                        <td style={{ color: '#64748b' }}>{idx + 1}</td>
                                        <td>
                                            <strong style={{ fontSize: '14px', color: '#003b73' }}>
                                                {item.nama_layout}
                                            </strong>
                                        </td>
                                        <td>
                                            <div style={{ display: 'flex', gap: '6px' }}>
                                                <button
                                                    type="button"
                                                    onClick={() => openEdit(item)}
                                                    className="btn-table-action"
                                                    style={{ border: 'none', background: 'none' }}
                                                >
                                                    <i className="bi bi-pencil"></i> Edit
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeleteTarget(item)}
                                                    style={{ background: '#fef2f2', color: '#dc2626', border: '1px solid #fecdd3', borderRadius: '6px', padding: '4px 8px', fontSize: '12px', cursor: 'pointer' }}
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
                )}
            </div>

            {/* Modal Create / Edit */}
            <Modal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                title={editingLayout ? `Edit Layout: ${editingLayout.nama_layout}` : 'Tambah Layout Baru'}
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsModalOpen(false)} disabled={isSubmitting}>
                            Batal
                        </button>
                        <button type="submit" form="layoutForm" className="btn-primary" disabled={isSubmitting}>
                            {isSubmitting ? 'Menyimpan...' : 'Simpan Layout'}
                        </button>
                    </>
                }
            >
                <form id="layoutForm" onSubmit={handleFormSubmit}>
                    <div className="form-group">
                        <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13px' }}>
                            Nama Layout / Susunan Meja
                        </label>
                        <input
                            type="text"
                            className="login-input"
                            value={namaLayout}
                            onChange={(e) => setNamaLayout(e.target.value)}
                            placeholder="Contoh: Classroom, U-Shape, Theater, Round Table"
                            required
                        />
                    </div>
                </form>
            </Modal>

            {/* Modal Delete */}
            <Modal
                isOpen={!!deleteTarget}
                onClose={() => setDeleteTarget(null)}
                title="Hapus Layout"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setDeleteTarget(null)} disabled={isDeleting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDelete} disabled={isDeleting}>
                            {isDeleting ? 'Menghapus...' : 'Ya, Hapus'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah Anda yakin ingin menghapus tipe layout <strong>{deleteTarget?.nama_layout}</strong>?
                </p>
            </Modal>
        </div>
    );
};
