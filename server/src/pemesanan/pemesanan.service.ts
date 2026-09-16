import {
  Injectable,
  NotFoundException,
  ForbiddenException,
  BadRequestException,
  Logger,
} from '@nestjs/common';
import { Cron, CronExpression } from '@nestjs/schedule';
import { PrismaService } from '../prisma/prisma.service';
import { CreatePemesananDto } from './dto/create-pemesanan.dto';
import dayjs from 'dayjs';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';

dayjs.extend(utc);
dayjs.extend(timezone);

const WITA = 'Asia/Makassar';

const STATUS = {
  PENDING: 'Pending' as const,
  DISETUJUI: 'Disetujui' as const,
  DITOLAK: 'Ditolak' as const,
  DIBATALKAN: 'Cancel' as const,
  SELESAI: 'Selesai' as const,
};

function parseTimeToDate(timeStr: string): Date {
  if (!timeStr) return new Date('1970-01-01T00:00:00Z');
  const parts = String(timeStr).split(':');
  const h = (parts[0] || '00').padStart(2, '0');
  const m = (parts[1] || '00').padStart(2, '0');
  const s = (parts[2] || '00').padStart(2, '0');
  return new Date(`1970-01-01T${h}:${m}:${s}Z`);
}

export function formatTimeStr(val: any): string {
  if (!val) return '';
  if (val instanceof Date) {
    return val.toISOString().substring(11, 19);
  }
  return String(val);
}

import { NotificationService } from '../notification/notification.service';
import { WhatsappService } from '../whatsapp/whatsapp.service';
import { AuditLogService } from '../audit-log/audit-log.service';

@Injectable()
export class PemesananService {
  private readonly logger = new Logger(PemesananService.name);

  constructor(
    private prisma: PrismaService,
    private notificationService: NotificationService,
    private whatsapp: WhatsappService,
    private auditLog: AuditLogService,
  ) {}

  /**
   * Cron job running every 5 minutes to automatically transition finished meetings to 'Selesai'
   */
  @Cron(CronExpression.EVERY_5_MINUTES)
  async handleCronMarkFinishedAgendas() {
    try {
      const count = await this.markFinishedAgendas();
      if (count > 0) {
        this.logger.log(`Cron: Successfully auto-marked ${count} meetings as Selesai.`);
      }
    } catch (err) {
      this.logger.error('Cron markFinishedAgendas failed:', err);
    }
  }

  formatTimeStr(val: any): string {
    return formatTimeStr(val);
  }

  /**
   * Auto-mark finished agendas — equivalent to Pemesanan::markFinishedAgendas()
   */
  async markFinishedAgendas(): Promise<number> {
    const now = dayjs().tz(WITA);
    const todayStr = now.format('YYYY-MM-DD');
    const currentTime = now.format('HH:mm:ss');
    const todayDate = new Date(`${todayStr}T00:00:00.000Z`);

    // 1. Mark meetings from past days as Selesai
    const pastResult = await this.prisma.pemesanan.updateMany({
      where: {
        status: STATUS.DISETUJUI,
        tanggal_kegiatan: { lt: todayDate },
      },
      data: { status: STATUS.SELESAI },
    });

    // 2. Mark today's meetings that have ended as Selesai
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

  /**
   * Generate unique kode pemesanan with daily sequence counter and dynamic zero-padding.
   * Total length: exactly 20 characters!
   * Format: SIL-YYYYMMDD-[SEQ_PAD][KODE_UNIT]
   *
   * Example:
   *   Unit Kehumasan (UK, 2 huruf) -> SIL-20260915-00001UK (Length: 20)
   *   Unit FDSEK (FDSEK, 5 huruf)  -> SIL-20260915-01FDSEK  (Length: 20)
   *   Unit PUR (PUR, 3 huruf)      -> SIL-20260915-0001PUR  (Length: 20)
   *   Unit FPKP (FPKP, 4 huruf)    -> SIL-20260915-001FPKP  (Length: 20)
   *   Unit PIPEBI (PIPEBI, 6 huruf)-> SIL-20260915-1PIPEBI  (Length: 20)
   */
  async generateKodePemesanan(rawUnitCode?: string): Promise<string> {
    const dateStr = dayjs().tz(WITA).format('YYYYMMDD');

    // Clean unit code: uppercase, alphanumeric only, max 6 chars (so at least 1 digit for counter)
    let cleanUnit = (rawUnitCode || 'BI')
      .toString()
      .replace(/[^a-zA-Z0-9]/g, '')
      .toUpperCase();

    if (!cleanUnit) {
      cleanUnit = 'BI';
    } else if (cleanUnit.length > 6) {
      cleanUnit = cleanUnit.substring(0, 6);
    }

    // Target tail length is 7 characters (e.g. 00001UK, 01FDSEK)
    const targetTailLength = 7;
    const padLength = Math.max(1, targetTailLength - cleanUnit.length);

    // Find existing bookings on this date for this unit to determine next sequence number
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
    let kode: string;
    let exists = true;

    do {
      const numStr = String(seq).padStart(padLength, '0');
      kode = `${prefix}${numStr}${cleanUnit}`;
      const found = await this.prisma.pemesanan.findFirst({
        where: { kode_pemesanan: kode },
      });
      if (found) {
        seq++;
      } else {
        exists = false;
      }
    } while (exists);

    return kode;
  }

  /**
   * Check booking conflict — equivalent to Pemesanan::scopeConflict()
   */
  async checkConflict(data: {
    ruangan_id: number;
    tanggal_kegiatan: string;
    waktu_mulai: string;
    waktu_selesai: string;
    exclude_id?: number;
    include_pending?: boolean;
  }) {
    const statusFilter: any[] = data.include_pending
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

  /**
   * List user's bookings with pagination — equivalent to PemesananApiController::index()
   */
  async index(userId: number, query: any) {
    await this.markFinishedAgendas();

    const page = parseInt(query.page ?? '1');
    const perPage = parseInt(query.per_page ?? '10');
    const skip = (page - 1) * perPage;

    const where: any = { user_id: userId };

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

  /**
   * Create booking — equivalent to CreatePemesananAction
   */
  async store(dto: CreatePemesananDto, user: any, filePath?: string) {
    // Validate that meeting time is not in the past
    const nowWita = dayjs().tz(WITA);
    const todayWita = nowWita.format('YYYY-MM-DD');
    const bookingDate = dayjs(dto.tanggal_kegiatan).format('YYYY-MM-DD');
    const currentTimeWita = nowWita.format('HH:mm');

    if (bookingDate < todayWita) {
      throw new BadRequestException('Tanggal kegiatan tidak boleh di masa lalu.');
    }

    // Validasi Hari Kerja: Hanya Senin - Jumat (0 = Minggu, 6 = Sabtu)
    const dayOfWeek = dayjs(bookingDate).day();
    if (dayOfWeek === 0 || dayOfWeek === 6) {
      throw new BadRequestException(
        'Pemesanan ruangan hanya dapat dilakukan pada hari kerja (Senin - Jumat). Hari Sabtu dan Minggu tidak dapat dipilih untuk rapat.',
      );
    }

    // Validasi Hari Libur: Cek apakah tanggal terdaftar di database hari_libur
    const holiday = await this.prisma.hari_libur.findFirst({
      where: { tanggal: new Date(bookingDate) },
    });
    if (holiday) {
      throw new BadRequestException(
        `Tanggal ${bookingDate} merupakan hari libur (${holiday.keterangan}). Pemesanan ruangan tidak dapat dilakukan pada hari libur.`,
      );
    }

    // Validasi Jam Operasional Rapat: 08:00 - 19:00 WITA
    if (dto.waktu_mulai < '08:00' || dto.waktu_mulai >= '19:00') {
      throw new BadRequestException(
        'Jam mulai rapat harus berada di antara pukul 08:00 WITA hingga 18:30 WITA.',
      );
    }

    if (dto.waktu_selesai <= dto.waktu_mulai) {
      throw new BadRequestException(
        'Jam selesai rapat harus lebih besar dari jam mulai.',
      );
    }

    if (dto.waktu_selesai > '19:00') {
      throw new BadRequestException(
        'Jam selesai rapat tidak boleh melebihi pukul 19:00 WITA. Jam operasional rapat adalah pukul 08:00 - 19:00 WITA.',
      );
    }

    if (bookingDate === todayWita && dto.waktu_mulai <= currentTimeWita) {
      throw new BadRequestException(
        `Waktu mulai (${dto.waktu_mulai} WITA) sudah lewat untuk hari ini. Silakan pilih waktu yang akan datang.`,
      );
    }

    const ruanganId = Number(dto.ruangan_id);
    const layoutRuanganId = dto.layout_ruangan_id ? Number(dto.layout_ruangan_id) : null;
    const jumlahTamu = Number(dto.jumlah_tamu);

    // Check room capacity
    const ruangan = await this.prisma.ruangan.findUnique({
      where: { id: ruanganId },
    });
    if (!ruangan) throw new NotFoundException('Ruangan tidak ditemukan.');

    if (jumlahTamu > (ruangan.kapasitas ?? 0)) {
      throw new BadRequestException(
        `Jumlah tamu (${jumlahTamu}) melebihi kapasitas maksimal ruangan ${ruangan.nama_ruangan} (${ruangan.kapasitas} orang).`,
      );
    }

    // Check conflict
    const conflicts = await this.checkConflict({
      ruangan_id: ruanganId,
      tanggal_kegiatan: dto.tanggal_kegiatan,
      waktu_mulai: dto.waktu_mulai,
      waktu_selesai: dto.waktu_selesai,
      include_pending: false,
    });

    if (conflicts.length > 0) {
      throw new BadRequestException(
        'Ruangan sudah memiliki agenda kegiatan pada tanggal dan jam tersebut.',
      );
    }

    const isAdmin = user.role === 'admin';
    const initialStatus = isAdmin ? STATUS.DISETUJUI : STATUS.PENDING;
    const now = new Date();

    // Determine target user and unit code
    let targetUserId = user.id;
    let unitCode = (user.kode_unit || (user as any).departments?.kode_unit || 'BI').trim().toUpperCase();

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
      } else if (dto.kode_unit) {
        const found = await this.prisma.users.findFirst({
          where: { kode_unit: dto.kode_unit.trim().toUpperCase() },
        });
        if (found) {
          targetUserId = found.id;
          unitCode = found.kode_unit.trim().toUpperCase();
        } else {
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
        jenis_pic: (dto.jenis_pic === 'Non Organik' ? 'Non_Organik' : dto.jenis_pic) as any,
        no_wa_pic: dto.no_wa_pic ?? null,
        jumlah_tamu: jumlahTamu,
        keterangan_layout: dto.keterangan_layout ?? null,
        catatan_user: dto.catatan_user ?? null,
        file_disposisi: filePath ?? null,
        status: initialStatus as any,
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
          status_lama: STATUS.PENDING as any,
          status_baru: initialStatus as any,
          changed_by: user.id ? BigInt(user.id) : null,
          changed_at: now,
          created_at: now,
          updated_at: now,
        },
      });
    } catch {
      // Ignored
    }

    // Notify admins if created by regular unit/user
    if (!isAdmin) {
      const unitName = user.nama_unit || user.name || 'Suatu Unit';
      const pesan = `Unit ${unitName} mengajukan pemesanan untuk kegiatan "${pemesanan.judul_kegiatan}" di ruangan ${ruangan.nama_ruangan} pada tanggal ${dto.tanggal_kegiatan} (${dto.waktu_mulai} - ${dto.waktu_selesai} WITA).`;

      // 1. In-App Web Notification for all admins
      try {
        await this.notificationService.notifyAdmins({
          type: 'App\\Notifications\\PemesananBaruNotification',
          judul: 'Pengajuan Pemesanan Baru',
          pesan,
          pemesananId: pemesanan.id,
          url: `/admin/approval/${pemesanan.id}`,
        });
      } catch (err) {
        console.error('Failed to create admin notification:', err);
      }

      // 2. WhatsApp Notification for admin (async non-blocking)
      this.whatsapp
        .notifyAdmin(
          `🔔 *PENGAJUAN PEMESANAN BARU*\n\nKode: *${pemesanan.kode_pemesanan}*\nUnit: *${unitName}*\nKegiatan: *${pemesanan.judul_kegiatan}*\nRuangan: *${ruangan.nama_ruangan}*\nTanggal: *${dto.tanggal_kegiatan}*\nJam: *${dto.waktu_mulai} - ${dto.waktu_selesai} WITA*\nPIC: *${pemesanan.pic_kegiatan}*\n\nSilakan verifikasi melalui web SILAKAN.`,
        )
        .catch((err) => console.error('Failed to send admin WA notification:', err));
    }

    await this.auditLog.log({
      userId: user.id ? BigInt(user.id) : null,
      aksi: 'CREATE_PEMESANAN',
      modul: 'Pemesanan',
      keterangan: `Membuat pengajuan pemesanan baru: ${pemesanan.kode_pemesanan} ("${pemesanan.judul_kegiatan}")`,
    });

    return {
      status: 'success',
      message: isAdmin
        ? 'Pemesanan berhasil dijadwalkan dan langsung disetujui.'
        : 'Pemesanan berhasil dibuat dan menunggu persetujuan admin.',
      data: this.formatPemesanan(pemesanan),
    };
  }

  /**
   * Show single booking — equivalent to PemesananApiController::show()
   */
  async show(id: number, user: any) {
    const pemesanan = await this.prisma.pemesanan.findUnique({
      where: { id },
      include: {
        ruangan: true,
        layout_ruangan: true,
        users: true,
        pemesanan_status_history: { include: { users: true } },
      },
    });

    if (!pemesanan) throw new NotFoundException('Pemesanan tidak ditemukan.');

    if (user.role !== 'admin' && Number(pemesanan.user_id) !== Number(user.id)) {
      throw new ForbiddenException('Akses ditolak.');
    }

    return { status: 'success', data: this.formatPemesanan(pemesanan) };
  }

  /**
   * Cancel booking — equivalent to CancelPemesananAction
   */
  async cancel(id: number, user: any) {
    const pemesanan = await this.prisma.pemesanan.findUnique({ where: { id } });
    if (!pemesanan) throw new NotFoundException('Pemesanan tidak ditemukan.');

    if (user.role !== 'admin' && Number(pemesanan.user_id) !== Number(user.id)) {
      throw new ForbiddenException('Akses ditolak.');
    }

    if (!['Pending', 'Disetujui'].includes(pemesanan.status ?? '')) {
      throw new BadRequestException('Pemesanan tidak dapat dibatalkan pada status ini.');
    }

    const now = new Date();
    const updated = await this.prisma.pemesanan.update({
      where: { id },
      data: {
        status: STATUS.DIBATALKAN as any,
        cancelled_by: user.id ? BigInt(user.id) : null,
        cancelled_at: now,
      },
    });

    await this.prisma.pemesanan_status_history
      .create({
        data: {
          pemesanan_id: pemesanan.id,
          status_lama: pemesanan.status as any,
          status_baru: STATUS.DIBATALKAN as any,
          changed_by: user.id ? BigInt(user.id) : null,
          changed_at: now,
          created_at: now,
          updated_at: now,
        },
      })
      .catch(() => null);

    await this.auditLog.log({
      userId: user.id ? BigInt(user.id) : null,
      aksi: 'CANCEL_PEMESANAN',
      modul: 'Pemesanan',
      keterangan: `Membatalkan pemesanan: ${pemesanan.kode_pemesanan} ("${pemesanan.judul_kegiatan}")`,
    });

    return {
      status: 'success',
      message: 'Pemesanan berhasil dibatalkan.',
      data: this.formatPemesanan(updated),
    };
  }

  /**
   * Finish booking early — equivalent to PemesananApiController::selesaiAwal()
   */
  async selesaiAwal(id: number, user: any) {
    const pemesanan = await this.prisma.pemesanan.findUnique({
      where: { id },
      include: { ruangan: true },
    });
    if (!pemesanan) throw new NotFoundException('Pemesanan tidak ditemukan.');

    if (user.role !== 'admin' && Number(pemesanan.user_id) !== Number(user.id)) {
      throw new ForbiddenException('Akses ditolak.');
    }

    if (pemesanan.status !== STATUS.DISETUJUI) {
      throw new BadRequestException(
        'Hanya kegiatan berstatus Disetujui yang dapat diselesaikan lebih awal.',
      );
    }

    const now = dayjs().tz(WITA);
    const today = now.format('YYYY-MM-DD');
    const eventDate = dayjs(pemesanan.tanggal_kegiatan).format('YYYY-MM-DD');

    if (user.role !== 'admin' && eventDate !== today) {
      throw new BadRequestException(
        'Hanya kegiatan yang berlangsung hari ini yang dapat diselesaikan lebih awal.',
      );
    }

    const currentTime = now.format('HH:mm:ss');

    const updated = await this.prisma.pemesanan.update({
      where: { id },
      data: { status: STATUS.SELESAI as any, waktu_selesai: parseTimeToDate(currentTime) },
    });

    const historyNow = new Date();
    await this.prisma.pemesanan_status_history.create({
      data: {
        pemesanan_id: pemesanan.id,
        status_lama: pemesanan.status as any,
        status_baru: STATUS.SELESAI as any,
        changed_by: user.id ? BigInt(user.id) : null,
        changed_at: historyNow,
        created_at: historyNow,
        updated_at: historyNow,
      },
    }).catch(() => null);

    await this.auditLog.log({
      userId: user.id ? BigInt(user.id) : null,
      aksi: 'SELESAI_AWAL',
      modul: 'Pemesanan',
      keterangan: `Menyelesaikan rapat lebih awal: ${pemesanan.kode_pemesanan} pada pukul ${currentTime} WITA`,
    });

    return {
      status: 'success',
      message: `Kegiatan di ${pemesanan.ruangan?.nama_ruangan || 'ruangan'} berhasil diselesaikan lebih awal pada ${currentTime} WITA.`,
      data: this.formatPemesanan(updated),
    };
  }

  /**
   * Conflict check API — equivalent to PemesananApiController::checkConflict()
   */
  async checkConflictApi(query: any) {
    const conflicts = await this.checkConflict({
      ruangan_id: parseInt(query.ruangan_id),
      tanggal_kegiatan: query.tanggal_kegiatan,
      waktu_mulai: query.waktu_mulai,
      waktu_selesai: query.waktu_selesai,
      exclude_id: query.exclude_id ? parseInt(query.exclude_id) : undefined,
      include_pending: true,
    });

    const hasConflict = conflicts.length > 0;

    const conflictDetails = conflicts.map((c: any) => ({
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

  /**
   * Format pemesanan — compute durasi like Laravel's $appends accessors
   */
  formatPemesanan = (p: any) => {
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
        ? dayjs(p.tanggal_kegiatan).format('YYYY-MM-DD')
        : null,
    };
  };

  private computeDurasi = (mulai: string | null, selesai: string | null) => {
    if (!mulai || !selesai) return { jam: 0, menit: 0 };
    const [mH, mM] = mulai.split(':').map(Number);
    const [sH, sM] = selesai.split(':').map(Number);
    const totalMenit = sH * 60 + sM - (mH * 60 + mM);
    return { jam: Math.floor(totalMenit / 60), menit: totalMenit % 60 };
  };

  private formatDurasi = (d: { jam: number; menit: number }): string => {
    if (d.jam > 0 && d.menit > 0) return `${d.jam} jam ${d.menit} menit`;
    if (d.jam > 0) return `${d.jam} jam`;
    if (d.menit > 0) return `${d.menit} menit`;
    return '-';
  };

  /**
   * Get registered units from users table
   */
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
}
