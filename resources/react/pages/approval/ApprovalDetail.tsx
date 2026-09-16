import React, { useEffect, useState } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { Pemesanan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const ApprovalDetail: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    const [pemesanan, setPemesanan] = useState<Pemesanan | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal states
    const [isApproveOpen, setIsApproveOpen] = useState(false);
    const [catatanAdmin, setCatatanAdmin] = useState('');
    const [isApproving, setIsApproving] = useState(false);

    const [isRejectOpen, setIsRejectOpen] = useState(false);
    const [alasanPenolakan, setAlasanPenolakan] = useState('');
    const [isRejecting, setIsRejecting] = useState(false);

    const [isDeleteOpen, setIsDeleteOpen] = useState(false);
    const [isDeleting, setIsDeleting] = useState(false);

    const [isEarlyFinishOpen, setIsEarlyFinishOpen] = useState(false);
    const [isFinishingEarly, setIsFinishingEarly] = useState(false);

    const loadDetail = async () => {
        if (!id) return;
        setIsLoading(true);
        try {
            const res = await adminService.getApprovalDetail(id);
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

    const handleApprove = async () => {
        if (!id) return;
        setIsApproving(true);
        try {
            const res = await adminService.approve(id, catatanAdmin);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil disetujui.' });
            setIsApproveOpen(false);
            setCatatanAdmin('');
            loadDetail();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyetujui pemesanan.' });
        } finally {
            setIsApproving(false);
        }
    };

    const handleReject = async () => {
        if (!id) return;
        if (!alasanPenolakan.trim()) {
            alert('Alasan penolakan wajib diisi.');
            return;
        }
        setIsRejecting(true);
        try {
            const res = await adminService.reject(id, alasanPenolakan);
            setAlertMessage({ type: 'success', text: res.message || 'Pemesanan berhasil ditolak.' });
            setIsRejectOpen(false);
            setAlasanPenolakan('');
            loadDetail();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menolak pemesanan.' });
        } finally {
            setIsRejecting(false);
        }
    };

    const handleDelete = async () => {
        if (!id) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteBooking(id);
            setIsDeleteOpen(false);
            navigate('/admin/approval', { replace: true });
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menghapus pemesanan.' });
            setIsDeleting(false);
        }
    };

    const handleEarlyFinish = async () => {
        if (!id) return;
        setIsFinishingEarly(true);
        try {
            const res = await adminService.selesaiAwal(id);
            setAlertMessage({ type: 'success', text: res.message || 'Kegiatan berhasil diselesaikan lebih awal.' });
            setIsEarlyFinishOpen(false);
            loadDetail();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyelesaikan kegiatan.' });
        } finally {
            setIsFinishingEarly(false);
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
        if (statusVal === 'Disetujui') {
            return (
                <span className="badge badge-success">
                    <i className="bi bi-check-circle-fill"></i> Disetujui
                </span>
            );
        }
        if (statusVal === 'Pending') {
            return (
                <span className="badge badge-warning">
                    <i className="bi bi-clock-history"></i> Pending
                </span>
            );
        }
        if (statusVal === 'Ditolak') {
            return (
                <span className="badge badge-danger">
                    <i className="bi bi-x-circle-fill"></i> Ditolak
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
        return <LoadingSpinner message="Memuat detail pengajuan..." />;
    }

    if (!pemesanan) {
        return (
            <div style={{ padding: '48px', textAlign: 'center' }}>
                {alertMessage && (
                    <div style={{ maxWidth: '600px', margin: '0 auto 20px auto' }}>
                        <AlertBanner type={alertMessage.type} message={alertMessage.text} onClose={() => setAlertMessage(null)} />
                    </div>
                )}
                <p style={{ color: '#64748b' }}>Data pemesanan tidak ditemukan.</p>
                <Link to="/admin/approval" className="btn-secondary">Kembali ke Daftar</Link>
            </div>
        );
    }

    const statusVal = typeof pemesanan.status === 'object' ? (pemesanan.status as any).value : pemesanan.status;
    const isLiveToday = canBeFinishedEarly(pemesanan);

    // Prepare WhatsApp link
    let cleanPhone = pemesanan.no_wa_pic ? pemesanan.no_wa_pic.replace(/[^0-9]/g, '') : '';
    if (cleanPhone.startsWith('0')) {
        cleanPhone = '62' + cleanPhone.substring(1);
    }
    const waMsg = encodeURIComponent(
        `Halo Bapak/Ibu ${pemesanan.pic_kegiatan}, kami dari Tim Pengelola Ruangan KPwBI Prov. Sulut mengonfirmasi terkait pengajuan pemesanan ruangan ${pemesanan.ruangan?.nama_ruangan} untuk agenda "${pemesanan.judul_kegiatan}" (Kode: ${pemesanan.kode_pemesanan}).`
    );

    return (
        <div>
            {/* Header matching Blade */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-file-earmark-check" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Detail Pemesanan
                    </h1>
                    <p>Review informasi pengajuan penggunaan ruangan sebelum persetujuan.</p>
                </div>
                <Link to="/admin/approval" className="btn-secondary" style={{ textDecoration: 'none' }}>
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
                {/* Info Pemohon */}
                <div className="detail-card">
                    <div className="detail-title">
                        <i className="bi bi-person-circle"></i>
                        Informasi Pemohon
                    </div>
                    <div className="detail-grid">
                        <div>
                            <label>Nama Pemohon</label>
                            <p>{pemesanan.user?.name || (pemesanan as any).users?.name || pemesanan.pic_kegiatan || '-'}</p>
                        </div>
                        <div>
                            <label>Unit Kerja</label>
                            <p>{pemesanan.user?.nama_unit || (pemesanan as any).users?.nama_unit || (pemesanan as any).nama_unit || '-'}</p>
                        </div>
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
                    </div>
                </div>

                {/* Info Kegiatan */}
                <div className="detail-card">
                    <div className="detail-title">
                        <i className="bi bi-calendar-event"></i>
                        Informasi Kegiatan
                    </div>
                    <div className="detail-grid">
                        <div>
                            <label>Judul Kegiatan</label>
                            <p>{pemesanan.judul_kegiatan}</p>
                        </div>
                        <div>
                            <label>PIC Kegiatan</label>
                            <div style={{ display: 'flex', alignItems: 'center', gap: '10px', flexWrap: 'wrap', marginTop: '3px' }}>
                                <strong style={{ color: '#0f172a', fontSize: '14px' }}>{pemesanan.pic_kegiatan}</strong>
                                {cleanPhone && (
                                    <a
                                        href={`https://wa.me/${cleanPhone}?text=${waMsg}`}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="btn-sm"
                                        style={{
                                            background: '#16a34a',
                                            color: '#fff',
                                            textDecoration: 'none',
                                            borderRadius: '8px',
                                            padding: '4px 10px',
                                            fontSize: '12px',
                                            fontWeight: 700,
                                            display: 'inline-flex',
                                            alignItems: 'center',
                                            gap: '5px',
                                            boxShadow: '0 2px 6px rgba(22,163,74,0.25)',
                                        }}
                                    >
                                        <i className="bi bi-whatsapp"></i> Chat WA PIC ({pemesanan.no_wa_pic})
                                    </a>
                                )}
                            </div>
                        </div>
                        <div>
                            <label>Jenis PIC</label>
                            <p>{pemesanan.jenis_pic ?? '-'}</p>
                        </div>
                        <div>
                            <label>Jumlah Tamu</label>
                            <p>{pemesanan.jumlah_tamu} orang</p>
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
                                        fontSize: '12px',
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

                {/* Info Ruangan */}
                <div className="detail-card">
                    <div className="detail-title">
                        <i className="bi bi-building"></i>
                        Informasi Ruangan
                    </div>
                    <div className="detail-grid">
                        <div>
                            <label>Ruangan</label>
                            <p style={{ color: '#005baa', fontWeight: 700 }}>{pemesanan.ruangan?.nama_ruangan}</p>
                        </div>
                        <div>
                            <label>Layout</label>
                            <p>{pemesanan.layout?.nama_layout ?? '-'}</p>
                        </div>
                    </div>

                    {pemesanan.keterangan_layout && (
                        <div className="layout-note">
                            <label>Keterangan Layout</label>
                            <p>{pemesanan.keterangan_layout}</p>
                        </div>
                    )}
                </div>

                {/* Lembar Disposisi */}
                <div className="detail-card">
                    <div className="detail-title">
                        <i className="bi bi-file-earmark-text"></i>
                        Lembar Disposisi
                    </div>
                    <div style={{ padding: '16px 22px' }}>
                        {pemesanan.file_disposisi ? (
                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', background: '#f0f9ff', padding: '16px', borderRadius: '10px', border: '1px solid #bae6fd', flexWrap: 'wrap', gap: '10px' }}>
                                <div>
                                    <strong style={{ color: '#003b73', fontSize: '14px', display: 'flex', alignItems: 'center', gap: '7px' }}>
                                        <i className="bi bi-file-earmark-pdf" style={{ color: '#005baa' }}></i>
                                        File Disposisi Terlampir
                                    </strong>
                                    <small style={{ color: '#64748b' }}>Pengguna telah mengunggah berkas lembar disposisi.</small>
                                </div>
                                <a href={`/storage/${pemesanan.file_disposisi}`} target="_blank" rel="noreferrer" className="btn-primary btn-sm" style={{ textDecoration: 'none' }}>
                                    <i className="bi bi-download"></i> Buka / Unduh
                                </a>
                            </div>
                        ) : (
                            <div style={{ background: '#fffbeb', border: '1px solid #fde68a', padding: '14px 16px', borderRadius: '10px', color: '#b45309', fontSize: '13.5px', display: 'flex', alignItems: 'center', gap: '9px' }}>
                                <i className="bi bi-exclamation-triangle-fill"></i>
                                Pengguna tidak mengunggah berkas lembar disposisi.
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Action Bar matching Blade 1:1 */}
            <div className="dashboard-section" style={{ marginTop: '20px' }}>
                <div style={{ padding: '20px 24px' }}>
                    <div className="approval-action" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '16px', flexWrap: 'wrap' }}>
                        <Link to="/admin/approval" className="btn-secondary" style={{ textDecoration: 'none' }}>
                            <i className="bi bi-arrow-left"></i> Kembali ke Daftar
                        </Link>

                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px', flexWrap: 'wrap' }}>
                            {/* Early Release if Live Today */}
                            {isLiveToday && (
                                <button
                                    type="button"
                                    onClick={() => setIsEarlyFinishOpen(true)}
                                    className="btn-sm"
                                    style={{ padding: '10px 20px', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '8px', background: '#059669', color: '#fff', border: 'none', borderRadius: '10px', cursor: 'pointer', fontSize: '13.5px' }}
                                >
                                    <i className="bi bi-check2-circle"></i> Selesaikan Rapat Sekarang
                                </button>
                            )}

                            {/* Delete Button */}
                            <button
                                type="button"
                                onClick={() => setIsDeleteOpen(true)}
                                className="btn-secondary"
                                style={{ padding: '10px 20px', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '8px', background: '#fee2e2', color: '#dc2626', border: '1px solid #fecdd3', borderRadius: '10px', cursor: 'pointer', fontSize: '13.5px' }}
                                title="Hapus pemesanan secara permanen"
                            >
                                <i className="bi bi-trash-fill"></i> Hapus Pemesanan
                            </button>

                            {/* Pending action buttons */}
                            {statusVal === 'Pending' && (
                                <>
                                    <button
                                        type="button"
                                        className="btn-danger"
                                        onClick={() => setIsRejectOpen(true)}
                                        style={{ padding: '10px 24px', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '8px', borderRadius: '10px', fontSize: '13.5px', cursor: 'pointer' }}
                                    >
                                        <i className="bi bi-x-circle-fill"></i> Tolak Pemesanan
                                    </button>

                                    <button
                                        type="button"
                                        className="btn-success"
                                        onClick={() => setIsApproveOpen(true)}
                                        style={{ padding: '10px 24px', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '8px', background: '#059669', color: '#fff', border: 'none', borderRadius: '10px', cursor: 'pointer', fontSize: '13.5px' }}
                                    >
                                        <i className="bi bi-check-circle-fill"></i> Setujui Pemesanan
                                    </button>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Modal Reject */}
            <Modal
                isOpen={isRejectOpen}
                onClose={() => setIsRejectOpen(false)}
                title="Konfirmasi Penolakan Pemesanan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsRejectOpen(false)} disabled={isRejecting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleReject} disabled={isRejecting}>
                            {isRejecting ? 'Menolak...' : 'Kirim & Tolak Pemesanan'}
                        </button>
                    </>
                }
            >
                <div style={{ background: '#fef2f2', border: '1px solid #fecdd3', padding: '12px 16px', borderRadius: '10px', color: '#9f1239', fontSize: '13px', marginBottom: '16px' }}>
                    Pemberitahuan penolakan beserta alasan di bawah ini akan dikirimkan kepada pemohon ({pemesanan.pic_kegiatan}).
                </div>
                <div className="form-group" style={{ marginBottom: 0 }}>
                    <label className="required" style={{ display: 'block', fontWeight: 700, fontSize: '13.5px', color: '#334155', marginBottom: '8px' }}>
                        Alasan Penolakan
                    </label>
                    <textarea
                        rows={4}
                        value={alasanPenolakan}
                        onChange={(e) => setAlasanPenolakan(e.target.value)}
                        placeholder="Tuliskan alasan penolakan secara jelas (misal: Ruangan digunakan untuk rapat internal pimpinan mendadak)..."
                        style={{ width: '100%', padding: '12px 14px', border: '1px solid #cbd5e1', borderRadius: '10px', fontSize: '13.5px', outline: 'none', resize: 'vertical', boxSizing: 'border-box' }}
                        required
                    />
                    <span style={{ display: 'block', fontSize: '11.5px', color: '#64748b', marginTop: '6px' }}>
                        Berikan alasan yang informatif agar pemohon dapat memilih jadwal/ruangan lain.
                    </span>
                </div>
            </Modal>

            {/* Modal Approve */}
            <Modal
                isOpen={isApproveOpen}
                onClose={() => setIsApproveOpen(false)}
                title="Setujui Pemesanan Ruangan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsApproveOpen(false)} disabled={isApproving}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#059669', borderColor: '#059669' }} onClick={handleApprove} disabled={isApproving}>
                            {isApproving ? 'Menyetujui...' : 'Ya, Setujui Pemesanan'}
                        </button>
                    </>
                }
            >
                <div style={{ background: '#f0fdf4', border: '1px solid #bbf7d0', padding: '12px 16px', borderRadius: '10px', color: '#166534', fontSize: '13px', marginBottom: '16px' }}>
                    Status pemesanan akan diubah menjadi <strong>Disetujui</strong> dan tercatat pada kalender ruangan serta layar monitor TV Lobby.
                </div>
                <div className="form-group" style={{ marginBottom: 0 }}>
                    <label style={{ display: 'block', fontWeight: 700, fontSize: '13.5px', color: '#334155', marginBottom: '8px' }}>
                        Catatan Persetujuan (Opsional)
                    </label>
                    <textarea
                        rows={3}
                        value={catatanAdmin}
                        onChange={(e) => setCatatanAdmin(e.target.value)}
                        placeholder="Tambahkan catatan khusus jika diperlukan (misal: Sound system dan pointer disiapkan oleh bagian Rumah Tangga)..."
                        style={{ width: '100%', padding: '12px 14px', border: '1px solid #cbd5e1', borderRadius: '10px', fontSize: '13.5px', outline: 'none', resize: 'vertical', boxSizing: 'border-box' }}
                    />
                </div>
            </Modal>

            {/* Modal Selesai Awal */}
            <Modal
                isOpen={isEarlyFinishOpen}
                onClose={() => setIsEarlyFinishOpen(false)}
                title="Selesaikan Rapat Lebih Awal"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsEarlyFinishOpen(false)} disabled={isFinishingEarly}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#059669', borderColor: '#059669' }} onClick={handleEarlyFinish} disabled={isFinishingEarly}>
                            {isFinishingEarly ? 'Memproses...' : 'Ya, Selesaikan Sekarang'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah rapat di ruangan <strong>{pemesanan.ruangan?.nama_ruangan}</strong> telah selesai lebih cepat dan siap dibebaskan?
                </p>
            </Modal>

            {/* Modal Delete */}
            <Modal
                isOpen={isDeleteOpen}
                onClose={() => setIsDeleteOpen(false)}
                title="Hapus Pemesanan Ruangan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsDeleteOpen(false)} disabled={isDeleting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDelete} disabled={isDeleting}>
                            {isDeleting ? 'Menghapus...' : 'Ya, Hapus Pemesanan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', lineHeight: 1.6 }}>
                    Apakah Anda yakin ingin menghapus data pemesanan <strong>{pemesanan.kode_pemesanan}</strong> ({pemesanan.judul_kegiatan}) secara permanen dari sistem? Jadwal ruangan akan dibebaskan seketika.
                </p>
            </Modal>
        </div>
    );
};
