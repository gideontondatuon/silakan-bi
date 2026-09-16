import {
  Injectable,
  UnauthorizedException,
  BadRequestException,
} from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import * as bcrypt from 'bcryptjs';
import { PrismaService } from '../prisma/prisma.service';
import { LoginDto } from './dto/login.dto';
import { UpdateProfileDto, UpdatePasswordDto } from './dto/update-profile.dto';
import { AuditLogService } from '../audit-log/audit-log.service';

@Injectable()
export class AuthService {
  constructor(
    private prisma: PrismaService,
    private jwtService: JwtService,
    private auditLog: AuditLogService,
  ) {}

  /**
   * Authenticate user — equivalent to Laravel's LoginRequest::authenticate()
   */
  async login(dto: LoginDto) {
    const identifier = (dto.login_input || dto.username || '').trim();
    if (!identifier) {
      throw new BadRequestException('Username atau email wajib diisi.');
    }

    const user = await this.prisma.users.findFirst({
      where: {
        OR: [{ username: identifier }, { email: identifier }],
      },
      include: { departments: true },
    });

    if (!user) {
      throw new UnauthorizedException('Username atau password salah.');
    }

    // Support both raw bcrypt ($2y$) and normalized ($2a$)
    let passwordValid = await bcrypt.compare(dto.password, user.password).catch(() => false);
    if (!passwordValid && user.password.startsWith('$2y$')) {
      const normalizedHash = user.password.replace(/^\$2y\$/, '$2a$');
      passwordValid = await bcrypt.compare(dto.password, normalizedHash).catch(() => false);
    }

    if (!passwordValid) {
      throw new UnauthorizedException('Username atau password salah.');
    }

    const payload = { sub: user.id, username: user.username, role: user.role };
    const token = this.jwtService.sign(payload);

    const roleValue = user.role ?? 'user';

    await this.auditLog.log({
      userId: user.id,
      aksi: 'LOGIN',
      modul: 'Auth',
      keterangan: `Pengguna ${user.username} (${user.nama_unit}) berhasil masuk ke sistem.`,
    });

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

  /**
   * Get current user — equivalent to AuthApiController::me()
   */
  async me(user: any) {
    const fullUser = await this.prisma.users.findUnique({
      where: { id: user.id },
      include: { departments: true },
    });

    return {
      status: 'success',
      data: this.formatUser(fullUser),
    };
  }

  /**
   * Update profile — equivalent to AuthApiController::updateProfile()
   */
  async updateProfile(userId: number, dto: UpdateProfileDto) {
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

    await this.auditLog.log({
      userId: BigInt(userId),
      aksi: 'UPDATE_PROFILE',
      modul: 'Profile',
      keterangan: `Pengguna memperbarui informasi profil akun.`,
    });

    return {
      status: 'success',
      message: 'Profil berhasil diperbarui.',
      data: this.formatUser(updated),
    };
  }

  /**
   * Update password — equivalent to AuthApiController::updatePassword()
   */
  async updatePassword(userId: number, dto: UpdatePasswordDto) {
    const user = await this.prisma.users.findUnique({ where: { id: userId } });

    const currentValid = await bcrypt.compare(dto.current_password, user!.password);
    if (!currentValid) {
      throw new BadRequestException('Password saat ini tidak sesuai.');
    }

    if (dto.password !== dto.password_confirmation) {
      throw new BadRequestException('Konfirmasi password tidak cocok.');
    }

    const hashed = await bcrypt.hash(dto.password, 12);

    await this.prisma.users.update({
      where: { id: userId },
      data: { password: hashed },
    });

    await this.auditLog.log({
      userId: BigInt(userId),
      aksi: 'UPDATE_PASSWORD',
      modul: 'Profile',
      keterangan: `Pengguna memperbarui kata sandi akun.`,
    });

    return {
      status: 'success',
      message: 'Kata sandi berhasil diperbarui.',
    };
  }

  /**
   * Logout — JWT is stateless, so we just return success.
   * Client must delete the token from localStorage.
   */
  logout() {
    return {
      status: 'success',
      message: 'Berhasil keluar dari sistem.',
    };
  }

  /**
   * Format user object — equivalent to Laravel's response shape in AuthApiController
   */
  private formatUser(user: any) {
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

  /**
   * Compute avatar initials — equivalent to User::getInitialsAttribute()
   */
  private getInitials(user: any): string {
    if (user.kode_unit) return user.kode_unit.toUpperCase();

    const sourceName = user.nama_unit || user.name || user.username || '';
    const words = sourceName.trim().split(/\s+/);

    if (words.length >= 2) {
      return words
        .map((w: string) => w[0] || '')
        .join('')
        .toUpperCase()
        .slice(0, 5);
    }

    return sourceName.slice(0, 2).toUpperCase();
  }
}
