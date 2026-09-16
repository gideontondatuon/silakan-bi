import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';

@Injectable()
export class NotificationService {
  constructor(private prisma: PrismaService) {}

  async index(userId: number, query: any) {
    const page = parseInt(query.page ?? '1');
    const perPage = parseInt(query.per_page ?? '15');
    const skip = (page - 1) * perPage;

    const [total, items] = await Promise.all([
      this.prisma.notifications.count({ where: { notifiable_id: userId } }),
      this.prisma.notifications.findMany({
        where: { notifiable_id: userId },
        orderBy: { created_at: 'desc' },
        skip,
        take: perPage,
      }),
    ]);

    const mappedItems = items.map((item) => {
      let parsedData: any = item.data;
      if (typeof parsedData === 'string') {
        try {
          parsedData = JSON.parse(parsedData);
        } catch {
          parsedData = {};
        }
      }
      return {
        ...item,
        data: parsedData,
      };
    });

    return {
      status: 'success',
      data: {
        data: mappedItems,
        current_page: page,
        per_page: perPage,
        total,
        last_page: Math.ceil(total / perPage),
      },
    };
  }

  async markAsRead(userId: number, id: string) {
    const notification = await this.prisma.notifications.findFirst({
      where: { id, notifiable_id: userId },
    });
    if (!notification) throw new Error('Notifikasi tidak ditemukan.');

    const updated = await this.prisma.notifications.update({
      where: { id },
      data: { read_at: new Date() },
    });
    return { status: 'success', message: 'Notifikasi ditandai telah dibaca.', data: updated };
  }

  async readAll(userId: number) {
    await this.prisma.notifications.updateMany({
      where: { notifiable_id: userId, read_at: null },
      data: { read_at: new Date() },
    });
    return { status: 'success', message: 'Semua notifikasi telah dibaca.' };
  }

  async destroy(userId: number, id: string) {
    await this.prisma.notifications.deleteMany({ where: { id, notifiable_id: userId } });
    return { status: 'success', message: 'Notifikasi berhasil dihapus.' };
  }

  async destroyAll(userId: number) {
    await this.prisma.notifications.deleteMany({ where: { notifiable_id: userId } });
    return { status: 'success', message: 'Semua riwayat notifikasi berhasil dihapus.' };
  }

  /** Send notification to a specific user */
  async sendNotification(params: {
    userId: number;
    type: string;
    judul: string;
    pesan: string;
    pemesananId?: number | bigint;
    url?: string;
  }) {
    const id = crypto.randomUUID();
    const now = new Date();
    const waktu =
      now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Makassar',
      }) + ' WITA';

    const data = JSON.stringify({
      judul: params.judul,
      pesan: params.pesan,
      waktu,
      pemesanan_id: params.pemesananId ? Number(params.pemesananId) : null,
      url: params.url ?? null,
    });

    return this.prisma.notifications.create({
      data: {
        id,
        type: params.type,
        notifiable_type: 'App\\Models\\User',
        notifiable_id: BigInt(params.userId),
        data,
        read_at: null,
        created_at: now,
        updated_at: now,
      },
    });
  }

  /** Send notification to all admin users */
  async notifyAdmins(params: {
    type: string;
    judul: string;
    pesan: string;
    pemesananId?: number | bigint;
    url?: string;
  }) {
    const admins = await this.prisma.users.findMany({
      where: { role: 'admin' },
      select: { id: true },
    });

    return Promise.all(
      admins.map((admin) =>
        this.sendNotification({
          userId: Number(admin.id),
          ...params,
        }),
      ),
    );
  }

  async liveSync(user: any) {
    const userId = user.id;
    const unreadCount = await this.prisma.notifications.count({
      where: { notifiable_id: userId, read_at: null },
    });

    const latest = await this.prisma.notifications.findMany({
      where: { notifiable_id: userId, read_at: null },
      orderBy: { created_at: 'desc' },
      take: 5,
    });

    const notifications = latest.map((n) => {
      const data = typeof n.data === 'string' ? JSON.parse(n.data) : n.data as any;
      return {
        id: n.id,
        judul: data?.judul ?? 'Notifikasi Baru',
        pesan: data?.pesan ?? '',
        waktu: data?.waktu ?? '',
        pemesanan_id: data?.pemesanan_id ?? null,
        created_at: n.created_at?.toISOString(),
      };
    });

    const isAdmin = user.role === 'admin';
    const extra: any = { is_admin: isAdmin };

    if (isAdmin) {
      extra.count_pending = await this.prisma.pemesanan.count({ where: { status: 'Pending' } });
      extra.count_disetujui = await this.prisma.pemesanan.count({ where: { status: 'Disetujui' } });
      extra.count_total = await this.prisma.pemesanan.count();
    } else {
      extra.count_my_pending = await this.prisma.pemesanan.count({
        where: { user_id: userId, status: 'Pending' },
      });
    }

    return {
      status: 'success',
      unread_count: unreadCount,
      notifications,
      extra,
      server_time: new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Makassar' }),
    };
  }
}
