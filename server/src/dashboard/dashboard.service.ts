import { Injectable } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import { PemesananService } from '../pemesanan/pemesanan.service';
import dayjs from 'dayjs';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';

dayjs.extend(utc);
dayjs.extend(timezone);

const WITA = 'Asia/Makassar';

@Injectable()
export class DashboardService {
  constructor(
    private prisma: PrismaService,
    private pemesananService: PemesananService,
  ) {}

  /** User dashboard — equivalent to DashboardApiController::user() */
  async user(userId: number) {
    await this.pemesananService.markFinishedAgendas();

    const now = dayjs().tz(WITA);
    const todayStr = now.format('YYYY-MM-DD');
    const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
    const currentTime = now.format('HH:mm:ss');

    const [total, pending, approved, upcoming, terbaru, hariIni] =
      await Promise.all([
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

  /** Admin dashboard — equivalent to DashboardApiController::admin() */
  async admin() {
    await this.pemesananService.markFinishedAgendas();

    const now = dayjs().tz(WITA);
    const todayStr = now.format('YYYY-MM-DD');
    const todayDate = new Date(`${todayStr}T00:00:00.000Z`);
    const currentTime = now.format('HH:mm:ss');

    const [
      totalRuangan, totalPemesanan, waitingApproval,
      disetujui, ditolak, bulanIni,
      hariIni, waitingList,
      ruanganTerpopuler, aktivitasTerbaru, agendaMendatang,
    ] = await Promise.all([
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

    // Chart 1: Monthly trend 6 months
    const chartMonthlyLabels: string[] = [];
    const chartMonthlyData: number[] = [];
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

    // Chart 2: Ruangan terpopuler
    const ruanganIds = ruanganTerpopuler.map((r) => r.ruangan_id);
    const ruanganData = await this.prisma.ruangan.findMany({
      where: { id: { in: ruanganIds } },
    });
    const chartRuanganLabels = ruanganTerpopuler.map(
      (r) => ruanganData.find((rd) => rd.id === r.ruangan_id)?.nama_ruangan ?? `Ruangan ${r.ruangan_id}`,
    );
    const chartRuanganData = ruanganTerpopuler.map((r) => r._count.id);

    // Chart 3: Unit distribution
    const unitDist = await this.prisma.pemesanan.groupBy({
      by: ['user_id'],
      _count: { id: true },
      orderBy: { _count: { id: 'desc' } },
      take: 5,
    });
    const userIds = unitDist.map((u) => u.user_id);
    const usersData = await this.prisma.users.findMany({ where: { id: { in: userIds } } });
    const chartUnitLabels = unitDist.map(
      (u) => usersData.find((ud) => ud.id === u.user_id)?.nama_unit ?? 'User',
    );
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

  /** Live activities — equivalent to DashboardApiController::kegiatanBerlangsung() */
  async kegiatanBerlangsung() {
    await this.pemesananService.markFinishedAgendas();
    const now = dayjs().tz(WITA);
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
}
