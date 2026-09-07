import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { GuestLayout } from '../../layouts/GuestLayout';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const Login: React.FC = () => {
    const { login } = useAuth();
    const navigate = useNavigate();

    const [loginInput, setLoginInput] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setErrorMessage('');
        setIsLoading(true);

        try {
            const data = await login(loginInput, password, remember);
            if (data.role === 'admin') {
                navigate('/admin/dashboard');
            } else {
                navigate('/dashboard');
            }
        } catch (err: any) {
            const msg =
                err.response?.data?.errors?.login_input?.[0] ||
                err.response?.data?.message ||
                'Username atau password yang Anda masukkan salah.';
            setErrorMessage(msg);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <GuestLayout>
            <div className="login-card">
                {/* Brand Header */}
                <div
                    className="login-brand"
                    style={{
                        textAlign: 'center',
                        marginBottom: '24px',
                        display: 'flex',
                        flexDirection: 'column',
                        alignItems: 'center',
                    }}
                >
                    <img
                        src="/images/logo-bi4.png"
                        className="login-logo-bi"
                        alt="Bank Indonesia"
                        style={{
                            height: '62px',
                            width: 'auto',
                            maxWidth: '280px',
                            objectFit: 'contain',
                            marginBottom: '14px',
                            filter: 'drop-shadow(0 3px 8px rgba(0,91,170,0.12))',
                        }}
                    />

                    {/* Accent Line Divider */}
                    <div
                        style={{
                            height: '2px',
                            width: '60px',
                            background: 'linear-gradient(90deg, #005baa, #b8972a)',
                            borderRadius: '2px',
                            marginBottom: '14px',
                        }}
                    />

                    {/* System Title */}
                    <div className="login-brand-text">
                        <h1
                            style={{
                                fontSize: '32px',
                                fontWeight: 800,
                                color: '#003b73',
                                letterSpacing: '4px',
                                margin: '0 0 6px 0',
                                lineHeight: 1,
                            }}
                        >
                            SILAKAN
                        </h1>
                        <p
                            style={{
                                fontSize: '13.5px',
                                fontWeight: 600,
                                color: '#475569',
                                margin: 0,
                                lineHeight: 1.4,
                                letterSpacing: '0.3px',
                            }}
                        >
                            Sistem Informasi Layanan Kantor
                            <br />
                            <span style={{ color: '#005baa', fontWeight: 700, fontSize: '14px' }}>
                                KPwBI Provinsi Sulawesi Utara
                            </span>
                        </p>
                    </div>
                </div>

                {/* Section Header */}
                <h2 className="login-title">MASUK KE SISTEM</h2>
                <p className="login-subtitle">Silakan masukkan akun terdaftar Anda untuk melanjutkan</p>

                {errorMessage && (
                    <AlertBanner
                        type="error"
                        message={errorMessage}
                        onClose={() => setErrorMessage('')}
                    />
                )}

                {/* Form */}
                <form onSubmit={handleSubmit}>
                    {/* Username / Email */}
                    <div className="login-group">
                        <label htmlFor="login_input">
                            <i className="bi bi-person" style={{ marginRight: '5px' }}></i>
                            Username / Email
                        </label>
                        <input
                            id="login_input"
                            className="login-input"
                            type="text"
                            name="login_input"
                            value={loginInput}
                            onChange={(e) => setLoginInput(e.target.value)}
                            required
                            autoFocus
                            autoComplete="username"
                            placeholder="Masukkan username atau email"
                            disabled={isLoading}
                        />
                    </div>

                    {/* Password */}
                    <div className="login-group">
                        <label htmlFor="password">
                            <i className="bi bi-lock" style={{ marginRight: '5px' }}></i>
                            Password
                        </label>
                        <div className="password-wrapper">
                            <input
                                id="password"
                                className="login-input"
                                type={showPassword ? 'text' : 'password'}
                                name="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                                autoComplete="current-password"
                                placeholder="Masukkan password"
                                disabled={isLoading}
                            />
                            <button
                                type="button"
                                className="toggle-password"
                                onClick={() => setShowPassword((prev) => !prev)}
                                tabIndex={-1}
                            >
                                <i className={`bi bi-eye${showPassword ? '-slash' : ''}`}></i>
                            </button>
                        </div>
                    </div>

                    {/* Remember Me */}
                    <label className="login-remember">
                        <input
                            type="checkbox"
                            checked={remember}
                            onChange={(e) => setRemember(e.target.checked)}
                            disabled={isLoading}
                        />
                        Ingat saya di perangkat ini
                    </label>

                    {/* Submit Button */}
                    <button
                        type="submit"
                        className="login-button"
                        id="login-btn"
                        disabled={isLoading}
                    >
                        {isLoading ? (
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
                                Memproses...
                            </>
                        ) : (
                            <>
                                <i className="bi bi-box-arrow-in-right"></i>
                                Masuk
                            </>
                        )}
                    </button>
                </form>

                {/* Footer */}
                <div className="login-footer">
                    <i className="bi bi-bank" style={{ fontSize: '16px', color: '#005baa' }}></i>
                    <br />
                    &copy; {new Date().getFullYear()} Bank Indonesia
                    <br />
                    KPwBI Provinsi Sulawesi Utara
                </div>
            </div>
        </GuestLayout>
    );
};
