import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { Pemesanan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const KegiatanBerlangsungPage: React.FC = () => {
    const [liveList, setLiveList] = useState<Pemesanan[]>([]);
    const [todayList, setTodayList] = useState<Pemesanan[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [selectedLive, setSelectedLive] = useState<Pemesanan | null>(null);
    const [isFinishing, setIsFinishing] = useState(false);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    const loadData = async () => {
        try {
            const res = await adminService.getAdminDashboard();
            if (res.status === 'success') {
                setLiveList(res.data.kegiatan_berlangsung || []);
                setTodayList(res.data.kegiatan_hari_ini || []);
            }
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
        const interval = setInterval(loadData, 10000);
        return () => clearInterval(interval);
    }, []);

    const handleSelesaiAwal = async () => {
        if (!selectedLive) return;
        setIsFinishing(true);
        try {
            const res = await adminService.selesaiAwal(selectedLive.id);
            setAlertMessage({ type: 'success', text: res.message || 'Kegiatan berhasil diselesaikan lebih awal.' });
            setSelectedLive(null);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyelesaikan kegiatan.' });
        } finally {
            setIsFinishing(false);
        }
    };

    if (isLoading && liveList.length === 0 && todayList.length === 0) {
        return <LoadingSpinner message="Memuat kegiatan langsung..." />;
    }

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className="bi bi-play-circle" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Pemantauan Kegiatan Berlangsung
                    </h1>
                    <p>Status pemakaian ruangan rapat real-time hari ini di KPwBI Sulut</p>
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Live Activities Section */}
            <div style={{ marginBottom: '32px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '14px' }}>
                    <span style={{ width: '10px', height: '10px', borderRadius: '50%', background: '#ef4444', animation: 'ping 1s infinite' }} />
                    <h2 style={{ fontSize: '17px', fontWeight: 700, color: '#003b73', margin: 0 }}>
                        Sedang Berlangsung Saat Ini ({liveList.length})
                    </h2>
                </div>

                {liveList.length > 0 ? (
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(340px, 1fr))', gap: '16px' }}>
                        {liveList.map((item) => (
                            <div
                                key={item.id}
                                style={{
                                    background: 'linear-gradient(135deg, #005baa, #003b73)',
                                    borderRadius: '16px',
                                    padding: '20px',
                                    color: '#fff',
                                    boxShadow: '0 8px 24px rgba(0, 91, 170, 0.25)',
                                }}
                            >
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px' }}>
                                    <strong style={{ fontSize: '15px', color: '#fef08a' }}>
                                        <i className="bi bi-building"></i> {item.ruangan?.nama_ruangan}
                                    </strong>
                                    <span style={{ fontSize: '12px', background: 'rgba(255,255,255,0.2)', padding: '3px 8px', borderRadius: '6px' }}>
                                        {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)} WITA
                                    </span>
                                </div>

                                <h3 style={{ fontSize: '16px', fontWeight: 700, margin: '8px 0 12px 0' }}>
                                    {item.judul_kegiatan}
                                </h3>

                                <div style={{ fontSize: '12.5px', color: 'rgba(255,255,255,0.85)', display: 'flex', flexDirection: 'column', gap: '4px', borderTop: '1px solid rgba(255,255,255,0.2)', paddingTop: '10px' }}>
                                    <div>Unit: <strong style={{ color: '#fff' }}>{item.user?.nama_unit || item.user?.name}</strong></div>
                                    <div>PIC: <strong style={{ color: '#fff' }}>{item.pic_kegiatan}</strong> ({item.jenis_pic})</div>
                                    <div>Estimasi: <strong style={{ color: '#fff' }}>{item.jumlah_tamu} Orang</strong></div>
                                </div>

                                <div style={{ marginTop: '16px', display: 'flex', justifyContent: 'flex-end' }}>
                                    <button
                                        type="button"
                                        onClick={() => setSelectedLive(item)}
                                        style={{
                                            background: '#10b981',
                                            border: 'none',
                                            color: '#fff',
                                            borderRadius: '8px',
                                            padding: '6px 14px',
                                            fontSize: '12px',
                                            fontWeight: 700,
                                            cursor: 'pointer',
                                            display: 'inline-flex',
                                            alignItems: 'center',
                                            gap: '6px',
                                        }}
                                    >
                                        <i className="bi bi-check2-circle"></i> Selesaikan Lebih Awal
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div style={{ background: '#fff', borderRadius: '14px', padding: '28px', textAlign: 'center', border: '1px solid #e2e8f0' }}>
                        <i className="bi bi-clock-history" style={{ fontSize: '32px', color: '#94a3b8' }}></i>
                        <p style={{ marginTop: '8px', color: '#64748b', fontSize: '13.5px' }}>
                            Saat ini tidak ada kegiatan rapat yang sedang aktif berjalan.
                        </p>
                    </div>
                )}
            </div>

            {/* Today Schedule List */}
            <div>
                <h2 style={{ fontSize: '17px', fontWeight: 700, color: '#003b73', marginBottom: '14px' }}>
                    Semua Agenda Rapat Hari Ini ({todayList.length})
                </h2>

                <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                    <div className="table-responsive">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Ruangan</th>
                                    <th>Kegiatan</th>
                                    <th>Unit / PIC</th>
                                    <th>Peserta</th>
                                </tr>
                            </thead>
                            <tbody>
                                {todayList.length > 0 ? (
                                    todayList.map((item) => (
                                        <tr key={item.id}>
                                            <td style={{ fontWeight: 700, color: '#005baa' }}>
                                                {item.waktu_mulai?.substring(0, 5)} - {item.waktu_selesai?.substring(0, 5)} WITA
                                            </td>
                                            <td><strong>{item.ruangan?.nama_ruangan}</strong></td>
                                            <td>{item.judul_kegiatan}</td>
                                            <td>
                                                <div>{item.user?.nama_unit || item.user?.name}</div>
                                                <small style={{ color: '#64748b' }}>PIC: {item.pic_kegiatan}</small>
                                            </td>
                                            <td>{item.jumlah_tamu} Orang</td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={5} style={{ textAlign: 'center', padding: '24px', color: '#64748b' }}>
                                            Tidak ada jadwal rapat untuk hari ini.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* Modal Selesai Awal */}
            <Modal
                isOpen={!!selectedLive}
                onClose={() => setSelectedLive(null)}
                title="Selesaikan Kegiatan Rapat Lebih Awal"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setSelectedLive(null)} disabled={isFinishing}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#059669', borderColor: '#059669' }} onClick={handleSelesaiAwal} disabled={isFinishing}>
                            {isFinishing ? 'Menyimpan...' : 'Ya, Selesaikan Rapat'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Selesaikan rapat <strong>{selectedLive?.judul_kegiatan}</strong> di ruangan <strong>{selectedLive?.ruangan?.nama_ruangan}</strong> sekarang? Ruangan akan langsung tercatat kosong dan siap digunakan kembali.
                </p>
            </Modal>
        </div>
    );
};
