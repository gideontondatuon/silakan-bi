import React from 'react';
import { useNotifications } from '../../context/NotificationContext';
import { Link } from 'react-router-dom';

export const ToastContainer: React.FC = () => {
    const { toasts, dismissToast } = useNotifications();

    if (toasts.length === 0) return null;

    return (
        <div id="silakan-toast-container" style={{
            position: 'fixed',
            bottom: '24px',
            right: '24px',
            zIndex: 9999999,
            display: 'flex',
            flexDirection: 'column',
            gap: '12px',
            maxWidth: '400px',
            width: 'calc(100vw - 32px)',
            pointerEvents: 'none',
        }}>
            {toasts.map((toast) => (
                <div
                    key={toast.id}
                    className="silakan-toast-card"
                    style={{
                        pointerEvents: 'auto',
                        background: '#ffffff',
                        border: '1px solid #cbd5e1',
                        borderLeft: '5px solid #005baa',
                        borderRadius: '12px',
                        padding: '14px 16px',
                        boxShadow: '0 12px 30px -4px rgba(0, 59, 115, 0.22), 0 4px 10px -2px rgba(0, 0, 0, 0.08)',
                        display: 'flex',
                        alignItems: 'flex-start',
                        gap: '12px',
                        animation: 'toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1)',
                        position: 'relative',
                    }}
                >
                    <div
                        style={{
                            width: '36px',
                            height: '36px',
                            borderRadius: '10px',
                            background: '#e0f2fe',
                            color: '#005baa',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontSize: '18px',
                            flexShrink: 0,
                        }}
                    >
                        <i className="bi bi-bell-fill"></i>
                    </div>

                    <div style={{ flex: 1, minWidth: 0 }}>
                        <div
                            style={{
                                fontSize: '13.5px',
                                fontWeight: 700,
                                color: '#003b73',
                                margin: '0 0 3px 0',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                            }}
                        >
                            <span>{toast.title}</span>
                            <button
                                onClick={() => dismissToast(toast.id)}
                                style={{
                                    background: 'none',
                                    border: 'none',
                                    color: '#94a3b8',
                                    cursor: 'pointer',
                                    fontSize: '18px',
                                    padding: 0,
                                    lineHeight: 1,
                                    marginLeft: '6px',
                                }}
                            >
                                &times;
                            </button>
                        </div>

                        <p
                            style={{
                                fontSize: '12.5px',
                                color: '#334155',
                                margin: '0 0 6px 0',
                                lineHeight: 1.4,
                                wordBreak: 'break-word',
                            }}
                        >
                            {toast.message}
                        </p>

                        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                            <small
                                style={{
                                    fontSize: '11px',
                                    color: '#94a3b8',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '4px',
                                }}
                            >
                                <i className="bi bi-clock"></i> {toast.time || 'Baru saja'}
                            </small>

                            {toast.url && (
                                <Link
                                    to={toast.url}
                                    onClick={() => dismissToast(toast.id)}
                                    style={{
                                        fontSize: '11.5px',
                                        fontWeight: 600,
                                        color: '#005baa',
                                        textDecoration: 'none',
                                    }}
                                >
                                    Lihat &rarr;
                                </Link>
                            )}
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
};
