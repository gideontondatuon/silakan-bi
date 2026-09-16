import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { PemesananService } from '../pemesanan/pemesanan.service';
import { WhatsappService } from '../whatsapp/whatsapp.service';
import * as bcrypt from 'bcryptjs';
import dayjs from 'dayjs';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';
dayjs.extend(utc); dayjs.extend(timezone);
const WITA = 'Asia/Makassar';

function sanitizeUser(user: any) {
  if (!user) return user;
  const { password: _p, password_plain: _pp, ...rest } = user;
  return rest;
}

import { NotificationService } from '../notification/notification.service';
import { AuditLogService } from '../audit-log/audit-log.service';

@Injectable()
export class AdminService {
  constructor(
    private prisma: PrismaService,
    private pemesananService: PemesananService,
    private whatsapp: WhatsappService,
    private notificationService: NotificationService,
    private auditLog: AuditLogService,
  ) {}

  // ─── APPROVAL ──────────────────────────────────────────────────────────────

  async approvalIndex(query: any) {
    await this.pemesananService.markFinishedAgendas();
    const tab = query.tab ?? 'pending';
    const page = parseInt(query.page ?? '1');
    const perPage = parseInt(query.per_page ?? '10');
    const skip = (page - 1) * perPage;

    const where: any = {};
    if (tab === 'pending') where.status = 'Pending';
    else if (tab === 'disetujui') where.status = 'Disetujui';
    else if (tab === 'selesai') where.status = 'Selesai';

    if (query.q) {
      where.OR = [
        { kode_pemesanan: { contains: query.q } },
        { judul_kegiatan: { contains: query.q } },
        { pic_kegiatan: { contains: query.q } },
        { users: { OR: [{ name: { contains: query.q } }, { nama_unit: { contains: query.q } }] } },
        { ruangan: { nama_ruangan: { contains: query.q } } },
      ];
    }
    if (query.ruangan_id) where.ruangan_id = parseInt(query.ruangan_id);
    if (query.tanggal) where.tanggal_kegiatan = new Date(query.tanggal);

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

  async approvalShow(id: number) {
    const pemesanan = await this.prisma.pemesanan.findUnique({
      where: { id },
      include: { ruangan: true, layout_ruangan: true, users: true, pemesanan_status_history: { include: { users: true } } },
    });
    if (!pemesanan) throw new NotFoundException('Pemesanan tidak ditemukan.');
    return { status: 'success', data: this.pemesananService.formatPemesanan(pemesanan) };
  }

  async approve(id: number, adminUser: any, catatanAdmin?: string) {
    const pemesanan = await this.prisma.pemesanan.findUnique({
      where: { id },
      include: { users: true, ruangan: true },
    });
    if (!pemesanan) throw new NotFoundException('Pemesanan tidak ditemukan.');
    if (pemesanan.status !== 'Pending') {
      throw new BadRequestException('Hanya pemesanan berstatus pending yang dapat disetujui.');
    }

    // 1. Validasi bentrok jadwal sebelum disetujui (mencegah double booking)
    const tanggalStr = dayjs(pemesanan.tanggal_kegiatan).tz(WITA).format('YYYY-MM-DD');
    const startStr = this.pemesananService.formatTimeStr(pemesanan.waktu_mulai).slice(0, 5);
    const endStr = this.pemesananService.formatTimeStr(pemesanan.waktu_selesai).slice(0, 5);

    const conflicts = await this.pemesananService.checkConflict({
      ruangan_id: Number(pemesanan.ruangan_id),
      tanggal_kegiatan: tanggalStr,
      waktu_mulai: startStr,
      waktu_selesai: endStr,
      exclude_id: Number(pemesanan.id),
      include_pending: false,
    });

    if (conflicts.length > 0) {
      const conflictDetails = conflicts
        .map(
          (c) =>
            `"${c.judul_kegiatan}" (${this.pemesananService.formatTimeStr(c.waktu_mulai).slice(0, 5)} - ${this.pemesananService.formatTimeStr(c.waktu_selesai).slice(0, 5)} WITA)`,
        )
        .join(', ');
      throw new BadRequestException(
        `Tidak dapat menyetujui: Jadwal ruangan bentrok dengan kegiatan yang sudah disetujui (${conflictDetails}).`,
      );
    }

    const now = new Date();
    const updated = await this.prisma.pemesanan.update({
      where: { id },
      data: {
        status: 'Disetujui' as any,
        approved_by: adminUser.id ? BigInt(adminUser.id) : null,
        approved_at: now,
        catatan_admin: catatanAdmin ?? null,
      },
      include: { ruangan: true, layout_ruangan: true, users: true },
    });

    // Catat ke linimasa pemesanan_status_history
    await this.prisma.pemesanan_status_history
      .create({
        data: {
          pemesanan_id: pemesanan.id,
          status_lama: pemesanan.status as any,
          status_baru: 'Disetujui' as any,
          changed_by: adminUser.id ? BigInt(adminUser.id) : null,
          changed_at: now,
          created_at: now,
          updated_at: now,
        },
      })
      .catch(() => null);

    // Send WA notification (fire-and-forget non-blocking)
    const userPhone = (pemesanan as any).users?.no_wa;
    if (userPhone) {
      this.whatsapp
        .send(
          userPhone,
          `✅ Pemesanan Anda *${pemesanan.kode_pemesanan}* untuk kegiatan "*${pemesanan.judul_kegiatan}*" di *${(pemesanan as any).ruangan?.nama_ruangan}* telah *DISETUJUI*.\n\nSilakan hadir sesuai jadwal.`,
        )
        .catch((err) => console.error('Failed to send WA approval notification:', err));
    }

    // Send in-app notification to user
    try {
      await this.notificationService.sendNotification({
        userId: Number(pemesanan.user_id),
        type: 'App\\Notifications\\PemesananDisetujuiNotification',
        judul: 'Pemesanan Disetujui ✅',
        pesan: `Pemesanan Anda ${pemesanan.kode_pemesanan} untuk kegiatan "${pemesanan.judul_kegiatan}" di ruangan ${(pemesanan as any).ruangan?.nama_ruangan || 'ruangan'} telah DISETUJUI.`,
        pemesananId: pemesanan.id,
        url: `/pemesanan/${pemesanan.id}`,
      });
    } catch (e) {
      console.error('Failed to send in-app notification:', e);
    }

    await this.auditLog.log({
      userId: adminUser?.id ? BigInt(adminUser.id) : null,
      aksi: 'APPROVE_PEMESANAN',
      modul: 'Approval',
      keterangan: `Menyetujui pemesanan ${pemesanan.kode_pemesanan} ("${pemesanan.judul_kegiatan}")`,
    });

    return {
      status: 'success',
      message: `Pemesanan ${pemesanan.kode_pemesanan} berhasil disetujui.`,
      data: this.pemesananService.formatPemesanan(updated),
    };
  }

  async reject(id: number, adminUser: any, alasan: string) {
    if (!alasan) throw new BadRequestException('Alasan penolakan wajib diisi.');
    const pemesanan = await this.prisma.pemesanan.findUnique({
      where: { id },
      include: { users: true, ruangan: true },
    });
    if (!pemesanan) throw new NotFoundException('Pemesanan tidak ditemukan.');

    const now = new Date();
    const updated = await this.prisma.pemesanan.update({
      where: { id },
      data: {
        status: 'Ditolak' as any,
        rejected_by: adminUser.id ? BigInt(adminUser.id) : null,
        rejected_at: now,
        alasan_penolakan: alasan,
      },
      include: { ruangan: true, layout_ruangan: true, users: true },
    });

    // Catat ke linimasa pemesanan_status_history
    await this.prisma.pemesanan_status_history
      .create({
        data: {
          pemesanan_id: pemesanan.id,
          status_lama: pemesanan.status as any,
          status_baru: 'Ditolak' as any,
          changed_by: adminUser.id ? BigInt(adminUser.id) : null,
          changed_at: now,
          created_at: now,
          updated_at: now,
        },
      })
      .catch(() => null);

    const userPhone = (pemesanan as any).users?.no_wa;
    if (userPhone) {
      this.whatsapp
        .send(
          userPhone,
          `❌ Pemesanan Anda *${pemesanan.kode_pemesanan}* untuk kegiatan "*${pemesanan.judul_kegiatan}*" telah *DITOLAK*.\n\nAlasan: ${alasan}`,
        )
        .catch((err) => console.error('Failed to send WA rejection notification:', err));
    }

    // Send in-app notification to user
    try {
      await this.notificationService.sendNotification({
        userId: Number(pemesanan.user_id),
        type: 'App\\Notifications\\PemesananDitolakNotification',
        judul: 'Pemesanan Ditolak ❌',
        pesan: `Pemesanan Anda ${pemesanan.kode_pemesanan} untuk kegiatan "${pemesanan.judul_kegiatan}" telah DITOLAK. Alasan: ${alasan}`,
        pemesananId: pemesanan.id,
        url: `/pemesanan/${pemesanan.id}`,
      });
    } catch (e) {
      console.error('Failed to send in-app notification:', e);
    }

    await this.auditLog.log({
      userId: adminUser?.id ? BigInt(adminUser.id) : null,
      aksi: 'REJECT_PEMESANAN',
      modul: 'Approval',
      keterangan: `Menolak pemesanan ${pemesanan.kode_pemesanan}. Alasan: ${alasan}`,
    });

    return {
      status: 'success',
      message: `Pemesanan ${pemesanan.kode_pemesanan} berhasil ditolak.`,
      data: this.pemesananService.formatPemesanan(updated),
    };
  }

  async approvalDestroy(id: number) {
    const pemesanan = await this.prisma.pemesanan.findUnique({ where: { id } });
    if (!pemesanan) throw new NotFoundException('Pemesanan tidak ditemukan.');
    await this.prisma.pemesanan.delete({ where: { id } });

    await this.auditLog.log({
      userId: null,
      aksi: 'DELETE_PEMESANAN',
      modul: 'Approval',
      keterangan: `Menghapus pemesanan ${pemesanan.kode_pemesanan} dari sistem.`,
    });

    return { status: 'success', message: `Pemesanan ${pemesanan.kode_pemesanan} berhasil dihapus dari sistem.` };
  }

  async adminStorePemesanan(body: any, adminUser: any, filePath?: string) {
    // Admin creates booking — instantly approved
    return this.pemesananService.store(body, adminUser, filePath);
  }

  // ─── USER MANAGEMENT ────────────────────────────────────────────────────────

  async userIndex(query: any) {
    const page = parseInt(query.page ?? '1');
    const perPage = parseInt(query.per_page ?? '15');
    const q = query.q?.trim();

    // Fetch all admins
    const admins = await this.prisma.users.findMany({
      where: { role: 'admin' },
      include: { departments: true },
      orderBy: { name: 'asc' },
    });

    // Work units / non-admin users
    const userWhere: any = { role: { not: 'admin' } };
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

    const formattedAdmins = admins.map((a) =>
      sanitizeUser({
        ...a,
        department: a.departments,
      }),
    );

    const formattedUsers = userItems.map((u) =>
      sanitizeUser({
        ...u,
        department: u.departments,
      }),
    );

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

  async userShow(id: number) {
    const user = await this.prisma.users.findUnique({
      where: { id: BigInt(id) },
      include: { departments: true },
    });
    if (!user) throw new NotFoundException('User tidak ditemukan.');
    return {
      status: 'success',
      data: sanitizeUser({
        ...user,
        department: user.departments,
      }),
    };
  }

  async userStore(body: any) {
    const hashed = await bcrypt.hash(body.password, 12);
    const user = await this.prisma.users.create({
      data: {
        name: body.name || body.nama_unit || '',
        username: body.username,
        email: body.email || null,
        no_wa: body.no_wa || null,
        password: hashed,
        password_plain: null, // Keamanan: Jangan simpan password teks polos
        role: body.role || 'user',
        nama_unit: body.nama_unit || body.name || '',
        kode_unit: body.kode_unit || '',
        department_id: body.department_id ? BigInt(body.department_id) : null,
      },
    });

    await this.auditLog.log({
      userId: null,
      aksi: 'CREATE_USER',
      modul: 'User Management',
      keterangan: `Membuat pengguna baru: ${user.username} (${user.nama_unit})`,
    });

    return { status: 'success', message: 'User berhasil dibuat.', data: sanitizeUser(user) };
  }

  async userUpdate(id: number, body: any) {
    const data: any = {};
    if (body.name !== undefined) data.name = body.name;
    if (body.username !== undefined) data.username = body.username;
    if (body.email !== undefined) data.email = body.email;
    if (body.no_wa !== undefined) data.no_wa = body.no_wa;
    if (body.role !== undefined) data.role = body.role;
    if (body.nama_unit !== undefined) data.nama_unit = body.nama_unit;
    if (body.kode_unit !== undefined) data.kode_unit = body.kode_unit;
    if (body.department_id !== undefined) {
      data.department_id = body.department_id ? BigInt(body.department_id) : null;
    }
    if (body.password) {
      data.password = await bcrypt.hash(body.password, 12);
      // Keamanan: Jangan simpan password teks polos
    }
    const user = await this.prisma.users.update({
      where: { id: BigInt(id) },
      data,
    });

    await this.auditLog.log({
      userId: null,
      aksi: 'UPDATE_USER',
      modul: 'User Management',
      keterangan: `Memperbarui data pengguna: ${user.username} (ID: ${user.id})`,
    });

    return { status: 'success', message: 'User berhasil diperbarui.', data: sanitizeUser(user) };
  }

  async userDestroy(id: number) {
    await this.prisma.users.delete({ where: { id: BigInt(id) } });

    await this.auditLog.log({
      userId: null,
      aksi: 'DELETE_USER',
      modul: 'User Management',
      keterangan: `Menghapus user ID: ${id}`,
    });

    return { status: 'success', message: 'User berhasil dihapus.' };
  }

  async getUnits() {
    return this.pemesananService.getUnits();
  }

  getWhatsappStatus() {
    return this.whatsapp.getStatus();
  }

  testWhatsapp(phone?: string) {
    return this.whatsapp.testConnection(phone);
  }
}
