import { JwtService } from '@nestjs/jwt';
import { PrismaService } from '../prisma/prisma.service';
import { LoginDto } from './dto/login.dto';
import { UpdateProfileDto, UpdatePasswordDto } from './dto/update-profile.dto';
export declare class AuthService {
    private prisma;
    private jwtService;
    constructor(prisma: PrismaService, jwtService: JwtService);
    login(dto: LoginDto): Promise<{
        status: string;
        message: string;
        data: {
            user: {
                id: any;
                name: any;
                username: any;
                email: any;
                nama_unit: any;
                kode_unit: any;
                no_wa: any;
                role: any;
                initials: string;
                department: {
                    id: any;
                    nama_department: any;
                    kode_department: any;
                    nama_unit: any;
                    kode_unit: any;
                };
            };
            token: string;
            role: import(".prisma/client").$Enums.users_role;
            redirect_url: string;
        };
    }>;
    me(user: any): Promise<{
        status: string;
        data: {
            id: any;
            name: any;
            username: any;
            email: any;
            nama_unit: any;
            kode_unit: any;
            no_wa: any;
            role: any;
            initials: string;
            department: {
                id: any;
                nama_department: any;
                kode_department: any;
                nama_unit: any;
                kode_unit: any;
            };
        };
    }>;
    updateProfile(userId: number, dto: UpdateProfileDto): Promise<{
        status: string;
        message: string;
        data: {
            id: any;
            name: any;
            username: any;
            email: any;
            nama_unit: any;
            kode_unit: any;
            no_wa: any;
            role: any;
            initials: string;
            department: {
                id: any;
                nama_department: any;
                kode_department: any;
                nama_unit: any;
                kode_unit: any;
            };
        };
    }>;
    updatePassword(userId: number, dto: UpdatePasswordDto): Promise<{
        status: string;
        message: string;
    }>;
    logout(): {
        status: string;
        message: string;
    };
    private formatUser;
    private getInitials;
}
