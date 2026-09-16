import { PrismaService } from '../prisma/prisma.service';
export declare class RuanganService {
    private prisma;
    constructor(prisma: PrismaService);
    findAll(query?: any): Promise<{
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
    findAdmin(query?: any): Promise<{
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
    findOne(id: number): Promise<{
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
    create(body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_ruangan: string;
        };
    }>;
    update(id: number, body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_ruangan: string;
        };
    }>;
    remove(id: number): Promise<{
        status: string;
        message: string;
    }>;
    getLayouts(ruanganId: number): Promise<{
        status: string;
        data: {
            id: number;
            ruangan_id: number;
            nama_layout: string;
            kapasitas_layout: number;
        }[];
    }>;
    findAllLayouts(query?: any): Promise<{
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
    findLayout(id: number): Promise<{
        status: string;
        data: {
            id: number;
            ruangan_id: number;
            nama_layout: string;
            kapasitas_layout: number;
            nama_ruangan: string;
        };
    }>;
    createLayout(body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_layout: string;
        };
    }>;
    updateLayout(id: number, body: any): Promise<{
        status: string;
        message: string;
        data: {
            id: number;
            nama_layout: string;
        };
    }>;
    deleteLayout(id: number): Promise<{
        status: string;
        message: string;
    }>;
}
