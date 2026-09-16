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
exports.RuanganService = void 0;
const common_1 = require("@nestjs/common");
const prisma_service_1 = require("../prisma/prisma.service");
function normalizeRuanganStatus(status) {
    if (!status)
        return 'aktif';
    const lower = status.toLowerCase();
    if (lower === 'pemeliharaan' || lower === 'perawatan')
        return 'perawatan';
    if (lower === 'nonaktif')
        return 'nonaktif';
    return 'aktif';
}
let RuanganService = class RuanganService {
    constructor(prisma) {
        this.prisma = prisma;
    }
    async findAll(query) {
        const onlyActive = query?.only_active === '1' ||
            query?.only_active === 1 ||
            query?.only_active === true ||
            query?.only_active === 'true';
        const where = {};
        if (onlyActive) {
            where.status = 'aktif';
        }
        const rooms = await this.prisma.ruangan.findMany({
            where,
            include: { layout_ruangan: true },
            orderBy: { nama_ruangan: 'asc' },
        });
        const data = rooms.map((r) => ({
            id: Number(r.id),
            nama_ruangan: r.nama_ruangan,
            kapasitas: r.kapasitas,
            status: r.status,
            lokasi: r.lokasi,
            layouts: r.layout_ruangan.map((l) => ({
                id: Number(l.id),
                nama_layout: l.nama_layout,
                kapasitas_layout: l.kapasitas_layout,
            })),
        }));
        return { status: 'success', data };
    }
    async findAdmin(query) {
        const page = Math.max(1, parseInt(query?.page ?? '1', 10));
        const perPage = Math.max(1, parseInt(query?.per_page ?? '10', 10));
        const q = (query?.q ?? '').trim();
        const where = {};
        if (q) {
            where.OR = [
                { nama_ruangan: { contains: q } },
                { lokasi: { contains: q } },
            ];
        }
        const [total, rooms] = await Promise.all([
            this.prisma.ruangan.count({ where }),
            this.prisma.ruangan.findMany({
                where,
                include: { layout_ruangan: true },
                orderBy: { nama_ruangan: 'asc' },
                skip: (page - 1) * perPage,
                take: perPage,
            }),
        ]);
        const data = rooms.map((r) => ({
            id: Number(r.id),
            nama_ruangan: r.nama_ruangan,
            kapasitas: r.kapasitas,
            status: r.status,
            lokasi: r.lokasi,
            layouts: r.layout_ruangan.map((l) => ({
                id: Number(l.id),
                nama_layout: l.nama_layout,
                kapasitas_layout: l.kapasitas_layout,
            })),
        }));
        return {
            status: 'success',
            data: {
                data,
                total,
                current_page: page,
                last_page: Math.ceil(total / perPage) || 1,
                per_page: perPage,
            },
        };
    }
    async findOne(id) {
        const room = await this.prisma.ruangan.findUnique({
            where: { id: BigInt(id) },
            include: { layout_ruangan: true },
        });
        if (!room) {
            throw new common_1.NotFoundException('Ruangan tidak ditemukan.');
        }
        const data = {
            id: Number(room.id),
            nama_ruangan: room.nama_ruangan,
            kapasitas: room.kapasitas,
            status: room.status,
            lokasi: room.lokasi,
            layouts: room.layout_ruangan.map((l) => ({
                id: Number(l.id),
                nama_layout: l.nama_layout,
                kapasitas_layout: l.kapasitas_layout,
            })),
        };
        return { status: 'success', data };
    }
    async create(body) {
        const room = await this.prisma.ruangan.create({
            data: {
                nama_ruangan: body.nama_ruangan,
                kapasitas: Number(body.kapasitas),
                lokasi: body.lokasi,
                status: normalizeRuanganStatus(body.status),
            },
        });
        if (Array.isArray(body.layouts)) {
            for (const layoutId of body.layouts) {
                await this.prisma.layout_ruangan.updateMany({
                    where: { id: BigInt(layoutId) },
                    data: { ruangan_id: room.id },
                });
            }
        }
        return {
            status: 'success',
            message: 'Ruangan berhasil ditambahkan.',
            data: { id: Number(room.id), nama_ruangan: room.nama_ruangan },
        };
    }
    async update(id, body) {
        const room = await this.prisma.ruangan.update({
            where: { id: BigInt(id) },
            data: {
                nama_ruangan: body.nama_ruangan,
                kapasitas: Number(body.kapasitas),
                lokasi: body.lokasi,
                status: body.status !== undefined ? normalizeRuanganStatus(body.status) : undefined,
            },
        });
        if (Array.isArray(body.layouts)) {
            for (const layoutId of body.layouts) {
                await this.prisma.layout_ruangan.updateMany({
                    where: { id: BigInt(layoutId) },
                    data: { ruangan_id: room.id },
                });
            }
        }
        return {
            status: 'success',
            message: 'Ruangan berhasil diperbarui.',
            data: { id: Number(room.id), nama_ruangan: room.nama_ruangan },
        };
    }
    async remove(id) {
        await this.prisma.ruangan.delete({ where: { id: BigInt(id) } });
        return { status: 'success', message: 'Ruangan berhasil dihapus.' };
    }
    async getLayouts(ruanganId) {
        const layouts = await this.prisma.layout_ruangan.findMany({
            where: {
                OR: [
                    { ruangan_id: BigInt(ruanganId) },
                    { ruangan_id: null },
                ],
            },
            orderBy: { nama_layout: 'asc' },
        });
        const data = layouts.map((l) => ({
            id: Number(l.id),
            ruangan_id: l.ruangan_id ? Number(l.ruangan_id) : null,
            nama_layout: l.nama_layout,
            kapasitas_layout: l.kapasitas_layout,
        }));
        return { status: 'success', data };
    }
    async findAllLayouts(query) {
        const page = Math.max(1, parseInt(query?.page ?? '1', 10));
        const perPage = Math.max(1, parseInt(query?.per_page ?? '10', 10));
        const q = (query?.q ?? '').trim();
        const where = {};
        if (q) {
            where.nama_layout = { contains: q };
        }
        const [total, layouts] = await Promise.all([
            this.prisma.layout_ruangan.count({ where }),
            this.prisma.layout_ruangan.findMany({
                where,
                include: { ruangan: true },
                orderBy: { nama_layout: 'asc' },
                skip: (page - 1) * perPage,
                take: perPage,
            }),
        ]);
        const data = layouts.map((l) => ({
            id: Number(l.id),
            ruangan_id: l.ruangan_id ? Number(l.ruangan_id) : null,
            nama_layout: l.nama_layout,
            kapasitas_layout: l.kapasitas_layout,
            nama_ruangan: l.ruangan?.nama_ruangan || 'Semua Ruangan (Global)',
        }));
        return {
            status: 'success',
            data: {
                data,
                total,
                current_page: page,
                last_page: Math.ceil(total / perPage) || 1,
                per_page: perPage,
            },
        };
    }
    async findLayout(id) {
        const layout = await this.prisma.layout_ruangan.findUnique({
            where: { id: BigInt(id) },
            include: { ruangan: true },
        });
        if (!layout) {
            throw new common_1.NotFoundException('Layout tidak ditemukan.');
        }
        const data = {
            id: Number(layout.id),
            ruangan_id: layout.ruangan_id ? Number(layout.ruangan_id) : null,
            nama_layout: layout.nama_layout,
            kapasitas_layout: layout.kapasitas_layout,
            nama_ruangan: layout.ruangan?.nama_ruangan,
        };
        return { status: 'success', data };
    }
    async createLayout(body) {
        const layout = await this.prisma.layout_ruangan.create({
            data: {
                nama_layout: body.nama_layout,
                ruangan_id: body.ruangan_id ? BigInt(body.ruangan_id) : null,
            },
        });
        return {
            status: 'success',
            message: 'Layout berhasil ditambahkan.',
            data: { id: Number(layout.id), nama_layout: layout.nama_layout },
        };
    }
    async updateLayout(id, body) {
        const layout = await this.prisma.layout_ruangan.update({
            where: { id: BigInt(id) },
            data: {
                nama_layout: body.nama_layout,
                ruangan_id: body.ruangan_id ? BigInt(body.ruangan_id) : null,
            },
        });
        return {
            status: 'success',
            message: 'Layout berhasil diperbarui.',
            data: { id: Number(layout.id), nama_layout: layout.nama_layout },
        };
    }
    async deleteLayout(id) {
        await this.prisma.layout_ruangan.delete({ where: { id: BigInt(id) } });
        return { status: 'success', message: 'Layout berhasil dihapus.' };
    }
};
exports.RuanganService = RuanganService;
exports.RuanganService = RuanganService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService])
], RuanganService);
//# sourceMappingURL=ruangan.service.js.map