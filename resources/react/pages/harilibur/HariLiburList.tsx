import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { HariLibur, PaginatedData } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const HariLiburList: React.FC = () => {
    const currentYear = new Date().getFullYear();

    const [hariLiburData, setHariLiburData] = useState<PaginatedData<HariLibur> | null>(null);
    const [tahunList, setTahunList] = useState<number[]>([]);
    const [selectedKategori, setSelectedKategori] = useState<string>('');
    const [selectedTahun, setSelectedTahun] = useState<string>('');
    const [currentPage, setCurrentPage] = useState<number>(1);
    const [isLoading, setIsLoading] = useState<boolean>(true);

    // Alert Banner
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Form Tambah Manual state
    const [formTanggal, setFormTanggal] = useState<string>('');
    const [formKeterangan, setFormKeterangan] = useState<string>('');
    const [formKategori, setFormKategori] = useState<'libur_nasional' | 'cuti_bersama' | 'internal'>('libur_nasional');
    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [errors, setErrors] = useState<Record<string, string>>({});

    // Modal Confirmation State (Sync & Delete)
    const [showSyncModal, setShowSyncModal] = useState<boolean>(false);
    const [isSyncing, setIsSyncing] = useState<boolean>(false);
    const [deleteTarget, setDeleteTarget] = useState<HariLibur | null>(null);
    const [isDeleting, setIsDeleting] = useState<boolean>(false);

    const loadData = async (page = 1) => {
        setIsLoading(true);
        try {
            const res = await adminService.getHariLiburList({
                kategori: selectedKategori || undefined,
                tahun: selectedTahun || undefined,
                page,
                per_page: 15,
            });

            if (res.status === 'success') {
                setHariLiburData(res.data.items);
                setTahunList(res.data.available_years || []);
                setCurrentPage(res.data.items.current_page || 1);
            }
        } catch {
            setAlertMessage({ type: 'error', text: 'Gagal memuat data hari libur.' });
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData(currentPage);
    }, [selectedKategori, selectedTahun, currentPage]);

    const handleFormSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSaving(true);
        setErrors({});

        try {
            const res = await adminService.createHariLibur({
                tanggal: formTanggal,
                keterangan: formKeterangan,
                kategori: formKategori,
            });

            setAlertMessage({
                type: 'success',
                text: res.message || 'Hari libur / cuti bersama berhasil ditambahkan.',
            });
            setFormTanggal('');
            setFormKeterangan('');
            setFormKategori('libur_nasional');
            loadData(1);
        } catch (err: any) {
            if (err.response?.data?.errors) {
                const apiErrors: Record<string, string> = {};
                for (const key of Object.keys(err.response.data.errors)) {
                    apiErrors[key] = err.response.data.errors[key][0];
                }
                setErrors(apiErrors);
            } else {
                setAlertMessage({
                    type: 'error',
                    text: err.response?.data?.message || 'Gagal menambahkan hari libur.',
                });
            }
        } finally {
            setIsSaving(false);
        }
    };

    const handleConfirmSync = async () => {
        setIsSyncing(true);
        try {
            const res = await adminService.syncHariLibur(currentYear);
            setAlertMessage({
                type: 'success',
                text: res.message || `Berhasil menyinkronkan data hari libur tahun ${currentYear}.`,
            });
            setShowSyncModal(false);
            loadData(1);
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal sinkronisasi data hari libur dari API resmi.',
            });
        } finally {
            setIsSyncing(false);
        }
    };

    const handleConfirmDelete = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);

        try {
            const res = await adminService.deleteHariLibur(deleteTarget.id);
            setAlertMessage({
                type: 'success',
                text: res.message || `Data libur ${deleteTarget.keterangan} berhasil dihapus.`,
            });
            setDeleteTarget(null);
            loadData(currentPage);
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal menghapus data hari libur.',
            });
            setDeleteTarget(null);
        } finally {
            setIsDeleting(false);
        }
    };

    const formatTanggalIndo = (dateStr: string) => {
        if (!dateStr) return '-';
        const datePart = dateStr.split('T')[0];
        const [y, m, d] = datePart.split('-').map(Number);
        const date = new Date(y, m - 1, d);

        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        const months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        const dayName = days[date.getDay()];
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();

        return `${dayName}, ${day} ${month} ${year}`;
    };

    const items = hariLiburData?.data || [];
    const totalPages = hariLiburData?.last_page || 1;
    const fromIndex = hariLiburData ? (hariLiburData.from || 1) : 1;

    return (
        <div>
            {/* Page Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-calendar2-week" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Kelola Hari Libur &amp; Cuti Bersama
                    </h1>
                    <p>Manajemen tanggal merah, hari libur nasional, cuti bersama, dan akhir pekan sistem SILAKAN.</p>
                </div>
                <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap' }}>
                    <button
                        type="button"
                        className="btn-primary"
                        onClick={() => setShowSyncModal(true)}
                    >
                        <i className="bi bi-cloud-download"></i> Sync API Libur &amp; Cuti {currentYear}
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

            {/* Dashboard Grid (Two parallel cards matching Blade) */}
            <div className="dashboard-grid">
                {/* Form Tambah Manual */}
                <div className="dashboard-section" style={{ marginBottom: 0 }}>
                    <div className="section-header">
                        <h2>
                            <i className="bi bi-plus-circle"></i> Tambah Hari Libur / Cuti Bersama
                        </h2>
                    </div>
                    <div style={{ padding: '24px' }}>
                        <form onSubmit={handleFormSubmit}>
                            <div className="form-group">
                                <label className="required">Tanggal</label>
                                <input
                                    type="date"
                                    name="tanggal"
                                    value={formTanggal}
                                    onChange={(e) => setFormTanggal(e.target.value)}
                                    required
                                />
                                {errors.tanggal && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.tanggal}
                                    </span>
                                )}
                            </div>

                            <div className="form-group">
                                <label className="required">Nama / Keterangan</label>
                                <input
                                    type="text"
                                    name="keterangan"
                                    value={formKeterangan}
                                    onChange={(e) => setFormKeterangan(e.target.value)}
                                    placeholder="Contoh: Cuti Bersama Idul Fitri"
                                    required
                                />
                                {errors.keterangan && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.keterangan}
                                    </span>
                                )}
                            </div>

                            <div className="form-group">
                                <label className="required">Kategori Hari Libur</label>
                                <select
                                    name="kategori"
                                    value={formKategori}
                                    onChange={(e) => setFormKategori(e.target.value as any)}
                                    required
                                >
                                    <option value="libur_nasional">Hari Libur Nasional</option>
                                    <option value="cuti_bersama">Cuti Bersama</option>
                                    <option value="internal">Libur Internal / Khusus BI</option>
                                </select>
                                {errors.kategori && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.kategori}
                                    </span>
                                )}
                            </div>

                            <div className="form-action" style={{ marginTop: '12px' }}>
                                <button
                                    type="submit"
                                    className="btn-primary"
                                    style={{ width: '100%' }}
                                    disabled={isSaving}
                                >
                                    <i className="bi bi-check-lg"></i>{' '}
                                    {isSaving ? 'Menyimpan...' : 'Simpan Data Libur'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {/* Data Tabel Hari Libur & Cuti Bersama */}
                <div className="dashboard-section" style={{ marginBottom: 0 }}>
                    <div className="section-header">
                        <h2>
                            <i className="bi bi-calendar-event"></i> Daftar Libur &amp; Cuti Bersama
                        </h2>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                            <select
                                name="kategori"
                                value={selectedKategori}
                                onChange={(e) => {
                                    setSelectedKategori(e.target.value);
                                    setCurrentPage(1);
                                }}
                                style={{
                                    padding: '4px 10px',
                                    fontSize: '12px',
                                    borderRadius: '6px',
                                    border: '1px solid #cbd5e1',
                                }}
                            >
                                <option value="">Semua Kategori</option>
                                <option value="libur_nasional">Libur Nasional</option>
                                <option value="cuti_bersama">Cuti Bersama</option>
                                <option value="internal">Internal BI</option>
                            </select>

                            <select
                                name="tahun"
                                value={selectedTahun}
                                onChange={(e) => {
                                    setSelectedTahun(e.target.value);
                                    setCurrentPage(1);
                                }}
                                style={{
                                    padding: '4px 10px',
                                    fontSize: '12px',
                                    borderRadius: '6px',
                                    border: '1px solid #cbd5e1',
                                }}
                            >
                                <option value="">Semua Tahun</option>
                                {tahunList.map((th) => (
                                    <option key={th} value={th}>
                                        Tahun {th}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div className="table-wrapper">
                        {isLoading ? (
                            <LoadingSpinner message="Memuat daftar libur..." />
                        ) : (
                            <table className="data-table">
                                <thead>
                                    <tr>
                                        <th style={{ width: '40px' }}>#</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Kategori</th>
                                        <th style={{ width: '60px' }}>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {items.length > 0 ? (
                                        items.map((item, index) => (
                                            <tr key={item.id}>
                                                <td style={{ color: '#94a3b8', fontSize: '12px' }}>
                                                    {fromIndex + index}
                                                </td>
                                                <td>
                                                    <strong style={{ color: '#003b73' }}>
                                                        {formatTanggalIndo(item.tanggal)}
                                                    </strong>
                                                </td>
                                                <td>{item.keterangan}</td>
                                                <td>
                                                    {item.kategori === 'cuti_bersama' ? (
                                                        <span
                                                            className="badge badge-warning"
                                                            style={{
                                                                background: '#fffbeb',
                                                                color: '#b45309',
                                                                border: '1px solid #fde68a',
                                                            }}
                                                        >
                                                            <i className="bi bi-umbrella-fill"></i> Cuti Bersama
                                                        </span>
                                                    ) : item.kategori === 'internal' ? (
                                                        <span className="badge badge-info">
                                                            <i className="bi bi-building"></i> Internal BI
                                                        </span>
                                                    ) : (
                                                        <span className="badge badge-danger">
                                                            <i className="bi bi-flag-fill"></i> Libur Nasional
                                                        </span>
                                                    )}
                                                </td>
                                                <td>
                                                    <button
                                                        type="button"
                                                        className="btn-danger btn-sm"
                                                        title="Hapus"
                                                        onClick={() => setDeleteTarget(item)}
                                                    >
                                                        <i className="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan={5}>
                                                <div className="empty-state">
                                                    <i className="bi bi-calendar-x"></i>
                                                    <p>
                                                        Belum ada data hari libur / cuti bersama. Klik &quot;Sync API
                                                        Libur &amp; Cuti&quot; di atas.
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
                                Halaman {currentPage} dari {totalPages}
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
            </div>

            {/* Custom Modal Sinkronisasi (1:1 with Blade submitFormWithConfirm 'primary') */}
            {showSyncModal && (
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
                    onClick={() => !isSyncing && setShowSyncModal(false)}
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
                                    background: '#e0f2fe',
                                    borderColor: '#bae6fd',
                                    color: '#0284c7',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    fontSize: '20px',
                                    flexShrink: 0,
                                    border: '1px solid #bae6fd',
                                }}
                            >
                                <i className="bi bi-question-circle-fill"></i>
                            </div>
                            <div>
                                <h3 style={{ margin: 0, fontSize: '16px', fontWeight: 700, color: '#0f172a' }}>
                                    Sinkronisasi Hari Libur
                                </h3>
                                <p style={{ margin: '2px 0 0', fontSize: '12px', color: '#64748b' }}>
                                    Sistem Informasi SILAKAN BI
                                </p>
                            </div>
                        </div>

                        <div style={{ padding: '22px 24px' }}>
                            <p style={{ margin: 0, fontSize: '13.5px', color: '#334155', lineHeight: 1.5 }}>
                                Sinkronkan data hari libur nasional &amp; cuti bersama tahun{' '}
                                <strong>{currentYear}</strong> dari API resmi?
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
                                onClick={() => setShowSyncModal(false)}
                                disabled={isSyncing}
                                style={{ padding: '9px 18px', fontSize: '13px', borderRadius: '10px' }}
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                onClick={handleConfirmSync}
                                disabled={isSyncing}
                                style={{
                                    background: '#005baa',
                                    color: '#fff',
                                    padding: '9px 20px',
                                    fontSize: '13px',
                                    fontWeight: 600,
                                    borderRadius: '10px',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                    border: 'none',
                                    cursor: isSyncing ? 'not-allowed' : 'pointer',
                                    opacity: isSyncing ? 0.7 : 1,
                                }}
                            >
                                <i className="bi bi-cloud-download"></i>
                                {isSyncing ? 'Menyinkronkan...' : 'Ya, Sinkronkan'}
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Custom Modal Hapus Hari Libur (1:1 with Blade submitFormWithConfirm 'danger') */}
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
                                    Hapus Hari Libur
                                </h3>
                                <p style={{ margin: '2px 0 0', fontSize: '12px', color: '#64748b' }}>
                                    Sistem Informasi SILAKAN BI
                                </p>
                            </div>
                        </div>

                        <div style={{ padding: '22px 24px' }}>
                            <p style={{ margin: 0, fontSize: '13.5px', color: '#334155', lineHeight: 1.5 }}>
                                Apakah Anda yakin ingin menghapus data libur{' '}
                                <strong>{deleteTarget.keterangan}</strong>?
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
                                onClick={handleConfirmDelete}
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
