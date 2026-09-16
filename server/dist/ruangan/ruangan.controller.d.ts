import { RuanganService } from './ruangan.service';
export declare class RuanganController {
    private ruanganService;
    constructor(ruanganService: RuanganService);
    index(query: any): Promise<{
        status: string;
        data: {
            id: number;
            nama_ruangan: string;
            kapasitas: number;
            status: import(".prisma/client").$Enums.ruangan_status;
            lokasi: string;
            layouts: {
                id: number;
                nama_layout: string;
                kapasitas_layout: number;
            }[];
        }[];
    }>;
    adminIndex(query: any): Promise<{
        status: string;
        data: {
            data: {
                id: number;
                nama_ruangan: string;
                kapasitas: number;
                status: import(".prisma/client").$Enums.ruangan_status;
                lokasi: string;
                layouts: {
                    id: number;
                    nama_layout: string;
                    kapasitas_layout: number;
                }[];
            }[];
            total: number;
            current_page: number;
            last_page: number;
            per_page: number;
        };
    }>;
    adminShow(id: number): Promise<{
        status: string;
        data: {
            id: number;
            nama_ruangan: string;
            kapasitas: number;
            status: import(".prisma/client").$Enums.ruangan_status;
            lokasi: string;
            layouts: {
                id: number;
                nama_layout: string;
                kapasitas_layout: number;
            }[];
        };
    }>;
    show(id: number): Promise<{
        status: string;
        data: {
            id: number;
            nama_ruangan: string;
            kapasitas: number;
            status: import(".prisma/client").$Enums.ruangan_status;
            lokasi: string;
            layouts: {
                id: number;
                nama_layout: string;
                kapasitas_layout: number;
            }[];
        };
    }>;
    layouts(id: number): Promise<{
        status: string;
        data: {
            id: number;
            ruangan_id: number;
            nama_layout: string;
            kapasitas_layout: number;
        }[];
    }>;
    layoutsById(id: number): Promise<{
        status: string;
        data: {
            id: number;
            ruangan_id: number;
            nama_layout: string;
            kapasitas_layout: number;
        }[];
    }>;
    adminCreate(body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_ruangan: string;
        };
    }>;
    adminUpdate(id: number, body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_ruangan: string;
        };
    }>;
    adminRemove(id: number): Promise<{
        status: string;
        message: string;
    }>;
    adminLayoutList(query: any): Promise<{
        status: string;
        data: {
            data: {
                id: number;
                ruangan_id: number;
                nama_layout: string;
                kapasitas_layout: number;
                nama_ruangan: string;
            }[];
            total: number;
            current_page: number;
            last_page: number;
            per_page: number;
        };
    }>;
    adminLayoutDetail(id: number): Promise<{
        status: string;
        data: {
            id: number;
            ruangan_id: number;
            nama_layout: string;
            kapasitas_layout: number;
            nama_ruangan: string;
        };
    }>;
    adminCreateLayout(body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_layout: string;
        };
    }>;
    adminUpdateLayout(id: number, body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_layout: string;
        };
    }>;
    adminDeleteLayout(id: number): Promise<{
        status: string;
        message: string;
    }>;
}
