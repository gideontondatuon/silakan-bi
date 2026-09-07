import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { PaginatedData, Pemesanan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const LaporanPage: React.FC = () => {
    const [summary, setSummary] = useState({ total: 0, disetujui: 0, ditolak: 0 });
    const [paginatedData, setPaginatedData] = useState<PaginatedData<Pemesanan> | null>(null);
    const [ruanganOptions, setRuanganOptions] = useState<Array<{ id: number; nama_ruangan: string }>>([]);
    const [userOptions, setUserOptions] = useState<Array<{ id: number; name: string; nama_unit: string }>>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Filter Form Inputs
    const [tanggalMulai, setTanggalMulai] = useState('');
    const [tanggalSelesai, setTanggalSelesai] = useState('');
    const [ruanganId, setRuanganId] = useState('');
    const [statusFilter, setStatusFilter] = useState('');
    const [jenisPic, setJenisPic] = useState('');
    const [userId, setUserId] = useState('');

    // Applied Filters (Used for actual API query & pagination)
    const [appliedFilters, setAppliedFilters] = useState({
        tanggal_mulai: '',
        tanggal_selesai: '',
        ruangan_id: '',
        status: '',
        jenis_pic: '',
        user_id: '',
    });

    // Excel & Print state
    const [isDownloadingExcel, setIsDownloadingExcel] = useState(false);
    const [excelDownloaded, setExcelDownloaded] = useState(false);

    // Delete modal state
    const [deleteTarget, setDeleteTarget] = useState<Pemesanan | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const hasActiveFilter = Boolean(
        appliedFilters.tanggal_mulai ||
        appliedFilters.tanggal_selesai ||
        appliedFilters.ruangan_id ||
        appliedFilters.status ||
        appliedFilters.jenis_pic ||
        appliedFilters.user_id
    );

    const loadData = async (pageToLoad = currentPage, filters = appliedFilters) => {
        setIsLoading(true);
        try {
            const params: any = {
                page: pageToLoad,
                per_page: 15,
                tanggal_mulai: filters.tanggal_mulai || undefined,
                tanggal_selesai: filters.tanggal_selesai || undefined,
                ruangan_id: filters.ruangan_id || undefined,
                user_id: filters.user_id || undefined,
                jenis_pic: filters.jenis_pic || undefined,
                status: filters.status || undefined,
            };

            const res = await adminService.getLaporanData(params);
            if (res.status === 'success' && res.data) {
                setSummary(res.data.summary || { total: 0, disetujui: 0, ditolak: 0 });

                if (Array.isArray(res.data.items)) {
                    setPaginatedData({
                        data: res.data.items,
                        current_page: 1,
                        last_page: 1,
                        per_page: res.data.items.length,
                        total: res.data.items.length,
                        from: res.data.items.length > 0 ? 1 : 0,
                        to: res.data.items.length,
                    });
                } else if (res.data.items) {
                    setPaginatedData(res.data.items);
                }

                if (res.data.filter_options) {
                    setRuanganOptions(res.data.filter_options.ruangan || []);
                    setUserOptions(res.data.filter_options.users || []);
                }
            }
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal memuat data laporan & rekapitulasi.',
            });
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData(currentPage, appliedFilters);
    }, [currentPage, appliedFilters]);

    const handleFilterSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setCurrentPage(1);
        setAppliedFilters({
            tanggal_mulai: tanggalMulai,
            tanggal_selesai: tanggalSelesai,
            ruangan_id: ruanganId,
            status: statusFilter,
            jenis_pic: jenisPic,
            user_id: userId,
        });
    };

    const handleResetFilter = () => {
        setTanggalMulai('');
        setTanggalSelesai('');
        setRuanganId('');
        setStatusFilter('');
        setJenisPic('');
        setUserId('');
        setCurrentPage(1);
        setAppliedFilters({
            tanggal_mulai: '',
            tanggal_selesai: '',
            ruangan_id: '',
            status: '',
            jenis_pic: '',
            user_id: '',
        });
    };

    const handleDownloadExcel = async () => {
        if (isDownloadingExcel) return;
        setIsDownloadingExcel(true);
        setExcelDownloaded(false);

        try {
            const params: any = {
                tanggal_mulai: appliedFilters.tanggal_mulai || undefined,
                tanggal_selesai: appliedFilters.tanggal_selesai || undefined,
                ruangan_id: appliedFilters.ruangan_id || undefined,
                user_id: appliedFilters.user_id || undefined,
                jenis_pic: appliedFilters.jenis_pic || undefined,
                status: appliedFilters.status || undefined,
            };

            const { blob, fileName } = await adminService.downloadExcelBlob(params);
            const blobUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = blobUrl;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => window.URL.revokeObjectURL(blobUrl), 1000);

            setExcelDownloaded(true);
            setTimeout(() => {
                setExcelDownloaded(false);
            }, 2000);
        } catch {
            // Fallback direct download
            const params = {
                tanggal_mulai: appliedFilters.tanggal_mulai || undefined,
                tanggal_selesai: appliedFilters.tanggal_selesai || undefined,
                ruangan_id: appliedFilters.ruangan_id || undefined,
                user_id: appliedFilters.user_id || undefined,
                jenis_pic: appliedFilters.jenis_pic || undefined,
                status: appliedFilters.status || undefined,
            };
            window.location.href = adminService.getExcelExportUrl(params);
        } finally {
            setIsDownloadingExcel(false);
        }
    };

    const handlePrintPreview = () => {
        const query = new URLSearchParams();
        if (appliedFilters.tanggal_mulai) query.append('tanggal_mulai', appliedFilters.tanggal_mulai);
        if (appliedFilters.tanggal_selesai) query.append('tanggal_selesai', appliedFilters.tanggal_selesai);
        if (appliedFilters.ruangan_id) query.append('ruangan_id', appliedFilters.ruangan_id);
        if (appliedFilters.user_id) query.append('user_id', appliedFilters.user_id);
        if (appliedFilters.jenis_pic) query.append('jenis_pic', appliedFilters.jenis_pic);
        if (appliedFilters.status) query.append('status', appliedFilters.status);

        window.open(`/admin/laporan/cetak?${query.toString()}`, '_blank');
    };

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteBooking(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil dihapus.' });
            setDeleteTarget(null);
            loadData(currentPage, appliedFilters);
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal menghapus pemesanan.',
            });
        } finally {
            setIsDeleting(false);
        }
    };

    const formatIndoDate = (dateStr?: string) => {
        if (!dateStr) return '-';
        try {
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        } catch {
            return dateStr;
        }
    };

    const approvalRate = summary.total > 0 ? Math.round((summary.disetujui / summary.total) * 100) : 0;
    const items = paginatedData?.data || [];
    const totalRecords = paginatedData?.total || 0;
    const curPageNum = paginatedData?.current_page || 1;
    const lastPageNum = paginatedData?.last_page || 1;
    const firstItemNum = paginatedData?.from || 1;

    return (
        <div>
            {/* ======================== PAGE HEADER ======================== */}
            <div style={{ marginBottom: '28px' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', flexWrap: 'wrap', gap: '16px' }}>
                    <div>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '6px' }}>
                            <div style={{ width: '42px', height: '42px', background: 'linear-gradient(135deg,#005baa,#003b73)', borderRadius: '10px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                <i className="bi bi-file-earmark-bar-graph-fill" style={{ color: '#ffffff', fontSize: '20px' }}></i>
                            </div>
                            <div>
                                <h1 style={{ fontSize: '21px', fontWeight: 800, color: '#003b73', margin: 0, lineHeight: 1.2 }}>
                                    Laporan &amp; Rekapitulasi Pemesanan
                                </h1>
                                <p style={{ color: '#64748b', fontSize: '13px', margin: '2px 0 0' }}>
                                    Rekapitulasi pemesanan ruangan rapat KPwBI Prov. Sulawesi Utara
                                </p>
                            </div>
                        </div>
                    </div>
                    <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap' }}>
                        <button
                            type="button"
                            id="btn-export-excel"
                            onClick={handleDownloadExcel}
                            disabled={isDownloadingExcel}
                            style={{
                                background: 'linear-gradient(135deg,#059669,#047857)',
                                color: 'white',
                                border: 'none',
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '8px',
                                padding: '10px 18px',
                                borderRadius: '10px',
                                fontWeight: 700,
                                fontSize: '13px',
                                textDecoration: 'none',
                                boxShadow: '0 4px 12px rgba(5,150,105,0.3)',
                                transition: 'all .2s',
                                cursor: isDownloadingExcel ? 'not-allowed' : 'pointer',
                                opacity: isDownloadingExcel ? 0.9 : 1,
                            }}
                        >
                            {isDownloadingExcel ? (
                                <>
                                    <span
                                        style={{
                                            display: 'inline-block',
                                            width: '14px',
                                            height: '14px',
                                            border: '2px solid #ffffff',
                                            borderRightColor: 'transparent',
                                            borderRadius: '50%',
                                            animation: 'spin 0.75s linear infinite',
                                        }}
                                    ></span>
                                    <span>Menyiapkan Excel...</span>
                                </>
                            ) : excelDownloaded ? (
                                <>
                                    <i className="bi bi-check-circle-fill"></i> <span>Berhasil Diunduh!</span>
                                </>
                            ) : (
                                <>
                                    <i className="bi bi-file-earmark-excel-fill"></i> <span>Ekspor Excel (.xlsx)</span>
                                </>
                            )}
                        </button>
                        <button
                            type="button"
                            onClick={handlePrintPreview}
                            style={{
                                background: 'linear-gradient(135deg,#005baa,#003b73)',
                                color: 'white',
                                border: 'none',
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '8px',
                                padding: '10px 18px',
                                borderRadius: '10px',
                                fontWeight: 700,
                                fontSize: '13px',
                                textDecoration: 'none',
                                boxShadow: '0 4px 12px rgba(0,91,170,0.3)',
                                transition: 'all .2s',
                                cursor: 'pointer',
                            }}
                        >
                            <i className="bi bi-printer-fill"></i> Cetak / Pratinjau PDF
                        </button>
                    </div>
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* ======================== FILTER CARD ======================== */}
            <div
                style={{
                    background: '#ffffff',
                    borderRadius: '16px',
                    border: '1px solid #e2e8f0',
                    boxShadow: '0 2px 12px rgba(0,0,0,0.04)',
                    padding: '22px 24px',
                    marginBottom: '24px',
                }}
            >
                <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '16px' }}>
                    <i className="bi bi-funnel-fill" style={{ color: '#005baa', fontSize: '15px' }}></i>
                    <span style={{ fontWeight: 700, fontSize: '13.5px', color: '#003b73' }}>
                        Filter &amp; Pencarian Data
                    </span>
                    {hasActiveFilter && (
                        <span
                            style={{
                                background: '#fef3c7',
                                color: '#92400e',
                                border: '1px solid #fde68a',
                                padding: '2px 8px',
                                borderRadius: '20px',
                                fontSize: '11px',
                                fontWeight: 700,
                                marginLeft: 'auto',
                            }}
                        >
                            Filter Aktif
                        </span>
                    )}
                </div>

                <form
                    onSubmit={handleFilterSubmit}
                    style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(auto-fit, minmax(175px, 1fr))',
                        gap: '14px',
                        alignItems: 'end',
                    }}
                >
                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#475569',
                                marginBottom: '6px',
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                            }}
                        >
                            Tanggal Mulai
                        </label>
                        <input
                            type="date"
                            value={tanggalMulai}
                            onChange={(e) => setTanggalMulai(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1.5px solid #cbd5e1',
                                borderRadius: '9px',
                                fontSize: '13px',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                            }}
                        />
                    </div>

                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#475569',
                                marginBottom: '6px',
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                            }}
                        >
                            Tanggal Selesai
                        </label>
                        <input
                            type="date"
                            value={tanggalSelesai}
                            onChange={(e) => setTanggalSelesai(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1.5px solid #cbd5e1',
                                borderRadius: '9px',
                                fontSize: '13px',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                            }}
                        />
                    </div>

                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#475569',
                                marginBottom: '6px',
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                            }}
                        >
                            Ruangan
                        </label>
                        <select
                            value={ruanganId}
                            onChange={(e) => setRuanganId(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1.5px solid #cbd5e1',
                                borderRadius: '9px',
                                fontSize: '13px',
                                background: 'white',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                            }}
                        >
                            <option value="">— Semua Ruangan —</option>
                            {ruanganOptions.map((r) => (
                                <option key={r.id} value={r.id}>
                                    {r.nama_ruangan}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#475569',
                                marginBottom: '6px',
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                            }}
                        >
                            Status
                        </label>
                        <select
                            value={statusFilter}
                            onChange={(e) => setStatusFilter(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1.5px solid #cbd5e1',
                                borderRadius: '9px',
                                fontSize: '13px',
                                background: 'white',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                            }}
                        >
                            <option value="">— Semua Status —</option>
                            <option value="Disetujui">✅ Disetujui</option>
                            <option value="Selesai">🏁 Selesai</option>
                            <option value="Pending">⏳ Pending</option>
                            <option value="Ditolak">❌ Ditolak</option>
                            <option value="Cancel">⬜ Cancel</option>
                        </select>
                    </div>

                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#475569',
                                marginBottom: '6px',
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                            }}
                        >
                            Jenis PIC
                        </label>
                        <select
                            value={jenisPic}
                            onChange={(e) => setJenisPic(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1.5px solid #cbd5e1',
                                borderRadius: '9px',
                                fontSize: '13px',
                                background: 'white',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                            }}
                        >
                            <option value="">— Semua Jenis —</option>
                            <option value="Organik">Pegawai Organik</option>
                            <option value="Non Organik">Pegawai Non-Organik</option>
                        </select>
                    </div>

                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#475569',
                                marginBottom: '6px',
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                            }}
                        >
                            Unit Kerja
                        </label>
                        <select
                            value={userId}
                            onChange={(e) => setUserId(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1.5px solid #cbd5e1',
                                borderRadius: '9px',
                                fontSize: '13px',
                                background: 'white',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                            }}
                        >
                            <option value="">— Semua Unit —</option>
                            {userOptions.map((u) => (
                                <option key={u.id} value={u.id}>
                                    {u.nama_unit || u.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div style={{ display: 'flex', gap: '8px' }}>
                        <button
                            type="submit"
                            style={{
                                flex: 1,
                                padding: '9px 14px',
                                borderRadius: '9px',
                                fontSize: '13px',
                                fontWeight: 700,
                                background: 'linear-gradient(135deg,#005baa,#003b73)',
                                color: 'white',
                                border: 'none',
                                cursor: 'pointer',
                                display: 'inline-flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                gap: '6px',
                                height: '38px',
                            }}
                        >
                            <i className="bi bi-search"></i> Cari
                        </button>
                        <button
                            type="button"
                            onClick={handleResetFilter}
                            title="Reset Filter"
                            style={{
                                padding: '9px 13px',
                                borderRadius: '9px',
                                fontSize: '13px',
                                fontWeight: 600,
                                display: 'inline-flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                background: '#f1f5f9',
                                color: '#475569',
                                border: '1.5px solid #e2e8f0',
                                cursor: 'pointer',
                                height: '38px',
                            }}
                        >
                            <i className="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </form>
            </div>

            {/* ======================== METRIC CARDS ======================== */}
            <div
                style={{
                    display: 'grid',
                    gridTemplateColumns: 'repeat(auto-fit, minmax(190px, 1fr))',
                    gap: '16px',
                    marginBottom: '24px',
                }}
            >
                {/* Total */}
                <div
                    style={{
                        background: '#ffffff',
                        padding: '20px',
                        borderRadius: '14px',
                        border: '1px solid #e2e8f0',
                        boxShadow: '0 2px 10px rgba(0,0,0,0.04)',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '16px',
                    }}
                >
                    <div
                        style={{
                            width: '48px',
                            height: '48px',
                            borderRadius: '12px',
                            background: 'linear-gradient(135deg,#e0f2fe,#bae6fd)',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            flexShrink: 0,
                        }}
                    >
                        <i className="bi bi-file-earmark-text-fill" style={{ fontSize: '22px', color: '#0284c7' }}></i>
                    </div>
                    <div>
                        <div
                            style={{
                                fontSize: '11.5px',
                                color: '#64748b',
                                fontWeight: 600,
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                                marginBottom: '2px',
                            }}
                        >
                            Total Pemesanan
                        </div>
                        <div style={{ fontSize: '22px', fontWeight: 800, color: '#0f172a', lineHeight: 1 }}>
                            {summary.total}
                        </div>
                    </div>
                </div>

                {/* Disetujui */}
                <div
                    style={{
                        background: '#ffffff',
                        padding: '20px',
                        borderRadius: '14px',
                        border: '1px solid #e2e8f0',
                        boxShadow: '0 2px 10px rgba(0,0,0,0.04)',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '16px',
                    }}
                >
                    <div
                        style={{
                            width: '48px',
                            height: '48px',
                            borderRadius: '12px',
                            background: 'linear-gradient(135deg,#dcfce7,#bbf7d0)',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            flexShrink: 0,
                        }}
                    >
                        <i className="bi bi-check-circle-fill" style={{ fontSize: '22px', color: '#16a34a' }}></i>
                    </div>
                    <div>
                        <div
                            style={{
                                fontSize: '11.5px',
                                color: '#64748b',
                                fontWeight: 600,
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                                marginBottom: '2px',
                            }}
                        >
                            Disetujui
                        </div>
                        <div style={{ fontSize: '22px', fontWeight: 800, color: '#16a34a', lineHeight: 1 }}>
                            {summary.disetujui}
                        </div>
                    </div>
                </div>

                {/* Ditolak */}
                <div
                    style={{
                        background: '#ffffff',
                        padding: '20px',
                        borderRadius: '14px',
                        border: '1px solid #e2e8f0',
                        boxShadow: '0 2px 10px rgba(0,0,0,0.04)',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '16px',
                    }}
                >
                    <div
                        style={{
                            width: '48px',
                            height: '48px',
                            borderRadius: '12px',
                            background: 'linear-gradient(135deg,#fee2e2,#fecaca)',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            flexShrink: 0,
                        }}
                    >
                        <i className="bi bi-x-circle-fill" style={{ fontSize: '22px', color: '#dc2626' }}></i>
                    </div>
                    <div>
                        <div
                            style={{
                                fontSize: '11.5px',
                                color: '#64748b',
                                fontWeight: 600,
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                                marginBottom: '2px',
                            }}
                        >
                            Ditolak
                        </div>
                        <div style={{ fontSize: '22px', fontWeight: 800, color: '#dc2626', lineHeight: 1 }}>
                            {summary.ditolak}
                        </div>
                    </div>
                </div>

                {/* Tingkat Persetujuan */}
                <div
                    style={{
                        background: '#ffffff',
                        padding: '20px',
                        borderRadius: '14px',
                        border: '1px solid #e2e8f0',
                        boxShadow: '0 2px 10px rgba(0,0,0,0.04)',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '16px',
                    }}
                >
                    <div
                        style={{
                            width: '48px',
                            height: '48px',
                            borderRadius: '12px',
                            background: 'linear-gradient(135deg,#ede9fe,#ddd6fe)',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            flexShrink: 0,
                        }}
                    >
                        <i className="bi bi-bar-chart-fill" style={{ fontSize: '22px', color: '#7c3aed' }}></i>
                    </div>
                    <div style={{ flex: 1 }}>
                        <div
                            style={{
                                fontSize: '11.5px',
                                color: '#64748b',
                                fontWeight: 600,
                                textTransform: 'uppercase',
                                letterSpacing: '.4px',
                                marginBottom: '2px',
                            }}
                        >
                            Tingkat Persetujuan
                        </div>
                        <div style={{ fontSize: '22px', fontWeight: 800, color: '#7c3aed', lineHeight: 1 }}>
                            {approvalRate}%
                        </div>
                        <div
                            style={{
                                marginTop: '6px',
                                height: '4px',
                                background: '#ede9fe',
                                borderRadius: '9px',
                                overflow: 'hidden',
                            }}
                        >
                            <div
                                style={{
                                    height: '100%',
                                    width: `${approvalRate}%`,
                                    background: 'linear-gradient(90deg,#7c3aed,#a78bfa)',
                                    borderRadius: '9px',
                                    transition: 'width 0.5s ease',
                                }}
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            {/* ======================== DATA TABLE ======================== */}
            <div
                style={{
                    background: '#ffffff',
                    borderRadius: '16px',
                    border: '1px solid #e2e8f0',
                    boxShadow: '0 2px 12px rgba(0,0,0,0.04)',
                    overflow: 'hidden',
                }}
            >
                {/* Table Header Bar */}
                <div
                    style={{
                        padding: '16px 22px',
                        borderBottom: '1px solid #f1f5f9',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        flexWrap: 'wrap',
                        gap: '10px',
                    }}
                >
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                        <i className="bi bi-table" style={{ color: '#005baa', fontSize: '15px' }}></i>
                        <span style={{ fontWeight: 700, fontSize: '13.5px', color: '#003b73' }}>
                            Data Rekap Pemesanan
                        </span>
                        <span
                            style={{
                                background: '#eff6ff',
                                color: '#005baa',
                                border: '1px solid #bfdbfe',
                                padding: '2px 8px',
                                borderRadius: '20px',
                                fontSize: '11.5px',
                                fontWeight: 700,
                            }}
                        >
                            {totalRecords} Record
                        </span>
                    </div>
                    <div style={{ fontSize: '12.5px', color: '#64748b' }}>
                        Halaman {curPageNum} dari {lastPageNum}
                    </div>
                </div>

                <div style={{ overflowX: 'auto' }}>
                    {isLoading ? (
                        <div style={{ padding: '60px', textAlign: 'center' }}>
                            <LoadingSpinner />
                        </div>
                    ) : (
                        <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: '900px' }}>
                            <thead>
                                <tr style={{ background: 'linear-gradient(135deg,#003b73,#005baa)' }}>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'left',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                            whiteSpace: 'nowrap',
                                            width: '40px',
                                        }}
                                    >
                                        #
                                    </th>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'left',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                            whiteSpace: 'nowrap',
                                        }}
                                    >
                                        Kode / Tanggal
                                    </th>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'left',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                            whiteSpace: 'nowrap',
                                        }}
                                    >
                                        Ruangan
                                    </th>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'left',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                        }}
                                    >
                                        Judul Kegiatan
                                    </th>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'left',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                            whiteSpace: 'nowrap',
                                        }}
                                    >
                                        Unit / PIC
                                    </th>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'left',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                            whiteSpace: 'nowrap',
                                        }}
                                    >
                                        Waktu
                                    </th>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'left',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                            whiteSpace: 'nowrap',
                                        }}
                                    >
                                        Status
                                    </th>
                                    <th
                                        style={{
                                            padding: '13px 16px',
                                            textAlign: 'center',
                                            fontSize: '11px',
                                            fontWeight: 700,
                                            color: 'rgba(255,255,255,.75)',
                                            textTransform: 'uppercase',
                                            letterSpacing: '.6px',
                                            whiteSpace: 'nowrap',
                                        }}
                                    >
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {items.length > 0 ? (
                                    items.map((item, index) => {
                                        const statusVal =
                                            typeof item.status === 'object' && item.status
                                                ? (item.status as any).value
                                                : item.status;
                                        const rowNum = firstItemNum + index;

                                        return (
                                            <tr
                                                key={item.id}
                                                style={{
                                                    borderBottom: '1px solid #f1f5f9',
                                                    transition: 'background .15s',
                                                }}
                                                onMouseEnter={(e) => (e.currentTarget.style.background = '#f8fafc')}
                                                onMouseLeave={(e) => (e.currentTarget.style.background = 'transparent')}
                                            >
                                                <td style={{ padding: '14px 16px', color: '#94a3b8', fontWeight: 600, fontSize: '12.5px' }}>
                                                    {rowNum}
                                                </td>

                                                <td style={{ padding: '14px 16px' }}>
                                                    <code
                                                        style={{
                                                            background: '#f0f9ff',
                                                            color: '#005baa',
                                                            padding: '3px 7px',
                                                            borderRadius: '5px',
                                                            fontSize: '11.5px',
                                                            fontWeight: 700,
                                                            fontFamily: 'Consolas, monospace',
                                                        }}
                                                    >
                                                        {item.kode_pemesanan}
                                                    </code>
                                                    <div style={{ marginTop: '5px', display: 'flex', alignItems: 'center', gap: '4px', color: '#64748b', fontSize: '12px' }}>
                                                        <i className="bi bi-calendar3"></i>
                                                        {formatIndoDate(item.tanggal_kegiatan)}
                                                    </div>
                                                </td>

                                                <td style={{ padding: '14px 16px' }}>
                                                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                                        <div
                                                            style={{
                                                                width: '30px',
                                                                height: '30px',
                                                                background: 'linear-gradient(135deg,#e0f2fe,#bae6fd)',
                                                                borderRadius: '8px',
                                                                display: 'flex',
                                                                alignItems: 'center',
                                                                justifyContent: 'center',
                                                                flexShrink: 0,
                                                            }}
                                                        >
                                                            <i className="bi bi-door-open-fill" style={{ color: '#0284c7', fontSize: '13px' }}></i>
                                                        </div>
                                                        <div>
                                                            <div style={{ fontWeight: 700, color: '#1e293b', fontSize: '13px' }}>
                                                                {item.ruangan?.nama_ruangan || '-'}
                                                            </div>
                                                            {item.layout && (
                                                                <div style={{ fontSize: '11px', color: '#64748b', marginTop: '1px' }}>
                                                                    <i className="bi bi-grid-3x3-gap"></i> {item.layout.nama_layout}
                                                                </div>
                                                            )}
                                                        </div>
                                                    </div>
                                                </td>

                                                <td style={{ padding: '14px 16px', maxWidth: '240px' }}>
                                                    <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '13px', whiteSpace: 'normal', lineHeight: 1.35 }}>
                                                        {item.judul_kegiatan}
                                                    </div>
                                                </td>

                                                <td style={{ padding: '14px 16px' }}>
                                                    <div style={{ fontWeight: 700, color: '#005baa', fontSize: '13px' }}>
                                                        {item.user?.nama_unit || item.user?.name || '-'}
                                                    </div>
                                                    <div style={{ display: 'flex', alignItems: 'center', gap: '4px', color: '#64748b', fontSize: '12px', marginTop: '2px' }}>
                                                        <i className="bi bi-person-fill"></i> {item.pic_kegiatan}
                                                    </div>
                                                    {item.jenis_pic && (
                                                        <span
                                                            style={{
                                                                display: 'inline-block',
                                                                marginTop: '4px',
                                                                fontSize: '10.5px',
                                                                padding: '2px 7px',
                                                                borderRadius: '4px',
                                                                fontWeight: 600,
                                                                background: item.jenis_pic === 'Organik' ? '#dbeafe' : '#f3e8ff',
                                                                color: item.jenis_pic === 'Organik' ? '#1d4ed8' : '#6d28d9',
                                                            }}
                                                        >
                                                            {item.jenis_pic}
                                                        </span>
                                                    )}
                                                </td>

                                                <td style={{ padding: '14px 16px', whiteSpace: 'nowrap' }}>
                                                    <div style={{ display: 'flex', alignItems: 'center', gap: '5px', fontWeight: 600, color: '#1e293b', fontSize: '13px' }}>
                                                        <i className="bi bi-clock-fill" style={{ color: '#0284c7' }}></i>
                                                        {item.waktu_mulai ? item.waktu_mulai.substring(0, 5) : '-'}
                                                    </div>
                                                    <div style={{ marginTop: '2px', fontSize: '12px', color: '#64748b', paddingLeft: '18px' }}>
                                                        s/d {item.waktu_selesai ? item.waktu_selesai.substring(0, 5) : '-'} WITA
                                                    </div>
                                                </td>

                                                <td style={{ padding: '14px 16px' }}>
                                                    {statusVal === 'Selesai' || item.is_finished ? (
                                                        <span
                                                            style={{
                                                                display: 'inline-flex',
                                                                alignItems: 'center',
                                                                gap: '5px',
                                                                padding: '5px 11px',
                                                                borderRadius: '8px',
                                                                background: '#f1f5f9',
                                                                color: '#475569',
                                                                border: '1px solid #cbd5e1',
                                                                fontSize: '11.5px',
                                                                fontWeight: 700,
                                                                whiteSpace: 'nowrap',
                                                            }}
                                                        >
                                                            <i className="bi bi-check2-all"></i> Selesai
                                                        </span>
                                                    ) : statusVal === 'Disetujui' ? (
                                                        <span
                                                            style={{
                                                                display: 'inline-flex',
                                                                alignItems: 'center',
                                                                gap: '5px',
                                                                padding: '5px 11px',
                                                                borderRadius: '8px',
                                                                background: '#f0fdf4',
                                                                color: '#166534',
                                                                border: '1px solid #bbf7d0',
                                                                fontSize: '11.5px',
                                                                fontWeight: 700,
                                                                whiteSpace: 'nowrap',
                                                            }}
                                                        >
                                                            <i className="bi bi-check-circle-fill"></i> Disetujui
                                                        </span>
                                                    ) : statusVal === 'Pending' ? (
                                                        <span
                                                            style={{
                                                                display: 'inline-flex',
                                                                alignItems: 'center',
                                                                gap: '5px',
                                                                padding: '5px 11px',
                                                                borderRadius: '8px',
                                                                background: '#fffbeb',
                                                                color: '#92400e',
                                                                border: '1px solid #fde68a',
                                                                fontSize: '11.5px',
                                                                fontWeight: 700,
                                                                whiteSpace: 'nowrap',
                                                            }}
                                                        >
                                                            <i className="bi bi-clock-fill"></i> Pending
                                                        </span>
                                                    ) : statusVal === 'Ditolak' ? (
                                                        <span
                                                            style={{
                                                                display: 'inline-flex',
                                                                alignItems: 'center',
                                                                gap: '5px',
                                                                padding: '5px 11px',
                                                                borderRadius: '8px',
                                                                background: '#fef2f2',
                                                                color: '#991b1b',
                                                                border: '1px solid #fecaca',
                                                                fontSize: '11.5px',
                                                                fontWeight: 700,
                                                                whiteSpace: 'nowrap',
                                                            }}
                                                        >
                                                            <i className="bi bi-x-circle-fill"></i> Ditolak
                                                        </span>
                                                    ) : (
                                                        <span
                                                            style={{
                                                                display: 'inline-flex',
                                                                alignItems: 'center',
                                                                gap: '5px',
                                                                padding: '5px 11px',
                                                                borderRadius: '8px',
                                                                background: '#f8fafc',
                                                                color: '#475569',
                                                                border: '1px solid #cbd5e1',
                                                                fontSize: '11.5px',
                                                                fontWeight: 700,
                                                                whiteSpace: 'nowrap',
                                                            }}
                                                        >
                                                            <i className="bi bi-dash-circle"></i> Cancel
                                                        </span>
                                                    )}
                                                </td>

                                                <td style={{ padding: '14px 16px', textAlign: 'center' }}>
                                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '6px' }}>
                                                        <Link
                                                            to={`/admin/approval/${item.id}`}
                                                            title="Lihat Detail"
                                                            style={{
                                                                width: '32px',
                                                                height: '32px',
                                                                display: 'inline-flex',
                                                                alignItems: 'center',
                                                                justifyContent: 'center',
                                                                background: '#eff6ff',
                                                                color: '#005baa',
                                                                border: '1px solid #bfdbfe',
                                                                borderRadius: '8px',
                                                                fontSize: '14px',
                                                                textDecoration: 'none',
                                                                transition: 'all .2s',
                                                            }}
                                                            onMouseEnter={(e) => {
                                                                e.currentTarget.style.background = '#005baa';
                                                                e.currentTarget.style.color = 'white';
                                                            }}
                                                            onMouseLeave={(e) => {
                                                                e.currentTarget.style.background = '#eff6ff';
                                                                e.currentTarget.style.color = '#005baa';
                                                            }}
                                                        >
                                                            <i className="bi bi-eye-fill"></i>
                                                        </Link>
                                                        <button
                                                            type="button"
                                                            title="Hapus"
                                                            onClick={() => setDeleteTarget(item)}
                                                            style={{
                                                                width: '32px',
                                                                height: '32px',
                                                                display: 'inline-flex',
                                                                alignItems: 'center',
                                                                justifyContent: 'center',
                                                                background: '#fef2f2',
                                                                color: '#dc2626',
                                                                border: '1px solid #fecaca',
                                                                borderRadius: '8px',
                                                                fontSize: '14px',
                                                                cursor: 'pointer',
                                                                transition: 'all .2s',
                                                            }}
                                                            onMouseEnter={(e) => {
                                                                e.currentTarget.style.background = '#dc2626';
                                                                e.currentTarget.style.color = 'white';
                                                            }}
                                                            onMouseLeave={(e) => {
                                                                e.currentTarget.style.background = '#fef2f2';
                                                                e.currentTarget.style.color = '#dc2626';
                                                            }}
                                                        >
                                                            <i className="bi bi-trash-fill"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })
                                ) : (
                                    <tr>
                                        <td colSpan={8} style={{ padding: '56px 20px', textAlign: 'center' }}>
                                            <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '10px' }}>
                                                <div
                                                    style={{
                                                        width: '64px',
                                                        height: '64px',
                                                        background: '#f1f5f9',
                                                        borderRadius: '50%',
                                                        display: 'flex',
                                                        alignItems: 'center',
                                                        justifyContent: 'center',
                                                    }}
                                                >
                                                    <i className="bi bi-search" style={{ fontSize: '26px', color: '#94a3b8' }}></i>
                                                </div>
                                                <div style={{ fontWeight: 700, color: '#475569', fontSize: '14px' }}>
                                                    Tidak ada data ditemukan
                                                </div>
                                                <div style={{ color: '#94a3b8', fontSize: '13px' }}>
                                                    Coba ubah filter pencarian Anda.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    )}
                </div>

                {paginatedData && lastPageNum > 1 && (
                    <div
                        style={{
                            padding: '16px 22px',
                            borderTop: '1px solid #f1f5f9',
                            background: '#fafbfc',
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'center',
                            flexWrap: 'wrap',
                            gap: '12px',
                        }}
                    >
                        <div style={{ fontSize: '13px', color: '#64748b' }}>
                            Menampilkan {paginatedData.from || 1} - {paginatedData.to || items.length} dari {totalRecords} record
                        </div>
                        <div style={{ display: 'flex', gap: '6px' }}>
                            <button
                                type="button"
                                className="btn-secondary btn-sm"
                                disabled={curPageNum <= 1}
                                onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
                            >
                                <i className="bi bi-chevron-left"></i> Sebelumnya
                            </button>
                            {Array.from({ length: lastPageNum }, (_, idx) => idx + 1).map((pg) => (
                                <button
                                    key={pg}
                                    type="button"
                                    className={`btn-sm ${pg === curPageNum ? 'btn-primary' : 'btn-secondary'}`}
                                    onClick={() => setCurrentPage(pg)}
                                >
                                    {pg}
                                </button>
                            ))}
                            <button
                                type="button"
                                className="btn-secondary btn-sm"
                                disabled={curPageNum >= lastPageNum}
                                onClick={() => setCurrentPage((p) => Math.min(lastPageNum, p + 1))}
                            >
                                Selanjutnya <i className="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* Custom Modal Delete Pemesanan (1:1 with submitFormWithConfirm Blade) */}
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
                >
                    <div
                        className="custom-modal-card"
                        style={{
                            background: '#ffffff',
                            borderRadius: '16px',
                            maxWidth: '440px',
                            width: '100%',
                            boxShadow: '0 25px 50px -12px rgba(0,0,0,0.25)',
                            overflow: 'hidden',
                            border: '1px solid #e2e8f0',
                            animation: 'modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1)',
                        }}
                    >
                        <div style={{ padding: '24px 24px 16px', display: 'flex', alignItems: 'flex-start', gap: '16px' }}>
                            <div
                                style={{
                                    width: '48px',
                                    height: '48px',
                                    borderRadius: '12px',
                                    background: '#fee2e2',
                                    color: '#dc2626',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    fontSize: '24px',
                                    flexShrink: 0,
                                }}
                            >
                                <i className="bi bi-exclamation-triangle"></i>
                            </div>
                            <div style={{ flex: 1 }}>
                                <h3 style={{ margin: '0 0 6px', fontSize: '18px', fontWeight: 700, color: '#0f172a' }}>
                                    Hapus Pemesanan
                                </h3>
                                <p style={{ margin: 0, fontSize: '14px', color: '#64748b', lineHeight: 1.5 }}>
                                    Hapus data <strong style={{ color: '#0f172a' }}>{deleteTarget.kode_pemesanan}</strong> secara permanen?
                                </p>
                            </div>
                        </div>

                        <div
                            style={{
                                padding: '16px 24px',
                                background: '#f8fafc',
                                borderTop: '1px solid #f1f5f9',
                                display: 'flex',
                                justifyContent: 'flex-end',
                                gap: '10px',
                            }}
                        >
                            <button
                                type="button"
                                className="btn-secondary"
                                onClick={() => setDeleteTarget(null)}
                                disabled={isDeleting}
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                className="btn-danger"
                                onClick={handleDelete}
                                disabled={isDeleting}
                                style={{
                                    background: '#dc2626',
                                    borderColor: '#dc2626',
                                    color: '#ffffff',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                }}
                            >
                                {isDeleting ? (
                                    <>
                                        <span
                                            className="spinner-border spinner-border-sm"
                                            style={{
                                                width: '14px',
                                                height: '14px',
                                                border: '2px solid #fff',
                                                borderRightColor: 'transparent',
                                                borderRadius: '50%',
                                                display: 'inline-block',
                                                animation: 'spin .75s linear infinite',
                                            }}
                                        ></span>
                                        Menghapus...
                                    </>
                                ) : (
                                    <>
                                        <i className="bi bi-trash"></i> Ya, Hapus Data
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};
