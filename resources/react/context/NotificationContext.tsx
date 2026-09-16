import React, { createContext, useContext, useEffect, useRef, useState } from 'react';
import { NotificationItem } from '../types';
import { notificationService } from '../services/notificationService';
import { playNotificationChime } from '../utils/audio';
import { useAuth } from './AuthContext';

export interface ToastItem {
    id: string;
    title: string;
    message: string;
    time?: string;
    url?: string;
}

interface NotificationContextType {
    unreadCount: number;
    pendingCount: number;
    notifications: NotificationItem[];
    toasts: ToastItem[];
    showToast: (title: string, message: string, url?: string, time?: string) => void;
    dismissToast: (id: string) => void;
    refreshSync: () => Promise<void>;
    refreshNotifications: () => Promise<void>;
}

const NotificationContext = createContext<NotificationContextType | undefined>(undefined);

export const NotificationProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const { isAuthenticated, role } = useAuth();
    const [unreadCount, setUnreadCount] = useState<number>(0);
    const [pendingCount, setPendingCount] = useState<number>(0);
    const [notifications, setNotifications] = useState<NotificationItem[]>([]);
    const [toasts, setToasts] = useState<ToastItem[]>([]);

    const lastUnreadCount = useRef<number | null>(null);
    const lastPendingCount = useRef<number | null>(null);
    const isSyncing = useRef<boolean>(false);

    const showToast = (title: string, message: string, url?: string, time: string = 'Baru saja') => {
        const id = 'toast_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7);
        setToasts((prev) => [...prev, { id, title, message, url, time }]);

        // Auto remove after 6 seconds
        setTimeout(() => {
            dismissToast(id);
        }, 6000);
    };

    const dismissToast = (id: string) => {
        setToasts((prev) => prev.filter((t) => t.id !== id));
    };

    const performSync = async () => {
        if (!isAuthenticated || isSyncing.current) return;
        isSyncing.current = true;

        try {
            const data = await notificationService.liveSync();
            if (data.status === 'success') {
                const count = data.unread_count || 0;
                const extra = data.extra || {};
                const currentPending = extra.count_pending ?? 0;

                setUnreadCount(count);
                setPendingCount(currentPending);
                setNotifications(data.notifications || []);

                // Check for new incoming notification (compared to baseline)
                const hasNewNotif = lastUnreadCount.current !== null && count > lastUnreadCount.current;
                const hasNewPending = role === 'admin' && lastPendingCount.current !== null && currentPending > lastPendingCount.current;

                if (hasNewNotif || hasNewPending) {
                    playNotificationChime();
                    if (data.notifications && data.notifications.length > 0) {
                        const top = data.notifications[0];
                        const targetUrl = role === 'admin'
                            ? (top.pemesanan_id ? `/admin/approval/${top.pemesanan_id}` : '/admin/approval')
                            : (top.pemesanan_id ? `/pemesanan/${top.pemesanan_id}` : '/pemesanan');
                        showToast(top.judul || 'Notifikasi Baru', top.pesan || '', targetUrl, top.waktu);
                    } else if (hasNewPending) {
                        showToast(
                            'Pengajuan Pemesanan Baru',
                            'Terdapat pengajuan pemesanan ruangan baru yang menunggu verifikasi Anda.',
                            '/admin/approval',
                            'Baru saja'
                        );
                    }
                }

                lastUnreadCount.current = count;
                lastPendingCount.current = currentPending;
            }
        } catch (e) {
            // Silently ignore network sync errors
        } finally {
            isSyncing.current = false;
        }
    };

    useEffect(() => {
        if (!isAuthenticated) {
            setUnreadCount(0);
            setPendingCount(0);
            setNotifications([]);
            lastUnreadCount.current = null;
            lastPendingCount.current = null;
            return;
        }

        // Initial sync
        performSync();

        // 5-second interval sync for fast, responsive in-app notifications
        const interval = setInterval(performSync, 5000);
        return () => clearInterval(interval);
    }, [isAuthenticated, role]);

    return (
        <NotificationContext.Provider
            value={{
                unreadCount,
                pendingCount,
                notifications,
                toasts,
                showToast,
                dismissToast,
                refreshSync: performSync,
                refreshNotifications: performSync,
            }}
        >
            {children}
        </NotificationContext.Provider>
    );
};

export const useNotifications = (): NotificationContextType => {
    const context = useContext(NotificationContext);
    if (!context) {
        throw new Error('useNotifications must be used within a NotificationProvider');
    }
    return context;
};
