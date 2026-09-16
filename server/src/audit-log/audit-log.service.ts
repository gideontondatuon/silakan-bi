import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';

@Injectable()
export class AuditLogService {
  constructor(private prisma: PrismaService) {}
  async index(query: any) {
    const page = parseInt(query.page ?? '1');
    const perPage = parseInt(query.per_page ?? '20');
    const [total, items] = await Promise.all([
      this.prisma.audit_log.count(),
      this.prisma.audit_log.findMany({
        include: { users: true },
        orderBy: { created_at: 'desc' },
        skip: (page - 1) * perPage,
        take: perPage,
      }),
    ]);
    return { status: 'success', data: { data: items, total, current_page: page, per_page: perPage, last_page: Math.ceil(total / perPage) } };
  }
}
