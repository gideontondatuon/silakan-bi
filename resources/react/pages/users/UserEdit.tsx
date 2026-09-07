import React, { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { adminService } from '../../services/adminService';
import { User } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const UserEdit: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    const [user, setUser] = useState<User | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    const [formData, setFormData] = useState({
        username: '',
        name: '',
        password: '',
        nama_unit: '',
        kode_unit: '',
        role: 'user' as 'user' | 'admin',
    });

    const [showCurrentPass, setShowCurrentPass] = useState(false);
    const [showNewPass, setShowNewPass] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});

    useEffect(() => {
        if (!id) return;

        const fetchUser = async () => {
            setIsLoading(true);
            try {
                const res = await adminService.getUserDetail(id);
                if (res.status === 'success' && res.data) {
                    const u = res.data;
                    setUser(u);
                    setFormData({
                        username: u.username || '',
                        name: u.name || '',
                        password: '',
                        nama_unit: u.nama_unit || '',
                        kode_unit: u.kode_unit || '',
                        role: (typeof u.role === 'object' && u.role ? (u.role as any).value : u.role) || 'user',
                    });
                }
            } catch {
                setAlertMessage({ type: 'error', text: 'Gagal memuat data user.' });
            } finally {
                setIsLoading(false);
            }
        };

        fetchUser();
    }, [id]);

    const roleVal = user ? (typeof user.role === 'object' && user.role ? (user.role as any).value : user.role) : 'user';
    const isAdminAccount = roleVal === 'admin';
    const defaultPass = user?.username === 'admin' || isAdminAccount ? 'password' : 'kpwbisulut';
    const currentPlain = (user as any)?.password_plain || defaultPass;

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!id) return;

        setIsSubmitting(true);
        setErrors({});

        try {
            const payload: any = {
                username: formData.username,
                name: formData.name,
                nama_unit: formData.nama_unit,
                kode_unit: formData.kode_unit,
                role: isAdminAccount ? 'admin' : formData.role,
            };

            if (formData.password) {
                payload.password = formData.password;
            }

            const res = await adminService.updateUser(id, payload);
            if (res.status === 'success') {
                navigate('/admin/users', {
                    state: { flashMessage: res.message || 'User berhasil diperbarui.' },
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
                    text: err.response?.data?.message || 'Gagal memperbarui user.',
                });
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    if (isLoading) {
        return <LoadingSpinner message="Memuat data user..." />;
    }

    if (!user) {
        return (
            <div className="dashboard-section">
                <div className="empty-state">
                    <i className="bi bi-exclamation-triangle"></i>
                    <p>Data user tidak ditemukan.</p>
                    <Link to="/admin/users" className="btn-secondary">
                        Kembali ke Data User
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
                        <i className="bi bi-person-gear" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Edit User
                    </h1>
                    <p>
                        Perbarui informasi akun pengguna <strong>{user.name || user.username}</strong>.
                    </p>
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
                                    required
                                />
                                {errors.username && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.username}
                                    </span>
                                )}
                            </div>
                            <div className="form-group">
                                <label>
                                    Nama Lengkap / Administrator{' '}
                                    {isAdminAccount && (
                                        <span style={{ color: '#005baa', fontSize: '11px' }}>
                                            (Dapat Anda sesuaikan)
                                        </span>
                                    )}
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                    placeholder="Masukkan Nama Pengguna / Administrator"
                                />
                                {errors.name && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.name}
                                    </span>
                                )}
                            </div>
                        </div>

                        {/* Password Akun Saat Ini */}
                        <div className="form-row">
                            <div className="form-group">
                                <label style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                                    <i className="bi bi-key-fill" style={{ color: '#005baa' }}></i> Password Akun Saat Ini
                                </label>
                                <div
                                    style={{
                                        display: 'flex',
                                        alignItems: 'center',
                                        gap: '10px',
                                        background: '#f8fafc',
                                        border: '1px solid #cbd5e1',
                                        borderRadius: '10px',
                                        padding: '10px 14px',
                                        maxWidth: '400px',
                                    }}
                                >
                                    <span
                                        style={{
                                            fontFamily: 'Consolas, monospace',
                                            fontWeight: 700,
                                            letterSpacing: '1px',
                                            fontSize: '14px',
                                            color: '#1e293b',
                                        }}
                                    >
                                        {showCurrentPass ? currentPlain : '••••••••'}
                                    </span>
                                    <button
                                        type="button"
                                        onClick={() => setShowCurrentPass(!showCurrentPass)}
                                        style={{
                                            background: 'none',
                                            border: 'none',
                                            cursor: 'pointer',
                                            color: '#005baa',
                                            marginLeft: 'auto',
                                            fontSize: '13px',
                                            fontWeight: 600,
                                            display: 'inline-flex',
                                            alignItems: 'center',
                                            gap: '5px',
                                        }}
                                    >
                                        <i className={`bi ${showCurrentPass ? 'bi-eye-slash' : 'bi-eye'}`}></i>{' '}
                                        <span>{showCurrentPass ? 'Sembunyikan' : 'Lihat'}</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Password Baru */}
                        <div className="form-row">
                            <div className="form-group">
                                <label>
                                    Password Baru{' '}
                                    <span style={{ fontWeight: 400, color: '#94a3b8' }}>
                                        (kosongkan jika tidak ingin mengubah password)
                                    </span>
                                </label>
                                <div className="password-wrapper" style={{ position: 'relative' }}>
                                    <input
                                        type={showNewPass ? 'text' : 'password'}
                                        name="password"
                                        value={formData.password}
                                        onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                                        placeholder="Ketik password baru (minimal 8 karakter)"
                                    />
                                    <button
                                        type="button"
                                        className="toggle-password"
                                        onClick={() => setShowNewPass(!showNewPass)}
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
                                        <i className={`bi ${showNewPass ? 'bi-eye-slash' : 'bi-eye'}`}></i>
                                    </button>
                                </div>
                                {errors.password && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.password}
                                    </span>
                                )}
                            </div>
                        </div>

                        {/* Unit Kerja */}
                        <div className="form-row">
                            <div className="form-group">
                                <label>
                                    Nama Unit{' '}
                                    {!isAdminAccount && <span style={{ color: '#ef4444' }}>*</span>}
                                </label>
                                <input
                                    type="text"
                                    name="nama_unit"
                                    value={formData.nama_unit}
                                    onChange={(e) => setFormData({ ...formData, nama_unit: e.target.value })}
                                    required={!isAdminAccount}
                                />
                                {errors.nama_unit && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.nama_unit}
                                    </span>
                                )}
                            </div>
                            <div className="form-group">
                                <label>
                                    Kode Unit{' '}
                                    {!isAdminAccount && <span style={{ color: '#ef4444' }}>*</span>}
                                </label>
                                <input
                                    type="text"
                                    name="kode_unit"
                                    value={formData.kode_unit}
                                    onChange={(e) => setFormData({ ...formData, kode_unit: e.target.value })}
                                    required={!isAdminAccount}
                                />
                                {errors.kode_unit && (
                                    <span className="form-error">
                                        <i className="bi bi-exclamation-circle"></i> {errors.kode_unit}
                                    </span>
                                )}
                            </div>
                        </div>

                        {/* Role */}
                        <div className="form-group" style={{ maxWidth: '340px' }}>
                            <label style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                                Role / Hak Akses
                                {isAdminAccount ? (
                                    <span style={{ fontSize: '11px', fontWeight: 600, color: '#dc2626' }}>
                                        <i className="bi bi-lock-fill"></i> (Terkunci)
                                    </span>
                                ) : (
                                    <span style={{ color: '#ef4444' }}>*</span>
                                )}
                            </label>

                            {isAdminAccount ? (
                                <>
                                    <div style={{ position: 'relative' }}>
                                        <input
                                            type="text"
                                            value="Admin (Administrator)"
                                            readOnly
                                            disabled
                                            style={{
                                                background: '#f1f5f9',
                                                color: '#475569',
                                                fontWeight: 700,
                                                cursor: 'not-allowed',
                                                border: '1px solid #cbd5e1',
                                                borderRadius: '10px',
                                                padding: '10px 14px 10px 42px',
                                                width: '100%',
                                                fontSize: '13.5px',
                                            }}
                                        />
                                        <i
                                            className="bi bi-shield-lock-fill"
                                            style={{
                                                position: 'absolute',
                                                left: '14px',
                                                top: '50%',
                                                transform: 'translateY(-50%)',
                                                color: '#005baa',
                                                fontSize: '15px',
                                            }}
                                        ></i>
                                    </div>
                                    <span
                                        style={{
                                            display: 'block',
                                            fontSize: '11.5px',
                                            color: '#64748b',
                                            marginTop: '5px',
                                            lineHeight: 1.4,
                                        }}
                                    >
                                        <i className="bi bi-info-circle" style={{ color: '#005baa' }}></i> Role akun
                                        Administrator dikunci permanen oleh sistem demi keamanan.
                                    </span>
                                </>
                            ) : (
                                <select
                                    name="role"
                                    value={formData.role}
                                    onChange={(e) => setFormData({ ...formData, role: e.target.value as any })}
                                    required
                                >
                                    <option value="user">User (Pengguna Biasa)</option>
                                    <option value="admin">Admin (Administrator)</option>
                                </select>
                            )}
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
                                {isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};
