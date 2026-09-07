import React from 'react';
import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { LoadingSpinner } from '../components/common/LoadingSpinner';

interface ProtectedRouteProps {
    allowedRoles?: Array<'admin' | 'user'>;
    children?: React.ReactNode;
}

export const ProtectedRoute: React.FC<ProtectedRouteProps> = ({ allowedRoles, children }) => {
    const { isAuthenticated, isLoading, user } = useAuth();

    if (isLoading) {
        return (
            <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: '#f8fafc' }}>
                <LoadingSpinner message="Memuat sesi akun..." />
            </div>
        );
    }

    if (!isAuthenticated || !user) {
        return <Navigate to="/login" replace />;
    }

    if (allowedRoles && !allowedRoles.includes(user.role)) {
        // If user is not admin trying to access admin route, redirect to user dashboard
        const fallbackUrl = user.role === 'admin' ? '/admin/dashboard' : '/dashboard';
        return <Navigate to={fallbackUrl} replace />;
    }

    return children ? <>{children}</> : <Outlet />;
};
