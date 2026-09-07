import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { ProtectedRoute } from './ProtectedRoute';
import { AppLayout } from '../layouts/AppLayout';

// Public & Kiosk
import { Login } from '../pages/auth/Login';
import { KioskDisplay } from '../pages/kiosk/KioskDisplay';

// User & Shared Pages
import { UserDashboard } from '../pages/dashboard/UserDashboard';
import { ProfilePage } from '../pages/profile/ProfilePage';
import { NotificationListPage } from '../pages/notifications/NotificationListPage';
import { KalenderPage } from '../pages/kalender/KalenderPage';
import { PemesananList } from '../pages/pemesanan/PemesananList';
import { PemesananCreate } from '../pages/pemesanan/PemesananCreate';
import { PemesananDetail } from '../pages/pemesanan/PemesananDetail';
import { KegiatanBerlangsungPage } from '../pages/approval/KegiatanBerlangsungPage';

// Admin Pages
import { AdminDashboard } from '../pages/dashboard/AdminDashboard';
import { ApprovalList } from '../pages/approval/ApprovalList';
import { ApprovalDetail } from '../pages/approval/ApprovalDetail';
import { RuanganList } from '../pages/ruangan/RuanganList';
import { LayoutList } from '../pages/layout/LayoutList';
import { HariLiburList } from '../pages/harilibur/HariLiburList';
import { UserList } from '../pages/users/UserList';
import { LaporanPage } from '../pages/laporan/LaporanPage';
import { AuditLogPage } from '../pages/auditlog/AuditLogPage';

// Smart redirect helper for root / and /dashboard
const DashboardRouter: React.FC = () => {
    const { user } = useAuth();
    if (user?.role === 'admin') {
        return <Navigate to="/admin/dashboard" replace />;
    }
    return <UserDashboard />;
};

const RootRedirect: React.FC = () => {
    const { isAuthenticated, user, isLoading } = useAuth();
    if (isLoading) return null;
    if (!isAuthenticated) return <Navigate to="/login" replace />;
    return user?.role === 'admin' ? <Navigate to="/admin/dashboard" replace /> : <Navigate to="/dashboard" replace />;
};

export const AppRoutes: React.FC = () => {
    return (
        <Routes>
            {/* Public Routes */}
            <Route path="/login" element={<Login />} />
            <Route path="/display" element={<KioskDisplay />} />
            <Route path="/kiosk" element={<KioskDisplay />} />

            {/* Root Redirect */}
            <Route path="/" element={<RootRedirect />} />

            {/* Authenticated Application Shell */}
            <Route element={<ProtectedRoute><AppLayout /></ProtectedRoute>}>
                {/* General / User Routes */}
                <Route path="/dashboard" element={<DashboardRouter />} />
                <Route path="/profile" element={<ProfilePage />} />
                <Route path="/notifikasi" element={<NotificationListPage />} />
                <Route path="/notifications" element={<NotificationListPage />} />
                <Route path="/kalender" element={<KalenderPage />} />
                <Route path="/kegiatan-berlangsung" element={<KegiatanBerlangsungPage />} />

                <Route path="/pemesanan" element={<PemesananList />} />
                <Route path="/pemesanan/create" element={<PemesananCreate />} />
                <Route path="/pemesanan/:id" element={<PemesananDetail />} />

                {/* Admin Only Routes */}
                <Route element={<ProtectedRoute allowedRoles={['admin']} />}>
                    <Route path="/admin/dashboard" element={<AdminDashboard />} />
                    <Route path="/admin/approval" element={<ApprovalList />} />
                    <Route path="/admin/approval/:id" element={<ApprovalDetail />} />
                    <Route path="/admin/ruangan" element={<RuanganList />} />
                    <Route path="/admin/layout" element={<LayoutList />} />
                    <Route path="/admin/hari-libur" element={<HariLiburList />} />
                    <Route path="/admin/users" element={<UserList />} />
                    <Route path="/admin/laporan" element={<LaporanPage />} />
                    <Route path="/admin/audit-log" element={<AuditLogPage />} />
                </Route>
            </Route>

            {/* Catch-all 404 Route */}
            <Route path="*" element={<RootRedirect />} />
        </Routes>
    );
};
