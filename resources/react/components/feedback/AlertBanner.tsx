import React from 'react';

interface AlertBannerProps {
    type: 'success' | 'error' | 'warning' | 'info';
    message: string;
    onClose?: () => void;
}

export const AlertBanner: React.FC<AlertBannerProps> = ({ type, message, onClose }) => {
    if (!message) return null;

    const styles = {
        success: {
            bg: '#ecfdf5',
            border: '#a7f3d0',
            color: '#047857',
            icon: 'bi-check-circle-fill',
            iconColor: '#059669',
            shadow: 'rgba(16,185,129,0.12)',
        },
        error: {
            bg: '#fef2f2',
            border: '#fecdd3',
            color: '#9f1239',
            icon: 'bi-exclamation-triangle-fill',
            iconColor: '#dc2626',
            shadow: 'rgba(225,29,72,0.12)',
        },
        warning: {
            bg: '#fffbeb',
            border: '#fde68a',
            color: '#92400e',
            icon: 'bi-exclamation-circle-fill',
            iconColor: '#d97706',
            shadow: 'rgba(245,158,11,0.12)',
        },
        info: {
            bg: '#f0f9ff',
            border: '#bae6fd',
            color: '#0369a1',
            icon: 'bi-info-circle-fill',
            iconColor: '#0284c7',
            shadow: 'rgba(14,165,233,0.12)',
        },
    }[type];

    return (
        <div
            style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                padding: '14px 18px',
                background: styles.bg,
                border: `1px solid ${styles.border}`,
                borderRadius: '12px',
                color: styles.color,
                marginBottom: '20px',
                boxShadow: `0 4px 12px ${styles.shadow}`,
            }}
        >
            <div style={{ display: 'flex', alignItems: 'center', gap: '12px', fontSize: '13.5px', fontWeight: 600 }}>
                <i className={`bi ${styles.icon}`} style={{ fontSize: '18px', color: styles.iconColor }}></i>
                <span>{message}</span>
            </div>
            {onClose && (
                <button
                    type="button"
                    onClick={onClose}
                    style={{
                        background: 'none',
                        border: 'none',
                        color: styles.color,
                        cursor: 'pointer',
                        fontSize: '20px',
                        padding: 0,
                        display: 'flex',
                        alignItems: 'center',
                    }}
                >
                    &times;
                </button>
            )}
        </div>
    );
};
