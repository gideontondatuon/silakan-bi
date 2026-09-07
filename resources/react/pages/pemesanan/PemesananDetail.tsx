import React, { useEffect, useState } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { bookingService } from '../../services/bookingService';
import { Pemesanan } from '../../types';
import { Badge } from '../../components/common/Badge';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const PemesananDetail: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    const [pemesanan, setPemesanan] = useState<Pemesanan | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Cancel modal
    const [isCancelModalOpen, setIsCancelModalOpen] = useState(false);
    const [isCancelling, setIsCancelling] = useState(false);

    // Selesai Awal modal
    const [isSelesaiModalOpen, setIsSelesaiModalOpen] = useState(false);
    const [isFinishing, setIsFinishing] = useState(false);

    const loadDetail = async () => {
        if (!id) return;
        setIsLoading(true);
        try {
            const res = await bookingService.getPemesananDetail(id);
            if (res.status === 'success') {
                setPemesanan(res.data);
            }
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal memuat detail pemesanan.' });
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadDetail();
    }, [id]);

    const handleCancel = async () => {
        if (!id) return;
        setIsCancelling(true);
        try {
            const res = await bookingService.cancelPemesanan(id);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil dibatalkan.' });
            setIsCancelModalOpen(false);
            loadDetail();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal membatalkan pemesanan.' });
        } finally {
            setIsCancelling(false);
        }
    };

    const handleSelesaiAwal = async () => {
        if (!id) return;
        setIsFinishing(true);
        try {
            const res = await bookingService.selesaiAwal(id);
            setAlertMessage({ type: 'success', text: res.message || 'Kegiatan berhasil diselesaikan lebih awal.' });
            setIsSelesaiModalOpen(false);
            loadDetail();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyelesaikan rapat.' });
        } finally {
            setIsFinishing(false);
        }
    };

    if (isLoading) {
        return <LoadingSpinner message="Memuat detail pemesanan..." />;
    }

    if (!pemesanan) {
        return (
            <div style={{ padding: '32px', textAlign: 'center' }}>
                <p>Data pemesanan tidak ditemukan.</p>
                <Link to="/pemesanan" className="btn-secondary">Kembali ke Riwayat</Link>
            </div>
        );
    }

    const canCancel = pemesanan.status === 'Pending' || pemesanan.status === 'Disetujui';
    const isToday = pemesanan.tanggal_kegiatan === new Date().toISOString().split('T')[0];
    const canSelesaiAwal = pemesanan.status === 'Disetujui' && isToday;

    return (
        <div>
            {/* Header */}
            <div style={{ marginBottom: '20px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '12px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                    <button
                        type="button"
                        className="btn-secondary"
                        onClick={() => navigate('/pemesanan')}
                        style={{ padding: '8px 14px' }}
                    >
                        <i className="bi bi-arrow-left"></i> Kembali
                    </button>
                    <div>
                        <h1 style={{ fontSize: '20px', fontWeight: 800, color: '#003b73', margin: 0 }}>
                            Pemesanan {pemesanan.kode_pemesanan}
                        </h1>
                        <small style={{ color: '#64748b' }}>Dibuat pada {new Date(pemesanan.created_at).toLocaleString('id-ID')}</small>
                    </div>
                </div>

                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <Badge status={pemesanan.status} />

                    {canSelesaiAwal && (
                        <button
                            type="button"
                            className="btn-primary"
                            style={{ background: '#059669', borderColor: '#059669', fontSize: '13px' }}
                            onClick={() => setIsSelesaiModalOpen(true)}
                        >
                            <i className="bi bi-check2-circle"></i> Selesaikan Lebih Awal
                        </button>
                    )}

                    {canCancel && (
                        <button
                            type="button"
                            className="btn-secondary"
                            style={{ color: '#dc2626', borderColor: '#fecdd3', background: '#fef2f2', fontSize: '13px' }}
                            onClick={() => setIsCancelModalOpen(true)}
                        >
                            <i className="bi bi-x-circle"></i> Batalkan Pemesanan
                        </button>
                    )}
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Content Cards */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '20px' }}>
                {/* Detail Kegiatan */}
                <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', padding: '24px', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                    <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', borderBottom: '1px solid #e2e8f0', paddingBottom: '12px', marginBottom: '16px' }}>
                        <i className="bi bi-info-circle" style={{ color: '#005baa', marginRight: '6px' }}></i> Informasi Rapat
                    </h3>

                    <div style={{ display: 'flex', flexDirection: 'column', gap: '14px', fontSize: '13.5px' }}>
                        <div>
                            <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Judul Kegiatan:</span>
                            <strong style={{ fontSize: '15px', color: '#1e293b' }}>{pemesanan.judul_kegiatan}</strong>
                        </div>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Ruangan:</span>
                                <strong>{pemesanan.ruangan?.nama_ruangan || '-'}</strong>
                                <div style={{ fontSize: '12px', color: '#64748b' }}>{pemesanan.ruangan?.lokasi}</div>
                            </div>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Layout:</span>
                                <strong>{pemesanan.layout?.nama_layout || 'Standar'}</strong>
                            </div>
                        </div>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Tanggal Kegiatan:</span>
                                <strong>{pemesanan.tanggal_kegiatan}</strong>
                            </div>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Waktu:</span>
                                <strong style={{ color: '#005baa' }}>
                                    {pemesanan.waktu_mulai?.substring(0, 5)} - {pemesanan.waktu_selesai?.substring(0, 5)} WITA
                                </strong>
                            </div>
                        </div>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>PIC Kegiatan:</span>
                                <strong>{pemesanan.pic_kegiatan}</strong>
                                <small style={{ display: 'block', color: '#64748b' }}>({pemesanan.jenis_pic})</small>
                            </div>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Estimasi Tamu:</span>
                                <strong>{pemesanan.jumlah_tamu} Orang</strong>
                            </div>
                        </div>
                        {pemesanan.no_wa_pic && (
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>WhatsApp PIC:</span>
                                <span>{pemesanan.no_wa_pic}</span>
                            </div>
                        )}
                        {pemesanan.catatan_user && (
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Catatan Tambahan User:</span>
                                <p style={{ margin: '4px 0 0 0', background: '#f8fafc', padding: '10px', borderRadius: '8px', border: '1px solid #e2e8f0' }}>
                                    {pemesanan.catatan_user}
                                </p>
                            </div>
                        )}
                        {pemesanan.file_disposisi && (
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Dokumen Disposisi:</span>
                                <a
                                    href={`/storage/${pemesanan.file_disposisi}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    style={{ display: 'inline-flex', alignItems: 'center', gap: '6px', color: '#005baa', fontWeight: 600, marginTop: '4px' }}
                                >
                                    <i className="bi bi-file-earmark-pdf"></i> Lihat / Unduh Berkas Disposisi &rarr;
                                </a>
                            </div>
                        )}
                    </div>
                </div>

                {/* Status & Approval Trail */}
                <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', padding: '24px', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                    <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', borderBottom: '1px solid #e2e8f0', paddingBottom: '12px', marginBottom: '16px' }}>
                        <i className="bi bi-clock-history" style={{ color: '#005baa', marginRight: '6px' }}></i> Status & Riwayat Verifikasi
                    </h3>

                    {pemesanan.status === 'Disetujui' && (
                        <div style={{ padding: '12px 16px', borderRadius: '10px', background: '#ecfdf5', border: '1px solid #a7f3d0', color: '#065f46', marginBottom: '16px' }}>
                            <strong><i className="bi bi-check-circle-fill"></i> Disetujui oleh Administrator</strong>
                            <div style={{ fontSize: '12px', marginTop: '4px' }}>
                                Oleh: {pemesanan.approver?.name || 'Admin'} pada {pemesanan.approved_at ? new Date(pemesanan.approved_at).toLocaleString('id-ID') : '-'}
                            </div>
                            {pemesanan.catatan_admin && (
                                <div style={{ fontSize: '12.5px', marginTop: '6px', fontStyle: 'italic' }}>
                                    Catatan: "{pemesanan.catatan_admin}"
                                </div>
                            )}
                        </div>
                    )}

                    {pemesanan.status === 'Ditolak' && (
                        <div style={{ padding: '12px 16px', borderRadius: '10px', background: '#fef2f2', border: '1px solid #fecdd3', color: '#991b1b', marginBottom: '16px' }}>
                            <strong><i className="bi bi-x-circle-fill"></i> Pengajuan Ditolak</strong>
                            <div style={{ fontSize: '12px', marginTop: '4px' }}>
                                Oleh: {pemesanan.rejector?.name || 'Admin'} pada {pemesanan.rejected_at ? new Date(pemesanan.rejected_at).toLocaleString('id-ID') : '-'}
                            </div>
                            {pemesanan.alasan_penolakan && (
                                <div style={{ fontSize: '12.5px', marginTop: '6px', fontWeight: 600 }}>
                                    Alasan: "{pemesanan.alasan_penolakan}"
                                </div>
                            )}
                        </div>
                    )}

                    {pemesanan.status === 'Cancel' && (
                        <div style={{ padding: '12px 16px', borderRadius: '10px', background: '#f1f5f9', border: '1px solid #cbd5e1', color: '#475569', marginBottom: '16px' }}>
                            <strong><i className="bi bi-slash-circle"></i> Pemesanan Dibatalkan</strong>
                            <div style={{ fontSize: '12px', marginTop: '4px' }}>
                                Dibatalkan pada {pemesanan.cancelled_at ? new Date(pemesanan.cancelled_at).toLocaleString('id-ID') : '-'}
                            </div>
                        </div>
                    )}

                    {pemesanan.history && pemesanan.history.length > 0 && (
                        <div style={{ marginTop: '16px' }}>
                            <span style={{ fontSize: '13px', fontWeight: 700, color: '#334155', display: 'block', marginBottom: '10px' }}>
                                Jejak Riwayat Status:
                            </span>
                            <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
                                {pemesanan.history.map((h) => (
                                    <div key={h.id} style={{ display: 'flex', justifyContent: 'space-between', fontSize: '12.5px', padding: '8px 12px', background: '#f8fafc', borderRadius: '8px', border: '1px solid #e2e8f0' }}>
                                        <div>
                                            <Badge status={h.status} />
                                            {h.catatan && <small style={{ marginLeft: '8px', color: '#64748b' }}>({h.catatan})</small>}
                                        </div>
                                        <small style={{ color: '#94a3b8' }}>
                                            {new Date(h.created_at).toLocaleString('id-ID')}
                                        </small>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>

            {/* Cancel Modal */}
            <Modal
                isOpen={isCancelModalOpen}
                onClose={() => setIsCancelModalOpen(false)}
                title="Batalkan Pengajuan Pemesanan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsCancelModalOpen(false)} disabled={isCancelling}>
                            Tutup
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleCancel} disabled={isCancelling}>
                            {isCancelling ? 'Membatalkan...' : 'Ya, Batalkan Pemesanan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah Anda yakin ingin membatalkan pengajuan pemesanan <strong>{pemesanan.kode_pemesanan}</strong> untuk kegiatan <em>{pemesanan.judul_kegiatan}</em>?
                </p>
            </Modal>

            {/* Selesai Awal Modal */}
            <Modal
                isOpen={isSelesaiModalOpen}
                onClose={() => setIsSelesaiModalOpen(false)}
                title="Selesaikan Rapat Lebih Awal"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsSelesaiModalOpen(false)} disabled={isFinishing}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#059669', borderColor: '#059669' }} onClick={handleSelesaiAwal} disabled={isFinishing}>
                            {isFinishing ? 'Menyimpan...' : 'Ya, Selesaikan Sekarang'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah rapat di ruangan <strong>{pemesanan.ruangan?.nama_ruangan}</strong> telah selesai lebih awal dan siap dibebaskan untuk kegiatan lain?
                </p>
            </Modal>
        </div>
    );
};
