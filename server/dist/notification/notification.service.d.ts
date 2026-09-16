import { PrismaService } from '../prisma/prisma.service';
export declare class NotificationService {
    private prisma;
    constructor(prisma: PrismaService);
    index(userId: number, query: any): Promise<{
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
    markAsRead(userId: number, id: string): Promise<{
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
    readAll(userId: number): Promise<{
        status: string;
        message: string;
    }>;
    destroy(userId: number, id: string): Promise<{
        status: string;
        message: string;
    }>;
    destroyAll(userId: number): Promise<{
        status: string;
        message: string;
    }>;
    sendNotification(params: {
        userId: number;
        type: string;
        judul: string;
        pesan: string;
        pemesananId?: number | bigint;
        url?: string;
    }): Promise<{
        id: string;
        created_at: Date | null;
        updated_at: Date | null;
        data: string;
        type: string;
        notifiable_type: string;
        notifiable_id: bigint;
        read_at: Date | null;
    }>;
    notifyAdmins(params: {
        type: string;
        judul: string;
        pesan: string;
        pemesananId?: number | bigint;
        url?: string;
    }): Promise<{
        id: string;
        created_at: Date | null;
        updated_at: Date | null;
        data: string;
        type: string;
        notifiable_type: string;
        notifiable_id: bigint;
        read_at: Date | null;
    }[]>;
    liveSync(user: any): Promise<{
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
}
