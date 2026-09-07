import React, { useEffect, useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { bookingService } from '../../services/bookingService';
import { PaginatedData, Pemesanan } from '../../types';
import { Badge } from '../../components/common/Badge';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const PemesananList: React.FC = () => {
    const location = useLocation();
    const [data, setData] = useState<PaginatedData<Pemesanan> | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [statusFilter, setStatusFilter] = useState('');
    const [searchQuery, setSearchQuery] = useState('');
    const [page, setPage] = useState(1);
    const [flashSuccess, setFlashSuccess] = useState<string | null>(
        location.state?.flashSuccess || null
    );

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await bookingService.getPemesananList({
                page,
                status: statusFilter || undefined,
                q: searchQuery || undefined,
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
    }, [page, statusFilter]);

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
                        <i className="bi bi-clock-history" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Riwayat Pemesanan Ruangan
                    </h1>
                    <p>Daftar seluruh pengajuan pemesanan ruangan rapat unit kerja Anda</p>
                </div>
                <div>
                    <Link to="/pemesanan/create" className="btn-primary">
                        <i className="bi bi-plus-circle-fill"></i> Buat Pemesanan Baru
                    </Link>
                </div>
            </div>

            {flashSuccess && (
                <AlertBanner
                    type="success"
                    message={flashSuccess}
                    onClose={() => setFlashSuccess(null)}
                />
            )}

            {/* Filter Bar */}
            <div style={{ background: '#fff', borderRadius: '12px', padding: '16px 20px', border: '1px solid #e2e8f0', marginBottom: '20px', display: 'flex', gap: '16px', flexWrap: 'wrap', alignItems: 'center', justifyContent: 'space-between' }}>
                <form onSubmit={handleSearch} style={{ display: 'flex', gap: '10px', flex: '1 1 300px', maxWidth: '450px' }}>
                    <input
                        type="text"
                        className="login-input"
                        placeholder="Cari kode tiket, judul kegiatan, PIC..."
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        style={{ height: '40px', fontSize: '13px' }}
                    />
                    <button type="submit" className="btn-primary" style={{ padding: '0 16px', height: '40px', flexShrink: 0 }}>
                        <i className="bi bi-search"></i>
                    </button>
                </form>

                <div style={{ display: 'flex', gap: '10px', alignItems: 'center' }}>
                    <label style={{ fontSize: '13px', fontWeight: 600, color: '#475569' }}>Status:</label>
                    <select
                        className="login-input"
                        value={statusFilter}
                        onChange={(e) => {
                            setStatusFilter(e.target.value);
                            setPage(1);
                        }}
                        style={{ height: '40px', fontSize: '13px', minWidth: '150px' }}
                    >
                        <option value="">Semua Status</option>
                        <option value="Pending">Menunggu (Pending)</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Cancel">Dibatalkan (Cancel)</option>
                    </select>
                </div>
            </div>

            {/* Table */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                {isLoading ? (
                    <LoadingSpinner message="Memuat daftar pemesanan..." />
                ) : data && data.data.length > 0 ? (
                    <>
                        <div className="table-responsive">
                            <table className="data-table">
                                <thead>
                                    <tr>
                                        <th>Kode Tiket</th>
                                        <th>Ruangan & Layout</th>
                                        <th>Judul Kegiatan</th>
                                        <th>Tanggal & Jam Rapat</th>
                                        <th>Tamu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.data.map((item) => (
                                        <tr key={item.id}>
                                            <td>
                                                <strong style={{ color: '#003b73' }}>{item.kode_pemesanan}</strong>
                                                <div style={{ fontSize: '11px', color: '#94a3b8' }}>
                                                    {new Date(item.created_at).toLocaleDateString('id-ID')}
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{item.ruangan?.nama_ruangan || '-'}</strong>
                                                <div style={{ fontSize: '12px', color: '#64748b' }}>
                                                    Layout: {item.layout?.nama_layout || 'Standar'}
                                                </div>
                                            </td>
                                            <td>
                                                <span style={{ fontWeight: 600, color: '#1e293b' }}>{item.judul_kegiatan}</span>
                                                <div style={{ fontSize: '12px', color: '#64748b' }}>PIC: {item.pic_kegiatan}</div>
                                            </td>
                                            <td>
                                                <div>{item.tanggal_kegiatan}</div>
                                                <small style={{ color: '#0284c7', fontWeight: 600 }}>
                                                    {item.waktu_mulai?.substring(0, 5)} – {item.waktu_selesai?.substring(0, 5)} WITA
                                                </small>
                                            </td>
                                            <td>{item.jumlah_tamu} Orang</td>
                                            <td>
                                                <Badge status={item.status} />
                                            </td>
                                            <td>
                                                <Link to={`/pemesanan/${item.id}`} className="btn-table-action" style={{ textDecoration: 'none' }}>
                                                    <i className="bi bi-eye"></i> Detail
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Pagination */}
                        {data.last_page > 1 && (
                            <div style={{ padding: '16px 24px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderTop: '1px solid #e2e8f0', background: '#f8fafc' }}>
                                <small style={{ color: '#64748b' }}>
                                    Menampilkan {data.from || 0}–{data.to || 0} dari total {data.total} pengajuan
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
                    </>
                ) : (
                    <div className="empty-state" style={{ padding: '48px', textAlign: 'center' }}>
                        <i className="bi bi-inbox" style={{ fontSize: '40px', color: '#94a3b8' }}></i>
                        <h4 style={{ marginTop: '12px', color: '#334155' }}>Tidak Ada Data Pemesanan</h4>
                        <p style={{ color: '#64748b', fontSize: '13px' }}>Belum ada riwayat pemesanan yang cocok dengan kriteria filter Anda.</p>
                    </div>
                )}
            </div>
        </div>
    );
};
