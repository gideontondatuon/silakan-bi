"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    var desc = Object.getOwnPropertyDescriptor(m, k);
    if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
    }
    Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __importStar = (this && this.__importStar) || (function () {
    var ownKeys = function(o) {
        ownKeys = Object.getOwnPropertyNames || function (o) {
            var ar = [];
            for (var k in o) if (Object.prototype.hasOwnProperty.call(o, k)) ar[ar.length] = k;
            return ar;
        };
        return ownKeys(o);
    };
    return function (mod) {
        if (mod && mod.__esModule) return mod;
        var result = {};
        if (mod != null) for (var k = ownKeys(mod), i = 0; i < k.length; i++) if (k[i] !== "default") __createBinding(result, mod, k[i]);
        __setModuleDefault(result, mod);
        return result;
    };
})();
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.AuthService = void 0;
const common_1 = require("@nestjs/common");
const jwt_1 = require("@nestjs/jwt");
const bcrypt = __importStar(require("bcryptjs"));
const prisma_service_1 = require("../prisma/prisma.service");
let AuthService = class AuthService {
    constructor(prisma, jwtService) {
        this.prisma = prisma;
        this.jwtService = jwtService;
    }
    async login(dto) {
        const identifier = (dto.login_input || dto.username || '').trim();
        if (!identifier) {
            throw new common_1.BadRequestException('Username atau email wajib diisi.');
        }
        const user = await this.prisma.users.findFirst({
            where: {
                OR: [{ username: identifier }, { email: identifier }],
            },
            include: { departments: true },
        });
        if (!user) {
            throw new common_1.UnauthorizedException('Username atau password salah.');
        }
        let passwordValid = await bcrypt.compare(dto.password, user.password).catch(() => false);
        if (!passwordValid && user.password.startsWith('$2y$')) {
            const normalizedHash = user.password.replace(/^\$2y\$/, '$2a$');
            passwordValid = await bcrypt.compare(dto.password, normalizedHash).catch(() => false);
        }
        if (!passwordValid) {
            throw new common_1.UnauthorizedException('Username atau password salah.');
        }
        const payload = { sub: user.id, username: user.username, role: user.role };
        const token = this.jwtService.sign(payload);
        const roleValue = user.role ?? 'user';
        return {
            status: 'success',
            message: 'Login berhasil.',
            data: {
                user: this.formatUser(user),
                token,
                role: roleValue,
                redirect_url: roleValue === 'admin' ? '/admin/dashboard' : '/dashboard',
            },
        };
    }
    async me(user) {
        const fullUser = await this.prisma.users.findUnique({
            where: { id: user.id },
            include: { departments: true },
        });
        return {
            status: 'success',
            data: this.formatUser(fullUser),
        };
    }
    async updateProfile(userId, dto) {
        const updated = await this.prisma.users.update({
            where: { id: userId },
            data: {
                name: dto.name,
                email: dto.email,
                no_wa: dto.no_wa,
                nama_unit: dto.nama_unit,
                kode_unit: dto.kode_unit,
            },
            include: { departments: true },
        });
        return {
            status: 'success',
            message: 'Profil berhasil diperbarui.',
            data: this.formatUser(updated),
        };
    }
    async updatePassword(userId, dto) {
        const user = await this.prisma.users.findUnique({ where: { id: userId } });
        const currentValid = await bcrypt.compare(dto.current_password, user.password);
        if (!currentValid) {
            throw new common_1.BadRequestException('Password saat ini tidak sesuai.');
        }
        if (dto.password !== dto.password_confirmation) {
            throw new common_1.BadRequestException('Konfirmasi password tidak cocok.');
        }
        const hashed = await bcrypt.hash(dto.password, 12);
        await this.prisma.users.update({
            where: { id: userId },
            data: { password: hashed },
        });
        return {
            status: 'success',
            message: 'Kata sandi berhasil diperbarui.',
        };
    }
    logout() {
        return {
            status: 'success',
            message: 'Berhasil keluar dari sistem.',
        };
    }
    formatUser(user) {
        return {
            id: user.id,
            name: user.name,
            username: user.username,
            email: user.email,
            nama_unit: user.nama_unit,
            kode_unit: user.kode_unit ?? user.departments?.kode_unit ?? '',
            no_wa: user.no_wa,
            role: user.role,
            initials: this.getInitials(user),
            department: user.departments
                ? {
                    id: user.departments.id,
                    nama_department: user.departments.nama_unit ?? '',
                    kode_department: user.departments.kode_unit ?? '',
                    nama_unit: user.departments.nama_unit ?? '',
                    kode_unit: user.departments.kode_unit ?? '',
                }
                : null,
        };
    }
    getInitials(user) {
        if (user.kode_unit)
            return user.kode_unit.toUpperCase();
        const sourceName = user.nama_unit || user.name || user.username || '';
        const words = sourceName.trim().split(/\s+/);
        if (words.length >= 2) {
            return words
                .map((w) => w[0] || '')
                .join('')
                .toUpperCase()
                .slice(0, 5);
        }
        return sourceName.slice(0, 2).toUpperCase();
    }
};
exports.AuthService = AuthService;
exports.AuthService = AuthService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService,
        jwt_1.JwtService])
], AuthService);
//# sourceMappingURL=auth.service.js.map