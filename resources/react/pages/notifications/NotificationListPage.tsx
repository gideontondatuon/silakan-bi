import React, { useEffect, useState } from 'react';
import { notificationService } from '../../services/notificationService';
import { useNotifications } from '../../context/NotificationContext';
import { NotificationItem, PaginatedData } from '../../types';
import { Modal } from '../../components/common/Modal';

export const NotificationListPage: React.FC = () => {
    const { refreshNotifications } = useNotifications();
    const [data, setData] = useState<PaginatedData<NotificationItem> | null>(null);
    const [loading, setLoading] = useState<boolean>(true);
    const [currentPage, setCurrentPage] = useState<number>(1);
    const [selectedDeleteId, setSelectedDeleteId] = useState<string | null>(null);
    const [showDeleteAllModal, setShowDeleteAllModal] = useState<boolean>(false);
    const [actionLoading, setActionLoading] = useState<boolean>(false);

    const loadData = async (page: number = 1) => {
        setLoading(true);
        try {
            const res = await notificationService.getNotifications(page);
            if (res.status === 'success') {
                setData(res.data);
                setCurrentPage(page);
            }
        } catch (err) {
            console.error('Failed to load notifications:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadData(currentPage);
    }, [currentPage]);

    const handleMarkAllRead = async () => {
        try {
            await notificationService.markAllAsRead();
            await refreshNotifications();
            loadData(currentPage);
        } catch (err) {
            console.error('Failed to mark all as read:', err);
        }
    };

    const handleItemClick = async (notif: NotificationItem) => {
        if (!notif.read_at) {
            try {
                await notificationService.markAsRead(notif.id);
                await refreshNotifications();
                // update local state
                if (data) {
                    setData({
                        ...data,
                        data: data.data.map(item => item.id === notif.id ? { ...item, read_at: new Date().toISOString() } : item)
                    });
                }
            } catch (err) {
                console.error('Failed to mark notification read:', err);
            }
        }
    };

    const handleDeleteSingle = async () => {
        if (!selectedDeleteId) return;
        setActionLoading(true);
        try {
            await notificationService.deleteNotification(selectedDeleteId);
            await refreshNotifications();
            setSelectedDeleteId(null);
            loadData(currentPage);
        } catch (err) {
            console.error('Failed to delete notification:', err);
        } finally {
            setActionLoading(false);
        }
    };

    const handleDeleteAll = async () => {
        setActionLoading(true);
        try {
            await notificationService.deleteAllNotifications();
            await refreshNotifications();
            setShowDeleteAllModal(false);
            loadData(1);
        } catch (err) {
            console.error('Failed to delete all notifications:', err);
        } finally {
            setActionLoading(false);
        }
    };

    return (
        <div>
            <div className="dashboard-header">
                <div>
                    <h1><i className="bi bi-bell-fill" style={{ color: '#005baa', marginRight: '8px' }}></i>Notifikasi</h1>
                    <p>Riwayat informasi dan aktivitas pemesanan ruangan Anda.</p>
                </div>
                {data && data.total > 0 && (
                    <div style={{ display: 'flex', gap: '10px', alignItems: 'center', flexWrap: 'wrap' }}>
                        <button
                            type="button"
                            onClick={handleMarkAllRead}
                            className="btn-secondary"
                        >
                            <i className="bi bi-check-all" style={{ fontSize: '18px' }}></i> Tandai Semua Dibaca
                        </button>
                        <button
                            type="button"
                            onClick={() => setShowDeleteAllModal(true)}
                            className="btn-secondary"
                            style={{ color: '#dc2626', borderColor: '#fecdd3', background: '#fff1f2' }}
                        >
                            <i className="bi bi-trash-fill" style={{ color: '#dc2626' }}></i> Hapus Semua
                        </button>
                    </div>
                )}
            </div>

            <div className="dashboard-section">
                <div className="section-header">
                    <h2><i className="bi bi-inbox-fill"></i> Daftar Notifikasi</h2>
                    {data && data.total > 0 && (
                        <span className="badge badge-primary">{data.total} Notifikasi</span>
                    )}
                </div>

                {loading ? (
                    <div style={{ padding: '40px', textAlign: 'center', color: '#64748b' }}>
                        <i className="bi bi-arrow-repeat spin" style={{ fontSize: '24px', display: 'block', marginBottom: '8px' }}></i>
                        Memuat data notifikasi...
                    </div>
                ) : (
                    <div className="notification-list" style={{ padding: '20px' }}>
                        {data && data.data.length > 0 ? (
                            data.data.map((notification) => {
                                const judul = notification.data?.judul || 'Notifikasi';
                                const pesan = notification.data?.pesan || '';
                                const waktu = notification.data?.waktu || '';
                                const isUnread = !notification.read_at;

                                let iconClass = 'bi bi-bell-fill';
                                let iconColor = '#005baa';

                                if (judul.toLowerCase().includes('disetujui')) {
                                    iconClass = 'bi bi-check-circle-fill';
                                    iconColor = '#10b981';
                                } else if (judul.toLowerCase().includes('ditolak')) {
                                    iconClass = 'bi bi-x-circle-fill';
                                    iconColor = '#ef4444';
                                }

                                return (
                                    <div key={notification.id} className="notification-card-wrapper" style={{ position: 'relative', marginBottom: '12px' }}>
                                        <div
                                            onClick={() => handleItemClick(notification)}
                                            className={`notification-card ${isUnread ? 'unread' : 'read'}`}
                                            style={{ paddingRight: '60px', cursor: 'pointer' }}
                                        >
                                            <div className="notification-card-icon">
                                                <i className={iconClass} style={{ color: iconColor }}></i>
                                            </div>

                                            <div className="notification-card-body">
                                                <div className="notification-card-top">
                                                    <strong className="notification-card-title">{judul}</strong>
                                                    {isUnread ? (
                                                        <span className="badge badge-warning">
                                                            <i className="bi bi-circle-fill" style={{ fontSize: '7px' }}></i> Baru
                                                        </span>
                                                    ) : (
                                                        <span className="badge badge-secondary">
                                                            <i className="bi bi-check2"></i> Dibaca
                                                        </span>
                                                    )}
                                                </div>
                                                <p className="notification-card-message">{pesan}</p>
                                                <small className="notification-card-time">
                                                    <i className="bi bi-clock"></i> {waktu}
                                                </small>
                                            </div>
                                        </div>

                                        {/* Single Delete Action */}
                                        <button
                                            type="button"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                setSelectedDeleteId(notification.id);
                                            }}
                                            style={{
                                                position: 'absolute',
                                                right: '16px',
                                                top: '50%',
                                                transform: 'translateY(-50%)',
                                                zIndex: 10,
                                                background: 'none',
                                                border: 'none',
                                                color: '#94a3b8',
                                                cursor: 'pointer',
                                                padding: '8px',
                                                borderRadius: '8px',
                                                transition: 'all 0.2s ease',
                                            }}
                                            onMouseEnter={(e) => {
                                                e.currentTarget.style.color = '#ef4444';
                                                e.currentTarget.style.background = '#fff1f2';
                                            }}
                                            onMouseLeave={(e) => {
                                                e.currentTarget.style.color = '#94a3b8';
                                                e.currentTarget.style.background = 'none';
                                            }}
                                            title="Hapus Notifikasi Ini"
                                        >
                                            <i className="bi bi-trash-fill" style={{ fontSize: '16px' }}></i>
                                        </button>
                                    </div>
                                );
                            })
                        ) : (
                            <div className="empty-state">
                                <i className="bi bi-bell-slash"></i>
                                <p>Belum ada notifikasi.</p>
                            </div>
                        )}
                    </div>
                )}

                {data && data.last_page > 1 && (
                    <div style={{ padding: '16px 24px', borderTop: '1px solid #f1f5f9', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                        <span style={{ fontSize: '13px', color: '#64748b' }}>
                            Menampilkan halaman {data.current_page} dari {data.last_page}
                        </span>
                        <div style={{ display: 'flex', gap: '8px' }}>
                            <button
                                disabled={currentPage <= 1}
                                onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                                className="btn-secondary"
                                style={{ padding: '6px 12px', fontSize: '12.5px' }}
                            >
                                Sebelumnya
                            </button>
                            <button
                                disabled={currentPage >= data.last_page}
                                onClick={() => setCurrentPage(prev => Math.min(prev + 1, data.last_page))}
                                className="btn-secondary"
                                style={{ padding: '6px 12px', fontSize: '12.5px' }}
                            >
                                Berikutnya
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* Confirm Single Delete Modal */}
            <Modal
                isOpen={!!selectedDeleteId}
                onClose={() => setSelectedDeleteId(null)}
                title="Hapus Notifikasi"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setSelectedDeleteId(null)} disabled={actionLoading}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDeleteSingle} disabled={actionLoading}>
                            {actionLoading ? 'Menghapus...' : 'Ya, Hapus'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>Apakah Anda yakin ingin menghapus notifikasi ini?</p>
            </Modal>

            {/* Confirm Delete All Modal */}
            <Modal
                isOpen={showDeleteAllModal}
                onClose={() => setShowDeleteAllModal(false)}
                title="Hapus Semua Notifikasi"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setShowDeleteAllModal(false)} disabled={actionLoading}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDeleteAll} disabled={actionLoading}>
                            {actionLoading ? 'Menghapus...' : 'Ya, Hapus Semua'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>Apakah Anda yakin ingin menghapus seluruh riwayat notifikasi?</p>
            </Modal>
        </div>
    );
};
