import { PemesananService } from './pemesanan.service';
import { CreatePemesananDto } from './dto/create-pemesanan.dto';
export declare const disposisiStorage: import("multer").StorageEngine;
export declare class PemesananController {
    private pemesananService;
    constructor(pemesananService: PemesananService);
    index(req: any, query: any): Promise<{
        status: string;
        data: {
            data: any[];
            current_page: number;
            per_page: number;
            total: number;
            last_page: number;
        };
    }>;
    checkConflict(query: any): Promise<{
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
    getUnits(): Promise<{
        status: string;
        data: {
            id: number;
            nama_unit: string;
            kode_unit: string;
            role: import(".prisma/client").$Enums.users_role;
        }[];
    }>;
    show(id: number, req: any): Promise<{
        status: string;
        data: any;
    }>;
    store(dto: CreatePemesananDto, req: any, file?: Express.Multer.File): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    cancel(id: number, req: any): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    selesaiAwal(id: number, req: any): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
}
