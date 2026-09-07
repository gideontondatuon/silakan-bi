import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { LayoutRuangan, Ruangan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { Badge } from '../../components/common/Badge';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const RuanganList: React.FC = () => {
    const [ruanganList, setRuanganList] = useState<Ruangan[]>([]);
    const [layoutOptions, setLayoutOptions] = useState<LayoutRuangan[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [searchQuery, setSearchQuery] = useState('');
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal Create / Edit
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingRoom, setEditingRoom] = useState<Ruangan | null>(null);
    const [formNama, setFormNama] = useState('');
    const [formLokasi, setFormLokasi] = useState('');
    const [formKapasitas, setFormKapasitas] = useState('');
    const [formStatus, setFormStatus] = useState<'aktif' | 'nonaktif' | 'perawatan'>('aktif');
    const [formLayouts, setFormLayouts] = useState<number[]>([]);
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Modal Delete
    const [deleteTarget, setDeleteTarget] = useState<Ruangan | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await adminService.getRuanganList({ q: searchQuery || undefined });
            if (res.status === 'success') {
                const data = Array.isArray(res.data) ? res.data : res.data.data;
                setRuanganList(data || []);
            }
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
        // Load layout options for checkboxes
        adminService.getLayoutList().then((res) => {
            if (res.status === 'success') {
                const data = Array.isArray(res.data) ? res.data : res.data.data;
                setLayoutOptions(data || []);
            }
        });
    }, []);

    const openCreateModal = () => {
        setEditingRoom(null);
        setFormNama('');
        setFormLokasi('');
        setFormKapasitas('');
        setFormStatus('aktif');
        setFormLayouts([]);
        setIsModalOpen(true);
    };

    const openEditModal = (room: Ruangan) => {
        setEditingRoom(room);
        setFormNama(room.nama_ruangan);
        setFormLokasi(room.lokasi);
        setFormKapasitas(String(room.kapasitas));
        setFormStatus(room.status);
        setFormLayouts(room.layouts?.map((l) => l.id) || []);
        setIsModalOpen(true);
    };

    const handleFormSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        try {
            const payload = {
                nama_ruangan: formNama,
                lokasi: formLokasi,
                kapasitas: Number(formKapasitas),
                status: formStatus,
                layouts: formLayouts,
            };

            if (editingRoom) {
                const res = await adminService.updateRuangan(editingRoom.id, payload);
                setAlertMessage({ type: 'success', text: res.message || 'Ruangan berhasil diperbarui.' });
            } else {
                const res = await adminService.createRuangan(payload);
                setAlertMessage({ type: 'success', text: res.message || 'Ruangan baru berhasil ditambahkan.' });
            }

            setIsModalOpen(false);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyimpan ruangan.' });
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleDeleteSubmit = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteRuangan(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Ruangan berhasil dihapus.' });
            setDeleteTarget(null);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menghapus ruangan.' });
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
                        <i className="bi bi-building" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Master Data Ruangan Rapat
                    </h1>
                    <p>Kelola ruangan rapat, kapasitas peserta, denah lokasi, dan layout tersedia</p>
                </div>
                <div>
                    <button type="button" className="btn-primary" onClick={openCreateModal}>
                        <i className="bi bi-plus-circle-fill"></i> Tambah Ruangan Baru
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

            {/* Content Table */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                {isLoading ? (
                    <LoadingSpinner message="Memuat master data ruangan..." />
                ) : (
                    <div className="table-responsive">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Nama Ruangan</th>
                                    <th>Lokasi / Lantai</th>
                                    <th>Kapasitas</th>
                                    <th>Layout Tersedia</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {ruanganList.map((room) => (
                                    <tr key={room.id}>
                                        <td>
                                            <strong style={{ fontSize: '14px', color: '#003b73' }}>{room.nama_ruangan}</strong>
                                        </td>
                                        <td>{room.lokasi}</td>
                                        <td><strong>{room.kapasitas} Orang</strong></td>
                                        <td>
                                            <div style={{ display: 'flex', gap: '4px', flexWrap: 'wrap' }}>
                                                {room.layouts && room.layouts.length > 0 ? (
                                                    room.layouts.map((l) => (
                                                        <span key={l.id} style={{ background: '#f1f5f9', color: '#475569', padding: '2px 8px', borderRadius: '6px', fontSize: '11px', fontWeight: 600 }}>
                                                            {l.nama_layout}
                                                        </span>
                                                    ))
                                                ) : (
                                                    <span style={{ color: '#94a3b8', fontSize: '12px' }}>Standar</span>
                                                )}
                                            </div>
                                        </td>
                                        <td>
                                            <Badge status={room.status} />
                                        </td>
                                        <td>
                                            <div style={{ display: 'flex', gap: '6px' }}>
                                                <button
                                                    type="button"
                                                    onClick={() => openEditModal(room)}
                                                    className="btn-table-action"
                                                    style={{ border: 'none', background: 'none' }}
                                                >
                                                    <i className="bi bi-pencil"></i> Edit
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeleteTarget(room)}
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
                title={editingRoom ? `Edit Ruangan: ${editingRoom.nama_ruangan}` : 'Tambah Ruangan Rapat Baru'}
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsModalOpen(false)} disabled={isSubmitting}>
                            Batal
                        </button>
                        <button type="submit" form="roomForm" className="btn-primary" disabled={isSubmitting}>
                            {isSubmitting ? 'Menyimpan...' : 'Simpan Data'}
                        </button>
                    </>
                }
            >
                <form id="roomForm" onSubmit={handleFormSubmit}>
                    <div className="form-group" style={{ marginBottom: '16px' }}>
                        <label className="required" style={{ display: 'block', marginBottom: '6px', fontWeight: 600, fontSize: '13px' }}>
                            Nama Ruangan
                        </label>
                        <input
                            type="text"
                            className="login-input"
                            value={formNama}
                            onChange={(e) => setFormNama(e.target.value)}
                            placeholder="Contoh: Ruang Rapat Maleo"
                            required
                        />
                    </div>

                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px', marginBottom: '16px' }}>
                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '6px', fontWeight: 600, fontSize: '13px' }}>
                                Lokasi / Lantai
                            </label>
                            <input
                                type="text"
                                className="login-input"
                                value={formLokasi}
                                onChange={(e) => setFormLokasi(e.target.value)}
                                placeholder="Contoh: Gedung A, Lantai 3"
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '6px', fontWeight: 600, fontSize: '13px' }}>
                                Kapasitas Maksimal (Orang)
                            </label>
                            <input
                                type="number"
                                min={1}
                                className="login-input"
                                value={formKapasitas}
                                onChange={(e) => setFormKapasitas(e.target.value)}
                                placeholder="Contoh: 30"
                                required
                            />
                        </div>
                    </div>

                    <div className="form-group" style={{ marginBottom: '16px' }}>
                        <label className="required" style={{ display: 'block', marginBottom: '6px', fontWeight: 600, fontSize: '13px' }}>
                            Status Ruangan
                        </label>
                        <select
                            className="login-input"
                            value={formStatus}
                            onChange={(e) => setFormStatus(e.target.value as any)}
                            required
                        >
                            <option value="aktif">Aktif (Dapat Dipesan)</option>
                            <option value="nonaktif">Nonaktif (Tidak Ditampilkan)</option>
                            <option value="perawatan">Perawatan / Maintenance</option>
                        </select>
                    </div>

                    <div className="form-group">
                        <label style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13px' }}>
                            Pilihan Layout yang Didukung:
                        </label>
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(180px, 1fr))', gap: '8px' }}>
                            {layoutOptions.map((layout) => (
                                <label key={layout.id} style={{ display: 'flex', alignItems: 'center', gap: '6px', fontSize: '13px', cursor: 'pointer' }}>
                                    <input
                                        type="checkbox"
                                        checked={formLayouts.includes(layout.id)}
                                        onChange={(e) => {
                                            if (e.target.checked) {
                                                setFormLayouts((prev) => [...prev, layout.id]);
                                            } else {
                                                setFormLayouts((prev) => prev.filter((id) => id !== layout.id));
                                            }
                                        }}
                                    />
                                    {layout.nama_layout}
                                </label>
                            ))}
                        </div>
                    </div>
                </form>
            </Modal>

            {/* Modal Delete */}
            <Modal
                isOpen={!!deleteTarget}
                onClose={() => setDeleteTarget(null)}
                title="Hapus Ruangan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setDeleteTarget(null)} disabled={isDeleting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDeleteSubmit} disabled={isDeleting}>
                            {isDeleting ? 'Menghapus...' : 'Ya, Hapus'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah Anda yakin ingin menghapus ruangan <strong>{deleteTarget?.nama_ruangan}</strong>? Ruangan yang memiliki riwayat pemesanan tidak dapat dihapus, namun dapat dinonaktifkan.
                </p>
            </Modal>
        </div>
    );
};
