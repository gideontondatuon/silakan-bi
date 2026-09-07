import React, { useEffect, useState } from 'react';
import { useNavigate, useLocation, Link } from 'react-router-dom';
import { bookingService, ConflictCheckResult } from '../../services/bookingService';
import { adminService } from '../../services/adminService';
import { LayoutRuangan, Ruangan } from '../../types';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const PemesananCreate: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const isAdminMode = location.pathname.startsWith('/admin');

    const [ruanganList, setRuanganList] = useState<Ruangan[]>([]);
    const [layoutList, setLayoutList] = useState<LayoutRuangan[]>([]);
    const [isLoadingInit, setIsLoadingInit] = useState(true);

    // Form fields
    const [ruanganId, setRuanganId] = useState('');
    const [layoutId, setLayoutId] = useState('');
    const [tanggal, setTanggal] = useState('');
    const [waktuMulai, setWaktuMulai] = useState('');
    const [waktuSelesai, setWaktuSelesai] = useState('');
    const [judulKegiatan, setJudulKegiatan] = useState('');
    const [picKegiatan, setPicKegiatan] = useState('');
    const [noWaPic, setNoWaPic] = useState('');
    const [jenisPic, setJenisPic] = useState<'Organik' | 'Non Organik'>('Organik');
    const [jumlahTamu, setJumlahTamu] = useState('');
    const [keteranganLayout, setKeteranganLayout] = useState('');
    const [catatanUser, setCatatanUser] = useState('');
    const [fileDisposisi, setFileDisposisi] = useState<File | null>(null);

    // Conflict & Capacity state
    const [conflictResult, setConflictResult] = useState<ConflictCheckResult | null>(null);
    const [isCheckingConflict, setIsCheckingConflict] = useState(false);
    const [selectedRoom, setSelectedRoom] = useState<Ruangan | null>(null);

    // Status messages
    const [errorMessage, setErrorMessage] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Generate 30-minute time intervals
    const timeSlots: string[] = [];
    for (let h = 6; h <= 23; h++) {
        const hStr = String(h).padStart(2, '0');
        timeSlots.push(`${hStr}:00`);
        if (h < 23) timeSlots.push(`${hStr}:30`);
    }

    const endSlots: string[] = [];
    for (let h = 6; h <= 23; h++) {
        const hStr = String(h).padStart(2, '0');
        endSlots.push(`${hStr}:30`);
        if (h < 23) {
            const nextHStr = String(h + 1).padStart(2, '0');
            endSlots.push(`${nextHStr}:00`);
        }
    }

    // Load active rooms on mount
    useEffect(() => {
        const init = async () => {
            try {
                const res = await bookingService.getRuanganList(true);
                if (res.status === 'success') {
                    setRuanganList(res.data);
                }
            } catch (e) {
                // Ignore
            } finally {
                setIsLoadingInit(false);
            }
        };
        init();
    }, []);

    // Load layouts when ruanganId changes
    useEffect(() => {
        if (!ruanganId) {
            setLayoutList([]);
            setSelectedRoom(null);
            return;
        }

        const room = ruanganList.find((r) => String(r.id) === String(ruanganId));
        setSelectedRoom(room || null);

        const fetchLayouts = async () => {
            try {
                const layouts = await bookingService.getLayoutsByRuangan(ruanganId);
                setLayoutList(layouts);
            } catch (e) {
                setLayoutList([]);
            }
        };
        fetchLayouts();
    }, [ruanganId, ruanganList]);

    // Check conflict when room, date, start, or end time changes
    useEffect(() => {
        if (!ruanganId || !tanggal || !waktuMulai || !waktuSelesai || waktuSelesai <= waktuMulai) {
            setConflictResult(null);
            return;
        }

        const timer = setTimeout(async () => {
            setIsCheckingConflict(true);
            try {
                const result = await bookingService.checkConflict({
                    ruangan_id: ruanganId,
                    tanggal_kegiatan: tanggal,
                    waktu_mulai: waktuMulai,
                    waktu_selesai: waktuSelesai,
                });
                setConflictResult(result);
            } catch (e) {
                setConflictResult(null);
            } finally {
                setIsCheckingConflict(false);
            }
        }, 300);

        return () => clearTimeout(timer);
    }, [ruanganId, tanggal, waktuMulai, waktuSelesai]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setErrorMessage('');

        if (conflictResult?.conflict) {
            setErrorMessage('Ruangan tidak tersedia pada jam tersebut karena bentrok dengan kegiatan lain.');
            return;
        }

        if (selectedRoom && Number(jumlahTamu) > selectedRoom.kapasitas) {
            const confirmed = window.confirm(
                `Jumlah tamu (${jumlahTamu}) melebihi kapasitas standar ruangan ${selectedRoom.nama_ruangan} (${selectedRoom.kapasitas} orang). Lanjutkan pengajuan?`
            );
            if (!confirmed) return;
        }

        setIsSubmitting(true);

        const formData = new FormData();
        formData.append('ruangan_id', ruanganId);
        if (layoutId) formData.append('layout_ruangan_id', layoutId);
        formData.append('tanggal_kegiatan', tanggal);
        formData.append('waktu_mulai', waktuMulai);
        formData.append('waktu_selesai', waktuSelesai);
        formData.append('judul_kegiatan', judulKegiatan);
        formData.append('pic_kegiatan', picKegiatan);
        if (noWaPic) formData.append('no_wa_pic', noWaPic);
        formData.append('jenis_pic', jenisPic);
        formData.append('jumlah_tamu', jumlahTamu);
        if (keteranganLayout) formData.append('keterangan_layout', keteranganLayout);
        if (catatanUser) formData.append('catatan_user', catatanUser);
        if (fileDisposisi) formData.append('file_disposisi', fileDisposisi);

        try {
            if (isAdminMode) {
                const res = await adminService.createBooking(formData);
                if (res.status === 'success') {
                    navigate('/admin/approval', {
                        state: { flashSuccess: res.message || 'Rapat berhasil dijadwalkan dan langsung berstatus Disetujui.' },
                    });
                }
            } else {
                const res = await bookingService.createPemesanan(formData);
                if (res.status === 'success') {
                    navigate('/pemesanan', {
                        state: { flashSuccess: 'Pemesanan berhasil dibuat dan menunggu persetujuan admin.' },
                    });
                }
            }
        } catch (err: any) {
            const msg = err.response?.data?.message || 'Gagal menyimpan pemesanan. Periksa input formulir Anda.';
            setErrorMessage(msg);
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className={`bi ${isAdminMode ? 'bi-calendar-plus-fill' : 'bi-calendar-plus'}`} style={{ color: '#005baa', marginRight: '8px' }}></i>
                        {isAdminMode ? 'Tambah Rapat (Admin)' : 'Formulir Pemesanan Ruangan'}
                    </h1>
                    <p>
                        {isAdminMode
                            ? 'Jadwalkan rapat atau kegiatan secara langsung dari sisi Administrator. Rapat langsung berstatus Disetujui.'
                            : 'Silakan isi informasi kegiatan dan jadwal pemakaian ruangan dengan lengkap'}
                    </p>
                </div>
                <div>
                    <Link
                        to={isAdminMode ? '/admin/approval' : '/pemesanan'}
                        className="btn-secondary"
                        style={{ textDecoration: 'none' }}
                    >
                        <i className="bi bi-arrow-left"></i> Kembali
                    </Link>
                </div>
            </div>

            {errorMessage && (
                <AlertBanner
                    type="error"
                    message={errorMessage}
                    onClose={() => setErrorMessage('')}
                />
            )}

            <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', padding: '28px', maxWidth: '900px', boxShadow: '0 4px 12px rgba(0,0,0,0.03)' }}>
                {isAdminMode && (
                    <div style={{ padding: '14px 18px', borderRadius: '12px', background: '#f0fdf4', border: '1px solid #bbf7d0', color: '#166534', fontSize: '13px', lineHeight: 1.5, marginBottom: '24px', display: 'flex', alignItems: 'flex-start', gap: '12px' }}>
                        <i className="bi bi-lightning-charge-fill" style={{ fontSize: '20px', color: '#16a34a', flexShrink: 0 }}></i>
                        <div>
                            <strong>Penjadwalan Instan Administrator:</strong>
                            <p style={{ margin: '2px 0 0 0', color: '#15803d', fontSize: '12.5px' }}>
                                Rapat yang dibuat melalui formulir ini tidak memerlukan verifikasi persetujuan lagi. Status pemesanan langsung <strong>Disetujui</strong> dan seketika tercatat pada kalender ruangan serta layar monitor TV Lobby.
                            </p>
                        </div>
                    </div>
                )}
                <form onSubmit={handleSubmit}>
                    {/* Ruangan & Layout */}
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(min(100%, 260px), 1fr))', gap: '20px', marginBottom: '20px' }}>
                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Pilih Ruangan Rapat
                            </label>
                            <select
                                className="login-input"
                                value={ruanganId}
                                onChange={(e) => {
                                    setRuanganId(e.target.value);
                                    setLayoutId('');
                                }}
                                required
                            >
                                <option value="">-- Pilih Ruangan --</option>
                                {ruanganList.map((r) => (
                                    <option key={r.id} value={r.id}>
                                        {r.nama_ruangan} (Kapasitas: {r.kapasitas} orang - {r.lokasi})
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div className="form-group">
                            <label style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Layout Ruangan (Opsional)
                            </label>
                            <select
                                className="login-input"
                                value={layoutId}
                                onChange={(e) => setLayoutId(e.target.value)}
                                disabled={!ruanganId || layoutList.length === 0}
                            >
                                <option value="">-- Pilih Layout Standar --</option>
                                {layoutList.map((l) => (
                                    <option key={l.id} value={l.id}>
                                        {l.nama_layout}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>

                    {/* Tanggal, Waktu Mulai, Waktu Selesai */}
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '20px', marginBottom: '20px' }}>
                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Tanggal Kegiatan
                            </label>
                            <input
                                type="date"
                                className="login-input"
                                value={tanggal}
                                min={new Date().toISOString().split('T')[0]}
                                onChange={(e) => setTanggal(e.target.value)}
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Waktu Mulai
                            </label>
                            <select
                                className="login-input"
                                value={waktuMulai}
                                onChange={(e) => {
                                    setWaktuMulai(e.target.value);
                                    if (!waktuSelesai || waktuSelesai <= e.target.value) {
                                        // Auto advance 1 hour
                                        const [h, m] = e.target.value.split(':').map(Number);
                                        const nextH = String(h + 1).padStart(2, '0');
                                        setWaktuSelesai(`${nextH}:${String(m).padStart(2, '0')}`);
                                    }
                                }}
                                required
                            >
                                <option value="">-- Jam Mulai --</option>
                                {timeSlots.map((slot) => (
                                    <option key={slot} value={slot}>
                                        {slot} WITA
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Waktu Selesai
                            </label>
                            <select
                                className="login-input"
                                value={waktuSelesai}
                                onChange={(e) => setWaktuSelesai(e.target.value)}
                                required
                            >
                                <option value="">-- Jam Selesai --</option>
                                {endSlots
                                    .filter((s) => !waktuMulai || s > waktuMulai)
                                    .map((slot) => (
                                        <option key={slot} value={slot}>
                                            {slot} WITA
                                        </option>
                                    ))}
                            </select>
                        </div>
                    </div>

                    {/* Conflict Alert Box */}
                    {isCheckingConflict && (
                        <div style={{ padding: '12px 16px', borderRadius: '10px', background: '#f8fafc', border: '1px solid #e2e8f0', color: '#64748b', fontSize: '13px', marginBottom: '20px' }}>
                            <i className="bi bi-arrow-repeat" style={{ animation: 'spin 1s infinite linear', marginRight: '6px' }}></i> Memeriksa ketersediaan jadwal ruangan...
                        </div>
                    )}

                    {conflictResult && (
                        <div
                            style={{
                                padding: '14px 18px',
                                borderRadius: '12px',
                                marginBottom: '20px',
                                background: conflictResult.conflict ? '#fef2f2' : '#ecfdf5',
                                border: `1px solid ${conflictResult.conflict ? '#fecdd3' : '#a7f3d0'}`,
                                color: conflictResult.conflict ? '#991b1b' : '#065f46',
                            }}
                        >
                            <div style={{ display: 'flex', alignItems: 'center', gap: '10px', fontWeight: 700, fontSize: '14px' }}>
                                <i className={`bi ${conflictResult.conflict ? 'bi-exclamation-octagon-fill' : 'bi-check-circle-fill'}`}></i>
                                <span>{conflictResult.message}</span>
                            </div>
                            {conflictResult.conflict && (
                                <ul style={{ marginTop: '8px', paddingLeft: '24px', fontSize: '12.5px' }}>
                                    {conflictResult.conflicts.map((c) => (
                                        <li key={c.id}>
                                            <strong>{c.judul_kegiatan}</strong> ({c.unit}) &mdash; {c.waktu_mulai} s.d. {c.waktu_selesai} WITA [{c.status}]
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </div>
                    )}

                    {/* Judul Kegiatan */}
                    <div className="form-group" style={{ marginBottom: '20px' }}>
                        <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                            Judul / Agenda Rapat
                        </label>
                        <input
                            type="text"
                            className="login-input"
                            value={judulKegiatan}
                            onChange={(e) => setJudulKegiatan(e.target.value)}
                            placeholder="Contoh: Rapat Koordinasi Pengendalian Inflasi Daerah (TPID)"
                            required
                        />
                    </div>

                    {/* PIC, Jenis PIC, No WA, Jumlah Tamu */}
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '20px', marginBottom: '20px' }}>
                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Nama PIC Kegiatan
                            </label>
                            <input
                                type="text"
                                className="login-input"
                                value={picKegiatan}
                                onChange={(e) => setPicKegiatan(e.target.value)}
                                placeholder="Nama lengkap penanggung jawab"
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Jenis Pegawai PIC
                            </label>
                            <select
                                className="login-input"
                                value={jenisPic}
                                onChange={(e) => setJenisPic(e.target.value as any)}
                                required
                            >
                                <option value="Organik">Organik (Pegawai BI)</option>
                                <option value="Non Organik">Non Organik (Mitra/External)</option>
                            </select>
                        </div>

                        <div className="form-group">
                            <label style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                No. WhatsApp PIC
                            </label>
                            <input
                                type="tel"
                                className="login-input"
                                value={noWaPic}
                                onChange={(e) => setNoWaPic(e.target.value)}
                                placeholder="08xxxxxxxxxx"
                            />
                        </div>

                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Estimasi Jumlah Tamu
                            </label>
                            <input
                                type="number"
                                min={1}
                                className="login-input"
                                value={jumlahTamu}
                                onChange={(e) => setJumlahTamu(e.target.value)}
                                placeholder="Jumlah peserta"
                                required
                            />
                            {selectedRoom && Number(jumlahTamu) > selectedRoom.kapasitas && (
                                <small style={{ color: '#dc2626', fontSize: '11.5px', marginTop: '4px', display: 'block' }}>
                                    Peringatan: Melebihi kapasitas ruangan ({selectedRoom.kapasitas} orang)
                                </small>
                            )}
                        </div>
                    </div>

                    {/* Upload Disposisi & Catatan */}
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(min(100%, 260px), 1fr))', gap: '20px', marginBottom: '24px' }}>
                        <div className="form-group">
                            <label style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Upload Dokumen Disposisi / Surat (PDF / JPG / PNG max 5MB)
                            </label>
                            <input
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                className="login-input"
                                onChange={(e) => setFileDisposisi(e.target.files?.[0] || null)}
                                style={{ padding: '8px' }}
                            />
                        </div>

                        <div className="form-group">
                            <label style={{ display: 'block', marginBottom: '8px', fontWeight: 600, fontSize: '13.5px', color: '#1e293b' }}>
                                Catatan Tambahan / Kebutuhan Khusus
                            </label>
                            <textarea
                                className="login-input"
                                rows={2}
                                value={catatanUser}
                                onChange={(e) => setCatatanUser(e.target.value)}
                                placeholder="Kebutuhan zoom meeting, proyektor, snack, dll."
                                style={{ resize: 'vertical' }}
                            />
                        </div>
                    </div>

                    {/* Submit Actions */}
                    <div className="form-action" style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', borderTop: '1px solid #e2e8f0', paddingTop: '20px' }}>
                        <button
                            type="button"
                            className="btn-secondary"
                            onClick={() => navigate(isAdminMode ? '/admin/approval' : '/pemesanan')}
                            disabled={isSubmitting}
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            className="btn-primary"
                            disabled={isSubmitting || !!conflictResult?.conflict}
                        >
                            {isSubmitting ? (
                                <>
                                    <span
                                        style={{
                                            display: 'inline-block',
                                            width: '16px',
                                            height: '16px',
                                            border: '2px solid rgba(255,255,255,0.4)',
                                            borderTopColor: '#fff',
                                            borderRadius: '50%',
                                            animation: 'spin 0.6s linear infinite',
                                            marginRight: '8px',
                                        }}
                                    />
                                    Menyimpan Pemesanan...
                                </>
                            ) : (
                                <>
                                    <i className={`bi ${isAdminMode ? 'bi-calendar-check-fill' : 'bi-send-fill'}`}></i>{' '}
                                    {isAdminMode ? 'Jadwalkan Rapat (Otomatis Disetujui)' : 'Ajukan Pemesanan'}
                                </>
                            )}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};
