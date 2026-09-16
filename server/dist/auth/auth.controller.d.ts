import { AuthService } from './auth.service';
import { LoginDto } from './dto/login.dto';
import { UpdateProfileDto, UpdatePasswordDto } from './dto/update-profile.dto';
export declare class AuthController {
    private authService;
    constructor(authService: AuthService);
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
    me(req: any): Promise<{
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
    updateProfile(req: any, dto: UpdateProfileDto): Promise<{
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
    updatePassword(req: any, dto: UpdatePasswordDto): Promise<{
        status: string;
        message: string;
    }>;
    logout(): {
        status: string;
        message: string;
    };
}
