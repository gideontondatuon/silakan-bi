import React, { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { LayoutRuangan } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const LayoutEdit: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    const [layout, setLayout] = useState<LayoutRuangan | null>(null);
    const [namaLayout, setNamaLayout] = useState('');
    const [isLoading, setIsLoading] = useState(true);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
    const [errorNama, setErrorNama] = useState<string | null>(null);

    useEffect(() => {
        if (!id) return;

        const fetchData = async () => {
            setIsLoading(true);
            try {
                const res = await adminService.getLayoutDetail(id);
                if (res.status === 'success' && res.data) {
                    setLayout(res.data);
                    setNamaLayout(res.data.nama_layout || '');
                }
            } catch {
                setAlertMessage({ type: 'error', text: 'Gagal memuat data layout.' });
            } finally {
                setIsLoading(false);
            }
        };

        fetchData();
    }, [id]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!id) return;

        setIsSubmitting(true);
        setErrorNama(null);

        try {
            const res = await adminService.updateLayout(id, { nama_layout: namaLayout });
            if (res.status === 'success') {
                navigate('/admin/layout', {
                    state: { flashMessage: res.message || 'Layout berhasil diperbarui.' },
                });
            }
        } catch (err: any) {
            if (err.response?.data?.errors?.nama_layout) {
                setErrorNama(err.response.data.errors.nama_layout[0]);
            } else {
                setAlertMessage({
                    type: 'error',
                    text: err.response?.data?.message || 'Gagal memperbarui layout.',
                });
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    if (isLoading) {
        return <LoadingSpinner message="Memuat data layout..." />;
    }

    if (!layout) {
        return (
            <div className="dashboard-section" style={{ maxWidth: '480px' }}>
                <div className="empty-state">
                    <i className="bi bi-exclamation-triangle"></i>
                    <p>Data layout tidak ditemukan.</p>
                    <Link to="/admin/layout" className="btn-secondary">
                        Kembali ke Data Layout
                    </Link>
                </div>
            </div>
        );
    }

    return (
        <div>
            {/* Page Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-pencil-square" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Edit Layout Ruangan
                    </h1>
                    <p>
                        Perbarui nama layout <strong>{layout.nama_layout}</strong>.
                    </p>
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
                                required
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
                                <i className="bi bi-check-lg"></i> {isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};
