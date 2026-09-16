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
exports.DisplayService = void 0;
const common_1 = require("@nestjs/common");
const prisma_service_1 = require("../prisma/prisma.service");
const dayjs_1 = __importDefault(require("dayjs"));
const timezone_1 = __importDefault(require("dayjs/plugin/timezone"));
const utc_1 = __importDefault(require("dayjs/plugin/utc"));
dayjs_1.default.extend(utc_1.default);
dayjs_1.default.extend(timezone_1.default);
const WITA = 'Asia/Makassar';
function formatTimeStr(val) {
    if (!val)
        return '';
    if (val instanceof Date) {
        return val.toISOString().substring(11, 16);
    }
    return String(val).substring(0, 5);
}
let DisplayService = class DisplayService {
    constructor(prisma) {
        this.prisma = prisma;
    }
    async apiData(jenis) {
        const now = (0, dayjs_1.default)().tz(WITA);
        const todayStr = now.format('YYYY-MM-DD');
        const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
        const currentTime = now.format('HH:mm');
        const whereCondition = {
            status: 'Disetujui',
            tanggal_kegiatan: todayDate,
        };
        if (jenis && ['internal', 'eksternal'].includes(jenis.toLowerCase())) {
            whereCondition.jenis_kegiatan = {
                equals: jenis.toLowerCase() === 'internal' ? 'Internal' : 'Eksternal',
            };
        }
        const [todayBookings, ruanganList] = await Promise.all([
            this.prisma.pemesanan.findMany({
                where: whereCondition,
                include: { ruangan: true, layout_ruangan: true, users: true },
                orderBy: { waktu_mulai: 'asc' },
            }),
            this.prisma.ruangan.findMany({ orderBy: { nama_ruangan: 'asc' } }),
        ]);
        const liveList = todayBookings.filter((item) => {
            const start = formatTimeStr(item.waktu_mulai);
            const end = formatTimeStr(item.waktu_selesai);
            return start <= currentTime && end >= currentTime;
        });
        const mapItem = (item) => {
            const waktuMulai = formatTimeStr(item.waktu_mulai);
            const waktuSelesai = formatTimeStr(item.waktu_selesai);
            return {
                id: Number(item.id),
                kode: item.kode_pemesanan,
                ruangan: item.ruangan?.nama_ruangan ?? '-',
                judul: item.judul_kegiatan,
                unit: item.users?.nama_unit ?? item.users?.name ?? 'Unit Internal',
                pic: item.pic_kegiatan,
                jenis_kegiatan: item.jenis_kegiatan ?? 'Internal',
                waktu: `${waktuMulai} - ${waktuSelesai} WITA`,
                end_time: `${todayStr}T${waktuSelesai}:00`,
                status: item.status,
            };
        };
        const live = liveList.map(mapItem);
        const today = todayBookings.map(mapItem);
        return {
            status: 'success',
            timestamp: now.format('dddd, DD MMMM YYYY HH:mm:ss'),
            live_count: live.length,
            live,
            today,
            ruangan: ruanganList,
            data: {
                server_time: now.format('HH:mm:ss'),
                server_date: todayStr,
                berlangsung: live,
                mendatang: today,
                ruangan: ruanganList,
            },
        };
    }
};
exports.DisplayService = DisplayService;
exports.DisplayService = DisplayService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService])
], DisplayService);
//# sourceMappingURL=display.service.js.map