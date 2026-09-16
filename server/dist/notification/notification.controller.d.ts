import { NotificationService } from './notification.service';
export declare class NotificationController {
    private notificationService;
    constructor(notificationService: NotificationService);
    index(req: any, query: any): Promise<{
        status: string;
        data: {
            data: {
                data: any;
                id: string;
                created_at: Date | null;
                updated_at: Date | null;
                type: string;
                notifiable_type: string;
                notifiable_id: bigint;
                read_at: Date | null;
            }[];
            current_page: number;
            per_page: number;
            total: number;
            last_page: number;
        };
    }>;
    liveSync(req: any): Promise<{
        status: string;
        unread_count: number;
        notifications: {
            id: string;
            judul: any;
            pesan: any;
            waktu: any;
            pemesanan_id: any;
            created_at: string;
        }[];
        extra: any;
        server_time: string;
    }>;
    readAll(req: any): Promise<{
        status: string;
        message: string;
    }>;
    markAsRead(id: string, req: any): Promise<{
        status: string;
        message: string;
        data: {
            id: string;
            created_at: Date | null;
            updated_at: Date | null;
            data: string;
            type: string;
            notifiable_type: string;
            notifiable_id: bigint;
            read_at: Date | null;
        };
    }>;
    destroy(id: string, req: any): Promise<{
        status: string;
        message: string;
    }>;
    destroyAll(req: any): Promise<{
        status: string;
        message: string;
    }>;
}
