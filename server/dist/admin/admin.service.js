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
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.AdminService = void 0;
const common_1 = require("@nestjs/common");
const prisma_service_1 = require("../prisma/prisma.service");
const pemesanan_service_1 = require("../pemesanan/pemesanan.service");
const whatsapp_service_1 = require("../whatsapp/whatsapp.service");
const bcrypt = __importStar(require("bcryptjs"));
const dayjs_1 = __importDefault(require("dayjs"));
const timezone_1 = __importDefault(require("dayjs/plugin/timezone"));
const utc_1 = __importDefault(require("dayjs/plugin/utc"));
dayjs_1.default.extend(utc_1.default);
dayjs_1.default.extend(timezone_1.default);
const WITA = 'Asia/Makassar';
const notification_service_1 = require("../notification/notification.service");
let AdminService = class AdminService {
    constructor(prisma, pemesananService, whatsapp, notificationService) {
        this.prisma = prisma;
        this.pemesananService = pemesananService;
        this.whatsapp = whatsapp;
        this.notificationService = notificationService;
    }
    async approvalIndex(query) {
        await this.pemesananService.markFinishedAgendas();
        const tab = query.tab ?? 'pending';
        const page = parseInt(query.page ?? '1');
        const perPage = parseInt(query.per_page ?? '10');
        const skip = (page - 1) * perPage;
        const where = {};
        if (tab === 'pending')
            where.status = 'Pending';
        else if (tab === 'disetujui')
            where.status = 'Disetujui';
        else if (tab === 'selesai')
            where.status = 'Selesai';
        if (query.q) {
            where.OR = [
                { kode_pemesanan: { contains: query.q } },
                { judul_kegiatan: { contains: query.q } },
                { pic_kegiatan: { contains: query.q } },
                { users: { OR: [{ name: { contains: query.q } }, { nama_unit: { contains: query.q } }] } },
                { ruangan: { nama_ruangan: { contains: query.q } } },
            ];
        }
        if (query.ruangan_id)
            where.ruangan_id = parseInt(query.ruangan_id);
        if (query.tanggal)
            where.tanggal_kegiatan = new Date(query.tanggal);
        const [total, items, countPending, countDisetujui, countSelesai, countSemua] = await Promise.all([
            this.prisma.pemesanan.count({ where }),
            this.prisma.pemesanan.findMany({ where, include: { users: true, ruangan: true, layout_ruangan: true }, orderBy: { tanggal_kegiatan: 'desc' }, skip, take: perPage }),
            this.prisma.pemesanan.count({ where: { status: 'Pending' } }),
            this.prisma.pemesanan.count({ where: { status: 'Disetujui' } }),
            this.prisma.pemesanan.count({ where: { status: 'Selesai' } }),
            this.prisma.pemesanan.count(),
        ]);
        return {
            status: 'success',
            data: {
                items: { data: items.map(this.pemesananService.formatPemesanan.bind(this.pemesananService)), total, current_page: page, per_page: perPage, last_page: Math.ceil(total / perPage) },
                counts: { pending: countPending, disetujui: countDisetujui, selesai: countSelesai, semua: countSemua },
            },
        };
    }
    async approvalShow(id) {
        const pemesanan = await this.prisma.pemesanan.findUnique({
            where: { id },
            include: { ruangan: true, layout_ruangan: true, users: true, pemesanan_status_history: { include: { users: true } } },
        });
        if (!pemesanan)
            throw new common_1.NotFoundException('Pemesanan tidak ditemukan.');
        return { status: 'success', data: this.pemesananService.formatPemesanan(pemesanan) };
    }
    async approve(id, adminUser, catatanAdmin) {
        const pemesanan = await this.prisma.pemesanan.findUnique({ where: { id }, include: { users: true, ruangan: true } });
        if (!pemesanan)
            throw new common_1.NotFoundException('Pemesanan tidak ditemukan.');
        if (pemesanan.status !== 'Pending')
            throw new common_1.BadRequestException('Hanya pemesanan berstatus pending yang dapat disetujui.');
        const updated = await this.prisma.pemesanan.update({
            where: { id },
            data: { status: 'Disetujui', approved_by: adminUser.id, approved_at: new Date(), catatan_admin: catatanAdmin ?? null },
            include: { ruangan: true, layout_ruangan: true, users: true },
        });
        const userPhone = pemesanan.users?.no_wa;
        if (userPhone) {
            await this.whatsapp.send(userPhone, `✅ Pemesanan Anda *${pemesanan.kode_pemesanan}* untuk kegiatan "*${pemesanan.judul_kegiatan}*" di *${pemesanan.ruangan?.nama_ruangan}* telah *DISETUJUI*.\n\nSilakan hadir sesuai jadwal.`);
        }
        try {
            await this.notificationService.sendNotification({
                userId: Number(pemesanan.user_id),
                type: 'App\\Notifications\\PemesananDisetujuiNotification',
                judul: 'Pemesanan Disetujui ✅',
                pesan: `Pemesanan Anda ${pemesanan.kode_pemesanan} untuk kegiatan "${pemesanan.judul_kegiatan}" di ruangan ${pemesanan.ruangan?.nama_ruangan || 'ruangan'} telah DISETUJUI.`,
                pemesananId: pemesanan.id,
                url: `/pemesanan/${pemesanan.id}`,
            });
        }
        catch (e) {
            console.error('Failed to send in-app notification:', e);
        }
        return { status: 'success', message: `Pemesanan ${pemesanan.kode_pemesanan} berhasil disetujui.`, data: this.pemesananService.formatPemesanan(updated) };
    }
    async reject(id, adminUser, alasan) {
        if (!alasan)
            throw new common_1.BadRequestException('Alasan penolakan wajib diisi.');
        const pemesanan = await this.prisma.pemesanan.findUnique({ where: { id }, include: { users: true, ruangan: true } });
        if (!pemesanan)
            throw new common_1.NotFoundException('Pemesanan tidak ditemukan.');
        const updated = await this.prisma.pemesanan.update({
            where: { id },
            data: { status: 'Ditolak', rejected_by: adminUser.id, rejected_at: new Date(), alasan_penolakan: alasan },
            include: { ruangan: true, layout_ruangan: true, users: true },
        });
        const userPhone = pemesanan.users?.no_wa;
        if (userPhone) {
            await this.whatsapp.send(userPhone, `❌ Pemesanan Anda *${pemesanan.kode_pemesanan}* untuk kegiatan "*${pemesanan.judul_kegiatan}*" telah *DITOLAK*.\n\nAlasan: ${alasan}`);
        }
        try {
            await this.notificationService.sendNotification({
                userId: Number(pemesanan.user_id),
                type: 'App\\Notifications\\PemesananDitolakNotification',
                judul: 'Pemesanan Ditolak ❌',
                pesan: `Pemesanan Anda ${pemesanan.kode_pemesanan} untuk kegiatan "${pemesanan.judul_kegiatan}" telah DITOLAK. Alasan: ${alasan}`,
                pemesananId: pemesanan.id,
                url: `/pemesanan/${pemesanan.id}`,
            });
        }
        catch (e) {
            console.error('Failed to send in-app notification:', e);
        }
        return { status: 'success', message: `Pemesanan ${pemesanan.kode_pemesanan} berhasil ditolak.`, data: this.pemesananService.formatPemesanan(updated) };
    }
    async approvalDestroy(id) {
        const pemesanan = await this.prisma.pemesanan.findUnique({ where: { id } });
        if (!pemesanan)
            throw new common_1.NotFoundException('Pemesanan tidak ditemukan.');
        await this.prisma.pemesanan.delete({ where: { id } });
        return { status: 'success', message: `Pemesanan ${pemesanan.kode_pemesanan} berhasil dihapus dari sistem.` };
    }
    async adminStorePemesanan(body, adminUser, filePath) {
        return this.pemesananService.store(body, adminUser, filePath);
    }
    async userIndex(query) {
        const page = parseInt(query.page ?? '1');
        const perPage = parseInt(query.per_page ?? '15');
        const q = query.q?.trim();
        const admins = await this.prisma.users.findMany({
            where: { role: 'admin' },
            include: { departments: true },
            orderBy: { name: 'asc' },
        });
        const userWhere = { role: { not: 'admin' } };
        if (q) {
            userWhere.AND = [
                {
                    OR: [
                        { name: { contains: q } },
                        { username: { contains: q } },
                        { nama_unit: { contains: q } },
                        { kode_unit: { contains: q } },
                        { email: { contains: q } },
                    ],
                },
            ];
        }
        const [total, userItems, departments] = await Promise.all([
            this.prisma.users.count({ where: userWhere }),
            this.prisma.users.findMany({
                where: userWhere,
                include: { departments: true },
                orderBy: { name: 'asc' },
                skip: (page - 1) * perPage,
                take: perPage,
            }),
            this.prisma.departments.findMany({
                orderBy: { nama_unit: 'asc' },
            }),
        ]);
        const formattedAdmins = admins.map((a) => ({
            ...a,
            department: a.departments,
        }));
        const formattedUsers = userItems.map((u) => ({
            ...u,
            department: u.departments,
        }));
        const from = total > 0 ? (page - 1) * perPage + 1 : 0;
        const to = Math.min(page * perPage, total);
        const last_page = Math.ceil(total / perPage) || 1;
        return {
            status: 'success',
            data: {
                admins: formattedAdmins,
                users: {
                    data: formattedUsers,
                    total,
                    current_page: page,
                    per_page: perPage,
                    last_page,
                    from,
                    to,
                },
                departments,
            },
        };
    }
    async userShow(id) {
        const user = await this.prisma.users.findUnique({
            where: { id: BigInt(id) },
            include: { departments: true },
        });
        if (!user)
            throw new common_1.NotFoundException('User tidak ditemukan.');
        return {
            status: 'success',
            data: {
                ...user,
                department: user.departments,
            },
        };
    }
    async userStore(body) {
        const hashed = await bcrypt.hash(body.password, 12);
        const user = await this.prisma.users.create({
            data: {
                name: body.name || body.nama_unit || '',
                username: body.username,
                email: body.email || null,
                no_wa: body.no_wa || null,
                password: hashed,
                password_plain: body.password || null,
                role: body.role || 'user',
                nama_unit: body.nama_unit || body.name || '',
                kode_unit: body.kode_unit || '',
                department_id: body.department_id ? BigInt(body.department_id) : null,
            },
        });
        return { status: 'success', message: 'User berhasil dibuat.', data: user };
    }
    async userUpdate(id, body) {
        const data = {};
        if (body.name !== undefined)
            data.name = body.name;
        if (body.username !== undefined)
            data.username = body.username;
        if (body.email !== undefined)
            data.email = body.email;
        if (body.no_wa !== undefined)
            data.no_wa = body.no_wa;
        if (body.role !== undefined)
            data.role = body.role;
        if (body.nama_unit !== undefined)
            data.nama_unit = body.nama_unit;
        if (body.kode_unit !== undefined)
            data.kode_unit = body.kode_unit;
        if (body.department_id !== undefined) {
            data.department_id = body.department_id ? BigInt(body.department_id) : null;
        }
        if (body.password) {
            data.password = await bcrypt.hash(body.password, 12);
            data.password_plain = body.password;
        }
        const user = await this.prisma.users.update({
            where: { id: BigInt(id) },
            data,
        });
        return { status: 'success', message: 'User berhasil diperbarui.', data: user };
    }
    async userDestroy(id) {
        await this.prisma.users.delete({ where: { id: BigInt(id) } });
        return { status: 'success', message: 'User berhasil dihapus.' };
    }
    async getUnits() {
        return this.pemesananService.getUnits();
    }
    getWhatsappStatus() {
        return this.whatsapp.getStatus();
    }
    testWhatsapp(phone) {
        return this.whatsapp.testConnection(phone);
    }
};
exports.AdminService = AdminService;
exports.AdminService = AdminService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService,
        pemesanan_service_1.PemesananService,
        whatsapp_service_1.WhatsappService,
        notification_service_1.NotificationService])
], AdminService);
//# sourceMappingURL=admin.service.js.map