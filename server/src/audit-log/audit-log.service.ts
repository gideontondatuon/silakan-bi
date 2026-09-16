import { Injectable, Logger } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';

export interface CreateAuditLogDto {
  userId?: number | bigint | null;
  aksi: string;
  modul: string;
  keterangan?: string;
}

@Injectable()
export class AuditLogService {
  private readonly logger = new Logger(AuditLogService.name);

  constructor(private prisma: PrismaService) {}

  /**
   * Record an action in the system audit log
   */
  async log(data: CreateAuditLogDto) {
    try {
      const now = new Date();
      return await this.prisma.audit_log.create({
        data: {
          user_id: data.userId ? BigInt(data.userId) : null,
          aksi: data.aksi,
          modul: data.modul,
          keterangan: data.keterangan || null,
          created_at: now,
          updated_at: now,
        },
      });
    } catch (err) {
      this.logger.error('Failed to write audit log:', err);
      return null;
    }
  }

  async index(query: any) {
    const page = parseInt(query.page ?? '1');
    const perPage = parseInt(query.per_page ?? '20');
    const [total, items] = await Promise.all([
      this.prisma.audit_log.count(),
      this.prisma.audit_log.findMany({
        include: {
          users: {
            select: {
              id: true,
              name: true,
              username: true,
              email: true,
              role: true,
              nama_unit: true,
            },
          },
        },
        orderBy: { created_at: 'desc' },
        skip: (page - 1) * perPage,
        take: perPage,
      }),
    ]);
    return {
      status: 'success',
      data: {
        data: items,
        total,
        current_page: page,
        per_page: perPage,
        last_page: Math.ceil(total / perPage),
      },
    };
  }
}

