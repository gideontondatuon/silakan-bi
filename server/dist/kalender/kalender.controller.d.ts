import { KalenderService } from './kalender.service';
export declare class KalenderController {
    private kalenderService;
    constructor(kalenderService: KalenderService);
    index(req: any, query: any): Promise<{
        status: string;
        data: {
            ruangan: {
                id: number;
                nama_ruangan: string;
                kapasitas: number;
                status: import(".prisma/client").$Enums.ruangan_status;
                lokasi: string;
            }[];
            stats: {
                total_ruangan: number;
                jadwal_aktif: number;
                akan_datang: number;
            };
            upcoming: {
                id: number;
                kode_pemesanan: string;
                judul_kegiatan: string;
                tanggal_kegiatan: string;
                waktu_mulai: string;
                waktu_selesai: string;
                pic_kegiatan: string;
                nama_ruangan: string;
                nama_unit: string;
            }[];
        };
    }>;
    events(req: any, query: any): Promise<{
        status: string;
        data: ({
            id: string;
            title: string;
            start: string;
            end: string;
            color: string;
            type: string;
            extendedProps: {
                id: number;
                kode_pemesanan: string;
                judul: string;
                ruangan: string;
                layout: string;
                tanggal: string;
                waktu: string;
                pic: string;
                no_wa_pic: string;
                unit: string;
                jumlah_tamu: number;
                status: import(".prisma/client").$Enums.pemesanan_status;
                jenis_kegiatan: string;
            };
        } | {
            id: string;
            title: string;
            start: string;
            allDay: boolean;
            color: string;
            type: string;
            extendedProps: {
                id: number;
                keterangan: string;
                tanggal: string;
                kategori: string;
                kategori_label: string;
                is_nasional: boolean;
            };
        })[];
    }>;
}
