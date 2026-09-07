import api from './api';
import { ApiResponse, NotificationItem, PaginatedData } from '../types';

export interface LiveSyncData {
    status: string;
    unread_count: number;
    notifications: NotificationItem[];
    extra: {
        is_admin: boolean;
        count_pending?: number;
        count_disetujui?: number;
        count_total?: number;
        latest_booking_id?: number;
        latest_updated_at?: number;
        count_my_pending?: number;
        latest_my_booking_id?: number;
        latest_my_updated_at?: number;
    };
    server_time: string;
}

export const notificationService = {
    async getNotifications(page: number = 1): Promise<ApiResponse<PaginatedData<NotificationItem>>> {
        const response = await api.get<ApiResponse<PaginatedData<NotificationItem>>>('/notifications', {
            params: { page },
        });
        return response.data;
    },

    async liveSync(): Promise<LiveSyncData> {
        const response = await api.get<LiveSyncData>('/notifications/live-sync');
        return response.data;
    },

    async markAsRead(id: string): Promise<ApiResponse<any>> {
        const response = await api.post<ApiResponse<any>>(`/notifications/${id}/read`);
        return response.data;
    },

    async markAllAsRead(): Promise<ApiResponse<void>> {
        const response = await api.post<ApiResponse<void>>('/notifications/read-all');
        return response.data;
    },

    async deleteNotification(id: string): Promise<ApiResponse<void>> {
        const response = await api.delete<ApiResponse<void>>(`/notifications/${id}`);
        return response.data;
    },

    async deleteAllNotifications(): Promise<ApiResponse<void>> {
        const response = await api.delete<ApiResponse<void>>('/notifications');
        return response.data;
    },
};
