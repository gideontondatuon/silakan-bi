import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { bookingService } from '../../services/bookingService';
import { Pemesanan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const PemesananDetail: React.FC = () => {
    const { id } = useParams<{ id: string }>();

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

    const formatTanggal = (dateStr?: string) => {
        if (!dateStr) return '-';
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            return d.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            });
        } catch {
            return dateStr;
        }
    };

    const canBeFinishedEarly = (item: Pemesanan): boolean => {
        const status = typeof item.status === 'object' ? (item.status as any).value : item.status;
        if (status !== 'Disetujui') return false;

        try {
            const now = new Date();
            const witaDate = new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Makassar' }).format(now);
            const witaTime = new Intl.DateTimeFormat('en-GB', { timeZone: 'Asia/Makassar', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' }).format(now);

            const itemDate = item.tanggal_kegiatan ? item.tanggal_kegiatan.substring(0, 10) : '';
            const itemStart = item.waktu_mulai ? (item.waktu_mulai.length === 5 ? item.waktu_mulai + ':00' : item.waktu_mulai) : '00:00:00';
            const itemEnd = item.waktu_selesai ? (item.waktu_selesai.length === 5 ? item.waktu_selesai + ':00' : item.waktu_selesai) : '23:59:59';

            return itemDate === witaDate && witaTime >= itemStart && witaTime <= itemEnd;
        } catch {
            return false;
        }
    };

    const renderStatusBadge = (item: Pemesanan) => {
        const statusVal = typeof item.status === 'object' ? (item.status as any).value : item.status;
        if (statusVal === 'Selesai' || item.is_finished) {
            return (
                <span className="badge" style={{ background: '#f1f5f9', color: '#475569', border: '1px solid #cbd5e1', fontWeight: 700 }}>
                    <i className="bi bi-check2-all"></i> Selesai
                </span>
            );
        }
        if (statusVal === 'Pending') {
            return (
                <span className="badge badge-warning">
                    <i className="bi bi-clock"></i> Menunggu Approval
                </span>
            );
        }
        if (statusVal === 'Disetujui') {
            return (
                <span className="badge badge-success">
                    <i className="bi bi-check-circle"></i> Disetujui
                </span>
            );
        }
        if (statusVal === 'Ditolak') {
            return (
                <span className="badge badge-danger">
                    <i className="bi bi-x-circle"></i> Ditolak
                </span>
            );
        }
        return (
            <span className="badge badge-secondary">
                <i className="bi bi-dash-circle"></i> Dibatalkan
            </span>
        );
    };

    if (isLoading) {
        return <LoadingSpinner message="Memuat detail pemesanan..." />;
    }

    if (!pemesanan) {
        return (
            <div style={{ padding: '48px', textAlign: 'center' }}>
                <p style={{ color: '#64748b' }}>Data pemesanan tidak ditemukan.</p>
                <Link to="/pemesanan" className="btn-secondary" style={{ textDecoration: 'none' }}>
                    Kembali ke Riwayat
                </Link>
            </div>
        );
    }

    const statusVal = typeof pemesanan.status === 'object' ? (pemesanan.status as any).value : pemesanan.status;
    const canFinishEarly = canBeFinishedEarly(pemesanan);

    return (
        <div>
            {/* Header matching Blade 1:1 */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-file-earmark-text" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Detail Pemesanan
                    </h1>
                    <p>Informasi lengkap pengajuan penggunaan ruangan.</p>
                </div>
                <Link to="/pemesanan" className="btn-secondary" style={{ textDecoration: 'none' }}>
                    <i className="bi bi-arrow-left"></i> Kembali
                </Link>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Detail Container matching Blade 1:1 */}
            <div className="detail-container">
                {/* Info Utama */}
                <div className="detail-card">
                    <div className="detail-title">
                        <i className="bi bi-info-circle"></i>
                        Informasi Umum
                    </div>
                    <div className="detail-grid">
                        <div>
                            <label>Kode Pemesanan</label>
                            <p style={{ fontFamily: 'monospace', fontWeight: 700, color: '#005baa' }}>
                                {pemesanan.kode_pemesanan}
                            </p>
                        </div>
                        <div>
                            <label>Status</label>
                            <p>{renderStatusBadge(pemesanan)}</p>
                        </div>
                        <div>
                            <label>Judul Kegiatan</label>
                            <p>{pemesanan.judul_kegiatan}</p>
                        </div>
                        <div>
                            <label>Ruangan</label>
                            <p style={{ color: '#005baa', fontWeight: 700 }}>
                                {pemesanan.ruangan?.nama_ruangan}
                            </p>
                        </div>
                        <div>
                            <label>Tanggal Kegiatan</label>
                            <p>{formatTanggal(pemesanan.tanggal_kegiatan)}</p>
                        </div>
                        <div>
                            <label>Waktu</label>
                            <p>{pemesanan.waktu_mulai?.substring(0, 5)} – {pemesanan.waktu_selesai?.substring(0, 5)} WITA</p>
                        </div>
                        <div>
                            <label>Sifat / Jenis Kegiatan</label>
                            <p>
                                <span
                                    style={{
                                        display: 'inline-flex',
                                        alignItems: 'center',
                                        gap: '6px',
                                        padding: '4px 10px',
                                        borderRadius: '8px',
                                        fontSize: '12.5px',
                                        fontWeight: 700,
                                        background: pemesanan.jenis_kegiatan === 'Eksternal' ? '#fef3c7' : '#e0f2fe',
                                        color: pemesanan.jenis_kegiatan === 'Eksternal' ? '#92400e' : '#0369a1',
                                    }}
                                >
                                    <i className={`bi ${pemesanan.jenis_kegiatan === 'Eksternal' ? 'bi-globe2' : 'bi-building'}`}></i>
                                    {pemesanan.jenis_kegiatan === 'Eksternal' ? 'Rapat Eksternal' : 'Rapat Internal'}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {/* Catatan Admin */}
                {pemesanan.catatan_admin && (
                    <div className="detail-card">
                        <div className="detail-title" style={{ color: '#047857' }}>
                            <i className="bi bi-chat-left-text-fill"></i> Catatan Admin
                        </div>
                        <div style={{ padding: '16px 22px', background: '#ecfdf5', borderRadius: '10px', border: '1px solid #a7f3d0', color: '#047857' }}>
                            <p style={{ fontSize: '13.5px', fontWeight: 600, margin: 0 }}>{pemesanan.catatan_admin}</p>
                        </div>
                    </div>
                )}

                {/* Alasan Penolakan */}
                {pemesanan.alasan_penolakan && (
                    <div className="detail-card">
                        <div className="detail-title" style={{ color: '#be123c' }}>
                            <i className="bi bi-exclamation-octagon-fill"></i> Alasan Penolakan
                        </div>
                        <div style={{ padding: '16px 22px', background: '#fff1f2', borderRadius: '10px', border: '1px solid #fecdd3', color: '#be123c' }}>
                            <p style={{ fontSize: '13.5px', fontWeight: 600, margin: 0 }}>{pemesanan.alasan_penolakan}</p>
                        </div>
                    </div>
                )}

                {/* Catatan User */}
                {pemesanan.catatan_user && (
                    <div className="detail-card">
                        <div className="detail-title">
                            <i className="bi bi-sticky"></i> Catatan Pemohon
                        </div>
                        <div style={{ padding: '16px 22px' }}>
                            <p style={{ color: '#334155', fontSize: '13.5px', margin: 0 }}>{pemesanan.catatan_user}</p>
                        </div>
                    </div>
                )}

                {/* Lembar Disposisi */}
                {pemesanan.file_disposisi && (
                    <div className="detail-card">
                        <div className="detail-title">
                            <i className="bi bi-file-earmark-text"></i> Lembar Disposisi
                        </div>
                        <div style={{ padding: '16px 22px' }}>
                            <a
                                href={`/storage/${pemesanan.file_disposisi}`}
                                target="_blank"
                                rel="noreferrer"
                                className="btn-primary"
                                style={{ textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '8px' }}
                            >
                                <i className="bi bi-file-earmark-pdf"></i> Lihat / Unduh Lembar Disposisi
                            </a>
                        </div>
                    </div>
                )}

                {/* Aksi Cepat */}
                {canFinishEarly && (
                    <div className="detail-card" style={{ border: '1px solid #a7f3d0', background: '#f0fdf4' }}>
                        <div className="detail-title" style={{ color: '#047857' }}>
                            <i className="bi bi-play-circle-fill"></i> Kontrol Penggunaan Ruangan
                        </div>
                        <div style={{ padding: '16px 22px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '12px' }}>
                            <div>
                                <p style={{ margin: 0, fontSize: '13.5px', color: '#065f46', fontWeight: 600 }}>
                                    Apakah rapat Anda telah selesai sebelum jadwal berakhir?
                                </p>
                                <small style={{ color: '#047857' }}>
                                    Tekan tombol di samping untuk segera membebaskan ruangan agar dapat digunakan rekan kerja lainnya.
                                </small>
                            </div>
                            <button
                                type="button"
                                onClick={() => setIsSelesaiModalOpen(true)}
                                className="btn-sm"
                                style={{ background: '#059669', color: '#fff', border: 'none', borderRadius: '10px', padding: '9px 18px', fontSize: '13px', fontWeight: 700, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: '6px' }}
                            >
                                <i className="bi bi-check2-circle"></i> Selesaikan Rapat Sekarang
                            </button>
                        </div>
                    </div>
                )}

                {statusVal === 'Pending' && (
                    <div className="detail-card">
                        <div className="detail-title" style={{ color: '#b45309' }}>
                            <i className="bi bi-clock-history"></i> Pembatalan Pengajuan
                        </div>
                        <div style={{ padding: '16px 22px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '12px' }}>
                            <p style={{ margin: 0, fontSize: '13.5px', color: '#475569' }}>
                                Pengajuan pemesanan masih menunggu verifikasi admin.
                            </p>
                            <button
                                type="button"
                                onClick={() => setIsCancelModalOpen(true)}
                                className="btn-danger btn-sm"
                                style={{ padding: '8px 16px' }}
                            >
                                <i className="bi bi-x-circle"></i> Batalkan Pengajuan
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* Modal Cancel */}
            <Modal
                isOpen={isCancelModalOpen}
                onClose={() => setIsCancelModalOpen(false)}
                title="Batalkan Pemesanan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsCancelModalOpen(false)} disabled={isCancelling}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleCancel} disabled={isCancelling}>
                            {isCancelling ? 'Membatalkan...' : 'Ya, Batalkan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah Anda yakin ingin membatalkan pengajuan pemesanan <strong>{pemesanan.kode_pemesanan}</strong>?
                </p>
            </Modal>

            {/* Modal Selesai Awal */}
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
                            {isFinishing ? 'Memproses...' : 'Ya, Selesaikan Sekarang'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah rapat di ruangan <strong>{pemesanan.ruangan?.nama_ruangan}</strong> telah selesai lebih cepat dan siap dibebaskan?
                </p>
            </Modal>
        </div>
    );
};
