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
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.DashboardService = void 0;
const common_1 = require("@nestjs/common");
const prisma_service_1 = require("../prisma/prisma.service");
const pemesanan_service_1 = require("../pemesanan/pemesanan.service");
const dayjs_1 = __importDefault(require("dayjs"));
const timezone_1 = __importDefault(require("dayjs/plugin/timezone"));
const utc_1 = __importDefault(require("dayjs/plugin/utc"));
dayjs_1.default.extend(utc_1.default);
dayjs_1.default.extend(timezone_1.default);
const WITA = 'Asia/Makassar';
let DashboardService = class DashboardService {
    constructor(prisma, pemesananService) {
        this.prisma = prisma;
        this.pemesananService = pemesananService;
    }
    async user(userId) {
        await this.pemesananService.markFinishedAgendas();
        const now = (0, dayjs_1.default)().tz(WITA);
        const todayStr = now.format('YYYY-MM-DD');
        const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
        const currentTime = now.format('HH:mm:ss');
        const [total, pending, approved, upcoming, terbaru, hariIni] = await Promise.all([
            this.prisma.pemesanan.count({ where: { user_id: userId } }),
            this.prisma.pemesanan.count({ where: { user_id: userId, status: 'Pending' } }),
            this.prisma.pemesanan.count({
                where: { user_id: userId, status: { in: ['Disetujui', 'Selesai'] } },
            }),
            this.prisma.pemesanan.count({
                where: {
                    user_id: userId,
                    status: { in: ['Disetujui', 'Selesai'] },
                    tanggal_kegiatan: { gte: todayDate },
                },
            }),
            this.prisma.pemesanan.findMany({
                where: { user_id: userId },
                include: { ruangan: true, layout_ruangan: true },
                orderBy: { created_at: 'desc' },
                take: 5,
            }),
            this.prisma.pemesanan.findMany({
                where: { status: 'Disetujui', tanggal_kegiatan: todayDate },
                include: { ruangan: true, users: true, layout_ruangan: true },
                orderBy: { waktu_mulai: 'asc' },
            }),
        ]);
        const berlangsung = hariIni.filter((p) => {
            const mulai = this.pemesananService.formatTimeStr(p.waktu_mulai);
            const selesai = this.pemesananService.formatTimeStr(p.waktu_selesai);
            return mulai <= currentTime && selesai >= currentTime;
        });
        return {
            status: 'success',
            data: {
                stats: { total, pending, approved, upcoming },
                pemesanan_terbaru: terbaru.map(this.pemesananService.formatPemesanan.bind(this.pemesananService)),
                kegiatan_hari_ini: hariIni.slice(0, 5).map(this.pemesananService.formatPemesanan.bind(this.pemesananService)),
                kegiatan_berlangsung: berlangsung.map(this.pemesananService.formatPemesanan.bind(this.pemesananService)),
            },
        };
    }
    async admin() {
        await this.pemesananService.markFinishedAgendas();
        const now = (0, dayjs_1.default)().tz(WITA);
        const todayStr = now.format('YYYY-MM-DD');
        const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
        const currentTime = now.format('HH:mm:ss');
        const [totalRuangan, totalPemesanan, waitingApproval, disetujui, ditolak, bulanIni, hariIni, waitingList, ruanganTerpopuler, aktivitasTerbaru, agendaMendatang,] = await Promise.all([
            this.prisma.ruangan.count(),
            this.prisma.pemesanan.count(),
            this.prisma.pemesanan.count({ where: { status: 'Pending' } }),
            this.prisma.pemesanan.count({ where: { status: 'Disetujui' } }),
            this.prisma.pemesanan.count({ where: { status: 'Ditolak' } }),
            this.prisma.pemesanan.count({
                where: {
                    created_at: {
                        gte: now.startOf('month').toDate(),
                        lte: now.endOf('month').toDate(),
                    },
                },
            }),
            this.prisma.pemesanan.findMany({
                where: { tanggal_kegiatan: todayDate, status: 'Disetujui' },
                include: { users: true, ruangan: true, layout_ruangan: true },
                orderBy: { waktu_mulai: 'asc' },
            }),
            this.prisma.pemesanan.findMany({
                where: { status: 'Pending' },
                include: { users: true, ruangan: true },
                orderBy: { created_at: 'desc' },
                take: 5,
            }),
            this.prisma.pemesanan.groupBy({
                by: ['ruangan_id'],
                _count: { id: true },
                orderBy: { _count: { id: 'desc' } },
                take: 5,
            }),
            this.prisma.pemesanan.findMany({
                include: { users: true, ruangan: true },
                orderBy: { created_at: 'desc' },
                take: 5,
            }),
            this.prisma.pemesanan.findMany({
                where: {
                    status: { in: ['Disetujui', 'Selesai'] },
                    tanggal_kegiatan: { gte: todayDate },
                },
                include: { users: true, ruangan: true, layout_ruangan: true },
                orderBy: [{ tanggal_kegiatan: 'asc' }, { waktu_mulai: 'asc' }],
                take: 10,
            }),
        ]);
        const berlangsung = hariIni.filter((p) => {
            const mulai = this.pemesananService.formatTimeStr(p.waktu_mulai);
            const selesai = this.pemesananService.formatTimeStr(p.waktu_selesai);
            return mulai <= currentTime && selesai >= currentTime;
        });
        const chartMonthlyLabels = [];
        const chartMonthlyData = [];
        for (let i = 5; i >= 0; i--) {
            const monthDate = now.subtract(i, 'month');
            chartMonthlyLabels.push(monthDate.format('MMM YYYY'));
            const count = await this.prisma.pemesanan.count({
                where: {
                    tanggal_kegiatan: {
                        gte: monthDate.startOf('month').toDate(),
                        lte: monthDate.endOf('month').toDate(),
                    },
                },
            });
            chartMonthlyData.push(count);
        }
        const ruanganIds = ruanganTerpopuler.map((r) => r.ruangan_id);
        const ruanganData = await this.prisma.ruangan.findMany({
            where: { id: { in: ruanganIds } },
        });
        const chartRuanganLabels = ruanganTerpopuler.map((r) => ruanganData.find((rd) => rd.id === r.ruangan_id)?.nama_ruangan ?? `Ruangan ${r.ruangan_id}`);
        const chartRuanganData = ruanganTerpopuler.map((r) => r._count.id);
        const unitDist = await this.prisma.pemesanan.groupBy({
            by: ['user_id'],
            _count: { id: true },
            orderBy: { _count: { id: 'desc' } },
            take: 5,
        });
        const userIds = unitDist.map((u) => u.user_id);
        const usersData = await this.prisma.users.findMany({ where: { id: { in: userIds } } });
        const chartUnitLabels = unitDist.map((u) => usersData.find((ud) => ud.id === u.user_id)?.nama_unit ?? 'User');
        const chartUnitData = unitDist.map((u) => u._count.id);
        const fmt = this.pemesananService.formatPemesanan.bind(this.pemesananService);
        return {
            status: 'success',
            data: {
                stats: {
                    total_ruangan: totalRuangan,
                    total_pemesanan: totalPemesanan,
                    waiting_approval: waitingApproval,
                    disetujui,
                    ditolak,
                    pemesanan_bulan_ini: bulanIni,
                },
                kegiatan_hari_ini: hariIni.map(fmt),
                kegiatan_berlangsung: berlangsung.map(fmt),
                agenda_mendatang: agendaMendatang.map(fmt),
                waiting_list: waitingList.map(fmt),
                ruangan_terpopuler: ruanganTerpopuler,
                aktivitas_terbaru: aktivitasTerbaru.map(fmt),
                charts: {
                    monthly: { labels: chartMonthlyLabels, data: chartMonthlyData },
                    popular_rooms: { labels: chartRuanganLabels, data: chartRuanganData },
                    unit_distribution: { labels: chartUnitLabels, data: chartUnitData },
                },
            },
        };
    }
    async kegiatanBerlangsung() {
        await this.pemesananService.markFinishedAgendas();
        const now = (0, dayjs_1.default)().tz(WITA);
        const todayStr = now.format('YYYY-MM-DD');
        const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
        const currentTime = now.format('HH:mm:ss');
        const allToday = await this.prisma.pemesanan.findMany({
            where: {
                status: 'Disetujui',
                tanggal_kegiatan: todayDate,
            },
            include: { ruangan: true, layout_ruangan: true, users: true },
            orderBy: { waktu_mulai: 'asc' },
        });
        const kegiatan = allToday.filter((p) => {
            const mulai = this.pemesananService.formatTimeStr(p.waktu_mulai);
            const selesai = this.pemesananService.formatTimeStr(p.waktu_selesai);
            return mulai <= currentTime && selesai >= currentTime;
        });
        return {
            status: 'success',
            data: kegiatan.map(this.pemesananService.formatPemesanan.bind(this.pemesananService)),
        };
    }
};
exports.DashboardService = DashboardService;
exports.DashboardService = DashboardService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService,
        pemesanan_service_1.PemesananService])
], DashboardService);
//# sourceMappingURL=dashboard.service.js.map