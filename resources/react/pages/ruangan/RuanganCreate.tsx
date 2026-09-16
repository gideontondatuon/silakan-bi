import React, { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { LayoutRuangan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const RuanganCreate: React.FC = () => {
    const navigate = useNavigate();
    const [layouts, setLayouts] = useState<LayoutRuangan[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    const [formData, setFormData] = useState({
        nama_ruangan: '',
        lokasi: '',
        kapasitas: '',
        status: 'aktif' as 'aktif' | 'nonaktif' | 'pemeliharaan',
    });
    const [selectedLayouts, setSelectedLayouts] = useState<number[]>([]);
    const [errors, setErrors] = useState<Record<string, string>>({});

    useEffect(() => {
        const fetchLayouts = async () => {
            try {
                const res = await adminService.getLayoutList();
                if (res.status === 'success') {
                    const data = Array.isArray(res.data) ? res.data : res.data.data;
                    setLayouts(data || []);
                }
            } catch {
                // Ignore error
            } finally {
                setIsLoading(false);
            }
        };

        fetchLayouts();
    }, []);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        setErrors({});

        try {
            const payload = {
                nama_ruangan: formData.nama_ruangan,
                lokasi: formData.lokasi,
                kapasitas: Number(formData.kapasitas),
                status: formData.status,
                layouts: selectedLayouts,
            };

            const res = await adminService.createRuangan(payload);
            if (res.status === 'success') {
                navigate('/admin/ruangan', {
                    state: { flashMessage: res.message || 'Ruangan berhasil ditambahkan.' },
                });
            }
        } catch (err: any) {
            if (err.response?.data?.errors) {
                const apiErrors: Record<string, string> = {};
                for (const key of Object.keys(err.response.data.errors)) {
                    apiErrors[key] = err.response.data.errors[key][0];
                }
                setErrors(apiErrors);
            } else {
                setAlertMessage({
                    type: 'error',
                    text: err.response?.data?.message || 'Gagal menyimpan ruangan.',
                });
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    if (isLoading) {
        return <LoadingSpinner message="Memuat form ruangan..." />;
    }

    return (
        <div>
            {/* Page Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-plus-circle" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Tambah Ruangan
                    </h1>
                    <p>Tambahkan data ruangan baru ke dalam sistem SILAKAN.</p>
                </div>
                <Link to="/admin/ruangan" className="btn-secondary">
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

            {/* Dashboard Section */}
            <div className="dashboard-section">
                <div className="section-header">
                    <h2>
                        <i className="bi bi-building"></i> Informasi Ruangan
                    </h2>
                </div>

                <div style={{ padding: '24px' }}>
                    <form onSubmit={handleSubmit}>
                        <div className="form-row">
                            <div className="form-group">
                                <label>
                                    Nama Ruangan <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nama_ruangan"
                                    value={formData.nama_ruangan}
                                    onChange={(e) => setFormData({ ...formData, nama_ruangan: e.target.value })}
                                    placeholder="Contoh: Ruang Rapat Utama"
                                    required
                                />
                                {errors.nama_ruangan && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.nama_ruangan}
                                    </span>
                                )}
                            </div>
                            <div className="form-group">
                                <label>
                                    Lokasi <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <input
                                    type="text"
                                    name="lokasi"
                                    value={formData.lokasi}
                                    onChange={(e) => setFormData({ ...formData, lokasi: e.target.value })}
                                    placeholder="Contoh: Lantai 3, Gedung A"
                                    required
                                />
                                {errors.lokasi && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.lokasi}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group">
                                <label>
                                    Kapasitas <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <input
                                    type="number"
                                    name="kapasitas"
                                    value={formData.kapasitas}
                                    onChange={(e) => setFormData({ ...formData, kapasitas: e.target.value })}
                                    min="1"
                                    placeholder="Jumlah orang"
                                    required
                                />
                                {errors.kapasitas && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.kapasitas}
                                    </span>
                                )}
                            </div>
                            <div className="form-group">
                                <label>
                                    Status Ruangan <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <select
                                    name="status"
                                    value={formData.status}
                                    onChange={(e) => setFormData({ ...formData, status: e.target.value as any })}
                                    required
                                >
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                    <option value="pemeliharaan">Pemeliharaan</option>
                                </select>
                                {errors.status && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.status}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="form-group">
                            <label>Layout Ruangan yang Dapat Diterapkan</label>
                            <p className="form-hint">
                                Centang layout yang berlaku untuk ruangan ini. Jika hanya 1 layout, pengguna tidak perlu memilih layout saat membuat pemesanan.
                            </p>
                            <div className="facility-list" style={{ marginTop: '10px' }}>
                                {layouts.map((layoutItem) => {
                                    const isChecked = selectedLayouts.includes(layoutItem.id);
                                    return (
                                        <label
                                            key={layoutItem.id}
                                            style={{
                                                cursor: 'pointer',
                                                userSelect: 'none',
                                                position: 'relative',
                                                borderColor: isChecked ? '#005baa' : undefined,
                                                background: isChecked ? '#f0f7ff' : undefined,
                                                color: isChecked ? '#003b73' : undefined,
                                                fontWeight: isChecked ? 700 : undefined,
                                            }}
                                        >
                                            <input
                                                type="checkbox"
                                                name="layouts[]"
                                                value={layoutItem.id}
                                                checked={isChecked}
                                                onChange={(e) => {
                                                    if (e.target.checked) {
                                                        setSelectedLayouts([...selectedLayouts, layoutItem.id]);
                                                    } else {
                                                        setSelectedLayouts(selectedLayouts.filter((id) => id !== layoutItem.id));
                                                    }
                                                }}
                                            />
                                            <span
                                                className="custom-check-badge"
                                                style={{
                                                    background: isChecked ? '#005baa' : '#ffffff',
                                                    borderColor: isChecked ? '#005baa' : '#cbd5e1',
                                                    color: isChecked ? '#ffffff' : 'transparent',
                                                    transform: isChecked ? 'scale(1.08)' : undefined,
                                                }}
                                            >
                                                <i className="bi bi-check-lg"></i>
                                            </span>
                                            <span>{layoutItem.nama_layout}</span>
                                        </label>
                                    );
                                })}
                            </div>
                        </div>

                        <div className="form-action">
                            <Link to="/admin/ruangan" className="btn-secondary">
                                <i className="bi bi-x"></i> Batal
                            </Link>
                            <button type="submit" className="btn-primary" disabled={isSubmitting}>
                                <i className="bi bi-check-lg"></i> {isSubmitting ? 'Menyimpan...' : 'Simpan Ruangan'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};
