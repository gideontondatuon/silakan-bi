import React, { useEffect, useState } from 'react';
import { useNavigate, useLocation, Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { bookingService, ConflictCheckResult } from '../../services/bookingService';
import { adminService } from '../../services/adminService';
import { LayoutRuangan, Ruangan } from '../../types';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const PemesananCreate: React.FC = () => {
    const { user: currentUser } = useAuth();
    const userKodeUnit = (
        currentUser?.kode_unit ||
        currentUser?.department?.kode_unit ||
        (currentUser?.role === 'admin' ? 'ADM' : 'UNIT')
    ).toUpperCase();
    const userNamaUnit = currentUser?.nama_unit || currentUser?.department?.nama_unit || '';

    const navigate = useNavigate();
    const location = useLocation();
    const isAdminMode = currentUser?.role === 'admin' || location.pathname.startsWith('/admin');

    const [ruanganList, setRuanganList] = useState<Ruangan[]>([]);
    const [layoutList, setLayoutList] = useState<LayoutRuangan[]>([]);
    const [isLoadingInit, setIsLoadingInit] = useState(true);

    // Unit selection (for admin dropdown)
    const [unitList, setUnitList] = useState<Array<{ id: number; nama_unit: string; kode_unit: string; role: string }>>([]);
    const [selectedUnitId, setSelectedUnitId] = useState<string>('');
    const [selectedKodeUnit, setSelectedKodeUnit] = useState<string>(userKodeUnit);
    const [selectedNamaUnit, setSelectedNamaUnit] = useState<string>(userNamaUnit);

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
    const [jenisKegiatan, setJenisKegiatan] = useState<'Internal' | 'Eksternal'>('Internal');
    const [fileDisposisi, setFileDisposisi] = useState<File | null>(null);

    // Conflict & Capacity state
    const [conflictResult, setConflictResult] = useState<ConflictCheckResult | null>(null);
    const [isCheckingConflict, setIsCheckingConflict] = useState(false);
    const [selectedRoom, setSelectedRoom] = useState<Ruangan | null>(null);

    // Status messages
    const [errorMessage, setErrorMessage] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Generate 30-minute intervals: Operating hours strictly 08:00 - 19:00 WITA
    const timeSlots: string[] = [];
    for (let h = 8; h <= 18; h++) {
        const hStr = String(h).padStart(2, '0');
        timeSlots.push(`${hStr}:00`);
        timeSlots.push(`${hStr}:30`);
    }

    const endSlots: string[] = [];
    for (let h = 8; h <= 18; h++) {
        const hStr = String(h).padStart(2, '0');
        endSlots.push(`${hStr}:30`);
        const nextHStr = String(h + 1).padStart(2, '0');
        endSlots.push(`${nextHStr}:00`);
    }

    // Holiday & working day restrictions
    const [holidays, setHolidays] = useState<Array<{ tanggal: string; keterangan: string; kategori: string }>>([]);
    const [dateWarning, setDateWarning] = useState<string>('');

    const isWeekend = (dateStr: string) => {
        if (!dateStr) return false;
        const [y, m, d] = dateStr.split('-').map(Number);
        const day = new Date(y, m - 1, d).getDay();
        return day === 0 || day === 6;
    };

    const getHolidayInfo = (dateStr: string) => {
        if (!dateStr || holidays.length === 0) return null;
        return holidays.find((h) => h.tanggal === dateStr) || null;
    };

    const handleDateChange = (newDate: string) => {
        setDateWarning('');
        if (!newDate) {
            setTanggal('');
            return;
        }

        if (isWeekend(newDate)) {
            setDateWarning('Pemesanan ruangan hanya dapat dilakukan pada hari kerja (Senin - Jumat). Hari Sabtu dan Minggu tidak dapat dipilih untuk rapat.');
            setTanggal('');
            return;
        }

        const hol = getHolidayInfo(newDate);
        if (hol) {
            setDateWarning(`Tanggal ${newDate} merupakan hari libur (${hol.keterangan}). Hari libur tidak dapat dipilih untuk rapat.`);
            setTanggal('');
            return;
        }

        setTanggal(newDate);
    };

    // Helper to get today's date YYYY-MM-DD in local time
    const getTodayDateString = () => {
        const d = new Date();
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    // Helper to get current HH:mm in local time
    const getCurrentTimeString = () => {
        const d = new Date();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    };

    // Check if a time slot is already passed for the selected date
    const isSlotPassed = (slot: string) => {
        if (!tanggal) return false;
        const today = getTodayDateString();
        if (tanggal < today) return true;
        if (tanggal === today) {
            return slot <= getCurrentTimeString();
        }
        return false;
    };

    // Automatically reset waktuMulai & waktuSelesai if they become invalid/passed
    useEffect(() => {
        if (tanggal && waktuMulai && isSlotPassed(waktuMulai)) {
            setWaktuMulai('');
            setWaktuSelesai('');
        }
    }, [tanggal]);

    // Load active rooms, holiday calendar, and registered units on mount
    useEffect(() => {
        const init = async () => {
            try {
                const promises: Promise<any>[] = [
                    bookingService.getRuanganList(true),
                    bookingService.getHolidays(),
                ];
                if (isAdminMode) {
                    promises.push(bookingService.getUnits());
                }
                const [ruanganRes, holidayRes, unitsRes] = await Promise.all(promises);
                if (ruanganRes.status === 'success') {
                    setRuanganList(ruanganRes.data);
                }
                if (Array.isArray(holidayRes)) {
                    setHolidays(holidayRes);
                }
                if (unitsRes && unitsRes.status === 'success' && Array.isArray(unitsRes.data)) {
                    setUnitList(unitsRes.data);
                }
            } catch (e) {
                // Ignore
            } finally {
                setIsLoadingInit(false);
            }
        };
        init();
    }, [isAdminMode]);

    useEffect(() => {
        if (currentUser) {
            setSelectedUnitId(String(currentUser.id));
            setSelectedKodeUnit(userKodeUnit);
            setSelectedNamaUnit(userNamaUnit);
        }
    }, [currentUser, userKodeUnit, userNamaUnit]);

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

    // Calculate meeting duration
    const calculateDuration = () => {
        if (!waktuMulai || !waktuSelesai) return null;
        const [h1, m1] = waktuMulai.split(':').map(Number);
        const [h2, m2] = waktuSelesai.split(':').map(Number);
        const diffMinutes = (h2 * 60 + m2) - (h1 * 60 + m1);
        if (diffMinutes <= 0) return null;
        const hours = Math.floor(diffMinutes / 60);
        const mins = diffMinutes % 60;
        if (hours > 0 && mins > 0) return `${hours} jam ${mins} menit`;
        if (hours > 0) return `${hours} jam`;
        return `${mins} menit`;
    };

    const duration = calculateDuration();

    // Capacity calculations
    const capacityPercentage = selectedRoom && jumlahTamu
        ? Math.min(100, Math.round((Number(jumlahTamu) / selectedRoom.kapasitas) * 100))
        : 0;
    const isOverCapacity = selectedRoom && Number(jumlahTamu) > selectedRoom.kapasitas;

    // Selected layout object
    const selectedLayout = layoutList.find((l) => String(l.id) === String(layoutId));



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

        // Check if file disposisi is uploaded in user mode
        if (!isAdminMode && !fileDisposisi) {
            setErrorMessage('Lembar disposisi / nota dinas wajib diunggah.');
            return;
        }

        // Validasi Hari Kerja: Hanya Senin - Jumat
        if (isWeekend(tanggal)) {
            setErrorMessage('Pemesanan ruangan hanya dapat dilakukan pada hari kerja (Senin - Jumat). Hari Sabtu dan Minggu tidak dapat dipilih untuk rapat.');
            return;
        }

        // Validasi Hari Libur
        const hol = getHolidayInfo(tanggal);
        if (hol) {
            setErrorMessage(`Tanggal ${tanggal} merupakan hari libur (${hol.keterangan}). Hari libur tidak dapat dipilih untuk rapat.`);
            return;
        }

        // Validasi Jam Operasional Rapat: 08:00 - 19:00 WITA
        if (waktuMulai < '08:00' || waktuMulai >= '19:00') {
            setErrorMessage('Jam mulai rapat harus berada di antara pukul 08:00 - 18:30 WITA.');
            return;
        }

        if (waktuSelesai <= waktuMulai) {
            setErrorMessage('Jam selesai rapat harus lebih besar dari jam mulai.');
            return;
        }

        if (waktuSelesai > '19:00') {
            setErrorMessage('Jam selesai rapat tidak boleh melebihi pukul 19:00 WITA (jam operasional 08:00 - 19:00 WITA).');
            return;
        }

        // Check if selected time today is already past
        const now = new Date();
        const todayStr = now.toISOString().split('T')[0];
        if (tanggal === todayStr) {
            const curH = String(now.getHours()).padStart(2, '0');
            const curM = String(now.getMinutes()).padStart(2, '0');
            const curTime = `${curH}:${curM}`;
            if (waktuMulai < curTime) {
                setErrorMessage(`Waktu mulai (${waktuMulai} WITA) tidak boleh menggunakan jam yang sudah terlewat untuk hari ini (waktu sekarang: ${curTime} WITA).`);
                return;
            }
        }

        setIsSubmitting(true);

        const formData = new FormData();
        formData.append('ruangan_id', ruanganId);
        if (layoutId) formData.append('layout_ruangan_id', layoutId);
        formData.append('tanggal_kegiatan', tanggal);
        formData.append('waktu_mulai', waktuMulai);
        formData.append('waktu_selesai', waktuSelesai);
        formData.append('judul_kegiatan', judulKegiatan);
        formData.append('jenis_kegiatan', jenisKegiatan);
        formData.append('pic_kegiatan', picKegiatan);
        formData.append('jenis_pic', jenisPic);
        if (noWaPic) formData.append('no_wa_pic', noWaPic);
        formData.append('jumlah_tamu', jumlahTamu);
        if (keteranganLayout) formData.append('keterangan_layout', keteranganLayout);
        if (catatanUser) formData.append('catatan_user', catatanUser);
        if (fileDisposisi) formData.append('file_disposisi', fileDisposisi);
        if (isAdminMode) {
            if (selectedUnitId) formData.append('user_id', selectedUnitId);
            if (selectedKodeUnit) formData.append('kode_unit', selectedKodeUnit);
        }

        try {
            if (isAdminMode) {
                const res = await adminService.adminCreateBooking(formData);
                if (res.status === 'success') {
                    navigate('/admin/approval', {
                        state: { flashSuccess: 'Pemesanan rapat berhasil dijadwalkan dan langsung disetujui.' },
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
            let msg = 'Gagal menyimpan pemesanan. Periksa input formulir Anda.';
            if (err.response?.data?.errors) {
                const errorsObj = err.response.data.errors;
                const errorList = Object.values(errorsObj).flat() as string[];
                if (errorList.length > 0) {
                    msg = errorList.join(' ');
                } else if (err.response?.data?.message) {
                    msg = err.response.data.message;
                }
            } else if (err.response?.data?.message) {
                msg = err.response.data.message;
            } else if (err.message) {
                msg = err.message;
            }
            setErrorMessage(msg);
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div style={{ maxWidth: '1280px', margin: '0 auto', paddingBottom: '40px' }}>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '24px' }}>
                <div>
                    <h1 style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                        <span
                            style={{
                                width: '38px',
                                height: '38px',
                                borderRadius: '10px',
                                background: 'linear-gradient(135deg, #005baa 0%, #003366 100%)',
                                color: '#ffffff',
                                display: 'inline-flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                fontSize: '18px',
                                boxShadow: '0 4px 10px rgba(0, 91, 170, 0.25)',
                            }}
                        >
                            <i className={`bi ${isAdminMode ? 'bi-calendar-plus-fill' : 'bi-calendar2-plus-fill'}`}></i>
                        </span>
                        {isAdminMode ? 'Tambah Rapat (Admin)' : 'Formulir Pemesanan Ruangan'}
                    </h1>
                    <p style={{ marginTop: '4px', color: '#64748b', fontSize: '13.5px' }}>
                        {isAdminMode
                            ? 'Jadwalkan rapat atau kegiatan secara langsung dari sisi Administrator. Rapat langsung berstatus Disetujui.'
                            : 'Silakan isi informasi kegiatan dan jadwal pemakaian ruangan dengan lengkap dan akurat.'}
                    </p>
                </div>
                <div>
                    <Link
                        to={isAdminMode ? '/admin/approval' : '/pemesanan'}
                        className="btn-secondary"
                        style={{
                            textDecoration: 'none',
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '8px',
                            padding: '9px 16px',
                            borderRadius: '10px',
                            fontWeight: 600,
                        }}
                    >
                        <i className="bi bi-arrow-left"></i> Kembali
                    </Link>
                </div>
            </div>

            {errorMessage && (
                <div style={{ marginBottom: '20px' }}>
                    <AlertBanner
                        type="error"
                        message={errorMessage}
                        onClose={() => setErrorMessage('')}
                    />
                </div>
            )}

            {isAdminMode && (
                <div
                    style={{
                        padding: '16px 20px',
                        borderRadius: '14px',
                        background: 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
                        border: '1px solid #86efac',
                        color: '#166534',
                        fontSize: '13.5px',
                        lineHeight: 1.5,
                        marginBottom: '24px',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '14px',
                        boxShadow: '0 4px 12px rgba(22, 163, 74, 0.08)',
                    }}
                >
                    <div
                        style={{
                            width: '36px',
                            height: '36px',
                            borderRadius: '50%',
                            background: '#16a34a',
                            color: '#ffffff',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontSize: '18px',
                            flexShrink: 0,
                        }}
                    >
                        <i className="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div>
                        <strong style={{ fontSize: '14px' }}>Penjadwalan Instan Administrator</strong>
                        <p style={{ margin: '2px 0 0 0', color: '#15803d', fontSize: '13px' }}>
                            Rapat yang dibuat tidak memerlukan approval. Status pemesanan langsung <strong>Disetujui</strong> dan otomatis disinkronkan ke kalender ruangan serta layar monitor TV Lobby.
                        </p>
                    </div>
                </div>
            )}

            <form onSubmit={handleSubmit}>
                <div className="booking-grid">
                    {/* LEFT COLUMN: Form Cards */}
                    <div>
                        {/* Section 1: Ruangan & Agenda */}
                        <div className="booking-card">
                            <div className="booking-card-header">
                                <div className="booking-card-icon">
                                    <i className="bi bi-door-open-fill"></i>
                                </div>
                                <div>
                                    <h2 className="booking-card-title">1. Pilih Ruangan & Agenda Kegiatan</h2>
                                    <p className="booking-card-subtitle">Tentukan ruangan rapat dan topik pembahasan acara</p>
                                </div>
                            </div>

                            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px', marginBottom: '16px' }}>
                                <div className="form-field">
                                    <label className="form-label">
                                        Pilih Ruangan Rapat <span className="req">*</span>
                                    </label>
                                    <select
                                        className="input-modern"
                                        value={ruanganId}
                                        onChange={(e) => {
                                            setRuanganId(e.target.value);
                                            setLayoutId('');
                                        }}
                                        required
                                    >
                                        <option value="">-- Pilih Ruangan Rapat --</option>
                                        {ruanganList.map((r) => (
                                            <option key={r.id} value={r.id}>
                                                {r.nama_ruangan} (Kapasitas: {r.kapasitas} orang)
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div className="form-field">
                                    <label className="form-label">
                                        Layout / Tata Letak Ruangan
                                        <span style={{ fontSize: '11px', color: '#94a3b8', fontWeight: 400 }}>Opsional</span>
                                    </label>
                                    <select
                                        className="input-modern"
                                        value={layoutId}
                                        onChange={(e) => setLayoutId(e.target.value)}
                                        disabled={!ruanganId || layoutList.length === 0}
                                    >
                                        <option value="">
                                            {layoutList.length === 0 && ruanganId
                                                ? '-- Layout Standar Ruangan --'
                                                : '-- Pilih Format Meja / Kursi --'}
                                        </option>
                                        {layoutList.map((l) => (
                                            <option key={l.id} value={l.id}>
                                                {l.nama_layout} {l.kapasitas_layout ? `(Maks ${l.kapasitas_layout} org)` : ''}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            </div>

                            <div className="form-field">
                                <label className="form-label">
                                    Judul / Agenda Rapat <span className="req">*</span>
                                </label>
                                <input
                                    type="text"
                                    className="input-modern"
                                    value={judulKegiatan}
                                    onChange={(e) => setJudulKegiatan(e.target.value)}
                                    placeholder="Contoh: Rapat Koordinasi Pengendalian Inflasi Daerah (TPID) Semester II"
                                    required
                                />
                            </div>

                            {/* Sifat / Jenis Kegiatan */}
                            <div className="form-field" style={{ marginTop: '16px' }}>
                                <label className="form-label" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                                    <span>Sifat / Jenis Kegiatan <span className="req">*</span></span>
                                    <span style={{ fontSize: '11.5px', color: '#64748b', fontWeight: 500 }}>
                                        Dipisahkan pada layar monitor Kiosk TV Lobby
                                    </span>
                                </label>
                                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '12px' }}>
                                    <button
                                        type="button"
                                        onClick={() => setJenisKegiatan('Internal')}
                                        style={{
                                            display: 'flex',
                                            alignItems: 'center',
                                            gap: '12px',
                                            padding: '12px 16px',
                                            borderRadius: '12px',
                                            border: jenisKegiatan === 'Internal' ? '2px solid #005baa' : '1px solid #cbd5e1',
                                            background: jenisKegiatan === 'Internal' ? 'linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%)' : '#ffffff',
                                            cursor: 'pointer',
                                            textAlign: 'left',
                                            transition: 'all 0.2s ease',
                                            boxShadow: jenisKegiatan === 'Internal' ? '0 4px 12px rgba(0, 91, 170, 0.12)' : 'none',
                                        }}
                                    >
                                        <div
                                            style={{
                                                width: '38px',
                                                height: '38px',
                                                borderRadius: '10px',
                                                background: jenisKegiatan === 'Internal' ? 'linear-gradient(135deg, #005baa 0%, #003366 100%)' : '#f1f5f9',
                                                color: jenisKegiatan === 'Internal' ? '#ffffff' : '#64748b',
                                                display: 'flex',
                                                alignItems: 'center',
                                                justifyContent: 'center',
                                                fontSize: '18px',
                                                flexShrink: 0,
                                            }}
                                        >
                                            <i className="bi bi-building"></i>
                                        </div>
                                        <div style={{ flex: 1 }}>
                                            <div style={{ fontWeight: 700, fontSize: '13.5px', color: jenisKegiatan === 'Internal' ? '#005baa' : '#1e293b' }}>
                                                Rapat Internal
                                            </div>
                                            <div style={{ fontSize: '11.5px', color: '#64748b', marginTop: '1px' }}>
                                                Khusus pegawai / internal BI
                                            </div>
                                        </div>
                                        {jenisKegiatan === 'Internal' && (
                                            <i className="bi bi-check-circle-fill" style={{ color: '#005baa', fontSize: '18px' }}></i>
                                        )}
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => setJenisKegiatan('Eksternal')}
                                        style={{
                                            display: 'flex',
                                            alignItems: 'center',
                                            gap: '12px',
                                            padding: '12px 16px',
                                            borderRadius: '12px',
                                            border: jenisKegiatan === 'Eksternal' ? '2px solid #d97706' : '1px solid #cbd5e1',
                                            background: jenisKegiatan === 'Eksternal' ? 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)' : '#ffffff',
                                            cursor: 'pointer',
                                            textAlign: 'left',
                                            transition: 'all 0.2s ease',
                                            boxShadow: jenisKegiatan === 'Eksternal' ? '0 4px 12px rgba(217, 119, 6, 0.15)' : 'none',
                                        }}
                                    >
                                        <div
                                            style={{
                                                width: '38px',
                                                height: '38px',
                                                borderRadius: '10px',
                                                background: jenisKegiatan === 'Eksternal' ? 'linear-gradient(135deg, #d97706 0%, #b45309 100%)' : '#f1f5f9',
                                                color: jenisKegiatan === 'Eksternal' ? '#ffffff' : '#64748b',
                                                display: 'flex',
                                                alignItems: 'center',
                                                justifyContent: 'center',
                                                fontSize: '18px',
                                                flexShrink: 0,
                                            }}
                                        >
                                            <i className="bi bi-globe2"></i>
                                        </div>
                                        <div style={{ flex: 1 }}>
                                            <div style={{ fontWeight: 700, fontSize: '13.5px', color: jenisKegiatan === 'Eksternal' ? '#b45309' : '#1e293b' }}>
                                                Rapat Eksternal
                                            </div>
                                            <div style={{ fontSize: '11.5px', color: '#64748b', marginTop: '1px' }}>
                                                Stakeholder, perbankan, tamu luar
                                            </div>
                                        </div>
                                        {jenisKegiatan === 'Eksternal' && (
                                            <i className="bi bi-check-circle-fill" style={{ color: '#d97706', fontSize: '18px' }}></i>
                                        )}
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Section 2: Jadwal & Waktu */}
                        <div className="booking-card">
                            <div className="booking-card-header">
                                <div className="booking-card-icon" style={{ background: 'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)' }}>
                                    <i className="bi bi-clock-history"></i>
                                </div>
                                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', width: '100%', flexWrap: 'wrap', gap: '8px' }}>
                                    <div>
                                        <h2 className="booking-card-title">2. Jadwal & Waktu Pemakaian</h2>
                                        <p className="booking-card-subtitle">Waktu rapat menggunakan zona Waktu Indonesia Tengah (WITA)</p>
                                    </div>
                                    {duration && (
                                        <span
                                            style={{
                                                background: '#f0fdf4',
                                                border: '1px solid #bbf7d0',
                                                color: '#15803d',
                                                padding: '4px 12px',
                                                borderRadius: '20px',
                                                fontSize: '12px',
                                                fontWeight: 700,
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                gap: '5px',
                                            }}
                                        >
                                            <i className="bi bi-hourglass-split"></i> Durasi: {duration}
                                        </span>
                                    )}
                                </div>
                            </div>

                            {/* Ketentuan Jam Operasional & Hari Kerja */}
                            <div
                                style={{
                                    marginBottom: '16px',
                                    padding: '12px 16px',
                                    borderRadius: '10px',
                                    background: '#eff6ff',
                                    border: '1.5px solid #bfdbfe',
                                    display: 'flex',
                                    alignItems: 'flex-start',
                                    gap: '12px',
                                }}
                            >
                                <div
                                    style={{
                                        width: '28px',
                                        height: '28px',
                                        borderRadius: '8px',
                                        background: '#005baa',
                                        color: '#ffffff',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        flexShrink: 0,
                                        marginTop: '1px',
                                        fontSize: '14px',
                                    }}
                                >
                                    <i className="bi bi-info-lg"></i>
                                </div>
                                <div style={{ fontSize: '12.5px', color: '#1e3a8a', lineHeight: 1.5 }}>
                                    <strong style={{ color: '#003b73' }}>Ketentuan Hari Kerja &amp; Jam Rapat:</strong>
                                    <ul style={{ margin: '4px 0 0 16px', padding: 0 }}>
                                        <li>Pemesanan ruangan hanya dapat dilakukan pada hari kerja (<strong>Senin – Jumat</strong>).</li>
                                        <li>Jam operasional rapat adalah pukul <strong>08:00 – 19:00 WITA</strong>.</li>
                                        <li>Hari <strong>Sabtu, Minggu</strong>, dan <strong>Hari Libur Nasional / Cuti Bersama</strong> tidak dapat dipilih untuk rapat.</li>
                                    </ul>
                                </div>
                            </div>

                            {dateWarning && (
                                <div
                                    style={{
                                        marginBottom: '16px',
                                        padding: '12px 16px',
                                        borderRadius: '10px',
                                        background: '#fef2f2',
                                        border: '1.5px solid #fecaca',
                                        color: '#b91c1c',
                                        fontSize: '13px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        gap: '10px',
                                        fontWeight: 600,
                                    }}
                                >
                                    <i className="bi bi-exclamation-octagon-fill" style={{ color: '#ef4444', fontSize: '18px', flexShrink: 0 }}></i>
                                    <span>{dateWarning}</span>
                                </div>
                            )}

                            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))', gap: '16px', marginBottom: '16px' }}>
                                <div className="form-field">
                                    <label className="form-label">
                                        Tanggal Kegiatan <span className="req">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        className="input-modern tabular-nums"
                                        value={tanggal}
                                        min={getTodayDateString()}
                                        onChange={(e) => handleDateChange(e.target.value)}
                                        required
                                    />
                                    {tanggal && getHolidayInfo(tanggal) && (
                                        <small style={{ color: '#dc2626', fontWeight: 600, marginTop: '4px', display: 'block' }}>
                                            ⚠️ Libur: {getHolidayInfo(tanggal)?.keterangan}
                                        </small>
                                    )}
                                </div>

                                <div className="form-field">
                                    <label className="form-label">
                                        Waktu Mulai <span className="req">*</span>
                                    </label>
                                    <select
                                        className="input-modern tabular-nums"
                                        value={waktuMulai}
                                        onChange={(e) => {
                                            const val = e.target.value;
                                            setWaktuMulai(val);
                                            if (!waktuSelesai || waktuSelesai <= val) {
                                                const [h, m] = val.split(':').map(Number);
                                                const nextH = Math.min(19, h + 1);
                                                const nextHStr = String(nextH).padStart(2, '0');
                                                const cand = `${nextHStr}:${String(m).padStart(2, '0')}`;
                                                setWaktuSelesai(cand > '19:00' ? '19:00' : cand);
                                            }
                                        }}
                                        required
                                    >
                                        <option value="">-- Jam Mulai --</option>
                                        {timeSlots.map((slot) => {
                                            const passed = isSlotPassed(slot);
                                            return (
                                                <option
                                                    key={slot}
                                                    value={slot}
                                                    disabled={passed}
                                                    style={passed ? { color: '#94a3b8', background: '#f8fafc', fontStyle: 'italic' } : {}}
                                                >
                                                    {slot} WITA {passed ? '🔒 (Sudah Lewat)' : ''}
                                                </option>
                                            );
                                        })}
                                    </select>
                                </div>

                                <div className="form-field">
                                    <label className="form-label">
                                        Waktu Selesai <span className="req">*</span>
                                    </label>
                                    <select
                                        className="input-modern tabular-nums"
                                        value={waktuSelesai}
                                        onChange={(e) => setWaktuSelesai(e.target.value)}
                                        required
                                    >
                                        <option value="">-- Jam Selesai --</option>
                                        {endSlots
                                            .filter((s) => !waktuMulai || s > waktuMulai)
                                            .map((slot) => {
                                                const passed = isSlotPassed(slot);
                                                return (
                                                    <option
                                                        key={slot}
                                                        value={slot}
                                                        disabled={passed}
                                                        style={passed ? { color: '#94a3b8', background: '#f8fafc', fontStyle: 'italic' } : {}}
                                                    >
                                                        {slot} WITA {passed ? '🔒 (Sudah Lewat)' : ''}
                                                    </option>
                                                );
                                            })}
                                    </select>
                                </div>
                            </div>

                            {tanggal === getTodayDateString() && (
                                <div
                                    style={{
                                        marginBottom: '16px',
                                        padding: '10px 14px',
                                        borderRadius: '8px',
                                        background: '#fffbeb',
                                        border: '1px solid #fef3c7',
                                        color: '#b45309',
                                        fontSize: '12px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        gap: '8px',
                                    }}
                                >
                                    <span style={{ fontSize: '14px' }}>ℹ️</span>
                                    <span>
                                        <strong>Informasi:</strong> Jam yang sudah terlewat untuk hari ini otomatis dikunci 🔒 dan tidak dapat dipilih.
                                    </span>
                                </div>
                            )}

                            {/* Conflict Check Status Box */}
                            {isCheckingConflict && (
                                <div
                                    style={{
                                        padding: '12px 16px',
                                        borderRadius: '10px',
                                        background: '#f8fafc',
                                        border: '1px solid #e2e8f0',
                                        color: '#64748b',
                                        fontSize: '13px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        gap: '8px',
                                    }}
                                >
                                    <span
                                        style={{
                                            width: '14px',
                                            height: '14px',
                                            border: '2px solid #005baa',
                                            borderTopColor: 'transparent',
                                            borderRadius: '50%',
                                            display: 'inline-block',
                                            animation: 'spin 0.6s linear infinite',
                                        }}
                                    />
                                    <span>Memeriksa ketersediaan jadwal ruangan secara real-time...</span>
                                </div>
                            )}

                            {conflictResult && !isCheckingConflict && (
                                <div
                                    style={{
                                        padding: '14px 18px',
                                        borderRadius: '12px',
                                        background: conflictResult.conflict ? '#fef2f2' : '#f0fdf4',
                                        border: `1px solid ${conflictResult.conflict ? '#fecdd3' : '#bbf7d0'}`,
                                        color: conflictResult.conflict ? '#991b1b' : '#166534',
                                    }}
                                >
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '10px', fontWeight: 700, fontSize: '13.5px' }}>
                                        <i
                                            className={`bi ${
                                                conflictResult.conflict ? 'bi-exclamation-octagon-fill' : 'bi-check-circle-fill'
                                            }`}
                                            style={{ fontSize: '18px', color: conflictResult.conflict ? '#dc2626' : '#16a34a' }}
                                        ></i>
                                        <span>{conflictResult.message}</span>
                                    </div>
                                    {conflictResult.conflict && (
                                        <ul style={{ marginTop: '10px', paddingLeft: '22px', fontSize: '12.5px', lineHeight: 1.6 }}>
                                            {conflictResult.conflicts.map((c) => (
                                                <li key={c.id}>
                                                    <strong>{c.judul_kegiatan}</strong> ({c.unit}) &mdash;{' '}
                                                    <span className="tabular-nums">{c.waktu_mulai} s.d. {c.waktu_selesai} WITA</span> [{c.status}]
                                                </li>
                                            ))}
                                        </ul>
                                    )}
                                </div>
                            )}
                        </div>

                        {/* Section 3: PIC & Peserta */}
                        <div className="booking-card">
                            <div className="booking-card-header">
                                <div className="booking-card-icon" style={{ background: 'linear-gradient(135deg, #4f46e5 0%, #3730a3 100%)' }}>
                                    <i className="bi bi-person-badge-fill"></i>
                                </div>
                                <div>
                                    <h2 className="booking-card-title">3. Penanggung Jawab (PIC) & Estimasi Peserta</h2>
                                    <p className="booking-card-subtitle">Informasi kontak pemohon untuk koordinasi teknis ruangan</p>
                                </div>
                            </div>

                            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '16px' }}>
                                {/* Kode Unit: Dropdown untuk Admin, Terkunci untuk User */}
                                <div className="form-field">
                                    <label className="form-label" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                                        <span>Kode Unit <span className="req">*</span></span>
                                        {isAdminMode ? (
                                            <span style={{ fontSize: '11px', color: '#16a34a', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '4px' }}>
                                                <i className="bi bi-unlock-fill"></i> Pilihan Unit
                                            </span>
                                        ) : (
                                            <span style={{ fontSize: '11px', color: '#005baa', fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: '3px' }}>
                                                <i className="bi bi-lock-fill"></i> Terkunci
                                            </span>
                                        )}
                                    </label>
                                    {isAdminMode ? (
                                        <div style={{ position: 'relative' }}>
                                            <select
                                                className="input-modern tabular-nums"
                                                value={selectedUnitId}
                                                onChange={(e) => {
                                                    const uId = e.target.value;
                                                    setSelectedUnitId(uId);
                                                    const found = unitList.find((u) => String(u.id) === String(uId));
                                                    if (found) {
                                                        setSelectedKodeUnit(found.kode_unit);
                                                        setSelectedNamaUnit(found.nama_unit);
                                                    } else {
                                                        setSelectedKodeUnit('');
                                                        setSelectedNamaUnit('');
                                                    }
                                                }}
                                                required
                                                style={{
                                                    backgroundColor: '#ffffff',
                                                    color: '#0f172a',
                                                    fontWeight: 600,
                                                    border: '1.5px solid #005baa',
                                                }}
                                            >
                                                <option value="">-- Pilih Kode Unit --</option>
                                                {unitList.map((u) => (
                                                    <option key={u.id} value={u.id}>
                                                        {u.kode_unit} &mdash; {u.nama_unit}
                                                    </option>
                                                ))}
                                            </select>
                                        </div>
                                    ) : (
                                        <div style={{ position: 'relative' }}>
                                            <input
                                                type="text"
                                                className="input-modern tabular-nums"
                                                value={userKodeUnit}
                                                readOnly
                                                disabled
                                                style={{
                                                    backgroundColor: '#f1f5f9',
                                                    color: '#0f172a',
                                                    fontWeight: 800,
                                                    letterSpacing: '1px',
                                                    cursor: 'not-allowed',
                                                    paddingLeft: '36px',
                                                    border: '1.5px solid #cbd5e1',
                                                }}
                                                title="Kode unit kerja diambil otomatis dari akun login Anda dan terkunci (tidak dapat diubah)."
                                            />
                                            <i
                                                className="bi bi-shield-lock-fill"
                                                style={{
                                                    position: 'absolute',
                                                    left: '12px',
                                                    top: '50%',
                                                    transform: 'translateY(-50%)',
                                                    color: '#005baa',
                                                    fontSize: '15px',
                                                }}
                                            />
                                        </div>
                                    )}
                                    {(isAdminMode ? selectedNamaUnit : userNamaUnit) && (
                                        <small style={{ color: '#64748b', fontSize: '11px', marginTop: '4px', display: 'block', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }} title={isAdminMode ? selectedNamaUnit : userNamaUnit}>
                                            Unit: <strong style={{ color: '#005baa' }}>{isAdminMode ? selectedNamaUnit : userNamaUnit}</strong>
                                        </small>
                                    )}
                                </div>

                                <div className="form-field">
                                    <label className="form-label">
                                        Nama PIC Kegiatan <span className="req">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className="input-modern"
                                        value={picKegiatan}
                                        onChange={(e) => setPicKegiatan(e.target.value)}
                                        placeholder="Nama lengkap penanggung jawab"
                                        required
                                    />
                                </div>

                                <div className="form-field">
                                    <label className="form-label">
                                        Status Kepegawaian PIC <span className="req">*</span>
                                    </label>
                                    <select
                                        className="input-modern"
                                        value={jenisPic}
                                        onChange={(e) => setJenisPic(e.target.value as any)}
                                        required
                                    >
                                        <option value="Organik">Organik (Pegawai BI)</option>
                                        <option value="Non Organik">Non Organik (Mitra / Eksternal)</option>
                                    </select>
                                </div>

                                <div className="form-field">
                                    <label className="form-label">
                                        No. WhatsApp PIC
                                        <span style={{ fontSize: '11px', color: '#94a3b8', fontWeight: 400 }}>Untuk konfirmasi</span>
                                    </label>
                                    <input
                                        type="tel"
                                        className="input-modern tabular-nums"
                                        value={noWaPic}
                                        onChange={(e) => setNoWaPic(e.target.value)}
                                        placeholder="0812xxxxxxxx"
                                    />
                                </div>
                            </div>

                            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px', marginBottom: '16px' }}>
                                <div className="form-field">
                                    <label className="form-label">
                                        Estimasi Jumlah Tamu <span className="req">*</span>
                                        {selectedRoom && (
                                            <span style={{ fontSize: '11.5px', color: '#64748b' }}>
                                                Maks: {selectedRoom.kapasitas} orang
                                            </span>
                                        )}
                                    </label>
                                    <input
                                        type="number"
                                        min={1}
                                        className="input-modern tabular-nums"
                                        value={jumlahTamu}
                                        onChange={(e) => setJumlahTamu(e.target.value)}
                                        placeholder="Jumlah peserta rapat"
                                        required
                                    />
                                </div>
                            </div>

                            {/* Capacity Visual Progress */}
                            {selectedRoom && jumlahTamu && (
                                <div style={{ background: '#f8fafc', padding: '12px 16px', borderRadius: '10px', border: '1px solid #e2e8f0', marginTop: '4px' }}>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '12.5px', fontWeight: 600 }}>
                                        <span style={{ color: '#475569' }}>Tingkat Okupansi Ruangan:</span>
                                        <span style={{ color: isOverCapacity ? '#dc2626' : '#059669' }}>
                                            {jumlahTamu} / {selectedRoom.kapasitas} Orang ({capacityPercentage}%)
                                        </span>
                                    </div>
                                    <div className="capacity-meter-bg">
                                        <div
                                            className="capacity-meter-fill"
                                            style={{
                                                width: `${capacityPercentage}%`,
                                                background: isOverCapacity ? '#dc2626' : capacityPercentage > 85 ? '#eab308' : '#10b981',
                                            }}
                                        />
                                    </div>
                                    {isOverCapacity && (
                                        <p style={{ margin: '4px 0 0 0', color: '#dc2626', fontSize: '11.5px', display: 'flex', alignItems: 'center', gap: '4px' }}>
                                            <i className="bi bi-exclamation-triangle-fill"></i> Peringatan: Jumlah tamu melebihi kapasitas standar ruangan ({selectedRoom.kapasitas} orang).
                                        </p>
                                    )}
                                </div>
                            )}
                        </div>

                        {/* Section 4: Lampiran Dokumen & Catatan */}
                        <div className="booking-card">
                            <div className="booking-card-header">
                                <div className="booking-card-icon" style={{ background: 'linear-gradient(135deg, #ea580c 0%, #c2410c 100%)' }}>
                                    <i className="bi bi-paperclip"></i>
                                </div>
                                <div>
                                    <h2 className="booking-card-title">4. Dokumen Disposisi & Kebutuhan Khusus</h2>
                                    <p className="booking-card-subtitle">Surat tugas/nota dinas dan catatan fasilitas pendukung</p>
                                </div>
                            </div>

                            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))', gap: '20px' }}>
                                <div className="form-field">
                                    <label className="form-label">
                                        <span>
                                            Upload Dokumen / Surat Disposisi {!isAdminMode && <span className="req">*</span>}
                                        </span>
                                        <span style={{ fontSize: '11.5px', color: !isAdminMode ? '#e11d48' : '#94a3b8', fontWeight: 600 }}>
                                            {!isAdminMode ? 'Wajib Diunggah (Maks 5MB)' : 'Opsional untuk Admin (Maks 5MB)'}
                                        </span>
                                    </label>
                                    <div className="file-upload-box" style={{ borderColor: !isAdminMode && !fileDisposisi ? '#fda4af' : undefined }}>
                                        <input
                                            type="file"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            className="file-upload-input"
                                            onChange={(e) => setFileDisposisi(e.target.files?.[0] || null)}
                                            required={!isAdminMode}
                                        />
                                        {fileDisposisi ? (
                                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '10px' }}>
                                                <i className="bi bi-file-earmark-check-fill" style={{ fontSize: '24px', color: '#059669' }}></i>
                                                <div style={{ textAlign: 'left' }}>
                                                    <div style={{ fontSize: '13px', fontWeight: 700, color: '#1e293b' }}>
                                                        {fileDisposisi.name}
                                                    </div>
                                                    <div style={{ fontSize: '11px', color: '#64748b' }}>
                                                        {(fileDisposisi.size / 1024).toFixed(1)} KB &bull; Klik untuk ganti
                                                    </div>
                                                </div>
                                            </div>
                                        ) : (
                                            <div>
                                                <i className="bi bi-cloud-arrow-up-fill" style={{ fontSize: '28px', color: '#005baa', display: 'block', marginBottom: '6px' }}></i>
                                                <span style={{ fontSize: '13px', fontWeight: 600, color: '#1e293b' }}>
                                                    Tarik berkas ke sini atau <span style={{ color: '#005baa', textDecoration: 'underline' }}>Pilih Berkas</span>
                                                </span>
                                                <p style={{ margin: '4px 0 0 0', fontSize: '11.5px', color: '#94a3b8' }}>
                                                    Format: PDF, JPG, PNG (Maksimal 5MB)
                                                </p>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="form-field">
                                    <label className="form-label">
                                        Catatan Tambahan & Kebutuhan Khusus
                                        <span style={{ fontSize: '11px', color: '#94a3b8', fontWeight: 400 }}>Opsional</span>
                                    </label>
                                    <textarea
                                        className="input-modern"
                                        rows={4}
                                        value={catatanUser}
                                        onChange={(e) => setCatatanUser(e.target.value)}
                                        placeholder="Tuliskan jika membutuhkan fasilitas khusus (misal: ID Zoom Meeting, penataan mic delegasi, konsumsi snack/makan siang, dll.)"
                                        style={{ resize: 'vertical' }}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* RIGHT COLUMN: Sticky Summary & Room Preview */}
                    <div className="booking-summary-sticky">
                        <div className="summary-card">
                            <div className="summary-header">
                                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '4px' }}>
                                    <span style={{ fontSize: '11px', textTransform: 'uppercase', letterSpacing: '0.08em', opacity: 0.85, fontWeight: 700 }}>
                                        Preview Pemesanan
                                    </span>
                                    <span style={{ fontSize: '11px', background: 'rgba(255,255,255,0.2)', padding: '2px 8px', borderRadius: '12px' }}>
                                        {isAdminMode ? 'Mode Admin' : 'Permohonan'}
                                    </span>
                                </div>
                                <h3 style={{ margin: '0', fontSize: '18px', fontWeight: 800, color: '#ffffff' }}>
                                    {selectedRoom ? selectedRoom.nama_ruangan : 'Pilih Ruangan Rapat'}
                                </h3>
                                <p style={{ margin: '4px 0 0 0', fontSize: '12.5px', opacity: 0.9 }}>
                                    {selectedRoom ? (
                                        <span>
                                            <i className="bi bi-geo-alt-fill" style={{ color: '#fde047', marginRight: '4px' }}></i>
                                            {selectedRoom.lokasi}
                                        </span>
                                    ) : (
                                        'Pilih ruangan pada form sebelah kiri'
                                    )}
                                </p>
                            </div>

                            <div className="summary-body">
                                {selectedRoom ? (
                                    <>


                                        {selectedLayout && (
                                            <div style={{ padding: '10px 14px', borderRadius: '10px', background: '#f8fafc', border: '1px solid #e2e8f0', marginBottom: '16px' }}>
                                                <div style={{ fontSize: '11px', color: '#64748b', fontWeight: 600 }}>FORMAT LAYOUT</div>
                                                <div style={{ fontSize: '13.5px', fontWeight: 700, color: '#1e293b' }}>
                                                    <i className="bi bi-grid-3x3-gap-fill" style={{ color: '#005baa', marginRight: '6px' }}></i>
                                                    {selectedLayout.nama_layout}
                                                </div>
                                            </div>
                                        )}

                                        {/* Booking breakdown */}
                                        <div style={{ borderTop: '1px solid #f1f5f9', paddingTop: '10px', marginBottom: '20px' }}>
                                            <div className="summary-item">
                                                <span className="summary-label">Unit Pemohon</span>
                                                <span className="summary-val">
                                                    <strong style={{ color: '#005baa' }}>
                                                        {isAdminMode ? (selectedKodeUnit ? `${selectedKodeUnit} - ${selectedNamaUnit}` : '-') : (userKodeUnit ? `${userKodeUnit} - ${userNamaUnit}` : '-')}
                                                    </strong>
                                                </span>
                                            </div>

                                            <div className="summary-item">
                                                <span className="summary-label">Tanggal</span>
                                                <span className="summary-val tabular-nums">
                                                    {tanggal
                                                        ? new Date(tanggal).toLocaleDateString('id-ID', {
                                                              weekday: 'short',
                                                              day: 'numeric',
                                                              month: 'short',
                                                              year: 'numeric',
                                                          })
                                                        : '-'}
                                                </span>
                                            </div>

                                            <div className="summary-item">
                                                <span className="summary-label">Waktu Rapat</span>
                                                <span className="summary-val tabular-nums">
                                                    {waktuMulai ? `${waktuMulai} WITA` : '-'} s.d.{' '}
                                                    {waktuSelesai ? `${waktuSelesai} WITA` : '-'}
                                                </span>
                                            </div>

                                            <div className="summary-item">
                                                <span className="summary-label">Durasi Acara</span>
                                                <span className="summary-val" style={{ color: duration ? '#005baa' : '#64748b' }}>
                                                    {duration || '-'}
                                                </span>
                                            </div>

                                            <div className="summary-item">
                                                <span className="summary-label">Sifat Rapat</span>
                                                <span className="summary-val">
                                                    <span
                                                        style={{
                                                            display: 'inline-flex',
                                                            alignItems: 'center',
                                                            gap: '5px',
                                                            padding: '2px 8px',
                                                            borderRadius: '8px',
                                                            fontSize: '11.5px',
                                                            fontWeight: 700,
                                                            background: jenisKegiatan === 'Eksternal' ? '#fef3c7' : '#e0f2fe',
                                                            color: jenisKegiatan === 'Eksternal' ? '#92400e' : '#0369a1',
                                                        }}
                                                    >
                                                        <i className={`bi ${jenisKegiatan === 'Eksternal' ? 'bi-globe2' : 'bi-building'}`}></i>
                                                        {jenisKegiatan === 'Eksternal' ? 'Eksternal' : 'Internal'}
                                                    </span>
                                                </span>
                                            </div>

                                            <div className="summary-item">
                                                <span className="summary-label">Estimasi Tamu</span>
                                                <span className="summary-val tabular-nums">
                                                    {jumlahTamu ? `${jumlahTamu} orang` : '-'}
                                                </span>
                                            </div>

                                            <div className="summary-item">
                                                <span className="summary-label">Status Jadwal</span>
                                                <span className="summary-val">
                                                    {conflictResult ? (
                                                        conflictResult.conflict ? (
                                                            <span style={{ color: '#dc2626' }}>
                                                                <i className="bi bi-x-circle-fill"></i> Bentrok
                                                            </span>
                                                        ) : (
                                                            <span style={{ color: '#059669' }}>
                                                                <i className="bi bi-check-circle-fill"></i> Tersedia
                                                            </span>
                                                        )
                                                    ) : (
                                                        <span style={{ color: '#94a3b8' }}>Menunggu input jam</span>
                                                    )}
                                                </span>
                                            </div>
                                        </div>
                                    </>
                                ) : (
                                    <div style={{ textAlign: 'center', padding: '24px 10px', color: '#94a3b8' }}>
                                        <i className="bi bi-building" style={{ fontSize: '36px', display: 'block', marginBottom: '8px', color: '#cbd5e1' }}></i>
                                        <p style={{ fontSize: '13px', margin: 0 }}>
                                            Pilih ruangan di sebelah kiri untuk melihat ringkasan kapasitas dan fasilitas.
                                        </p>
                                    </div>
                                )}

                                {/* Action Buttons */}
                                <button
                                    type="submit"
                                    className="btn-primary"
                                    style={{
                                        width: '100%',
                                        padding: '13px 18px',
                                        borderRadius: '12px',
                                        fontSize: '14.5px',
                                        fontWeight: 700,
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        gap: '10px',
                                        boxShadow: '0 6px 18px rgba(0, 91, 170, 0.3)',
                                        border: 'none',
                                        cursor: isSubmitting || !!conflictResult?.conflict ? 'not-allowed' : 'pointer',
                                    }}
                                    disabled={isSubmitting || !!conflictResult?.conflict}
                                >
                                    {isSubmitting ? (
                                        <>
                                            <span
                                                style={{
                                                    display: 'inline-block',
                                                    width: '18px',
                                                    height: '18px',
                                                    border: '2px solid rgba(255,255,255,0.4)',
                                                    borderTopColor: '#fff',
                                                    borderRadius: '50%',
                                                    animation: 'spin 0.6s linear infinite',
                                                }}
                                            />
                                            Memproses Pemesanan...
                                        </>
                                    ) : (
                                        <>
                                            <i className={`bi ${isAdminMode ? 'bi-calendar-check-fill' : 'bi-send-fill'}`}></i>
                                            {isAdminMode ? 'Jadwalkan Langsung' : 'Ajukan Pemesanan Ruangan'}
                                        </>
                                    )}
                                </button>

                                <div style={{ marginTop: '14px', textAlign: 'center', fontSize: '11.5px', color: '#64748b' }}>
                                    <i className="bi bi-shield-check" style={{ color: '#059669', marginRight: '4px' }}></i>
                                    KPwBI Sulawesi Utara &bull; Layanan Kantor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    );
};
