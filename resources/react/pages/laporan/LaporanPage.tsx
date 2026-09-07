import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { Pemesanan } from '../../types';
import { Badge } from '../../components/common/Badge';
import { StatCard } from '../../components/common/StatCard';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';

export const LaporanPage: React.FC = () => {
    const [summary, setSummary] = useState({ total: 0, disetujui: 0, ditolak: 0 });
    const [items, setItems] = useState<Pemesanan[]>([]);
    const [ruanganOptions, setRuanganOptions] = useState<Array<{ id: number; nama_ruangan: string }>>([]);
    const [userOptions, setUserOptions] = useState<Array<{ id: number; name: string; nama_unit: string }>>([]);
    const [isLoading, setIsLoading] = useState(true);

    // Filters
    const [tanggalMulai, setTanggalMulai] = useState('');
    const [tanggalSelesai, setTanggalSelesai] = useState('');
    const [ruanganId, setRuanganId] = useState('');
    const [userId, setUserId] = useState('');
    const [jenisPic, setJenisPic] = useState('');
    const [statusFilter, setStatusFilter] = useState('');

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await adminService.getLaporanData({
                tanggal_mulai: tanggalMulai || undefined,
                tanggal_selesai: tanggalSelesai || undefined,
                ruangan_id: ruanganId || undefined,
                user_id: userId || undefined,
                jenis_pic: jenisPic || undefined,
                status: statusFilter || undefined,
            });

            if (res.status === 'success') {
                setSummary(res.data.summary);
                setItems(Array.isArray(res.data.items) ? res.data.items : res.data.items.data);
                setRuanganOptions(res.data.filter_options?.ruangan || []);
                setUserOptions(res.data.filter_options?.users || []);
            }
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, [tanggalMulai, tanggalSelesai, ruanganId, userId, jenisPic, statusFilter]);

    const handleDownloadExcel = () => {
        const params = {
            tanggal_mulai: tanggalMulai || undefined,
            tanggal_selesai: tanggalSelesai || undefined,
            ruangan_id: ruanganId || undefined,
            user_id: userId || undefined,
            jenis_pic: jenisPic || undefined,
            status: statusFilter || undefined,
        };
        window.location.href = adminService.getExcelExportUrl(params);
    };

    const handlePrintKopBi = () => {
        // Open the existing official print template with filter params
        const query = new URLSearchParams({
            tanggal_mulai: tanggalMulai || '',
            tanggal_selesai: tanggalSelesai || '',
            ruangan_id: ruanganId || '',
            user_id: userId || '',
            jenis_pic: jenisPic || '',
            status: statusFilter || '',
        }).toString();

        window.open(`/admin/laporan/cetak?${query}`, '_blank');
    };

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className="bi bi-file-earmark-bar-graph" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Laporan Rekapitulasi Pemesanan Ruangan
                    </h1>
                    <p>Rekap data pemakaian ruangan rapat, statistik okupansi, dan ekspor dokumen resmi Bank Indonesia</p>
                </div>
                <div style={{ display: 'flex', gap: '10px' }}>
                    <button type="button" className="btn-secondary" onClick={handleDownloadExcel}>
                        <i className="bi bi-file-earmark-excel" style={{ color: '#16a34a' }}></i> Unduh Excel (.xlsx)
                    </button>
                    <button type="button" className="btn-primary" onClick={handlePrintKopBi}>
                        <i className="bi bi-printer"></i> Cetak Dokumen KOP BI
                    </button>
                </div>
            </div>

            {/* Summary Cards */}
            <div className="stat-grid" style={{ marginBottom: '24px' }}>
                <StatCard
                    title="Total Pemesanan Terfilter"
                    value={summary.total}
                    icon="calculator"
                    variant="blue"
                />
                <StatCard
                    title="Disetujui & Selesai"
                    value={summary.disetujui}
                    icon="check-circle"
                    variant="green"
                />
                <StatCard
                    title="Ditolak / Dibatalkan"
                    value={summary.ditolak}
                    icon="x-circle"
                    variant="yellow"
                />
            </div>

            {/* Multivariate Filter Panel */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', padding: '20px', marginBottom: '24px', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                <h3 style={{ fontSize: '14px', fontWeight: 700, color: '#003b73', marginBottom: '14px' }}>
                    <i className="bi bi-funnel" style={{ color: '#005baa', marginRight: '6px' }}></i> Filter Data Laporan
                </h3>

                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '14px' }}>
                    <div>
                        <label style={{ display: 'block', fontSize: '12px', fontWeight: 600, color: '#64748b', marginBottom: '4px' }}>Dari Tanggal:</label>
                        <input
                            type="date"
                            className="login-input"
                            value={tanggalMulai}
                            onChange={(e) => setTanggalMulai(e.target.value)}
                            style={{ height: '38px', fontSize: '12.5px' }}
                        />
                    </div>

                    <div>
                        <label style={{ display: 'block', fontSize: '12px', fontWeight: 600, color: '#64748b', marginBottom: '4px' }}>Sampai Tanggal:</label>
                        <input
                            type="date"
                            className="login-input"
                            value={tanggalSelesai}
                            onChange={(e) => setTanggalSelesai(e.target.value)}
                            style={{ height: '38px', fontSize: '12.5px' }}
                        />
                    </div>

                    <div>
                        <label style={{ display: 'block', fontSize: '12px', fontWeight: 600, color: '#64748b', marginBottom: '4px' }}>Ruangan:</label>
                        <select
                            className="login-input"
                            value={ruanganId}
                            onChange={(e) => setRuanganId(e.target.value)}
                            style={{ height: '38px', fontSize: '12.5px' }}
                        >
                            <option value="">Semua Ruangan</option>
                            {ruanganOptions.map((r) => (
                                <option key={r.id} value={r.id}>{r.nama_ruangan}</option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label style={{ display: 'block', fontSize: '12px', fontWeight: 600, color: '#64748b', marginBottom: '4px' }}>Unit / Pemohon:</label>
                        <select
                            className="login-input"
                            value={userId}
                            onChange={(e) => setUserId(e.target.value)}
                            style={{ height: '38px', fontSize: '12.5px' }}
                        >
                            <option value="">Semua Unit</option>
                            {userOptions.map((u) => (
                                <option key={u.id} value={u.id}>{u.nama_unit || u.name}</option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label style={{ display: 'block', fontSize: '12px', fontWeight: 600, color: '#64748b', marginBottom: '4px' }}>Jenis PIC:</label>
                        <select
                            className="login-input"
                            value={jenisPic}
                            onChange={(e) => setJenisPic(e.target.value)}
                            style={{ height: '38px', fontSize: '12.5px' }}
                        >
                            <option value="">Semua PIC</option>
                            <option value="Organik">Organik</option>
                            <option value="Non Organik">Non Organik</option>
                        </select>
                    </div>

                    <div>
                        <label style={{ display: 'block', fontSize: '12px', fontWeight: 600, color: '#64748b', marginBottom: '4px' }}>Status:</label>
                        <select
                            className="login-input"
                            value={statusFilter}
                            onChange={(e) => setStatusFilter(e.target.value)}
                            style={{ height: '38px', fontSize: '12.5px' }}
                        >
                            <option value="">Semua Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            {/* Table */}
            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                {isLoading ? (
                    <LoadingSpinner message="Mengolah data laporan..." />
                ) : (
                    <div className="table-responsive">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Tanggal & Jam</th>
                                    <th>Ruangan</th>
                                    <th>Kegiatan</th>
                                    <th>Unit & PIC</th>
                                    <th>Tamu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {items.length > 0 ? (
                                    items.map((item) => (
                                        <tr key={item.id}>
                                            <td style={{ fontWeight: 700, color: '#003b73' }}>{item.kode_pemesanan}</td>
                                            <td>
                                                <div>{item.tanggal_kegiatan}</div>
                                                <small style={{ color: '#0284c7' }}>
                                                    {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)} WITA
                                                </small>
                                            </td>
                                            <td>{item.ruangan?.nama_ruangan}</td>
                                            <td><strong>{item.judul_kegiatan}</strong></td>
                                            <td>
                                                <div>{item.user?.nama_unit || item.user?.name}</div>
                                                <small style={{ color: '#64748b' }}>PIC: {item.pic_kegiatan} ({item.jenis_pic})</small>
                                            </td>
                                            <td>{item.jumlah_tamu} Orang</td>
                                            <td><Badge status={item.status} /></td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={7} style={{ textAlign: 'center', padding: '36px', color: '#64748b' }}>
                                            Tidak ada data pemesanan yang sesuai dengan filter yang dipilih.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </div>
    );
};
