import { PrismaService } from '../prisma/prisma.service';
import { PemesananService } from '../pemesanan/pemesanan.service';
export declare class DashboardService {
    private prisma;
    private pemesananService;
    constructor(prisma: PrismaService, pemesananService: PemesananService);
    user(userId: number): Promise<{
        status: string;
        data: {
            stats: {
                total: number;
                pending: number;
                approved: number;
                upcoming: number;
            };
            pemesanan_terbaru: unknown[];
            kegiatan_hari_ini: unknown[];
            kegiatan_berlangsung: unknown[];
        };
    }>;
    admin(): Promise<{
        status: string;
        data: {
            stats: {
                total_ruangan: number;
                total_pemesanan: number;
                waiting_approval: number;
                disetujui: number;
                ditolak: number;
                pemesanan_bulan_ini: number;
            };
            kegiatan_hari_ini: unknown[];
            kegiatan_berlangsung: unknown[];
            agenda_mendatang: unknown[];
            waiting_list: unknown[];
            ruangan_terpopuler: (import(".prisma/client").Prisma.PickEnumerable<import(".prisma/client").Prisma.PemesananGroupByOutputType, "ruangan_id"[]> & {
                _count: {
                    id: number;
                };
            })[];
            aktivitas_terbaru: unknown[];
            charts: {
                monthly: {
                    labels: string[];
                    data: number[];
                };
                popular_rooms: {
                    labels: string[];
                    data: number[];
                };
                unit_distribution: {
                    labels: string[];
                    data: number[];
                };
            };
        };
    }>;
    kegiatanBerlangsung(): Promise<{
        status: string;
        data: unknown[];
    }>;
}
