import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import dayjs from 'dayjs';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';

dayjs.extend(utc);
dayjs.extend(timezone);
const WITA = 'Asia/Makassar';

function formatTimeStr(val: any): string {
  if (!val) return '';
  if (val instanceof Date) {
    return val.toISOString().substring(11, 16);
  }
  return String(val).substring(0, 5);
}

@Injectable()
export class DisplayService {
  constructor(private prisma: PrismaService) {}

  /**
   * Kiosk TV Display data — supports ?jenis=internal|eksternal filter
   */
  async apiData(jenis?: string) {
    const now = dayjs().tz(WITA);
    const todayStr = now.format('YYYY-MM-DD');
    const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
    const currentTime = now.format('HH:mm');

    const whereCondition: any = {
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

    // Split into live (sedang berlangsung) and all today
    const liveList = todayBookings.filter((item) => {
      const start = formatTimeStr(item.waktu_mulai);
      const end = formatTimeStr(item.waktu_selesai);
      return start <= currentTime && end >= currentTime;
    });

    const mapItem = (item: any) => {
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
}
