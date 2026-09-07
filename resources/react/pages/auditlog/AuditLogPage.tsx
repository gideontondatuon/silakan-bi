import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { AuditLog, PaginatedData } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';

export const AuditLogPage: React.FC = () => {
    const [data, setData] = useState<PaginatedData<AuditLog> | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [tanggalMulai, setTanggalMulai] = useState('');
    const [tanggalSelesai, setTanggalSelesai] = useState('');
    const [modulFilter, setModulFilter] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [page, setPage] = useState(1);

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await adminService.getAuditLogs({
                tanggal_mulai: tanggalMulai || undefined,
                tanggal_selesai: tanggalSelesai || undefined,
                modul: modulFilter || undefined,
                q: searchQuery || undefined,
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
    }, [page, modulFilter, tanggalMulai, tanggalSelesai]);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        setPage(1);
        loadData();
    };

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className="bi bi-journal-text" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Audit Trail & Aktivitas Sistem
                    </h1>
                    <p>Catatan rekam jejak terpusat seluruh aksi pengguna dan administrator untuk kebutuhan akuntabilitas</p>
                </div>
            </div>

            {/* Filter Bar */}
            <div style={{ background: '#fff', borderRadius: '12px', padding: '16px 20px', border: '1px solid #e2e8f0', marginBottom: '20px', display: 'flex', gap: '14px', flexWrap: 'wrap', alignItems: 'center' }}>
                <form onSubmit={handleSearch} style={{ display: 'flex', gap: '8px', flex: '1 1 260px' }}>
                    <input
                        type="text"
                        className="login-input"
                        placeholder="Cari aktivitas, user, IP..."
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        style={{ height: '38px', fontSize: '13px' }}
                    />
                    <button type="submit" className="btn-primary" style={{ padding: '0 12px', height: '38px' }}>
                        <i className="bi bi-search"></i>
                    </button>
                </form>

                <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap', alignItems: 'center' }}>
                    <input
                        type="date"
                        className="login-input"
                        value={tanggalMulai}
                        onChange={(e) => { setTanggalMulai(e.target.value); setPage(1); }}
                        style={{ height: '38px', fontSize: '12.5px' }}
                        title="Dari Tanggal"
                    />
                    <input
                        type="date"
                        className="login-input"
                        value={tanggalSelesai}
                        onChange={(e) => { setTanggalSelesai(e.target.value); setPage(1); }}
                        style={{ height: '38px', fontSize: '12.5px' }}
                        title="Sampai Tanggal"
                    />
                </div>
            </div>

            {/* Table */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                {isLoading ? (
                    <LoadingSpinner message="Memuat rekaman audit log..." />
                ) : (
                    <div className="table-responsive">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Waktu Kejadian</th>
                                    <th>Pengguna</th>
                                    <th>Aktivitas</th>
                                    <th>Keterangan / Detail</th>
                                    <th>Alamat IP & Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data && data.data.length > 0 ? (
                                    data.data.map((log) => (
                                        <tr key={log.id}>
                                            <td style={{ fontSize: '12px', color: '#64748b', whiteSpace: 'nowrap' }}>
                                                {new Date(log.created_at).toLocaleString('id-ID')}
                                            </td>
                                            <td>
                                                <strong>{log.user?.name || log.user?.username || 'Sistem'}</strong>
                                                <div style={{ fontSize: '11px', color: '#64748b' }}>{log.user?.nama_unit || '-'}</div>
                                            </td>
                                            <td>
                                                <span style={{ fontWeight: 600, color: '#005baa' }}>{log.activity}</span>
                                                {log.modul && <small style={{ display: 'block', color: '#64748b' }}>[{log.modul}]</small>}
                                            </td>
                                            <td style={{ fontSize: '13px', color: '#334155' }}>
                                                {log.description}
                                            </td>
                                            <td style={{ fontSize: '11.5px', color: '#64748b' }}>
                                                <div><code>{log.ip_address || '-'}</code></div>
                                                <small style={{ maxWidth: '200px', display: 'block', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }} title={log.user_agent || ''}>
                                                    {log.user_agent || '-'}
                                                </small>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={5} style={{ textAlign: 'center', padding: '36px', color: '#64748b' }}>
                                            Tidak ada data audit log yang sesuai.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                )}

                {/* Pagination */}
                {data && data.last_page > 1 && (
                    <div style={{ padding: '16px 24px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderTop: '1px solid #e2e8f0', background: '#f8fafc' }}>
                        <small style={{ color: '#64748b' }}>
                            Halaman {data.current_page} dari {data.last_page} (Total {data.total} catatan)
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
        </div>
    );
};
