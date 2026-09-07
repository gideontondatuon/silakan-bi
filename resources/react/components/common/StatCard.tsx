import React from 'react';

interface StatCardProps {
    title: string;
    value: number | string;
    icon: string;
    color?: 'blue' | 'teal' | 'yellow' | 'green' | 'red' | 'purple';
    variant?: 'blue' | 'teal' | 'yellow' | 'green' | 'red' | 'purple';
    trend?: number | null;
    trendLabel?: string | null;
    subtitle?: string;
}

export const StatCard: React.FC<StatCardProps> = ({
    title,
    value,
    icon,
    color,
    variant = 'blue',
    trend = null,
    trendLabel = null,
    subtitle,
}) => {
    const cardColor = color || variant;
    const formattedValue = typeof value === 'number' ? value.toLocaleString('id-ID') : value;

    // Clean up icon string if it has 'bi-' prefix or not
    const iconClass = icon.startsWith('bi-') ? icon : `bi-${icon}`;

    return (
        <div className={`stat-card stat-card-${cardColor}`}>
            <div className="stat-header">
                <div className="stat-title">{title}</div>
                <div className="stat-icon">
                    <i className={`bi ${iconClass}`}></i>
                </div>
            </div>
            <div className="stat-value">{formattedValue}</div>
            <div className="stat-footer">
                {trend !== null ? (
                    <>
                        {trend >= 0 ? (
                            <>
                                <i className="bi bi-arrow-up-short" style={{ color: '#10b981', fontSize: '14px' }}></i>
                                <span style={{ color: '#10b981', fontWeight: 700 }}>+{trend}</span>
                            </>
                        ) : (
                            <>
                                <i className="bi bi-arrow-down-short" style={{ color: '#ef4444', fontSize: '14px' }}></i>
                                <span style={{ color: '#ef4444', fontWeight: 700 }}>{trend}</span>
                            </>
                        )}
                        <span>{trendLabel || 'vs bulan lalu'}</span>
                    </>
                ) : subtitle ? (
                    <>
                        <i className="bi bi-info-circle" style={{ fontSize: '12px' }}></i>
                        <span>{subtitle}</span>
                    </>
                ) : (
                    <>
                        <i className="bi bi-info-circle" style={{ fontSize: '12px' }}></i>
                        <span>Total keseluruhan</span>
                    </>
                )}
            </div>
        </div>
    );
};
