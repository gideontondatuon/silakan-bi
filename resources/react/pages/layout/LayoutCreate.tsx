import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const LayoutCreate: React.FC = () => {
    const navigate = useNavigate();
    const [namaLayout, setNamaLayout] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
    const [errorNama, setErrorNama] = useState<string | null>(null);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        setErrorNama(null);

        try {
            const res = await adminService.createLayout({ nama_layout: namaLayout });
            if (res.status === 'success') {
                navigate('/admin/layout', {
                    state: { flashMessage: res.message || 'Layout berhasil ditambahkan.' },
                });
            }
        } catch (err: any) {
            if (err.response?.data?.errors?.nama_layout) {
                setErrorNama(err.response.data.errors.nama_layout[0]);
            } else {
                setAlertMessage({
                    type: 'error',
                    text: err.response?.data?.message || 'Gagal menyimpan layout.',
                });
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div>
            {/* Page Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-plus-circle" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Tambah Layout Ruangan
                    </h1>
                    <p>Tambahkan variasi layout baru pada sistem SILAKAN.</p>
                </div>
                <Link to="/admin/layout" className="btn-secondary">
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
            <div className="dashboard-section" style={{ maxWidth: '480px' }}>
                <div className="section-header">
                    <h2>
                        <i className="bi bi-layout-wtf"></i> Data Layout
                    </h2>
                </div>
                <div style={{ padding: '24px' }}>
                    <form onSubmit={handleSubmit}>
                        <div className="form-group">
                            <label>
                                Nama Layout <span style={{ color: '#ef4444' }}>*</span>
                            </label>
                            <input
                                type="text"
                                name="nama_layout"
                                value={namaLayout}
                                onChange={(e) => setNamaLayout(e.target.value)}
                                placeholder="Contoh: Theater, Classroom, U-Shape"
                                required
                                autoFocus
                            />
                            {errorNama && (
                                <span className="form-error">
                                    <i className="bi bi-exclamation-circle"></i> {errorNama}
                                </span>
                            )}
                        </div>
                        <div className="form-action">
                            <Link to="/admin/layout" className="btn-secondary">
                                <i className="bi bi-x"></i> Batal
                            </Link>
                            <button type="submit" className="btn-primary" disabled={isSubmitting}>
                                <i className="bi bi-check-lg"></i> {isSubmitting ? 'Menyimpan...' : 'Simpan'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};
