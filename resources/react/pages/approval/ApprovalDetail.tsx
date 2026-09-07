import React, { useEffect, useState } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { Pemesanan } from '../../types';
import { Badge } from '../../components/common/Badge';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const ApprovalDetail: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    const [pemesanan, setPemesanan] = useState<Pemesanan | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal state
    const [isApproveOpen, setIsApproveOpen] = useState(false);
    const [catatanAdmin, setCatatanAdmin] = useState('');
    const [isApproving, setIsApproving] = useState(false);

    const [isRejectOpen, setIsRejectOpen] = useState(false);
    const [alasanPenolakan, setAlasanPenolakan] = useState('');
    const [isRejecting, setIsRejecting] = useState(false);

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
            loadDetail();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menolak pemesanan.' });
        } finally {
            setIsRejecting(false);
        }
    };

    if (isLoading) {
        return <LoadingSpinner message="Memuat detail pengajuan..." />;
    }

    if (!pemesanan) {
        return (
            <div style={{ padding: '32px', textAlign: 'center' }}>
                <p>Data pemesanan tidak ditemukan.</p>
                <Link to="/admin/approval" className="btn-secondary">Kembali ke Daftar</Link>
            </div>
        );
    }

    return (
        <div>
            {/* Header */}
            <div style={{ marginBottom: '20px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '12px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                    <button
                        type="button"
                        className="btn-secondary"
                        onClick={() => navigate('/admin/approval')}
                        style={{ padding: '8px 14px' }}
                    >
                        <i className="bi bi-arrow-left"></i> Kembali
                    </button>
                    <div>
                        <h1 style={{ fontSize: '20px', fontWeight: 800, color: '#003b73', margin: 0 }}>
                            Verifikasi Pemesanan {pemesanan.kode_pemesanan}
                        </h1>
                        <small style={{ color: '#64748b' }}>Pemohon: {pemesanan.user?.nama_unit || pemesanan.user?.name}</small>
                    </div>
                </div>

                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <Badge status={pemesanan.status} />

                    {pemesanan.status === 'Pending' && (
                        <>
                            <button
                                type="button"
                                className="btn-primary"
                                style={{ background: '#059669', borderColor: '#059669' }}
                                onClick={() => setIsApproveOpen(true)}
                            >
                                <i className="bi bi-check-circle-fill"></i> Setujui Pemesanan
                            </button>
                            <button
                                type="button"
                                className="btn-secondary"
                                style={{ color: '#dc2626', borderColor: '#fecdd3', background: '#fef2f2' }}
                                onClick={() => setIsRejectOpen(true)}
                            >
                                <i className="bi bi-x-circle-fill"></i> Tolak Pemesanan
                            </button>
                        </>
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

            {/* Grid */}
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '20px' }}>
                {/* Info Card */}
                <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', padding: '24px' }}>
                    <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', borderBottom: '1px solid #e2e8f0', paddingBottom: '12px', marginBottom: '16px' }}>
                        <i className="bi bi-card-text" style={{ color: '#005baa', marginRight: '6px' }}></i> Informasi Rapat
                    </h3>

                    <div style={{ display: 'flex', flexDirection: 'column', gap: '14px', fontSize: '13.5px' }}>
                        <div>
                            <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Judul Kegiatan:</span>
                            <strong style={{ fontSize: '15px', color: '#1e293b' }}>{pemesanan.judul_kegiatan}</strong>
                        </div>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Ruangan:</span>
                                <strong>{pemesanan.ruangan?.nama_ruangan}</strong>
                                <div style={{ fontSize: '12px', color: '#64748b' }}>{pemesanan.ruangan?.lokasi}</div>
                            </div>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Layout:</span>
                                <strong>{pemesanan.layout?.nama_layout || 'Standar'}</strong>
                            </div>
                        </div>
                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Tanggal:</span>
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
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>No. WA PIC:</span>
                                <span>{pemesanan.no_wa_pic}</span>
                            </div>
                        )}
                        {pemesanan.file_disposisi && (
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Berkas Disposisi:</span>
                                <a
                                    href={`/storage/${pemesanan.file_disposisi}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    style={{ display: 'inline-flex', alignItems: 'center', gap: '6px', color: '#005baa', fontWeight: 600, marginTop: '4px' }}
                                >
                                    <i className="bi bi-file-earmark-pdf"></i> Buka / Unduh Dokumen Disposisi &rarr;
                                </a>
                            </div>
                        )}
                    </div>
                </div>

                {/* Status & Approver Card */}
                <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', padding: '24px' }}>
                    <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', borderBottom: '1px solid #e2e8f0', paddingBottom: '12px', marginBottom: '16px' }}>
                        <i className="bi bi-shield-check" style={{ color: '#005baa', marginRight: '6px' }}></i> Detail Verifikasi
                    </h3>

                    <div style={{ fontSize: '13px', display: 'flex', flexDirection: 'column', gap: '12px' }}>
                        <div>
                            <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Status Terkini:</span>
                            <div style={{ marginTop: '4px' }}><Badge status={pemesanan.status} /></div>
                        </div>

                        {pemesanan.approved_by && (
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Disetujui Oleh:</span>
                                <strong>{pemesanan.approver?.name || 'Admin'}</strong>
                                <small style={{ display: 'block', color: '#64748b' }}>
                                    {pemesanan.approved_at ? new Date(pemesanan.approved_at).toLocaleString('id-ID') : '-'}
                                </small>
                            </div>
                        )}

                        {pemesanan.catatan_admin && (
                            <div>
                                <span style={{ color: '#64748b', display: 'block', fontSize: '12px' }}>Catatan Admin:</span>
                                <div style={{ background: '#f8fafc', padding: '8px 12px', borderRadius: '8px', border: '1px solid #e2e8f0', fontStyle: 'italic' }}>
                                    "{pemesanan.catatan_admin}"
                                </div>
                            </div>
                        )}

                        {pemesanan.rejected_by && (
                            <div>
                                <span style={{ color: '#dc2626', display: 'block', fontSize: '12px', fontWeight: 700 }}>Alasan Penolakan:</span>
                                <div style={{ background: '#fef2f2', padding: '10px 14px', borderRadius: '8px', border: '1px solid #fecdd3', color: '#991b1b', fontWeight: 600 }}>
                                    "{pemesanan.alasan_penolakan}"
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>

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
                            {isApproving ? 'Menyetujui...' : 'Setujui Pemesanan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', marginBottom: '12px' }}>
                    Setujui pengajuan tiket <strong>{pemesanan.kode_pemesanan}</strong> ({pemesanan.judul_kegiatan})?
                </p>
                <div className="form-group">
                    <label style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                        Catatan Admin (Opsional):
                    </label>
                    <textarea
                        className="login-input"
                        rows={2}
                        value={catatanAdmin}
                        onChange={(e) => setCatatanAdmin(e.target.value)}
                        placeholder="Contoh: Kunci ruangan dan remote AC tersedia di bagian Rumah Tangga."
                    />
                </div>
            </Modal>

            {/* Modal Reject */}
            <Modal
                isOpen={isRejectOpen}
                onClose={() => setIsRejectOpen(false)}
                title="Tolak Pemesanan Ruangan"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsRejectOpen(false)} disabled={isRejecting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleReject} disabled={isRejecting}>
                            {isRejecting ? 'Menolak...' : 'Tolak Pengajuan'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155', marginBottom: '12px' }}>
                    Tolak pengajuan tiket <strong>{pemesanan.kode_pemesanan}</strong>?
                </p>
                <div className="form-group">
                    <label className="required" style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                        Alasan Penolakan:
                    </label>
                    <textarea
                        className="login-input"
                        rows={3}
                        value={alasanPenolakan}
                        onChange={(e) => setAlasanPenolakan(e.target.value)}
                        placeholder="Tuliskan alasan penolakan..."
                        required
                    />
                </div>
            </Modal>
        </div>
    );
};
