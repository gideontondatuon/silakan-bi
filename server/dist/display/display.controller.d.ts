import { DisplayService } from './display.service';
export declare class DisplayController {
    private displayService;
    constructor(displayService: DisplayService);
    apiData(jenis?: string, type?: string): Promise<{
        status: string;
        timestamp: string;
        live_count: number;
        live: {
            id: number;
            kode: any;
            ruangan: any;
            judul: any;
            unit: any;
            pic: any;
            jenis_kegiatan: any;
            waktu: string;
            end_time: string;
            status: any;
        }[];
        today: {
            id: number;
            kode: any;
            ruangan: any;
            judul: any;
            unit: any;
            pic: any;
            jenis_kegiatan: any;
            waktu: string;
            end_time: string;
            status: any;
        }[];
        ruangan: {
            id: bigint;
            created_at: Date | null;
            updated_at: Date | null;
            status: import(".prisma/client").$Enums.ruangan_status;
            nama_ruangan: string;
            kapasitas: number;
            lokasi: string;
        }[];
        data: {
            server_time: string;
            server_date: string;
            berlangsung: {
                id: number;
                kode: any;
                ruangan: any;
                judul: any;
                unit: any;
                pic: any;
                jenis_kegiatan: any;
                waktu: string;
                end_time: string;
                status: any;
            }[];
            mendatang: {
                id: number;
                kode: any;
                ruangan: any;
                judul: any;
                unit: any;
                pic: any;
                jenis_kegiatan: any;
                waktu: string;
                end_time: string;
                status: any;
            }[];
            ruangan: {
                id: bigint;
                created_at: Date | null;
                updated_at: Date | null;
                status: import(".prisma/client").$Enums.ruangan_status;
                nama_ruangan: string;
                kapasitas: number;
                lokasi: string;
            }[];
        };
    }>;
}
