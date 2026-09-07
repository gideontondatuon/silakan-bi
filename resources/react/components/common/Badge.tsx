import React from 'react';
import { BookingStatus } from '../../types';

interface BadgeProps {
    status?: BookingStatus | string;
    children?: React.ReactNode;
    variant?: 'success' | 'warning' | 'danger' | 'info' | 'secondary';
}

export const Badge: React.FC<BadgeProps> = ({ status, children, variant }) => {
    let resolvedVariant: 'success' | 'warning' | 'danger' | 'info' | 'secondary' = variant || 'secondary';

    if (status) {
        const s = status.toLowerCase();
        if (s === 'disetujui' || s === 'aktif') resolvedVariant = 'success';
        else if (s === 'pending' || s === 'perawatan') resolvedVariant = 'warning';
        else if (s === 'ditolak' || s === 'nonaktif') resolvedVariant = 'danger';
        else if (s === 'selesai') resolvedVariant = 'info';
        else if (s === 'cancel') resolvedVariant = 'secondary';
    }

    const styles = {
        success: { bg: '#ecfdf5', text: '#059669', border: '#a7f3d0' },
        warning: { bg: '#fffbeb', text: '#d97706', border: '#fde68a' },
        danger: { bg: '#fef2f2', text: '#dc2626', border: '#fecdd3' },
        info: { bg: '#f0f9ff', text: '#0284c7', border: '#bae6fd' },
        secondary: { bg: '#f1f5f9', text: '#64748b', border: '#cbd5e1' },
    }[resolvedVariant];

    return (
        <span
            style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '5px',
                padding: '4px 10px',
                borderRadius: '9999px',
                fontSize: '11.5px',
                fontWeight: 700,
                background: styles.bg,
                color: styles.text,
                border: `1px solid ${styles.border}`,
                lineHeight: 1.2,
                textTransform: 'capitalize',
            }}
        >
            <span
                style={{
                    width: '6px',
                    height: '6px',
                    borderRadius: '50%',
                    backgroundColor: styles.text,
                }}
            />
            {children || status}
        </span>
    );
};
