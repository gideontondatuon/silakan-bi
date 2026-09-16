import { AdminService } from './admin.service';
import { PemesananService } from '../pemesanan/pemesanan.service';
export declare class AdminController {
    private adminService;
    private pemesananService;
    constructor(adminService: AdminService, pemesananService: PemesananService);
    approvalIndex(q: any): Promise<{
        status: string;
        data: {
            items: {
                data: unknown[];
                total: number;
                current_page: number;
                per_page: number;
                last_page: number;
            };
            counts: {
                pending: number;
                disetujui: number;
                selesai: number;
                semua: number;
            };
        };
    }>;
    approvalStore(body: any, req: any, file?: Express.Multer.File): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    approvalShow(id: number): Promise<{
        status: string;
        data: any;
    }>;
    approve(id: number, body: any, req: any): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    reject(id: number, body: any, req: any): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    approvalSelesaiAwal(id: number, req: any): Promise<{
        status: string;
        message: string;
        data: any;
    }>;
    approvalDestroy(id: number): Promise<{
        status: string;
        message: string;
    }>;
    userIndex(q: any): Promise<{
        status: string;
        data: {
            admins: {
                department: {
                    nama_unit: string;
                    kode_unit: string;
                    id: bigint;
                    created_at: Date | null;
                    updated_at: Date | null;
                    status: import(".prisma/client").$Enums.departments_status;
                };
                departments: {
                    nama_unit: string;
                    kode_unit: string;
                    id: bigint;
                    created_at: Date | null;
                    updated_at: Date | null;
                    status: import(".prisma/client").$Enums.departments_status;
                };
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
            }[];
            users: {
                data: {
                    department: {
                        nama_unit: string;
                        kode_unit: string;
                        id: bigint;
                        created_at: Date | null;
                        updated_at: Date | null;
                        status: import(".prisma/client").$Enums.departments_status;
                    };
                    departments: {
                        nama_unit: string;
                        kode_unit: string;
                        id: bigint;
                        created_at: Date | null;
                        updated_at: Date | null;
                        status: import(".prisma/client").$Enums.departments_status;
                    };
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
                }[];
                total: number;
                current_page: number;
                per_page: number;
                last_page: number;
                from: number;
                to: number;
            };
            departments: {
                nama_unit: string;
                kode_unit: string;
                id: bigint;
                created_at: Date | null;
                updated_at: Date | null;
                status: import(".prisma/client").$Enums.departments_status;
            }[];
        };
    }>;
    userShow(id: number): Promise<{
        status: string;
        data: {
            department: {
                nama_unit: string;
                kode_unit: string;
                id: bigint;
                created_at: Date | null;
                updated_at: Date | null;
                status: import(".prisma/client").$Enums.departments_status;
            };
            departments: {
                nama_unit: string;
                kode_unit: string;
                id: bigint;
                created_at: Date | null;
                updated_at: Date | null;
                status: import(".prisma/client").$Enums.departments_status;
            };
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
    }>;
    userStore(body: any): Promise<{
        status: string;
        message: string;
        data: {
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
    }>;
    userUpdate(id: number, body: any): Promise<{
        status: string;
        message: string;
        data: {
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
    }>;
    userDestroy(id: number): Promise<{
        status: string;
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
    getWhatsappStatus(): {
        status: string;
        data: {
            provider: string;
            enabled: boolean;
            has_token: boolean;
            token_masked: string;
            api_url: string;
            admin_wa_number: string;
        };
    };
    testWhatsapp(body: any): Promise<{
        status: string;
        message: string;
    }>;
}
