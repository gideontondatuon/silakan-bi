import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { useNotifications } from '../../context/NotificationContext';

interface SidebarProps {
    isOpen: boolean;
    onCloseMobile?: () => void;
}

export const Sidebar: React.FC<SidebarProps> = ({ isOpen, onCloseMobile }) => {
    const { user, role, logout } = useAuth();
    const { unreadCount, pendingCount } = useNotifications();
    const location = useLocation();

    const isActive = (path: string) => {
        if (path === '/dashboard' || path === '/admin/dashboard') {
            return location.pathname === path;
        }
        return location.pathname.startsWith(path);
    };

    // Compute initials from user name
    const getInitials = (name?: string | null) => {
        if (!name) return 'BI';
        const parts = name.trim().split(/\s+/);
        if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    };

    return (
        <aside className={`sidebar ${isOpen ? 'show-mobile' : ''}`} id="sidebar">
            {/* Fixed Sidebar Brand */}
            <div className="sidebar-brand">
                <img
                    src="/images/logo-bi2.png"
                    className="sidebar-logo"
                    alt="Bank Indonesia"
                />
                <div className="sidebar-brand-text">
                    <strong>SILAKAN</strong>
                    <span>Sistem Informasi Layanan Kantor</span>
                    <small>KPwBI Prov. Sulut</small>
                </div>
            </div>

            {/* Scrollable Sidebar Menu */}
            <nav className="sidebar-menu">
                {role === 'admin' ? (
                    <>
                        <div className="menu-section">UTAMA</div>

                        <Link
                            to="/admin/dashboard"
                            className={isActive('/admin/dashboard') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </Link>

                        <Link
                            to="/admin/approval"
                            className={isActive('/admin/approval') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-calendar-check"></i>
                            <span>Pemesanan Ruangan</span>
                            {pendingCount > 0 && (
                                <small
                                    className="sidebar-badge"
                                    id="sidebarPendingBadge"
                                    style={{ background: '#f59e0b', color: '#fff' }}
                                >
                                    {pendingCount}
                                </small>
                            )}
                        </Link>

                        <Link
                            to="/admin/kegiatan-berlangsung"
                            className={isActive('/admin/kegiatan-berlangsung') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-play-circle"></i>
                            <span>Kegiatan Berlangsung</span>
                        </Link>

                        <Link
                            to="/kalender"
                            className={isActive('/kalender') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-calendar3"></i>
                            <span>Kalender Ruangan</span>
                        </Link>

                        <Link
                            to="/notifications"
                            className={isActive('/notifications') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-bell"></i>
                            <span>Notifikasi</span>
                            {unreadCount > 0 && (
                                <small className="sidebar-badge" id="sidebarNotificationBadge">
                                    {unreadCount}
                                </small>
                            )}
                        </Link>

                        <div className="menu-section">
                            MASTER <br /> DATA
                        </div>

                        <Link
                            to="/admin/ruangan"
                            className={isActive('/admin/ruangan') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-building"></i>
                            <span>Data Ruangan</span>
                        </Link>

                        <Link
                            to="/admin/layout"
                            className={isActive('/admin/layout') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-layout-text-sidebar-reverse"></i>
                            <span>Data Layout</span>
                        </Link>

                        <Link
                            to="/admin/hari-libur"
                            className={isActive('/admin/hari-libur') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-calendar2-week"></i>
                            <span>Hari Libur</span>
                        </Link>

                        <Link
                            to="/admin/users"
                            className={isActive('/admin/users') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-people"></i>
                            <span>Data User</span>
                        </Link>

                        <div className="menu-section">SISTEM</div>

                        <Link
                            to="/admin/laporan"
                            className={isActive('/admin/laporan') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-file-earmark-bar-graph"></i>
                            <span>Laporan</span>
                        </Link>

                        <Link
                            to="/admin/audit-log"
                            className={isActive('/admin/audit-log') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-journal-text"></i>
                            <span>Audit Log</span>
                        </Link>
                    </>
                ) : (
                    <>
                        <div className="menu-section">MENU</div>

                        <Link
                            to="/dashboard"
                            className={isActive('/dashboard') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </Link>

                        <Link
                            to="/pemesanan/create"
                            className={isActive('/pemesanan/create') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-calendar-plus"></i>
                            <span>Pemesanan</span>
                        </Link>

                        <Link
                            to="/kalender"
                            className={isActive('/kalender') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-calendar3"></i>
                            <span>Kalender Ruangan</span>
                        </Link>

                        <Link
                            to="/pemesanan"
                            className={isActive('/pemesanan') && !isActive('/pemesanan/create') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-clock-history"></i>
                            <span>Riwayat</span>
                        </Link>

                        <Link
                            to="/notifications"
                            className={isActive('/notifications') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-bell"></i>
                            <span>Notifikasi</span>
                            {unreadCount > 0 && (
                                <small className="sidebar-badge" id="sidebarNotificationBadge">
                                    {unreadCount}
                                </small>
                            )}
                        </Link>

                        <Link
                            to="/profile"
                            className={isActive('/profile') ? 'active' : ''}
                            onClick={onCloseMobile}
                        >
                            <i className="bi bi-person-circle"></i>
                            <span>Profil</span>
                        </Link>
                    </>
                )}
            </nav>

            {/* Sidebar Footer */}
            <div className="sidebar-footer">
                <Link to="/profile" className="user-info-link" title="Lihat Profil" onClick={onCloseMobile}>
                    <div className="user-info">
                        <div
                            className="user-avatar-initials"
                            style={{
                                background: 'linear-gradient(135deg, #005baa, #003b73)',
                                color: '#ffffff',
                                fontWeight: 700,
                            }}
                        >
                            {getInitials(user?.name || user?.username)}
                        </div>
                        <div className="user-info-text">
                            <strong>{user?.name || user?.username}</strong>
                            <small>{role === 'admin' ? 'Administrator' : (user?.nama_unit || 'User')}</small>
                        </div>
                    </div>
                </Link>

                <button type="button" onClick={() => logout()} className="logout-button" title="Keluar">
                    <i className="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </div>
        </aside>
    );
};
