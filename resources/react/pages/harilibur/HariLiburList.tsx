import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { HariLibur, PaginatedData } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const HariLiburList: React.FC = () => {
    const [data, setData] = useState<PaginatedData<HariLibur> | null>(null);
    const [years, setYears] = useState<number[]>([]);
    const [selectedYear, setSelectedYear] = useState<string>(String(new Date().getFullYear()));
    const [selectedCategory, setSelectedCategory] = useState<string>('');
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal Create
    const [isCreateOpen, setIsCreateOpen] = useState(false);
    const [formTanggal, setFormTanggal] = useState('');
    const [formKeterangan, setFormKeterangan] = useState('');
    const [formKategori, setFormKategori] = useState('libur_nasional');
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Syncing state
    const [isSyncing, setIsSyncing] = useState(false);

    // Delete
    const [deleteTarget, setDeleteTarget] = useState<HariLibur | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await adminService.getHariLiburList({
                tahun: selectedYear || undefined,
                kategori: selectedCategory || undefined,
            });
            if (res.status === 'success') {
                setData(res.data.items);
                setYears(res.data.available_years || []);
            }
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, [selectedYear, selectedCategory]);

    const handleCreateSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        try {
            const res = await adminService.createHariLibur({
                tanggal: formTanggal,
                keterangan: formKeterangan,
                kategori: formKategori,
            });
            setAlertMessage({ type: 'success', text: res.message || 'Hari libur berhasil ditambahkan.' });
            setIsCreateOpen(false);
            setFormTanggal('');
            setFormKeterangan('');
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menambahkan hari libur.' });
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleSync = async () => {
        setIsSyncing(true);
        try {
            const res = await adminService.syncHariLibur(selectedYear);
            setAlertMessage({ type: 'success', text: res.message || 'Sinkronisasi berhasil.' });
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal sinkronisasi data hari libur.' });
        } finally {
            setIsSyncing(false);
        }
    };

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteHariLibur(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Data hari libur berhasil dihapus.' });
            setDeleteTarget(null);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menghapus hari libur.' });
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
                        <i className="bi bi-calendar2-week" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Master Hari Libur & Cuti Bersama
                    </h1>
                    <p>Sinkronisasi kalender hari libur nasional dan kelola libur operasional kantor</p>
                </div>
                <div style={{ display: 'flex', gap: '10px' }}>
                    <button
                        type="button"
                        className="btn-secondary"
                        onClick={handleSync}
                        disabled={isSyncing}
                    >
                        <i className={`bi bi-arrow-repeat ${isSyncing ? 'spin' : ''}`}></i>
                        {isSyncing ? 'Menyinkronkan...' : `Sinkronkan API Tahun ${selectedYear}`}
                    </button>
                    <button type="button" className="btn-primary" onClick={() => setIsCreateOpen(true)}>
                        <i className="bi bi-plus-circle-fill"></i> Tambah Manual
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

            {/* Filter Bar */}
            <div style={{ background: '#fff', borderRadius: '12px', padding: '16px 20px', border: '1px solid #e2e8f0', marginBottom: '20px', display: 'flex', gap: '14px', flexWrap: 'wrap' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <label style={{ fontSize: '13px', fontWeight: 600 }}>Tahun:</label>
                    <select
                        className="login-input"
                        value={selectedYear}
                        onChange={(e) => setSelectedYear(e.target.value)}
                        style={{ height: '38px', minWidth: '120px', fontSize: '13px' }}
                    >
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2027">2027</option>
                        {years.map((y) => (
                            <option key={y} value={y}>{y}</option>
                        ))}
                    </select>
                </div>

                <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <label style={{ fontSize: '13px', fontWeight: 600 }}>Kategori:</label>
                    <select
                        className="login-input"
                        value={selectedCategory}
                        onChange={(e) => setSelectedCategory(e.target.value)}
                        style={{ height: '38px', minWidth: '180px', fontSize: '13px' }}
                    >
                        <option value="">Semua Kategori</option>
                        <option value="libur_nasional">Libur Nasional</option>
                        <option value="cuti_bersama">Cuti Bersama</option>
                        <option value="internal">Libur Internal BI</option>
                    </select>
                </div>
            </div>

            {/* Table */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                {isLoading ? (
                    <LoadingSpinner message="Memuat hari libur..." />
                ) : (
                    <div className="table-responsive">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan Libur</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data && data.data.length > 0 ? (
                                    data.data.map((item) => (
                                        <tr key={item.id}>
                                            <td style={{ fontWeight: 700, color: '#003b73' }}>
                                                {item.tanggal}
                                            </td>
                                            <td>
                                                <strong style={{ fontSize: '13.5px' }}>{item.keterangan}</strong>
                                            </td>
                                            <td>
                                                <span
                                                    style={{
                                                        padding: '4px 10px',
                                                        borderRadius: '9999px',
                                                        fontSize: '11.5px',
                                                        fontWeight: 700,
                                                        background: item.kategori === 'cuti_bersama' ? '#fffbeb' : (item.kategori === 'internal' ? '#f0f9ff' : '#fef2f2'),
                                                        color: item.kategori === 'cuti_bersama' ? '#d97706' : (item.kategori === 'internal' ? '#0284c7' : '#dc2626'),
                                                    }}
                                                >
                                                    {item.kategori === 'cuti_bersama' ? 'Cuti Bersama' : (item.kategori === 'internal' ? 'Libur Internal' : 'Libur Nasional')}
                                                </span>
                                            </td>
                                            <td>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeleteTarget(item)}
                                                    style={{ background: '#fef2f2', color: '#dc2626', border: '1px solid #fecdd3', borderRadius: '6px', padding: '4px 8px', fontSize: '12px', cursor: 'pointer' }}
                                                >
                                                    <i className="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={4} style={{ textAlign: 'center', padding: '32px', color: '#64748b' }}>
                                            Tidak ada data hari libur untuk filter tahun ini. Anda dapat menekan tombol <strong>Sinkronkan API</strong> di atas.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>

            {/* Modal Create Manual */}
            <Modal
                isOpen={isCreateOpen}
                onClose={() => setIsCreateOpen(false)}
                title="Tambah Hari Libur Manual"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsCreateOpen(false)} disabled={isSubmitting}>
                            Batal
                        </button>
                        <button type="submit" form="holidayForm" className="btn-primary" disabled={isSubmitting}>
                            {isSubmitting ? 'Menyimpan...' : 'Simpan'}
                        </button>
                    </>
                }
            >
                <form id="holidayForm" onSubmit={handleCreateSubmit}>
                    <div className="form-group" style={{ marginBottom: '16px' }}>
                        <label className="required" style={{ display: 'block', marginBottom: '6px', fontWeight: 600, fontSize: '13px' }}>
                            Tanggal
                        </label>
                        <input
                            type="date"
                            className="login-input"
                            value={formTanggal}
                            onChange={(e) => setFormTanggal(e.target.value)}
                            required
                        />
                    </div>

                    <div className="form-group" style={{ marginBottom: '16px' }}>
                        <label className="required" style={{ display: 'block', marginBottom: '6px', fontWeight: 600, fontSize: '13px' }}>
                            Keterangan Hari Libur
                        </label>
                        <input
                            type="text"
                            className="login-input"
                            value={formKeterangan}
                            onChange={(e) => setFormKeterangan(e.target.value)}
                            placeholder="Contoh: Hari Ulang Tahun KPwBI Sulut"
                            required
                        />
                    </div>

                    <div className="form-group">
                        <label className="required" style={{ display: 'block', marginBottom: '6px', fontWeight: 600, fontSize: '13px' }}>
                            Kategori
                        </label>
                        <select
                            className="login-input"
                            value={formKategori}
                            onChange={(e) => setFormKategori(e.target.value)}
                            required
                        >
                            <option value="libur_nasional">Libur Nasional</option>
                            <option value="cuti_bersama">Cuti Bersama</option>
                            <option value="internal">Libur Internal BI</option>
                        </select>
                    </div>
                </form>
            </Modal>

            {/* Modal Delete */}
            <Modal
                isOpen={!!deleteTarget}
                onClose={() => setDeleteTarget(null)}
                title="Hapus Hari Libur"
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
                    Hapus hari libur <strong>{deleteTarget?.keterangan}</strong> ({deleteTarget?.tanggal})?
                </p>
            </Modal>
        </div>
    );
};
