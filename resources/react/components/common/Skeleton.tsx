import React from 'react';

interface SkeletonProps {
    className?: string;
    width?: string | number;
    height?: string | number;
    borderRadius?: string | number;
    style?: React.CSSProperties;
}

export const Skeleton: React.FC<SkeletonProps> = ({
    className = '',
    width,
    height,
    borderRadius = '8px',
    style = {},
}) => {
    const computedStyle: React.CSSProperties = {
        width: typeof width === 'number' ? `${width}px` : width,
        height: typeof height === 'number' ? `${height}px` : height,
        borderRadius: typeof borderRadius === 'number' ? `${borderRadius}px` : borderRadius,
        ...style,
    };

    return <div className={`skeleton-shimmer ${className}`} style={computedStyle} />;
};

export const StatCardSkeleton: React.FC = () => {
    return (
        <div className="stat-card" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '20px' }}>
            <div style={{ flex: 1 }}>
                <Skeleton width="45%" height={14} style={{ marginBottom: '10px' }} />
                <Skeleton width="30%" height={28} style={{ marginBottom: '6px' }} />
                <Skeleton width="60%" height={12} />
            </div>
            <Skeleton width={52} height={52} borderRadius="14px" />
        </div>
    );
};

export const LiveBannerSkeleton: React.FC = () => {
    return (
        <div style={{ marginBottom: '24px', padding: '24px', borderRadius: '16px', background: '#e2e8f0' }} className="skeleton-shimmer">
            <Skeleton width="35%" height={24} style={{ marginBottom: '16px', background: '#cbd5e1' }} />
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))', gap: '14px' }}>
                <Skeleton height={110} borderRadius="12px" style={{ background: '#cbd5e1' }} />
                <Skeleton height={110} borderRadius="12px" style={{ background: '#cbd5e1' }} />
            </div>
        </div>
    );
};
