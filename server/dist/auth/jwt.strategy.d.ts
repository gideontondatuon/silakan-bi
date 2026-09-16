import { Strategy } from 'passport-jwt';
import { PrismaService } from '../prisma/prisma.service';
export interface JwtPayload {
    sub: number;
    username: string;
    role: string;
}
declare const JwtStrategy_base: new (...args: [opt: import("passport-jwt").StrategyOptionsWithRequest] | [opt: import("passport-jwt").StrategyOptionsWithoutRequest]) => Strategy & {
    validate(...args: any[]): unknown;
};
export declare class JwtStrategy extends JwtStrategy_base {
    private prisma;
    constructor(prisma: PrismaService);
    validate(payload: JwtPayload): Promise<{
        departments: {
            nama_unit: string;
            kode_unit: string;
            id: bigint;
            created_at: Date | null;
            updated_at: Date | null;
            status: import(".prisma/client").$Enums.departments_status;
        };
    } & {
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
    }>;
}
export {};
