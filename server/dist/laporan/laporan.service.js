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
Object.defineProperty(exports, "__esModule", { value: true });
exports.LaporanService = void 0;
const common_1 = require("@nestjs/common");
const prisma_service_1 = require("../prisma/prisma.service");
const ExcelJS = __importStar(require("exceljs"));
function formatTimeStr(val) {
    if (!val)
        return '';
    if (val instanceof Date) {
        return val.toISOString().substring(11, 19);
    }
    return String(val);
}
let LaporanService = class LaporanService {
    constructor(prisma) {
        this.prisma = prisma;
    }
    buildWhere(query) {
        const where = {};
        if (query.ruangan_id)
            where.ruangan_id = parseInt(query.ruangan_id);
        if (query.status)
            where.status = query.status;
        if (query.user_id)
            where.user_id = parseInt(query.user_id);
        if (query.jenis_pic)
            where.jenis_pic = query.jenis_pic;
        const dateFrom = query.tanggal_mulai || query.from;
        const dateTo = query.tanggal_selesai || query.to;
        if (dateFrom || dateTo) {
            where.tanggal_kegiatan = {};
            if (dateFrom)
                where.tanggal_kegiatan.gte = new Date(dateFrom);
            if (dateTo) {
                const toDate = new Date(dateTo);
                toDate.setHours(23, 59, 59, 999);
                where.tanggal_kegiatan.lte = toDate;
            }
        }
        return where;
    }
    async index(query) {
        const where = this.buildWhere(query);
        const page = parseInt(query.page) || 1;
        const perPage = parseInt(query.per_page) || 15;
        const skip = (page - 1) * perPage;
        const [items, total] = await Promise.all([
            this.prisma.pemesanan.findMany({
                where,
                include: { ruangan: true, users: true, layout_ruangan: true },
                orderBy: [{ tanggal_kegiatan: 'desc' }, { waktu_mulai: 'asc' }],
                skip,
                take: perPage,
            }),
            this.prisma.pemesanan.count({ where }),
        ]);
        const [disetujui, ditolak] = await Promise.all([
            this.prisma.pemesanan.count({ where: { ...where, status: 'Disetujui' } }),
            this.prisma.pemesanan.count({ where: { ...where, status: 'Ditolak' } }),
        ]);
        const [ruanganRaw, usersRaw] = await Promise.all([
            this.prisma.ruangan.findMany({ select: { id: true, nama_ruangan: true }, orderBy: { nama_ruangan: 'asc' } }),
            this.prisma.users.findMany({ select: { id: true, name: true, nama_unit: true }, orderBy: { name: 'asc' } }),
        ]);
        const formattedItems = items.map((p) => ({
            ...p,
            waktu_mulai: formatTimeStr(p.waktu_mulai),
            waktu_selesai: formatTimeStr(p.waktu_selesai),
            tanggal_kegiatan: p.tanggal_kegiatan
                ? (p.tanggal_kegiatan instanceof Date
                    ? p.tanggal_kegiatan.toISOString().slice(0, 10)
                    : String(p.tanggal_kegiatan).slice(0, 10))
                : null,
        }));
        return {
            status: 'success',
            data: {
                summary: { total, disetujui, ditolak },
                items: {
                    data: formattedItems,
                    current_page: page,
                    last_page: Math.ceil(total / perPage) || 1,
                    per_page: perPage,
                    total,
                    from: total > 0 ? skip + 1 : 0,
                    to: Math.min(skip + perPage, total),
                },
                filter_options: {
                    ruangan: ruanganRaw,
                    users: usersRaw.map(u => ({ id: u.id, name: u.name, nama_unit: u.nama_unit })),
                },
            },
        };
    }
    async exportExcel(query) {
        const where = this.buildWhere(query);
        const data = await this.prisma.pemesanan.findMany({
            where,
            include: { ruangan: true, users: true, layout_ruangan: true },
            orderBy: [{ tanggal_kegiatan: 'desc' }, { waktu_mulai: 'asc' }],
        });
        const workbook = new ExcelJS.Workbook();
        workbook.creator = 'Sistem SILAKAN';
        workbook.created = new Date();
        const sheet = workbook.addWorksheet('Laporan Pemesanan', {
            pageSetup: { paperSize: 9, orientation: 'landscape', fitToPage: true, fitToWidth: 1 },
        });
        sheet.mergeCells('A1:K1');
        const titleCell = sheet.getCell('A1');
        titleCell.value = 'LAPORAN PEMESANAN RUANGAN';
        titleCell.font = { bold: true, size: 14, color: { argb: 'FF003366' } };
        titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
        sheet.getRow(1).height = 30;
        sheet.mergeCells('A2:K2');
        const subTitleCell = sheet.getCell('A2');
        subTitleCell.value = 'Sistem Informasi Layanan Kantor — Kantor Perwakilan Provinsi Sulawesi Utara';
        subTitleCell.font = { size: 10, color: { argb: 'FF64748b' } };
        subTitleCell.alignment = { horizontal: 'center' };
        sheet.mergeCells('A3:K3');
        const dateCell = sheet.getCell('A3');
        dateCell.value = `Dicetak: ${new Date().toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' })} WITA`;
        dateCell.font = { size: 9, italic: true, color: { argb: 'FF94a3b8' } };
        dateCell.alignment = { horizontal: 'center' };
        sheet.getRow(3).height = 16;
        sheet.addRow([]);
        const headerRow = sheet.addRow([
            'No', 'Kode Pemesanan', 'Tanggal', 'Waktu Mulai', 'Waktu Selesai',
            'Ruangan', 'Agenda / Kegiatan', 'PIC', 'Unit', 'Tamu', 'Status',
        ]);
        headerRow.height = 22;
        headerRow.eachCell((cell) => {
            cell.font = { bold: true, color: { argb: 'FFFFFFFF' }, size: 10 };
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF003366' } };
            cell.alignment = { horizontal: 'center', vertical: 'middle' };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF005baa' } },
                bottom: { style: 'thin', color: { argb: 'FF005baa' } },
                left: { style: 'thin', color: { argb: 'FF005baa' } },
                right: { style: 'thin', color: { argb: 'FF005baa' } },
            };
        });
        sheet.getColumn(1).width = 5;
        sheet.getColumn(2).width = 22;
        sheet.getColumn(3).width = 14;
        sheet.getColumn(4).width = 12;
        sheet.getColumn(5).width = 12;
        sheet.getColumn(6).width = 22;
        sheet.getColumn(7).width = 36;
        sheet.getColumn(8).width = 22;
        sheet.getColumn(9).width = 22;
        sheet.getColumn(10).width = 8;
        sheet.getColumn(11).width = 14;
        data.forEach((p, index) => {
            const isEven = index % 2 === 0;
            const waktuMulai = formatTimeStr(p.waktu_mulai).slice(0, 5) || '-';
            const waktuSelesai = formatTimeStr(p.waktu_selesai).slice(0, 5) || '-';
            const row = sheet.addRow([
                index + 1,
                p.kode_pemesanan ?? '-',
                p.tanggal_kegiatan ? new Date(p.tanggal_kegiatan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-',
                waktuMulai,
                waktuSelesai,
                p.ruangan?.nama_ruangan ?? '-',
                p.judul_kegiatan ?? '-',
                p.pic_kegiatan ?? '-',
                p.users?.nama_unit ?? p.users?.name ?? '-',
                p.jumlah_tamu ?? 0,
                p.status ?? '-',
            ]);
            const rowBg = isEven ? 'FFF8FAFC' : 'FFFFFFFF';
            let statusBg = rowBg;
            let statusFg = 'FF374151';
            if (p.status === 'Disetujui') {
                statusBg = 'FFdcfce7';
                statusFg = 'FF166534';
            }
            else if (p.status === 'Ditolak') {
                statusBg = 'FFfee2e2';
                statusFg = 'FF991b1b';
            }
            else if (p.status === 'Menunggu') {
                statusBg = 'FFfef9c3';
                statusFg = 'FF854d0e';
            }
            else if (p.status === 'Selesai') {
                statusBg = 'FFe0f2fe';
                statusFg = 'FF075985';
            }
            row.eachCell((cell, colNumber) => {
                const bg = colNumber === 11 ? statusBg : rowBg;
                const fg = colNumber === 11 ? statusFg : 'FF374151';
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: bg } };
                cell.font = { size: 9, color: { argb: fg }, bold: colNumber === 11 };
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: (colNumber === 1 || colNumber === 10) ? 'center' : 'left',
                    wrapText: colNumber === 7,
                };
                cell.border = {
                    bottom: { style: 'hair', color: { argb: 'FFe2e8f0' } },
                    right: { style: 'hair', color: { argb: 'FFe2e8f0' } },
                };
            });
            row.height = 18;
        });
        sheet.addRow([]);
        const lastRow = sheet.rowCount + 1;
        sheet.mergeCells(`A${lastRow}:K${lastRow}`);
        const footerRow = sheet.getRow(lastRow);
        footerRow.getCell(1).value = `Total: ${data.length} data pemesanan`;
        footerRow.getCell(1).font = { italic: true, size: 9, color: { argb: 'FF64748b' } };
        return workbook.xlsx.writeBuffer();
    }
};
exports.LaporanService = LaporanService;
exports.LaporanService = LaporanService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [prisma_service_1.PrismaService])
], LaporanService);
//# sourceMappingURL=laporan.service.js.map