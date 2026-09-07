import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { AuditLog, PaginatedData } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';

export const AuditLogPage: React.FC = () => {
    const [data, setData] = useState<PaginatedData<AuditLog> | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);

    // Form Filter State
    const [tanggalMulai, setTanggalMulai] = useState('');
    const [tanggalSelesai, setTanggalSelesai] = useState('');
    const [modulFilter, setModulFilter] = useState('');

    // Applied Filter State
    const [appliedFilters, setAppliedFilters] = useState({
        tanggal_mulai: '',
        tanggal_selesai: '',
        modul: '',
    });

    const loadData = async (pageToLoad = currentPage, filters = appliedFilters) => {
        setIsLoading(true);
        try {
            const res = await adminService.getAuditLogs({
                tanggal_mulai: filters.tanggal_mulai || undefined,
                tanggal_selesai: filters.tanggal_selesai || undefined,
                modul: filters.modul || undefined,
                page: pageToLoad,
                per_page: 15,
            });
            if (res.status === 'success') {
                setData(res.data);
            }
        } catch {
            // Ignore error
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
            modul: modulFilter,
        });
    };

    const handleResetFilter = () => {
        setTanggalMulai('');
        setTanggalSelesai('');
        setModulFilter('');
        setCurrentPage(1);
        setAppliedFilters({
            tanggal_mulai: '',
            tanggal_selesai: '',
            modul: '',
        });
    };

    const formatIndoDateTime = (dateStr: string) => {
        try {
            const d = new Date(dateStr);
            const pad = (n: number) => String(n).padStart(2, '0');
            const day = pad(d.getDate());
            const month = pad(d.getMonth() + 1);
            const year = d.getFullYear();
            const hours = pad(d.getHours());
            const minutes = pad(d.getMinutes());
            const seconds = pad(d.getSeconds());
            return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
        } catch {
            return dateStr;
        }
    };

    const getTimeAgo = (dateStr: string) => {
        try {
            const d = new Date(dateStr);
            const diffMs = Date.now() - d.getTime();
            const diffSecs = Math.floor(diffMs / 1000);
            if (diffSecs < 60) return 'baru saja';
            const diffMins = Math.floor(diffSecs / 60);
            if (diffMins < 60) return `${diffMins} menit yang lalu`;
            const diffHours = Math.floor(diffMins / 60);
            if (diffHours < 24) return `${diffHours} jam yang lalu`;
            const diffDays = Math.floor(diffHours / 24);
            if (diffDays < 30) return `${diffDays} hari yang lalu`;
            const diffMonths = Math.floor(diffDays / 30);
            if (diffMonths < 12) return `${diffMonths} bulan yang lalu`;
            const diffYears = Math.floor(diffMonths / 12);
            return `${diffYears} tahun yang lalu`;
        } catch {
            return '';
        }
    };

    const getActivityBadgeStyle = (actionText: string) => {
        const textLower = (actionText || '').toLowerCase();
        if (
            textLower.includes('tambah') ||
            textLower.includes('approve') ||
            textLower.includes('setuju') ||
            textLower.includes('simpan') ||
            textLower.includes('create')
        ) {
            return { bg: '#dcfce7', color: '#15803d', border: '#bbf7d0' };
        }
        if (
            textLower.includes('hapus') ||
            textLower.includes('reject') ||
            textLower.includes('batal') ||
            textLower.includes('delete')
        ) {
            return { bg: '#fee2e2', color: '#b91c1c', border: '#fecaca' };
        }
        if (
            textLower.includes('edit') ||
            textLower.includes('update') ||
            textLower.includes('ubah')
        ) {
            return { bg: '#fef3c7', color: '#b45309', border: '#fde68a' };
        }
        return { bg: '#e0f2fe', color: '#0369a1', border: '#bae6fd' };
    };

    const logs = data?.data || [];

    return (
        <div>
            {/* Dashboard Header */}
            <div className="dashboard-header" style={{ marginBottom: '24px' }}>
                <div>
                    <h1
                        style={{
                            fontSize: '22px',
                            fontWeight: 800,
                            color: '#003b73',
                            display: 'flex',
                            alignItems: 'center',
                            gap: '10px',
                            margin: 0,
                        }}
                    >
                        <i className="bi bi-shield-check" style={{ color: '#005baa' }}></i> Audit Log System &amp; Aktivitas User
                    </h1>
                    <p style={{ color: '#64748b', fontSize: '13.5px', marginTop: '4px', marginBottom: 0 }}>
                        Jejak aktivitas keamanan, perizinan, persetujuan, dan perubahan data pada sistem SILAKAN.
                    </p>
                </div>
                <span className="badge badge-info" style={{ fontSize: '13px', padding: '8px 16px' }}>
                    <i className="bi bi-list-columns-reverse"></i> {data?.total || 0} Log Tercatat
                </span>
            </div>

            {/* Filter Card */}
            <div
                className="dashboard-section"
                style={{
                    marginBottom: '24px',
                    padding: '20px 24px',
                    background: '#ffffff',
                    borderRadius: '14px',
                    border: '1px solid #e2e8f0',
                    boxShadow: '0 4px 16px rgba(0,0,0,0.02)',
                }}
            >
                <form
                    onSubmit={handleFilterSubmit}
                    style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))',
                        gap: '16px',
                        alignItems: 'end',
                    }}
                >
                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12.5px',
                                fontWeight: 700,
                                color: '#334155',
                                marginBottom: '6px',
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
                                border: '1px solid #cbd5e1',
                                borderRadius: '8px',
                                fontSize: '13px',
                                boxSizing: 'border-box',
                            }}
                        />
                    </div>

                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12.5px',
                                fontWeight: 700,
                                color: '#334155',
                                marginBottom: '6px',
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
                                border: '1px solid #cbd5e1',
                                borderRadius: '8px',
                                fontSize: '13px',
                                boxSizing: 'border-box',
                            }}
                        />
                    </div>

                    <div>
                        <label
                            style={{
                                display: 'block',
                                fontSize: '12.5px',
                                fontWeight: 700,
                                color: '#334155',
                                marginBottom: '6px',
                            }}
                        >
                            Modul Aktivitas
                        </label>
                        <select
                            value={modulFilter}
                            onChange={(e) => setModulFilter(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '9px 12px',
                                border: '1px solid #cbd5e1',
                                borderRadius: '8px',
                                fontSize: '13px',
                                background: 'white',
                                boxSizing: 'border-box',
                            }}
                        >
                            <option value="">-- Semua Modul --</option>
                            <option value="Approval">Approval</option>
                            <option value="Pemesanan">Pemesanan</option>
                            <option value="Master Data">Master Data</option>
                            <option value="User">User &amp; Auth</option>
                        </select>
                    </div>

                    <div style={{ display: 'flex', gap: '8px' }}>
                        <button
                            type="submit"
                            className="btn-primary"
                            style={{
                                flex: 1,
                                padding: '9px 14px',
                                borderRadius: '8px',
                                fontSize: '13px',
                                fontWeight: 700,
                                height: '38px',
                                display: 'inline-flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                gap: '6px',
                            }}
                        >
                            <i className="bi bi-filter"></i> Filter Log
                        </button>
                        <button
                            type="button"
                            onClick={handleResetFilter}
                            className="btn-secondary"
                            style={{
                                padding: '9px 14px',
                                borderRadius: '8px',
                                fontSize: '13px',
                                fontWeight: 600,
                                display: 'inline-flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                height: '38px',
                                cursor: 'pointer',
                            }}
                            title="Reset Filter"
                        >
                            <i className="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </form>
            </div>

            {/* Audit Log Table */}
            <div
                className="dashboard-section"
                style={{
                    padding: 0,
                    overflow: 'hidden',
                    borderRadius: '14px',
                    border: '1px solid #e2e8f0',
                    background: '#ffffff',
                }}
            >
                <div className="table-wrapper">
                    {isLoading ? (
                        <div style={{ padding: '60px', textAlign: 'center' }}>
                            <LoadingSpinner />
                        </div>
                    ) : (
                        <table className="data-table" style={{ width: '100%', borderCollapse: 'collapse' }}>
                            <thead>
                                <tr
                                    style={{
                                        background: '#f8fafc',
                                        borderBottom: '1px solid #e2e8f0',
                                        textAlign: 'left',
                                        fontSize: '12px',
                                        color: '#475569',
                                        textTransform: 'uppercase',
                                    }}
                                >
                                    <th style={{ padding: '14px 18px' }}>Waktu Kejadian</th>
                                    <th style={{ padding: '14px 18px' }}>Pelaku (User/Admin)</th>
                                    <th style={{ padding: '14px 18px' }}>Aktivitas</th>
                                    <th style={{ padding: '14px 18px' }}>Modul</th>
                                    <th style={{ padding: '14px 18px' }}>Keterangan Rinci</th>
                                </tr>
                            </thead>
                            <tbody>
                                {logs.length > 0 ? (
                                    logs.map((log) => {
                                        const aksiText = log.aksi || log.aktivitas || log.activity || '-';
                                        const badgeStyle = getActivityBadgeStyle(aksiText);
                                        const descText =
                                            log.keterangan ||
                                            log.deskripsi ||
                                            log.description ||
                                            (aksiText !== '-' ? `${aksiText} pada modul ${log.modul || 'Sistem'}` : '-');

                                        return (
                                            <tr key={log.id} style={{ borderBottom: '1px solid #f1f5f9', fontSize: '13px' }}>
                                                <td style={{ padding: '14px 18px', whiteSpace: 'nowrap' }}>
                                                    <strong style={{ color: '#0f172a', display: 'block' }}>
                                                        {formatIndoDateTime(log.created_at)} WITA
                                                    </strong>
                                                    <small style={{ color: '#64748b' }}>
                                                        <i className="bi bi-clock-history"></i> {getTimeAgo(log.created_at)}
                                                    </small>
                                                </td>
                                                <td style={{ padding: '14px 18px' }}>
                                                    <strong style={{ color: '#003b73', display: 'block' }}>
                                                        {log.user?.name || log.user?.username || 'Sistem'}
                                                    </strong>
                                                    <small style={{ color: '#64748b' }}>
                                                        {log.user?.nama_unit || (log.user?.role === 'admin' ? 'Administrator' : 'User') || 'System'}
                                                    </small>
                                                </td>
                                                <td style={{ padding: '14px 18px' }}>
                                                    <span
                                                        className="badge"
                                                        style={{
                                                            fontSize: '11.5px',
                                                            padding: '4px 9px',
                                                            background: badgeStyle.bg,
                                                            color: badgeStyle.color,
                                                            border: `1px solid ${badgeStyle.border}`,
                                                            fontWeight: 700,
                                                            borderRadius: '6px',
                                                            display: 'inline-block',
                                                        }}
                                                    >
                                                        {aksiText}
                                                    </span>
                                                </td>
                                                <td style={{ padding: '14px 18px', fontWeight: 600, color: '#334155' }}>
                                                    {log.modul || '-'}
                                                </td>
                                                <td style={{ padding: '14px 18px', color: '#475569', lineHeight: 1.4 }}>
                                                    {descText}
                                                </td>
                                            </tr>
                                        );
                                    })
                                ) : (
                                    <tr>
                                        <td colSpan={5} style={{ padding: '40px 20px', textAlign: 'center', color: '#64748b' }}>
                                            <i
                                                className="bi bi-shield-x"
                                                style={{ fontSize: '32px', color: '#94a3b8', display: 'block', marginBottom: '8px' }}
                                            ></i>
                                            Belum ada rekaman audit log yang sesuai.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    )}
                </div>

                {data && data.last_page > 1 && (
                    <div
                        style={{
                            padding: '16px 20px',
                            borderTop: '1px solid #e2e8f0',
                            background: '#f8fafc',
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'center',
                            flexWrap: 'wrap',
                            gap: '12px',
                        }}
                    >
                        <div style={{ fontSize: '13px', color: '#64748b' }}>
                            Menampilkan {data.from || 1} - {data.to || logs.length} dari {data.total} log
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
                            {Array.from({ length: data.last_page }, (_, idx) => idx + 1).map((pg) => (
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
                                disabled={currentPage >= data.last_page}
                                onClick={() => setCurrentPage((p) => Math.min(data.last_page, p + 1))}
                            >
                                Selanjutnya <i className="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};
