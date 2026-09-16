import { Response } from 'express';
import { LaporanService } from './laporan.service';
export declare class LaporanController {
    private laporanService;
    constructor(laporanService: LaporanService);
    index(query: any): Promise<{
        status: string;
        data: {
            summary: {
                total: number;
                disetujui: number;
                ditolak: number;
            };
            items: {
                data: {
                    waktu_mulai: string;
                    waktu_selesai: string;
                    tanggal_kegiatan: string;
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
                    layout_ruangan: {
                        id: bigint;
                        created_at: Date | null;
                        updated_at: Date | null;
                        ruangan_id: bigint | null;
                        nama_layout: string;
                        kapasitas_layout: number | null;
                    };
                    id: bigint;
                    created_at: Date | null;
                    updated_at: Date | null;
                    user_id: bigint;
                    ruangan_id: bigint;
                    layout_ruangan_id: bigint | null;
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
                }[];
                current_page: number;
                last_page: number;
                per_page: number;
                total: number;
                from: number;
                to: number;
            };
            filter_options: {
                ruangan: {
                    id: bigint;
                    nama_ruangan: string;
                }[];
                users: {
                    id: bigint;
                    name: string;
                    nama_unit: string;
                }[];
            };
        };
    }>;
    exportExcel(query: any, res: Response): Promise<void>;
}
