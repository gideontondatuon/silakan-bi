import React from 'react';

export const LoadingSpinner: React.FC<{ message?: string; height?: string }> = ({
    message = 'Memuat data...',
    height = '300px',
}) => {
    return (
        <div
            style={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                justifyContent: 'center',
                minHeight: height,
                gap: '14px',
                color: '#64748b',
            }}
        >
            <div
                style={{
                    width: '40px',
                    height: '40px',
                    border: '3px solid #e2e8f0',
                    borderTop: '3px solid #005baa',
                    borderRadius: '50%',
                    animation: 'spin 0.8s linear infinite',
                }}
            />
            <span style={{ fontSize: '13.5px', fontWeight: 500 }}>{message}</span>
            <style>{`
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `}</style>
        </div>
    );
};
