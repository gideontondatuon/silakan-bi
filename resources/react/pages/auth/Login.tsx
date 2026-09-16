import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

export const Login: React.FC = () => {
    const { login } = useAuth();
    const navigate = useNavigate();

    const [loginInput, setLoginInput] = useState('');
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');
    const [focusedField, setFocusedField] = useState<string | null>(null);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setErrorMessage('');
        setIsLoading(true);
        try {
            const data = await login(loginInput, password, true);
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
        <div
            style={{
                minHeight: '100vh',
                width: '100%',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                background: 'linear-gradient(145deg, #001428 0%, #00264d 30%, #003b73 65%, #005baa 100%)',
                position: 'relative',
                overflow: 'hidden',
                fontFamily: "'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif",
                padding: '24px 16px',
                boxSizing: 'border-box',
            }}
        >
            {/* Background Ambient Glows */}
            <div
                style={{
                    position: 'absolute',
                    top: '-15%',
                    left: '-10%',
                    width: '600px',
                    height: '600px',
                    borderRadius: '50%',
                    background: 'radial-gradient(circle, rgba(0, 168, 232, 0.18) 0%, transparent 65%)',
                    pointerEvents: 'none',
                }}
            />
            <div
                style={{
                    position: 'absolute',
                    bottom: '-15%',
                    right: '-10%',
                    width: '650px',
                    height: '650px',
                    borderRadius: '50%',
                    background: 'radial-gradient(circle, rgba(212, 175, 55, 0.12) 0%, transparent 65%)',
                    pointerEvents: 'none',
                }}
            />

            {/* Subtle Dot Grid */}
            <div
                style={{
                    position: 'absolute',
                    inset: 0,
                    backgroundImage: 'radial-gradient(rgba(255, 255, 255, 0.07) 1.5px, transparent 1.5px)',
                    backgroundSize: '28px 28px',
                    pointerEvents: 'none',
                }}
            />

            {/* Login Card */}
            <div
                style={{
                    position: 'relative',
                    zIndex: 10,
                    width: '100%',
                    maxWidth: '430px',
                    backgroundColor: '#ffffff',
                    borderRadius: '26px',
                    boxShadow: '0 25px 60px -15px rgba(0, 15, 40, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.15)',
                    padding: '38px 34px 30px',
                    textAlign: 'center',
                    boxSizing: 'border-box',
                    animation: 'fadeInCard 0.4s ease-out',
                }}
            >
                {/* Decorative Top Accent Bar */}
                <div
                    style={{
                        position: 'absolute',
                        top: 0,
                        left: '40px',
                        right: '40px',
                        height: '3px',
                        borderRadius: '0 0 4px 4px',
                        background: 'linear-gradient(90deg, #005baa, #d4af37, #005baa)',
                    }}
                />

                {/* SILAKAN Logo */}
                <div style={{ marginBottom: '18px', display: 'flex', justifyContent: 'center' }}>
                    <img
                        src="/images/SILAKAN.png"
                        alt="SILAKAN"
                        style={{
                            width: '84px',
                            height: '84px',
                            objectFit: 'contain',
                            filter: 'drop-shadow(0 6px 14px rgba(0, 59, 115, 0.16))',
                        }}
                    />
                </div>

                {/* Title & Subtitle */}
                <h1
                    style={{
                        fontSize: '24px',
                        fontWeight: 800,
                        color: '#003366',
                        margin: '0 0 4px 0',
                        letterSpacing: '0.6px',
                    }}
                >
                    SILAKAN
                </h1>
                <p
                    style={{
                        fontSize: '13.5px',
                        fontWeight: 600,
                        color: '#475569',
                        margin: '0 0 3px 0',
                        letterSpacing: '0.2px',
                    }}
                >
                    Sistem Informasi Layanan Kantor
                </p>
                <p
                    style={{
                        fontSize: '12px',
                        color: '#94a3b8',
                        margin: '0 0 24px 0',
                        fontWeight: 400,
                    }}
                >
                    KPw Prov. Sulawesi Utara
                </p>

                {/* Error Banner */}
                {errorMessage && (
                    <div
                        style={{
                            backgroundColor: '#fef2f2',
                            border: '1px solid #fecaca',
                            borderRadius: '12px',
                            padding: '10px 14px',
                            marginBottom: '18px',
                            color: '#dc2626',
                            fontSize: '13px',
                            display: 'flex',
                            alignItems: 'center',
                            gap: '8px',
                            textAlign: 'left',
                            lineHeight: 1.4,
                        }}
                    >
                        <i className="bi bi-exclamation-circle-fill" style={{ fontSize: '15px', flexShrink: 0 }} />
                        <span>{errorMessage}</span>
                    </div>
                )}

                {/* Login Form */}
                <form onSubmit={handleSubmit} style={{ textAlign: 'left' }}>
                    {/* Username / Email */}
                    <div style={{ marginBottom: '14px', position: 'relative' }}>
                        <i
                            className="bi bi-person"
                            style={{
                                position: 'absolute',
                                left: '16px',
                                top: '50%',
                                transform: 'translateY(-50%)',
                                color: focusedField === 'login' ? '#005baa' : '#9ca3af',
                                fontSize: '18px',
                                pointerEvents: 'none',
                                transition: 'color 0.2s ease',
                            }}
                        />
                        <input
                            id="login_input"
                            type="text"
                            value={loginInput}
                            onChange={(e) => setLoginInput(e.target.value)}
                            onFocus={() => setFocusedField('login')}
                            onBlur={() => setFocusedField(null)}
                            placeholder="Username"
                            required
                            autoFocus
                            disabled={isLoading}
                            style={{
                                width: '100%',
                                height: '48px',
                                padding: '0 16px 0 46px',
                                borderRadius: '12px',
                                border: focusedField === 'login' ? '1.5px solid #005baa' : '1.5px solid #e2e8f0',
                                backgroundColor: focusedField === 'login' ? '#ffffff' : '#f8fafc',
                                fontSize: '14px',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                                transition: 'all 0.2s ease',
                                boxShadow: focusedField === 'login' ? '0 0 0 3.5px rgba(0, 91, 170, 0.12)' : 'none',
                            }}
                        />
                    </div>

                    {/* Password */}
                    <div style={{ marginBottom: '18px', position: 'relative' }}>
                        <i
                            className="bi bi-lock"
                            style={{
                                position: 'absolute',
                                left: '16px',
                                top: '50%',
                                transform: 'translateY(-50%)',
                                color: focusedField === 'password' ? '#005baa' : '#9ca3af',
                                fontSize: '18px',
                                pointerEvents: 'none',
                                transition: 'color 0.2s ease',
                            }}
                        />
                        <input
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            onFocus={() => setFocusedField('password')}
                            onBlur={() => setFocusedField(null)}
                            placeholder="Password"
                            required
                            disabled={isLoading}
                            style={{
                                width: '100%',
                                height: '48px',
                                padding: '0 44px 0 46px',
                                borderRadius: '12px',
                                border: focusedField === 'password' ? '1.5px solid #005baa' : '1.5px solid #e2e8f0',
                                backgroundColor: focusedField === 'password' ? '#ffffff' : '#f8fafc',
                                fontSize: '14px',
                                color: '#1e293b',
                                outline: 'none',
                                boxSizing: 'border-box',
                                transition: 'all 0.2s ease',
                                boxShadow: focusedField === 'password' ? '0 0 0 3.5px rgba(0, 91, 170, 0.12)' : 'none',
                            }}
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            aria-label={showPassword ? 'Sembunyikan password' : 'Lihat password'}
                            style={{
                                position: 'absolute',
                                right: '14px',
                                top: '50%',
                                transform: 'translateY(-50%)',
                                background: 'transparent',
                                border: 'none',
                                color: '#9ca3af',
                                cursor: 'pointer',
                                fontSize: '16px',
                                padding: '4px',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                            }}
                        >
                            <i className={showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'} />
                        </button>
                    </div>

                    {/* Submit Button */}
                    <button
                        type="submit"
                        disabled={isLoading}
                        style={{
                            width: '100%',
                            height: '48px',
                            borderRadius: '12px',
                            border: 'none',
                            background: 'linear-gradient(135deg, #005baa 0%, #003b73 100%)',
                            color: '#ffffff',
                            fontSize: '15px',
                            fontWeight: 700,
                            letterSpacing: '0.3px',
                            cursor: isLoading ? 'not-allowed' : 'pointer',
                            opacity: isLoading ? 0.75 : 1,
                            boxShadow: '0 8px 22px -4px rgba(0, 91, 170, 0.42)',
                            transition: 'all 0.2s ease',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            gap: '8px',
                        }}
                        onMouseEnter={(e) => {
                            if (!isLoading) (e.currentTarget as HTMLElement).style.background = 'linear-gradient(135deg, #0068c2 0%, #004785 100%)';
                        }}
                        onMouseLeave={(e) => {
                            if (!isLoading) (e.currentTarget as HTMLElement).style.background = 'linear-gradient(135deg, #005baa 0%, #003b73 100%)';
                        }}
                    >
                        {isLoading ? (
                            <>
                                <span
                                    style={{
                                        width: '16px',
                                        height: '16px',
                                        border: '2px solid rgba(255, 255, 255, 0.3)',
                                        borderTopColor: '#ffffff',
                                        borderRadius: '50%',
                                        display: 'inline-block',
                                        animation: 'spin 0.8s linear infinite',
                                    }}
                                />
                                Memproses...
                            </>
                        ) : (
                            'Masuk'
                        )}
                    </button>
                </form>

                {/* Footer Version */}
                <div
                    style={{
                        marginTop: '22px',
                        fontSize: '12px',
                        color: '#94a3b8',
                        fontWeight: 400,
                    }}
                >
                    Versi 1.0.0
                </div>
            </div>

            <style>{`
                @keyframes fadeInCard {
                    from {
                        opacity: 0;
                        transform: translateY(14px) scale(0.98);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
                @keyframes spin {
                    to {
                        transform: rotate(360deg);
                    }
                }
            `}</style>
        </div>
    );
};
