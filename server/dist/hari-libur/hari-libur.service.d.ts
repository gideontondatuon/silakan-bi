import { PrismaService } from '../prisma/prisma.service';
export declare class HariLiburService {
    private prisma;
    constructor(prisma: PrismaService);
    index(query?: any): Promise<{
        status: string;
        data: {
            items: {
                data: {
                    id: number;
                    tanggal: string;
                    created_at: Date | null;
                    updated_at: Date | null;
                    keterangan: string;
                    kategori: string;
                    is_nasional: boolean;
                }[];
                total: number;
                current_page: number;
                per_page: number;
                last_page: number;
                from: number;
                to: number;
            };
            available_years: number[];
        };
    }>;
    syncApi(year: number): Promise<{
        status: string;
        message: string;
        data: {
            count: number;
            year: number;
        };
    }>;
    store(body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            tanggal: string;
            created_at: Date | null;
            updated_at: Date | null;
            keterangan: string;
            kategori: string;
            is_nasional: boolean;
        };
    }>;
    remove(id: number): Promise<{
        status: string;
        message: string;
    }>;
    allDates(): Promise<{
        status: string;
        data: {
            id: number;
            tanggal: string;
            keterangan: string;
            kategori: string;
            is_nasional: boolean;
        }[];
    }>;
}
