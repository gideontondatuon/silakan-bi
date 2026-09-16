import { HariLiburService } from './hari-libur.service';
export declare class HariLiburController {
    private service;
    constructor(service: HariLiburService);
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
    index(query: any): Promise<{
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
    sync(body: any): Promise<{
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
}
