import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const UserCreate: React.FC = () => {
    const navigate = useNavigate();

    const [formData, setFormData] = useState({
        username: '',
        name: '',
        email: '',
        no_wa: '',
        password: '',
        nama_unit: '',
        kode_unit: '',
        role: 'user' as 'user' | 'admin',
    });

    const [showPassword, setShowPassword] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        setErrors({});

        try {
            const res = await adminService.createUser(formData);
            if (res.status === 'success') {
                navigate('/admin/users', {
                    state: { flashMessage: res.message || 'User baru berhasil dibuat.' },
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
                    text: err.response?.data?.message || 'Gagal menyimpan user baru.',
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
                        <i className="bi bi-person-plus" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Tambah User
                    </h1>
                    <p>Tambahkan pengguna baru ke dalam sistem SILAKAN.</p>
                </div>
                <Link to="/admin/users" className="btn-secondary">
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
                        <i className="bi bi-person"></i> Informasi Akun
                    </h2>
                </div>

                <div style={{ padding: '24px' }}>
                    <form onSubmit={handleSubmit}>
                        <div className="form-row">
                            <div className="form-group">
                                <label>
                                    Username <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <input
                                    type="text"
                                    name="username"
                                    value={formData.username}
                                    onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                                    placeholder="Masukkan username"
                                    required
                                    autoFocus
                                />
                                {errors.username && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.username}
                                    </span>
                                )}
                            </div>
                            <div className="form-group">
                                <label>Nama Lengkap</label>
                                <input
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                    placeholder="Masukkan nama lengkap"
                                />
                                {errors.name && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.name}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group">
                                <label>Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                                    placeholder="email@example.com"
                                />
                                {errors.email && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.email}
                                    </span>
                                )}
                            </div>
                            <div className="form-group">
                                <label>
                                    <i className="bi bi-whatsapp" style={{ color: '#25d366', marginRight: '4px' }}></i>{' '}
                                    No. WhatsApp
                                </label>
                                <input
                                    type="text"
                                    name="no_wa"
                                    value={formData.no_wa}
                                    onChange={(e) => setFormData({ ...formData, no_wa: e.target.value })}
                                    placeholder="Contoh: 081234567890"
                                />
                                {errors.no_wa && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.no_wa}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group">
                                <label>
                                    Password <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <div className="password-wrapper" style={{ position: 'relative' }}>
                                    <input
                                        type={showPassword ? 'text' : 'password'}
                                        name="password"
                                        value={formData.password}
                                        onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                                        placeholder="Buat password (minimal 8 karakter)"
                                        required
                                    />
                                    <button
                                        type="button"
                                        className="toggle-password"
                                        onClick={() => setShowPassword(!showPassword)}
                                        style={{
                                            position: 'absolute',
                                            right: '12px',
                                            top: '50%',
                                            transform: 'translateY(-50%)',
                                            background: 'none',
                                            border: 'none',
                                            color: '#64748b',
                                            cursor: 'pointer',
                                        }}
                                    >
                                        <i className={`bi ${showPassword ? 'bi-eye-slash' : 'bi-eye'}`}></i>
                                    </button>
                                </div>
                                {errors.password && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.password}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group">
                                <label>
                                    Nama Unit <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nama_unit"
                                    value={formData.nama_unit}
                                    onChange={(e) => setFormData({ ...formData, nama_unit: e.target.value })}
                                    placeholder="Contoh: Departemen Keuangan"
                                    required
                                />
                                {errors.nama_unit && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.nama_unit}
                                    </span>
                                )}
                            </div>
                            <div className="form-group">
                                <label>
                                    Kode Unit <span style={{ color: '#ef4444' }}>*</span>
                                </label>
                                <input
                                    type="text"
                                    name="kode_unit"
                                    value={formData.kode_unit}
                                    onChange={(e) => setFormData({ ...formData, kode_unit: e.target.value })}
                                    placeholder="Contoh: KEUANGAN"
                                    required
                                />
                                {errors.kode_unit && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.kode_unit}
                                    </span>
                                )}
                            </div>
                        </div>

                        <div className="form-group" style={{ maxWidth: '320px' }}>
                            <label>
                                Role / Hak Akses <span style={{ color: '#ef4444' }}>*</span>
                            </label>
                            <select
                                name="role"
                                value={formData.role}
                                onChange={(e) => setFormData({ ...formData, role: e.target.value as any })}
                                required
                            >
                                <option value="user">User (Pengguna Biasa)</option>
                                <option value="admin">Admin (Administrator)</option>
                            </select>
                            {errors.role && (
                                <span className="form-error">
                                    <i className="bi bi-exclamation-circle"></i> {errors.role}
                                </span>
                            )}
                        </div>

                        <div className="form-action">
                            <Link to="/admin/users" className="btn-secondary">
                                <i className="bi bi-x"></i> Batal
                            </Link>
                            <button type="submit" className="btn-primary" disabled={isSubmitting}>
                                <i className="bi bi-check-lg"></i>{' '}
                                {isSubmitting ? 'Menyimpan...' : 'Simpan User'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};
