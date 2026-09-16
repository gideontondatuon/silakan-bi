import { PrismaService } from '../prisma/prisma.service';
import { CreatePemesananDto } from './dto/create-pemesanan.dto';
export declare function formatTimeStr(val: any): string;
import { NotificationService } from '../notification/notification.service';
import { WhatsappService } from '../whatsapp/whatsapp.service';
export declare class PemesananService {
    private prisma;
    private notificationService;
    private whatsapp;
    constructor(prisma: PrismaService, notificationService: NotificationService, whatsapp: WhatsappService);
    formatTimeStr(val: any): string;
    markFinishedAgendas(): Promise<number>;
    generateKodePemesanan(rawUnitCode?: string): Promise<string>;
    checkConflict(data: {
        ruangan_id: number;
        tanggal_kegiatan: string;
        waktu_mulai: string;
        waktu_selesai: string;
        exclude_id?: number;
        include_pending?: boolean;
    }): Promise<({
        users: {
            username: string | null;
            password: string;
            name: string | null;
            email: string | null;
            no_wa: string | null;
            nama_unit: string;
            kode_unit: string;
            id: bigint;
            password_plain: string | null;
            role: import(".prisma/client").$Enums.users_role;
            department_id: bigint | null;
            remember_token: string | null;
            created_at: Date | null;
            updated_at: Date | null;
        };
        ruangan: {
            id: bigint;
            created_at: Date | null;
            updated_at: Date | null;
            status: import(".prisma/client").$Enums.ruangan_status;
            nama_ruangan: string;
            kapasitas: number;
            lokasi: string;
        };
    } & {
        id: bigint;
        created_at: Date | null;
        updated_at: Date | null;
        user_id: bigint;
        ruangan_id: bigint;
        layout_ruangan_id: bigint | null;
        tanggal_kegiatan: Date;
        waktu_mulai: Date;
        waktu_selesai: Date;
        judul_kegiatan: string;
        jenis_kegiatan: string;
        pic_kegiatan: string;
        jenis_pic: import(".prisma/client").$Enums.pemesanan_jenis_pic;
        no_wa_pic: string | null;
        jumlah_tamu: number;
        keterangan_layout: string | null;
        catatan_user: string | null;
        kode_pemesanan: string;
        file_disposisi: string | null;
        status: import(".prisma/client").$Enums.pemesanan_status;
        reschedule_status: import(".prisma/client").$Enums.pemesanan_reschedule_status;
        reschedule_tanggal: Date | null;
        reschedule_waktu_mulai: Date | null;
        reschedule_waktu_selesai: Date | null;
        reschedule_alasan: string | null;
        approved_at: Date | null;
        rejected_at: Date | null;
        alasan_penolakan: string | null;
        cancelled_at: Date | null;
        alasan_pembatalan: string | null;
        catatan_admin: string | null;
        approved_by: bigint | null;
        rejected_by: bigint | null;
        cancelled_by: bigint | null;
    })[]>;
    index(userId: number, query: any): Promise<{
        status: string;
        data: {
            data: any[];
            current_page: number;
            per_page: number;
            total: number;
            last_page: number;
        };
    }>;
    store(dto: CreatePemesananDto, user: any, filePath?: string): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    show(id: number, user: any): Promise<{
        status: string;
        data: any;
    }>;
    cancel(id: number, user: any): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    selesaiAwal(id: number, user: any): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    checkConflictApi(query: any): Promise<{
        status: string;
        conflict: boolean;
        count: number;
        conflicts: {
            id: any;
            kode_pemesanan: any;
            judul_kegiatan: any;
            unit: any;
            pic: any;
            waktu_mulai: string;
            waktu_selesai: string;
            status: any;
        }[];
        message: string;
    }>;
    formatPemesanan: (p: any) => any;
    private computeDurasi;
    private formatDurasi;
    getUnits(): Promise<{
        status: string;
        data: {
            id: number;
            nama_unit: string;
            kode_unit: string;
            role: import(".prisma/client").$Enums.users_role;
        }[];
    }>;
}
