import React, { useEffect, useRef, useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { useNotifications } from '../../context/NotificationContext';

interface NavbarProps {
    onToggleSidebar: () => void;
}

export const Navbar: React.FC<NavbarProps> = ({ onToggleSidebar }) => {
    const { user, role, logout } = useAuth();
    const { unreadCount, notifications } = useNotifications();

    const [isNotifOpen, setIsNotifOpen] = useState(false);
    const [isProfileOpen, setIsProfileOpen] = useState(false);

    const [currentTime, setCurrentTime] = useState('--:--:--');
    const [currentDate, setCurrentDate] = useState('-- --- ----');

    const notifRef = useRef<HTMLDivElement>(null);
    const profileRef = useRef<HTMLDivElement>(null);

    // Clock updater
    useEffect(() => {
        const updateClock = () => {
            const now = new Date();
            // Format time WITA
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            }) + ' WITA';

            // Format date
            const dateStr = now.toLocaleDateString('id-ID', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            });

            setCurrentTime(timeStr);
            setCurrentDate(dateStr);
        };

        updateClock();
        const timer = setInterval(updateClock, 1000);
        return () => clearInterval(timer);
    }, []);

    // Click outside to close dropdowns
    useEffect(() => {
        const handleClickOutside = (e: MouseEvent) => {
            if (notifRef.current && !notifRef.current.contains(e.target as Node)) {
                setIsNotifOpen(false);
            }
            if (profileRef.current && !profileRef.current.contains(e.target as Node)) {
                setIsProfileOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const getInitials = (name?: string | null) => {
        if (!name) return 'BI';
        const parts = name.trim().split(/\s+/);
        if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    };

    return (
        <header className="navbar">
            <div className="navbar-left">
                <button
                    type="button"
                    className="menu-toggle"
                    id="sidebarToggle"
                    title="Toggle Sidebar"
                    onClick={onToggleSidebar}
                >
                    <i className="bi bi-list"></i>
                </button>

                {/* Mobile Brand */}
                <Link to={role === 'admin' ? '/admin/dashboard' : '/dashboard'} className="mobile-brand">
                    <img src="/images/logo-bi2.png" alt="Bank Indonesia" className="mobile-brand-logo" />
                    <div className="mobile-brand-text">
                        <span className="mobile-brand-title">SILAKAN</span>
                        <span className="mobile-brand-sub">KPwBI Prov. Sulut</span>
                    </div>
                </Link>

                <div className="navbar-page-info" id="navbar-page-info">
                    <span className="navbar-page-title">SILAKAN</span>
                    <div className="navbar-breadcrumb">
                        <span>Kantor Perwakilan Bank Indonesia Sulawesi Utara</span>
                    </div>
                </div>
            </div>

            <div className="navbar-right">
                {/* Real-time Clock */}
                <div className="navbar-clock" id="navbar-clock">
                    <span className="navbar-clock-time" id="navbar-time">
                        {currentTime}
                    </span>
                    <span className="navbar-clock-date" id="navbar-date">
                        {currentDate}
                    </span>
                </div>

                {/* Notifications Dropdown */}
                <div className={`notification dropdown ${isNotifOpen ? 'open' : ''}`} ref={notifRef}>
                    <button
                        type="button"
                        className="notification-button"
                        id="navbarBellBtn"
                        title="Notifikasi"
                        onClick={() => setIsNotifOpen((prev) => !prev)}
                    >
                        <i className="bi bi-bell" id="navbarBellIcon"></i>
                        {unreadCount > 0 && (
                            <span className="notification-count" id="navbarNotificationCount">
                                {unreadCount}
                            </span>
                        )}
                    </button>

                    {isNotifOpen && (
                        <div
                            className="notification-panel show"
                            id="notificationPanel"
                            style={{ display: 'block' }}
                        >
                            <div className="notification-header">
                                <span>
                                    <i className="bi bi-bell-fill" style={{ color: '#005baa', marginRight: '6px' }}></i>
                                    Notifikasi
                                </span>
                                {unreadCount > 0 && (
                                    <span className="badge badge-primary" id="navbarNotificationBadge">
                                        {unreadCount} Baru
                                    </span>
                                )}
                            </div>

                            <div id="navbarNotificationList" style={{ maxHeight: '320px', overflowY: 'auto' }}>
                                {notifications.length > 0 ? (
                                    notifications.map((notif) => (
                                        <Link
                                            key={notif.id}
                                            to={notif.pemesanan_id ? (role === 'admin' ? `/admin/approval/${notif.pemesanan_id}` : `/pemesanan/${notif.pemesanan_id}`) : '/notifications'}
                                            className="notification-item"
                                            onClick={() => setIsNotifOpen(false)}
                                        >
                                            <div className="notification-icon">
                                                <i className="bi bi-calendar-event"></i>
                                            </div>
                                            <div className="notification-content">
                                                <strong>{notif.judul}</strong>
                                                <p>{notif.pesan}</p>
                                                <small>
                                                    <i className="bi bi-clock"></i> {notif.waktu}
                                                </small>
                                            </div>
                                        </Link>
                                    ))
                                ) : (
                                    <div className="notification-empty">
                                        <i className="bi bi-bell-slash"></i>
                                        <p>Tidak ada notifikasi baru.</p>
                                    </div>
                                )}
                            </div>

                            <div
                                className="notification-footer"
                                style={{
                                    padding: '10px 14px',
                                    textAlign: 'center',
                                    borderTop: '1px solid #f1f5f9',
                                    background: '#f8fafc',
                                    borderRadius: '0 0 12px 12px',
                                }}
                            >
                                <Link
                                    to="/notifications"
                                    onClick={() => setIsNotifOpen(false)}
                                    style={{
                                        fontSize: '12.5px',
                                        fontWeight: 700,
                                        color: '#005baa',
                                        textDecoration: 'none',
                                        display: 'inline-flex',
                                        alignItems: 'center',
                                        gap: '6px',
                                    }}
                                >
                                    <i className="bi bi-arrow-right-circle-fill"></i> Lihat Semua Notifikasi
                                </Link>
                            </div>
                        </div>
                    )}
                </div>

                {/* Profile Avatar & Dropdown */}
                <div className={`profile ${isProfileOpen ? 'open' : ''}`} ref={profileRef}>
                    <div
                        className="profile-link"
                        style={{ cursor: 'pointer' }}
                        onClick={() => setIsProfileOpen((prev) => !prev)}
                        title="Profil"
                    >
                        <div
                            className="profile-avatar"
                            style={{
                                background: 'linear-gradient(135deg, #005baa, #003b73)',
                                color: '#ffffff',
                                fontWeight: 700,
                            }}
                        >
                            {getInitials(user?.name || user?.username)}
                        </div>
                        <div className="profile-info">
                            <strong>{user?.name || user?.username}</strong>
                            <small>{role === 'admin' ? 'Administrator' : (user?.nama_unit || 'User')}</small>
                        </div>
                    </div>

                    {isProfileOpen && (
                        <div
                            className="profile-menu show"
                            style={{
                                display: 'block',
                                position: 'absolute',
                                right: 0,
                                top: '100%',
                                marginTop: '8px',
                                background: '#ffffff',
                                border: '1px solid #e2e8f0',
                                borderRadius: '12px',
                                boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1)',
                                minWidth: '170px',
                                padding: '6px 0',
                                zIndex: 1000,
                            }}
                        >
                            <Link
                                to="/profile"
                                onClick={() => setIsProfileOpen(false)}
                                style={{
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '8px',
                                    padding: '8px 16px',
                                    color: '#334155',
                                    textDecoration: 'none',
                                    fontSize: '13px',
                                    fontWeight: 500,
                                }}
                            >
                                <i className="bi bi-person"></i>
                                <span>Profil Akun</span>
                            </Link>
                            <button
                                type="button"
                                onClick={() => {
                                    setIsProfileOpen(false);
                                    logout();
                                }}
                                style={{
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '8px',
                                    padding: '8px 16px',
                                    color: '#dc2626',
                                    background: 'none',
                                    border: 'none',
                                    width: '100%',
                                    textAlign: 'left',
                                    cursor: 'pointer',
                                    fontSize: '13px',
                                    fontWeight: 500,
                                }}
                            >
                                <i className="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
};
