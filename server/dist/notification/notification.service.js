"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.NotificationService = void 0;
const common_1 = require("@nestjs/common");
const prisma_service_1 = require("../prisma/prisma.service");
let NotificationService = class NotificationService {
    constructor(prisma) {
        this.prisma = prisma;
    }
    async index(userId, query) {
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
            let parsedData = item.data;
            if (typeof parsedData === 'string') {
                try {
                    parsedData = JSON.parse(parsedData);
                }
                catch {
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
    async markAsRead(userId, id) {
        const notification = await this.prisma.notifications.findFirst({
            where: { id, notifiable_id: userId },
        });
        if (!notification)
            throw new Error('Notifikasi tidak ditemukan.');
        const updated = await this.prisma.notifications.update({
            where: { id },
            data: { read_at: new Date() },
        });
        return { status: 'success', message: 'Notifikasi ditandai telah dibaca.', data: updated };
    }
    async readAll(userId) {
        await this.prisma.notifications.updateMany({
            where: { notifiable_id: userId, read_at: null },
            data: { read_at: new Date() },
        });
        return { status: 'success', message: 'Semua notifikasi telah dibaca.' };
    }
    async destroy(userId, id) {
        await this.prisma.notifications.deleteMany({ where: { id, notifiable_id: userId } });
        return { status: 'success', message: 'Notifikasi berhasil dihapus.' };
    }
    async destroyAll(userId) {
        await this.prisma.notifications.deleteMany({ where: { notifiable_id: userId } });
        return { status: 'success', message: 'Semua riwayat notifikasi berhasil dihapus.' };
    }
    async sendNotification(params) {
        const id = crypto.randomUUID();
        const now = new Date();
        const waktu = now.toLocaleTimeString('id-ID', {
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
    async notifyAdmins(params) {
        const admins = await this.prisma.users.findMany({
            where: { role: 'admin' },
            select: { id: true },
        });
        return Promise.all(admins.map((admin) => this.sendNotification({
            userId: Number(admin.id),
            ...params,
        })));
    }
    async liveSync(user) {
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
            const data = typeof n.data === 'string' ? JSON.parse(n.data) : n.data;
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
        const extra = { is_admin: isAdmin };
        if (isAdmin) {
            extra.count_pending = await this.prisma.pemesanan.count({ where: { status: 'Pending' } });
            extra.count_disetujui = await this.prisma.pemesanan.count({ where: { status: 'Disetujui' } });
            extra.count_total = await this.prisma.pemesanan.count();
        }
        else {
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
};
exports.NotificationService = NotificationService;
exports.NotificationService = NotificationService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService])
], NotificationService);
//# sourceMappingURL=notification.service.js.map