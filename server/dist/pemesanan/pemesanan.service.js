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
exports.PemesananService = void 0;
exports.formatTimeStr = formatTimeStr;
const common_1 = require("@nestjs/common");
const prisma_service_1 = require("../prisma/prisma.service");
const dayjs_1 = __importDefault(require("dayjs"));
const timezone_1 = __importDefault(require("dayjs/plugin/timezone"));
const utc_1 = __importDefault(require("dayjs/plugin/utc"));
dayjs_1.default.extend(utc_1.default);
dayjs_1.default.extend(timezone_1.default);
const WITA = 'Asia/Makassar';
const STATUS = {
    PENDING: 'Pending',
    DISETUJUI: 'Disetujui',
    DITOLAK: 'Ditolak',
    DIBATALKAN: 'Cancel',
    SELESAI: 'Selesai',
};
function parseTimeToDate(timeStr) {
    if (!timeStr)
        return new Date('1970-01-01T00:00:00Z');
    const parts = String(timeStr).split(':');
    const h = (parts[0] || '00').padStart(2, '0');
    const m = (parts[1] || '00').padStart(2, '0');
    const s = (parts[2] || '00').padStart(2, '0');
    return new Date(`1970-01-01T${h}:${m}:${s}Z`);
}
function formatTimeStr(val) {
    if (!val)
        return '';
    if (val instanceof Date) {
        return val.toISOString().substring(11, 19);
    }
    return String(val);
}
const notification_service_1 = require("../notification/notification.service");
const whatsapp_service_1 = require("../whatsapp/whatsapp.service");
let PemesananService = class PemesananService {
    constructor(prisma, notificationService, whatsapp) {
        this.prisma = prisma;
        this.notificationService = notificationService;
        this.whatsapp = whatsapp;
        this.formatPemesanan = (p) => {
            const waktuMulaiStr = formatTimeStr(p.waktu_mulai);
            const waktuSelesaiStr = formatTimeStr(p.waktu_selesai);
            const durasi = this.computeDurasi(waktuMulaiStr, waktuSelesaiStr);
            const userObj = p.users || p.user || null;
            const layoutObj = p.layout_ruangan || p.layout || null;
            return {
                ...p,
                user: userObj,
                users: userObj,
                layout: layoutObj,
                layout_ruangan: layoutObj,
                waktu_mulai: waktuMulaiStr,
                waktu_selesai: waktuSelesaiStr,
                durasi: durasi.jam * 60 + durasi.menit,
                durasi_format: this.formatDurasi(durasi),
                tanggal_kegiatan: p.tanggal_kegiatan
                    ? (0, dayjs_1.default)(p.tanggal_kegiatan).format('YYYY-MM-DD')
                    : null,
            };
        };
        this.computeDurasi = (mulai, selesai) => {
            if (!mulai || !selesai)
                return { jam: 0, menit: 0 };
            const [mH, mM] = mulai.split(':').map(Number);
            const [sH, sM] = selesai.split(':').map(Number);
            const totalMenit = sH * 60 + sM - (mH * 60 + mM);
            return { jam: Math.floor(totalMenit / 60), menit: totalMenit % 60 };
        };
        this.formatDurasi = (d) => {
            if (d.jam > 0 && d.menit > 0)
                return `${d.jam} jam ${d.menit} menit`;
            if (d.jam > 0)
                return `${d.jam} jam`;
            if (d.menit > 0)
                return `${d.menit} menit`;
            return '-';
        };
    }
    formatTimeStr(val) {
        return formatTimeStr(val);
    }
    async markFinishedAgendas() {
        const now = (0, dayjs_1.default)().tz(WITA);
        const todayStr = now.format('YYYY-MM-DD');
        const currentTime = now.format('HH:mm:ss');
        const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
        const pastResult = await this.prisma.pemesanan.updateMany({
            where: {
                status: STATUS.DISETUJUI,
                tanggal_kegiatan: { lt: todayDate },
            },
            data: { status: STATUS.SELESAI },
        });
        const todayApproved = await this.prisma.pemesanan.findMany({
            where: {
                status: STATUS.DISETUJUI,
                tanggal_kegiatan: todayDate,
            },
            select: { id: true, waktu_selesai: true },
        });
        const finishedIds = todayApproved
            .filter((p) => formatTimeStr(p.waktu_selesai) <= currentTime)
            .map((p) => p.id);
        let todayCount = 0;
        if (finishedIds.length > 0) {
            const todayResult = await this.prisma.pemesanan.updateMany({
                where: { id: { in: finishedIds } },
                data: { status: STATUS.SELESAI },
            });
            todayCount = todayResult.count;
        }
        return pastResult.count + todayCount;
    }
    async generateKodePemesanan(rawUnitCode) {
        const dateStr = (0, dayjs_1.default)().tz(WITA).format('YYYYMMDD');
        let cleanUnit = (rawUnitCode || 'BI')
            .toString()
            .replace(/[^a-zA-Z0-9]/g, '')
            .toUpperCase();
        if (!cleanUnit) {
            cleanUnit = 'BI';
        }
        else if (cleanUnit.length > 6) {
            cleanUnit = cleanUnit.substring(0, 6);
        }
        const targetTailLength = 7;
        const padLength = Math.max(1, targetTailLength - cleanUnit.length);
        const prefix = `SIL-${dateStr}-`;
        const existing = await this.prisma.pemesanan.findMany({
            where: {
                kode_pemesanan: {
                    startsWith: prefix,
                    endsWith: cleanUnit,
                },
            },
            select: {
                kode_pemesanan: true,
            },
        });
        let maxSeq = 0;
        for (const item of existing) {
            const tail = item.kode_pemesanan.substring(prefix.length);
            if (tail.endsWith(cleanUnit)) {
                const numPart = tail.substring(0, tail.length - cleanUnit.length);
                const parsed = parseInt(numPart, 10);
                if (!isNaN(parsed) && parsed > maxSeq) {
                    maxSeq = parsed;
                }
            }
        }
        let seq = maxSeq + 1;
        let kode;
        let exists = true;
        do {
            const numStr = String(seq).padStart(padLength, '0');
            kode = `${prefix}${numStr}${cleanUnit}`;
            const found = await this.prisma.pemesanan.findFirst({
                where: { kode_pemesanan: kode },
            });
            if (found) {
                seq++;
            }
            else {
                exists = false;
            }
        } while (exists);
        return kode;
    }
    async checkConflict(data) {
        const statusFilter = data.include_pending
            ? [STATUS.PENDING, STATUS.DISETUJUI]
            : [STATUS.DISETUJUI, STATUS.SELESAI];
        const conflicts = await this.prisma.pemesanan.findMany({
            where: {
                ruangan_id: data.ruangan_id,
                tanggal_kegiatan: new Date(data.tanggal_kegiatan),
                status: { in: statusFilter },
                id: data.exclude_id ? { not: data.exclude_id } : undefined,
                AND: [
                    { waktu_mulai: { lt: parseTimeToDate(data.waktu_selesai) } },
                    { waktu_selesai: { gt: parseTimeToDate(data.waktu_mulai) } },
                ],
            },
            include: { users: true, ruangan: true },
        });
        return conflicts;
    }
    async index(userId, query) {
        await this.markFinishedAgendas();
        const page = parseInt(query.page ?? '1');
        const perPage = parseInt(query.per_page ?? '10');
        const skip = (page - 1) * perPage;
        const where = { user_id: userId };
        if (query.status) {
            where.status = query.status;
        }
        if (query.q) {
            where.OR = [
                { kode_pemesanan: { contains: query.q } },
                { judul_kegiatan: { contains: query.q } },
                { pic_kegiatan: { contains: query.q } },
            ];
        }
        const [total, items] = await Promise.all([
            this.prisma.pemesanan.count({ where }),
            this.prisma.pemesanan.findMany({
                where,
                include: { ruangan: true, layout_ruangan: true, users: true },
                orderBy: [{ created_at: 'desc' }, { id: 'desc' }],
                skip,
                take: perPage,
            }),
        ]);
        return {
            status: 'success',
            data: {
                data: items.map((p) => this.formatPemesanan(p)),
                current_page: page,
                per_page: perPage,
                total,
                last_page: Math.ceil(total / perPage),
            },
        };
    }
    async store(dto, user, filePath) {
        const nowWita = (0, dayjs_1.default)().tz(WITA);
        const todayWita = nowWita.format('YYYY-MM-DD');
        const bookingDate = (0, dayjs_1.default)(dto.tanggal_kegiatan).format('YYYY-MM-DD');
        const currentTimeWita = nowWita.format('HH:mm');
        if (bookingDate < todayWita) {
            throw new common_1.BadRequestException('Tanggal kegiatan tidak boleh di masa lalu.');
        }
        const dayOfWeek = (0, dayjs_1.default)(bookingDate).day();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            throw new common_1.BadRequestException('Pemesanan ruangan hanya dapat dilakukan pada hari kerja (Senin - Jumat). Hari Sabtu dan Minggu tidak dapat dipilih untuk rapat.');
        }
        const holiday = await this.prisma.hari_libur.findFirst({
            where: { tanggal: new Date(bookingDate) },
        });
        if (holiday) {
            throw new common_1.BadRequestException(`Tanggal ${bookingDate} merupakan hari libur (${holiday.keterangan}). Pemesanan ruangan tidak dapat dilakukan pada hari libur.`);
        }
        if (dto.waktu_mulai < '08:00' || dto.waktu_mulai >= '19:00') {
            throw new common_1.BadRequestException('Jam mulai rapat harus berada di antara pukul 08:00 WITA hingga 18:30 WITA.');
        }
        if (dto.waktu_selesai <= dto.waktu_mulai) {
            throw new common_1.BadRequestException('Jam selesai rapat harus lebih besar dari jam mulai.');
        }
        if (dto.waktu_selesai > '19:00') {
            throw new common_1.BadRequestException('Jam selesai rapat tidak boleh melebihi pukul 19:00 WITA. Jam operasional rapat adalah pukul 08:00 - 19:00 WITA.');
        }
        if (bookingDate === todayWita && dto.waktu_mulai <= currentTimeWita) {
            throw new common_1.BadRequestException(`Waktu mulai (${dto.waktu_mulai} WITA) sudah lewat untuk hari ini. Silakan pilih waktu yang akan datang.`);
        }
        const ruanganId = Number(dto.ruangan_id);
        const layoutRuanganId = dto.layout_ruangan_id ? Number(dto.layout_ruangan_id) : null;
        const jumlahTamu = Number(dto.jumlah_tamu);
        const ruangan = await this.prisma.ruangan.findUnique({
            where: { id: ruanganId },
        });
        if (!ruangan)
            throw new common_1.NotFoundException('Ruangan tidak ditemukan.');
        if (jumlahTamu > (ruangan.kapasitas ?? 0)) {
            throw new common_1.BadRequestException(`Jumlah tamu (${jumlahTamu}) melebihi kapasitas maksimal ruangan ${ruangan.nama_ruangan} (${ruangan.kapasitas} orang).`);
        }
        const conflicts = await this.checkConflict({
            ruangan_id: ruanganId,
            tanggal_kegiatan: dto.tanggal_kegiatan,
            waktu_mulai: dto.waktu_mulai,
            waktu_selesai: dto.waktu_selesai,
            include_pending: false,
        });
        if (conflicts.length > 0) {
            throw new common_1.BadRequestException('Ruangan sudah memiliki agenda kegiatan pada tanggal dan jam tersebut.');
        }
        const isAdmin = user.role === 'admin';
        const initialStatus = isAdmin ? STATUS.DISETUJUI : STATUS.PENDING;
        const now = new Date();
        let targetUserId = user.id;
        let unitCode = (user.kode_unit || user.departments?.kode_unit || 'BI').trim().toUpperCase();
        if (isAdmin) {
            if (dto.user_id) {
                const found = await this.prisma.users.findUnique({
                    where: { id: BigInt(dto.user_id) },
                });
                if (found) {
                    targetUserId = found.id;
                    if (found.kode_unit) {
                        unitCode = found.kode_unit.trim().toUpperCase();
                    }
                }
            }
            else if (dto.kode_unit) {
                const found = await this.prisma.users.findFirst({
                    where: { kode_unit: dto.kode_unit.trim().toUpperCase() },
                });
                if (found) {
                    targetUserId = found.id;
                    unitCode = found.kode_unit.trim().toUpperCase();
                }
                else {
                    unitCode = dto.kode_unit.trim().toUpperCase();
                }
            }
        }
        const kode = await this.generateKodePemesanan(unitCode);
        const pemesanan = await this.prisma.pemesanan.create({
            data: {
                kode_pemesanan: kode,
                user_id: targetUserId,
                ruangan_id: ruanganId,
                layout_ruangan_id: layoutRuanganId,
                tanggal_kegiatan: new Date(dto.tanggal_kegiatan),
                waktu_mulai: parseTimeToDate(dto.waktu_mulai),
                waktu_selesai: parseTimeToDate(dto.waktu_selesai),
                judul_kegiatan: dto.judul_kegiatan,
                jenis_kegiatan: dto.jenis_kegiatan ?? 'Internal',
                pic_kegiatan: dto.pic_kegiatan,
                jenis_pic: (dto.jenis_pic === 'Non Organik' ? 'Non_Organik' : dto.jenis_pic),
                no_wa_pic: dto.no_wa_pic ?? null,
                jumlah_tamu: jumlahTamu,
                keterangan_layout: dto.keterangan_layout ?? null,
                catatan_user: dto.catatan_user ?? null,
                file_disposisi: filePath ?? null,
                status: initialStatus,
                approved_by: isAdmin ? user.id : null,
                approved_at: isAdmin ? now : null,
                created_at: now,
                updated_at: now,
            },
            include: { ruangan: true, layout_ruangan: true, users: true },
        });
        try {
            await this.prisma.pemesanan_status_history.create({
                data: {
                    pemesanan_id: pemesanan.id,
                    status_lama: STATUS.PENDING,
                    status_baru: initialStatus,
                    changed_by: user.id,
                    changed_at: now,
                    created_at: now,
                    updated_at: now,
                },
            });
        }
        catch (e) {
        }
        if (!isAdmin) {
            const unitName = user.nama_unit || user.name || 'Suatu Unit';
            const pesan = `Unit ${unitName} mengajukan pemesanan untuk kegiatan "${pemesanan.judul_kegiatan}" di ruangan ${ruangan.nama_ruangan} pada tanggal ${dto.tanggal_kegiatan} (${dto.waktu_mulai} - ${dto.waktu_selesai} WITA).`;
            try {
                await this.notificationService.notifyAdmins({
                    type: 'App\\Notifications\\PemesananBaruNotification',
                    judul: 'Pengajuan Pemesanan Baru',
                    pesan,
                    pemesananId: pemesanan.id,
                    url: `/admin/approval/${pemesanan.id}`,
                });
            }
            catch (err) {
                console.error('Failed to create admin notification:', err);
            }
            try {
                await this.whatsapp.notifyAdmin(`🔔 *PENGAJUAN PEMESANAN BARU*\n\nKode: *${pemesanan.kode_pemesanan}*\nUnit: *${unitName}*\nKegiatan: *${pemesanan.judul_kegiatan}*\nRuangan: *${ruangan.nama_ruangan}*\nTanggal: *${dto.tanggal_kegiatan}*\nJam: *${dto.waktu_mulai} - ${dto.waktu_selesai} WITA*\nPIC: *${pemesanan.pic_kegiatan}*\n\nSilakan verifikasi melalui web SILAKAN.`);
            }
            catch (err) {
                console.error('Failed to send admin WA notification:', err);
            }
        }
        return {
            status: 'success',
            message: isAdmin
                ? 'Pemesanan berhasil dijadwalkan dan langsung disetujui.'
                : 'Pemesanan berhasil dibuat dan menunggu persetujuan admin.',
            data: this.formatPemesanan(pemesanan),
        };
    }
    async show(id, user) {
        const pemesanan = await this.prisma.pemesanan.findUnique({
            where: { id },
            include: {
                ruangan: true,
                layout_ruangan: true,
                users: true,
                pemesanan_status_history: { include: { users: true } },
            },
        });
        if (!pemesanan)
            throw new common_1.NotFoundException('Pemesanan tidak ditemukan.');
        if (user.role !== 'admin' && Number(pemesanan.user_id) !== Number(user.id)) {
            throw new common_1.ForbiddenException('Akses ditolak.');
        }
        return { status: 'success', data: this.formatPemesanan(pemesanan) };
    }
    async cancel(id, user) {
        const pemesanan = await this.prisma.pemesanan.findUnique({ where: { id } });
        if (!pemesanan)
            throw new common_1.NotFoundException('Pemesanan tidak ditemukan.');
        if (user.role !== 'admin' && Number(pemesanan.user_id) !== Number(user.id)) {
            throw new common_1.ForbiddenException('Akses ditolak.');
        }
        if (!['Pending', 'Disetujui'].includes(pemesanan.status ?? '')) {
            throw new common_1.BadRequestException('Pemesanan tidak dapat dibatalkan pada status ini.');
        }
        const updated = await this.prisma.pemesanan.update({
            where: { id },
            data: {
                status: STATUS.DIBATALKAN,
                cancelled_by: user.id ? BigInt(user.id) : null,
                cancelled_at: new Date(),
            },
        });
        return {
            status: 'success',
            message: 'Pemesanan berhasil dibatalkan.',
            data: this.formatPemesanan(updated),
        };
    }
    async selesaiAwal(id, user) {
        const pemesanan = await this.prisma.pemesanan.findUnique({
            where: { id },
            include: { ruangan: true },
        });
        if (!pemesanan)
            throw new common_1.NotFoundException('Pemesanan tidak ditemukan.');
        if (user.role !== 'admin' && Number(pemesanan.user_id) !== Number(user.id)) {
            throw new common_1.ForbiddenException('Akses ditolak.');
        }
        if (pemesanan.status !== STATUS.DISETUJUI) {
            throw new common_1.BadRequestException('Hanya kegiatan berstatus Disetujui yang dapat diselesaikan lebih awal.');
        }
        const now = (0, dayjs_1.default)().tz(WITA);
        const today = now.format('YYYY-MM-DD');
        const eventDate = (0, dayjs_1.default)(pemesanan.tanggal_kegiatan).format('YYYY-MM-DD');
        if (user.role !== 'admin' && eventDate !== today) {
            throw new common_1.BadRequestException('Hanya kegiatan yang berlangsung hari ini yang dapat diselesaikan lebih awal.');
        }
        const currentTime = now.format('HH:mm:ss');
        const updated = await this.prisma.pemesanan.update({
            where: { id },
            data: { status: STATUS.SELESAI, waktu_selesai: parseTimeToDate(currentTime) },
        });
        const historyNow = new Date();
        await this.prisma.pemesanan_status_history.create({
            data: {
                pemesanan_id: pemesanan.id,
                status_lama: pemesanan.status,
                status_baru: STATUS.SELESAI,
                changed_by: user.id ? BigInt(user.id) : null,
                changed_at: historyNow,
                created_at: historyNow,
                updated_at: historyNow,
            },
        }).catch(() => null);
        return {
            status: 'success',
            message: `Kegiatan di ${pemesanan.ruangan?.nama_ruangan || 'ruangan'} berhasil diselesaikan lebih awal pada ${currentTime} WITA.`,
            data: this.formatPemesanan(updated),
        };
    }
    async checkConflictApi(query) {
        const conflicts = await this.checkConflict({
            ruangan_id: parseInt(query.ruangan_id),
            tanggal_kegiatan: query.tanggal_kegiatan,
            waktu_mulai: query.waktu_mulai,
            waktu_selesai: query.waktu_selesai,
            exclude_id: query.exclude_id ? parseInt(query.exclude_id) : undefined,
            include_pending: true,
        });
        const hasConflict = conflicts.length > 0;
        const conflictDetails = conflicts.map((c) => ({
            id: c.id,
            kode_pemesanan: c.kode_pemesanan,
            judul_kegiatan: c.judul_kegiatan,
            unit: c.users?.nama_unit ?? c.users?.name ?? 'Unit tidak diketahui',
            pic: c.pic_kegiatan,
            waktu_mulai: formatTimeStr(c.waktu_mulai).slice(0, 5),
            waktu_selesai: formatTimeStr(c.waktu_selesai).slice(0, 5),
            status: c.status,
        }));
        return {
            status: 'success',
            conflict: hasConflict,
            count: conflicts.length,
            conflicts: conflictDetails,
            message: hasConflict
                ? `Terdapat ${conflicts.length} jadwal kegiatan yang bentrok pada ruangan dan jam tersebut.`
                : 'Jadwal ruangan tersedia!',
        };
    }
    async getUnits() {
        const users = await this.prisma.users.findMany({
            select: {
                id: true,
                name: true,
                nama_unit: true,
                kode_unit: true,
                role: true,
            },
            orderBy: { kode_unit: 'asc' },
        });
        const units = users.map((u) => ({
            id: Number(u.id),
            nama_unit: u.nama_unit || u.name || '',
            kode_unit: (u.kode_unit || '').toUpperCase(),
            role: u.role,
        }));
        return { status: 'success', data: units };
    }
};
exports.PemesananService = PemesananService;
exports.PemesananService = PemesananService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService,
        notification_service_1.NotificationService,
        whatsapp_service_1.WhatsappService])
], PemesananService);
//# sourceMappingURL=pemesanan.service.js.map