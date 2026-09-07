import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { authService } from '../../services/authService';

export const ProfilePage: React.FC = () => {
    const { user, refreshUser } = useAuth();

    // Profile Edit State (Admin only)
    const [name, setName] = useState(user?.name || '');
    const [username, setUsername] = useState(user?.username || '');
    const [profileLoading, setProfileLoading] = useState(false);
    const [profileSuccess, setProfileSuccess] = useState('');
    const [profileError, setProfileError] = useState('');

    // Password Edit State
    const [currentPassword, setCurrentPassword] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [passwordLoading, setPasswordLoading] = useState(false);
    const [passwordSuccess, setPasswordSuccess] = useState('');
    const [passwordError, setPasswordError] = useState('');

    const isAdmin = user?.role === 'admin';

    // Get initials
    const getInitials = (nameStr?: string | null) => {
        if (!nameStr) return 'BI';
        const parts = nameStr.trim().split(/\s+/);
        if (parts.length >= 2) {
            return (parts[0][0] + parts[1][0]).toUpperCase();
        }
        return nameStr.substring(0, 2).toUpperCase();
    };

    const handleUpdateProfile = async (e: React.FormEvent) => {
        e.preventDefault();
        setProfileLoading(true);
        setProfileSuccess('');
        setProfileError('');

        try {
            await authService.updateProfile({ name, username });
            setProfileSuccess('Profil berhasil diperbarui.');
            await refreshUser();
        } catch (err: any) {
            const msg = err.response?.data?.message || 'Gagal memperbarui profil. Periksa data Anda.';
            setProfileError(msg);
        } finally {
            setProfileLoading(false);
        }
    };

    const handleUpdatePassword = async (e: React.FormEvent) => {
        e.preventDefault();
        setPasswordLoading(true);
        setPasswordSuccess('');
        setPasswordError('');

        if (password !== passwordConfirmation) {
            setPasswordError('Konfirmasi password tidak cocok dengan password baru.');
            setPasswordLoading(false);
            return;
        }

        try {
            await authService.updatePassword({
                current_password: currentPassword,
                password: password,
                password_confirmation: passwordConfirmation,
            });
            setPasswordSuccess('Password berhasil diubah.');
            setCurrentPassword('');
            setPassword('');
            setPasswordConfirmation('');
        } catch (err: any) {
            const msg = err.response?.data?.message || 'Gagal mengubah password. Pastikan password lama benar.';
            setPasswordError(msg);
        } finally {
            setPasswordLoading(false);
        }
    };

    return (
        <div>
            <style>{`
                @media (max-width: 768px) {
                    .profile-grid {
                        grid-template-columns: 1fr !important;
                    }
                    .profile-banner {
                        padding: 20px 18px !important;
                        flex-direction: column !important;
                        align-items: flex-start !important;
                    }
                    .profile-banner-left {
                        flex-direction: row !important;
                        align-items: center !important;
                        gap: 14px !important;
                        width: 100% !important;
                    }
                    .profile-banner-avatar {
                        width: 58px !important;
                        height: 58px !important;
                        min-width: 58px !important;
                    }
                    .profile-banner-right {
                        text-align: left !important;
                        width: 100% !important;
                        border-top: 1px solid rgba(255, 255, 255, 0.2);
                        padding-top: 12px !important;
                        margin-top: 4px !important;
                    }
                }
            `}</style>

            <div className="dashboard-header">
                <div>
                    <h1><i className="bi bi-person-badge-fill" style={{ color: '#005baa', marginRight: '8px' }}></i>Profil Saya</h1>
                    <p>Kelola informasi akun dan pengaturan keamanan profil Anda.</p>
                </div>
            </div>

            <div style={{ display: 'flex', flexDirection: 'column', gap: '24px', maxWidth: '900px' }}>
                {/* User Header Banner */}
                <div className="profile-banner" style={{
                    background: 'linear-gradient(135deg, #003b73 0%, #005baa 100%)',
                    borderRadius: '16px',
                    padding: '28px 32px',
                    color: 'white',
                    boxShadow: '0 10px 25px -5px rgba(0,91,170,0.25)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    flexWrap: 'wrap',
                    gap: '20px'
                }}>
                    <div className="profile-banner-left" style={{ display: 'flex', alignItems: 'center', gap: '20px' }}>
                        <div className="profile-banner-avatar" style={{
                            width: '72px',
                            height: '72px',
                            borderRadius: '50%',
                            background: 'white',
                            color: '#003b73',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontWeight: 800,
                            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                            flexShrink: 0
                        }}>
                            <span style={{ fontSize: '1.4em' }}>{getInitials(user?.name)}</span>
                        </div>
                        <div>
                            <h2 style={{ fontSize: '20px', fontWeight: 800, margin: '0 0 6px 0', letterSpacing: '-0.3px', color: '#ffffff', lineHeight: 1.2 }}>
                                {user?.name}
                            </h2>
                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }}>
                                <span style={{
                                    background: 'rgba(255,255,255,0.2)',
                                    backdropFilter: 'blur(4px)',
                                    padding: '4px 12px',
                                    borderRadius: '9999px',
                                    fontSize: '12px',
                                    fontWeight: 600,
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px'
                                }}>
                                    <i className="bi bi-shield-check"></i> {isAdmin ? 'Administrator' : 'Unit Kerja'}
                                </span>
                                {user?.kode_unit && (
                                    <span style={{
                                        background: 'rgba(255,255,255,0.2)',
                                        backdropFilter: 'blur(4px)',
                                        padding: '4px 12px',
                                        borderRadius: '9999px',
                                        fontSize: '12px',
                                        fontWeight: 600,
                                        display: 'inline-flex',
                                        alignItems: 'center',
                                        gap: '6px'
                                    }}>
                                        <i className="bi bi-tag-fill"></i> Kode Unit: {user.kode_unit}
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>
                    <div className="profile-banner-right" style={{ textAlign: 'right' }}>
                        <span style={{ display: 'block', fontSize: '12px', opacity: 0.85 }}>Username Akun</span>
                        <strong style={{ fontSize: '16px', fontFamily: 'monospace', letterSpacing: '0.5px' }}>{user?.username}</strong>
                    </div>
                </div>

                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '24px' }} className="profile-grid">
                    {/* Profile Info Card */}
                    <div className="dashboard-section" style={{ margin: 0, background: '#fff', borderRadius: '16px', boxShadow: '0 4px 20px rgba(0,0,0,0.05)', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                        <div className="section-header" style={{ padding: '18px 24px', background: '#f8fafc', borderBottom: '1px solid #e2e8f0' }}>
                            <h2 style={{ fontSize: '16px', fontWeight: 700, color: '#0f172a', margin: 0, display: 'flex', alignItems: 'center', gap: '8px' }}>
                                <i className="bi bi-person-vcard" style={{ color: '#005baa' }}></i> Informasi Akun
                            </h2>
                        </div>
                        <div style={{ padding: '24px' }}>
                            {isAdmin ? (
                                <form onSubmit={handleUpdateProfile} style={{ display: 'flex', flexDirection: 'column', gap: '18px' }}>
                                    {profileSuccess && (
                                        <div style={{ color: '#059669', background: '#ecfdf5', border: '1px solid #a7f3d0', padding: '10px 14px', borderRadius: '8px', fontSize: '13px', fontWeight: 600, display: 'flex', alignItems: 'center', gap: '6px' }}>
                                            <i className="bi bi-check-circle-fill"></i> {profileSuccess}
                                        </div>
                                    )}
                                    {profileError && (
                                        <div style={{ color: '#dc2626', background: '#fef2f2', border: '1px solid #fecaca', padding: '10px 14px', borderRadius: '8px', fontSize: '13px', fontWeight: 600, display: 'flex', alignItems: 'center', gap: '6px' }}>
                                            <i className="bi bi-exclamation-circle-fill"></i> {profileError}
                                        </div>
                                    )}

                                    {/* Nama Administrator */}
                                    <div className="form-group" style={{ marginBottom: 0 }}>
                                        <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }}>
                                            Nama Administrator
                                        </label>
                                        <div style={{ position: 'relative' }}>
                                            <input
                                                type="text"
                                                value={name}
                                                onChange={(e) => setName(e.target.value)}
                                                required
                                                style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', fontSize: '13.5px', outline: 'none', transition: 'all .2s', fontWeight: 600 }}
                                                placeholder="Masukkan Nama Administrator"
                                            />
                                            <i className="bi bi-person-fill" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                        </div>
                                    </div>

                                    {/* Username ID */}
                                    <div className="form-group" style={{ marginBottom: 0 }}>
                                        <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }}>
                                            Username ID
                                        </label>
                                        <div style={{ position: 'relative' }}>
                                            <input
                                                type="text"
                                                value={username}
                                                onChange={(e) => setUsername(e.target.value)}
                                                required
                                                style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', fontSize: '13.5px', outline: 'none', transition: 'all .2s', fontWeight: 600 }}
                                                placeholder="Masukkan Username Admin"
                                            />
                                            <i className="bi bi-person-badge" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                        </div>
                                    </div>

                                    {/* Submit Button */}
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginTop: '6px', flexWrap: 'wrap' }}>
                                        <button
                                            type="submit"
                                            disabled={profileLoading}
                                            className="btn-primary"
                                            style={{ padding: '10px 20px', fontWeight: 600, display: 'inline-flex', alignItems: 'center', gap: '6px', borderRadius: '10px' }}
                                        >
                                            {profileLoading ? <i className="bi bi-arrow-repeat spin"></i> : <i className="bi bi-check2-circle"></i>} Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            ) : (
                                /* Tampilan Informasi Akun Unit Kerja (Read-Only) */
                                <div style={{ display: 'flex', flexDirection: 'column', gap: '18px' }}>
                                    {/* Nama Unit */}
                                    <div className="form-group" style={{ marginBottom: 0 }}>
                                        <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }}>
                                            Nama Unit / Pengguna
                                            <span style={{ fontSize: '11px', fontWeight: 600, color: '#dc2626', marginLeft: '6px' }}>
                                                <i className="bi bi-lock-fill"></i> (Terkunci)
                                            </span>
                                        </label>
                                        <div style={{ position: 'relative' }}>
                                            <input
                                                type="text"
                                                value={user?.name || ''}
                                                readOnly
                                                disabled
                                                style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', background: '#f8fafc', color: '#1e293b', fontWeight: 700, fontSize: '13.5px', cursor: 'not-allowed' }}
                                            />
                                            <i className="bi bi-building" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                        </div>
                                        <span style={{ display: 'block', fontSize: '11.5px', color: '#64748b', marginTop: '6px', lineHeight: 1.4 }}>
                                            <i className="bi bi-info-circle" style={{ color: '#005baa' }}></i> Nama unit dikunci oleh sistem. Hubungi Administrator jika terdapat penyesuaian nama unit.
                                        </span>
                                    </div>

                                    {/* Username ID */}
                                    <div className="form-group" style={{ marginBottom: 0 }}>
                                        <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }}>
                                            Username ID
                                            <span style={{ fontSize: '11px', fontWeight: 600, color: '#dc2626', marginLeft: '6px' }}>
                                                <i className="bi bi-lock-fill"></i> (Terkunci)
                                            </span>
                                        </label>
                                        <div style={{ position: 'relative' }}>
                                            <input
                                                type="text"
                                                value={user?.username || ''}
                                                readOnly
                                                disabled
                                                style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', background: '#f8fafc', color: '#1e293b', fontWeight: 700, fontSize: '13.5px', fontFamily: 'monospace', cursor: 'not-allowed' }}
                                            />
                                            <i className="bi bi-person-badge" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                        </div>
                                    </div>

                                    {user?.kode_unit && (
                                        <div className="form-group" style={{ marginBottom: 0 }}>
                                            <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }}>
                                                Kode Unit Kerja
                                            </label>
                                            <div style={{ position: 'relative' }}>
                                                <input
                                                    type="text"
                                                    value={user.kode_unit}
                                                    readOnly
                                                    disabled
                                                    style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', background: '#f8fafc', color: '#1e293b', fontWeight: 700, fontSize: '13.5px', cursor: 'not-allowed' }}
                                                />
                                                <i className="bi bi-tag" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                            </div>
                                        </div>
                                    )}

                                    <div className="form-group" style={{ marginBottom: 0 }}>
                                        <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }}>
                                            Hak Akses / Peran
                                        </label>
                                        <div style={{ position: 'relative' }}>
                                            <input
                                                type="text"
                                                value="Pegawai Unit Kerja (Pemohon Ruangan)"
                                                readOnly
                                                disabled
                                                style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', background: '#f8fafc', color: '#1e293b', fontWeight: 700, fontSize: '13.5px', cursor: 'not-allowed' }}
                                            />
                                            <i className="bi bi-shield-check" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#059669', fontSize: '15px' }}></i>
                                        </div>
                                    </div>

                                    <div style={{ padding: '12px 14px', borderRadius: '10px', background: '#f0f9ff', border: '1px solid #bae6fd', fontSize: '12px', color: '#0369a1', lineHeight: 1.45, display: 'flex', alignItems: 'flex-start', gap: '8px' }}>
                                        <i className="bi bi-info-circle-fill" style={{ fontSize: '15px', color: '#0284c7', flexShrink: 0, marginTop: '1px' }}></i>
                                        <span>Akun unit kerja dikelola secara terpusat oleh Administrator Silakan. Untuk keamanan, Anda dapat memperbarui kata sandi secara berkala pada kartu <strong>Keamanan & Password</strong> di samping.</span>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Password Card */}
                    <div className="dashboard-section" style={{ margin: 0, background: '#fff', borderRadius: '16px', boxShadow: '0 4px 20px rgba(0,0,0,0.05)', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                        <div className="section-header" style={{ padding: '18px 24px', background: '#f8fafc', borderBottom: '1px solid #e2e8f0' }}>
                            <h2 style={{ fontSize: '16px', fontWeight: 700, color: '#0f172a', margin: 0, display: 'flex', alignItems: 'center', gap: '8px' }}>
                                <i className="bi bi-shield-lock-fill" style={{ color: '#005baa' }}></i> Keamanan & Password
                            </h2>
                        </div>
                        <div style={{ padding: '24px' }}>
                            <form onSubmit={handleUpdatePassword} style={{ display: 'flex', flexDirection: 'column', gap: '18px' }}>
                                {passwordSuccess && (
                                    <div style={{ color: '#059669', background: '#ecfdf5', border: '1px solid #a7f3d0', padding: '10px 14px', borderRadius: '8px', fontSize: '13px', fontWeight: 600, display: 'flex', alignItems: 'center', gap: '6px' }}>
                                        <i className="bi bi-check-circle-fill"></i> {passwordSuccess}
                                    </div>
                                )}
                                {passwordError && (
                                    <div style={{ color: '#dc2626', background: '#fef2f2', border: '1px solid #fecaca', padding: '10px 14px', borderRadius: '8px', fontSize: '13px', fontWeight: 600, display: 'flex', alignItems: 'center', gap: '6px' }}>
                                        <i className="bi bi-exclamation-circle-fill"></i> {passwordError}
                                    </div>
                                )}

                                {/* Password Saat Ini */}
                                <div className="form-group" style={{ marginBottom: 0 }}>
                                    <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }} className="required">
                                        Password Saat Ini
                                    </label>
                                    <div style={{ position: 'relative' }}>
                                        <input
                                            type="password"
                                            value={currentPassword}
                                            onChange={(e) => setCurrentPassword(e.target.value)}
                                            required
                                            style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', fontSize: '13.5px', outline: 'none' }}
                                            placeholder="Masukkan password saat ini"
                                        />
                                        <i className="bi bi-key" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                    </div>
                                </div>

                                {/* Password Baru */}
                                <div className="form-group" style={{ marginBottom: 0 }}>
                                    <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }} className="required">
                                        Password Baru
                                    </label>
                                    <div style={{ position: 'relative' }}>
                                        <input
                                            type="password"
                                            value={password}
                                            onChange={(e) => setPassword(e.target.value)}
                                            required
                                            style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', fontSize: '13.5px', outline: 'none' }}
                                            placeholder="Minimal 8 karakter"
                                        />
                                        <i className="bi bi-lock" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                    </div>
                                </div>

                                {/* Konfirmasi Password */}
                                <div className="form-group" style={{ marginBottom: 0 }}>
                                    <label style={{ display: 'block', fontSize: '13px', fontWeight: 700, color: '#334155', marginBottom: '6px' }} className="required">
                                        Konfirmasi Password Baru
                                    </label>
                                    <div style={{ position: 'relative' }}>
                                        <input
                                            type="password"
                                            value={passwordConfirmation}
                                            onChange={(e) => setPasswordConfirmation(e.target.value)}
                                            required
                                            style={{ width: '100%', padding: '10px 14px 10px 42px', border: '1px solid #cbd5e1', borderRadius: '10px', fontSize: '13.5px', outline: 'none' }}
                                            placeholder="Ulangi password baru"
                                        />
                                        <i className="bi bi-shield-check" style={{ position: 'absolute', left: '14px', top: '50%', transform: 'translateY(-50%)', color: '#005baa', fontSize: '15px' }}></i>
                                    </div>
                                </div>

                                {/* Submit Button */}
                                <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginTop: '6px', flexWrap: 'wrap' }}>
                                    <button
                                        type="submit"
                                        disabled={passwordLoading}
                                        className="btn-primary"
                                        style={{ padding: '10px 20px', fontWeight: 600, display: 'inline-flex', alignItems: 'center', gap: '6px', background: '#005baa', borderRadius: '10px' }}
                                    >
                                        {passwordLoading ? <i className="bi bi-arrow-repeat spin"></i> : <i className="bi bi-shield-lock"></i>} Perbarui Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};
