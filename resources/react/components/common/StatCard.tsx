import React from 'react';

interface StatCardProps {
    title: string;
    value: number | string;
    icon: string;
    variant?: 'blue' | 'green' | 'yellow' | 'red' | 'purple';
    subtitle?: string;
}

export const StatCard: React.FC<StatCardProps> = ({
    title,
    value,
    icon,
    variant = 'blue',
    subtitle,
}) => {
    const variantClass = `stat-card-${variant}`;

    return (
        <div className={`stat-card ${variantClass}`}>
            <div className="stat-icon">
                <i className={`bi ${icon}`}></i>
            </div>
            <div className="stat-info">
                <span className="stat-label">{title}</span>
                <h3 className="stat-value">{value}</h3>
                {subtitle && <small className="stat-subtitle">{subtitle}</small>}
            </div>
        </div>
    );
};
