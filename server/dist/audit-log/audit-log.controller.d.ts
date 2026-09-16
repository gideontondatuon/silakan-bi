import { AuditLogService } from './audit-log.service';
export declare class AuditLogController {
    private service;
    constructor(service: AuditLogService);
    index(query: any): Promise<{
        status: string;
        data: {
            data: ({
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
            } & {
                id: bigint;
                created_at: Date | null;
                updated_at: Date | null;
                user_id: bigint | null;
                keterangan: string | null;
                aksi: string;
                modul: string;
            })[];
            total: number;
            current_page: number;
            per_page: number;
            last_page: number;
        };
    }>;
}
