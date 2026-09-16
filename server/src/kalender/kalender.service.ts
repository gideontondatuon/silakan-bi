import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import dayjs from 'dayjs';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';
dayjs.extend(utc);
dayjs.extend(timezone);
const WITA = 'Asia/Makassar';

@Injectable()
export class KalenderService {
  constructor(private prisma: PrismaService) {}

  private formatTime(val: any): string {
    if (!val) return '00:00';
    if (typeof val === 'string') return val.substring(0, 5);
    if (val instanceof Date) {
      const h = String(val.getUTCHours()).padStart(2, '0');
      const m = String(val.getUTCMinutes()).padStart(2, '0');
      return `${h}:${m}`;
    }
    return String(val).substring(0, 5);
  }

  async index(_userId: number, _query: any) {
    const ruanganRaw = await this.prisma.ruangan.findMany({
      where: { status: 'aktif' },
      orderBy: { nama_ruangan: 'asc' },
    });

    const ruangan = ruanganRaw.map((r) => ({
      id: Number(r.id),
      nama_ruangan: r.nama_ruangan,
      kapasitas: r.kapasitas,
      status: r.status,
      lokasi: r.lokasi,
    }));

    const now = dayjs().tz(WITA);
    const todayDateStr = now.format('YYYY-MM-DD');
    const todayStart = new Date(`${todayDateStr}T00:00:00.000Z`);

    const total_ruangan = ruangan.length;

    const jadwal_aktif = await this.prisma.pemesanan.count({
      where: {
        status: { in: ['Disetujui', 'Pending'] },
        tanggal_kegiatan: { gte: todayStart },
      },
    });

    const akan_datang = await this.prisma.pemesanan.count({
      where: {
        status: 'Disetujui',
        tanggal_kegiatan: { gte: todayStart },
      },
    });

    const upcomingRaw = await this.prisma.pemesanan.findMany({
      where: {
        status: { in: ['Disetujui', 'Pending'] },
        tanggal_kegiatan: { gte: todayStart },
      },
      include: {
        ruangan: true,
        users: { include: { departments: true } },
      },
      orderBy: [{ tanggal_kegiatan: 'asc' }, { waktu_mulai: 'asc' }],
      take: 10,
    });

    const upcoming = upcomingRaw.map((p) => ({
      id: Number(p.id),
      kode_pemesanan: p.kode_pemesanan,
      judul_kegiatan: p.judul_kegiatan,
      tanggal_kegiatan: dayjs(p.tanggal_kegiatan).format('YYYY-MM-DD'),
      waktu_mulai: this.formatTime(p.waktu_mulai),
      waktu_selesai: this.formatTime(p.waktu_selesai),
      pic_kegiatan: p.pic_kegiatan,
      nama_ruangan: p.ruangan?.nama_ruangan || '-',
      nama_unit: p.users?.departments?.nama_unit || p.users?.nama_unit || '-',
    }));

    return {
      status: 'success',
      data: {
        ruangan,
        stats: {
          total_ruangan,
          jadwal_aktif,
          akan_datang,
        },
        upcoming,
      },
    };
  }

  async events(_userId: number, query: any) {
    const ruanganId = query.ruangan_id ? BigInt(query.ruangan_id) : undefined;

    const pemesanan = await this.prisma.pemesanan.findMany({
      where: {
        status: { in: ['Pending', 'Disetujui', 'Selesai'] },
        ...(ruanganId ? { ruangan_id: ruanganId } : {}),
      },
      include: {
        ruangan: true,
        layout_ruangan: true,
        users: { include: { departments: true } },
      },
      orderBy: [{ tanggal_kegiatan: 'asc' }, { waktu_mulai: 'asc' }],
    });

    const bookingEvents = pemesanan.map((p) => {
      const dateStr = dayjs(p.tanggal_kegiatan).format('YYYY-MM-DD');
      const startTime = this.formatTime(p.waktu_mulai);
      const endTime = this.formatTime(p.waktu_selesai);
      const isPending = p.status === 'Pending';
      const color = isPending ? '#f59e0b' : '#005baa';

      return {
        id: `booking-${p.id}`,
        title: `${startTime} - ${p.judul_kegiatan}`,
        start: `${dateStr}T${startTime}:00`,
        end: `${dateStr}T${endTime}:00`,
        color,
        type: 'booking',
        extendedProps: {
          id: Number(p.id),
          kode_pemesanan: p.kode_pemesanan,
          judul: p.judul_kegiatan,
          ruangan: p.ruangan?.nama_ruangan || '-',
          layout: p.layout_ruangan?.nama_layout || p.keterangan_layout || '-',
          tanggal: dateStr,
          waktu: `${startTime} - ${endTime}`,
          pic: p.pic_kegiatan,
          no_wa_pic: p.no_wa_pic || '-',
          unit: p.users?.departments?.nama_unit || p.users?.nama_unit || '-',
          jumlah_tamu: p.jumlah_tamu,
          status: p.status,
          jenis_kegiatan: p.jenis_kegiatan,
        },
      };
    });

    // Hari Libur
    const holidays = await this.prisma.hari_libur.findMany({
      orderBy: { tanggal: 'asc' },
    });

    const holidayEvents = holidays.map((h) => {
      const dateStr = dayjs(h.tanggal).format('YYYY-MM-DD');
      const isCuti = h.kategori === 'cuti_bersama';
      const isInternal = h.kategori === 'internal';
      const color = isCuti ? '#d97706' : isInternal ? '#0284c7' : '#dc2626';

      return {
        id: `holiday-${h.id}`,
        title: h.keterangan,
        start: dateStr,
        allDay: true,
        color,
        type: 'holiday',
        extendedProps: {
          id: Number(h.id),
          keterangan: h.keterangan,
          tanggal: dateStr,
          kategori: h.kategori,
          kategori_label: isCuti ? 'Cuti Bersama' : isInternal ? 'Libur Internal' : 'Hari Libur Nasional',
          is_nasional: h.is_nasional,
        },
      };
    });

    return { status: 'success', data: [...bookingEvents, ...holidayEvents] };
  }
}

