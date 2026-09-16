import { Injectable, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../prisma/prisma.service';
import dayjs from 'dayjs';

const DEFAULT_HOLIDAYS: Record<string, [string, string]> = {
  // 2025
  '2025-01-01': ['Tahun Baru 2025 Masehi', 'libur_nasional'],
  '2025-01-27': ['Isra Mikraj Nabi Muhammad SAW', 'libur_nasional'],
  '2025-01-28': ['Cuti Bersama Tahun Baru Imlek', 'cuti_bersama'],
  '2025-01-29': ['Tahun Baru Imlek 2576 Kongzili', 'libur_nasional'],
  '2025-03-28': ['Cuti Bersama Hari Suci Nyepi', 'cuti_bersama'],
  '2025-03-29': ['Hari Suci Nyepi (Tahun Baru Saka 1947)', 'libur_nasional'],
  '2025-03-31': ['Hari Raya Idul Fitri 1446 Hijriah', 'libur_nasional'],
  '2025-04-01': ['Hari Raya Idul Fitri 1446 Hijriah', 'libur_nasional'],
  '2025-04-02': ['Cuti Bersama Idul Fitri 1446 H', 'cuti_bersama'],
  '2025-04-03': ['Cuti Bersama Idul Fitri 1446 H', 'cuti_bersama'],
  '2025-04-04': ['Cuti Bersama Idul Fitri 1446 H', 'cuti_bersama'],
  '2025-04-07': ['Cuti Bersama Idul Fitri 1446 H', 'cuti_bersama'],
  '2025-04-18': ['Wafat Yesus Kristus', 'libur_nasional'],
  '2025-04-20': ['Hari Paskah', 'libur_nasional'],
  '2025-05-01': ['Hari Buruh Internasional', 'libur_nasional'],
  '2025-05-12': ['Hari Raya Waisak 2569 BE', 'libur_nasional'],
  '2025-05-13': ['Cuti Bersama Hari Raya Waisak', 'cuti_bersama'],
  '2025-05-29': ['Kenaikan Yesus Kristus', 'libur_nasional'],
  '2025-05-30': ['Cuti Bersama Kenaikan Yesus Kristus', 'cuti_bersama'],
  '2025-06-01': ['Hari Lahir Pancasila', 'libur_nasional'],
  '2025-06-07': ['Hari Raya Idul Adha 1446 Hijriah', 'libur_nasional'],
  '2025-06-09': ['Cuti Bersama Idul Adha 1446 H', 'cuti_bersama'],
  '2025-06-27': ['1 Muharam Tahun Baru Islam 1447 Hijriah', 'libur_nasional'],
  '2025-08-17': ['Proklamasi Kemerdekaan Republik Indonesia', 'libur_nasional'],
  '2025-09-05': ['Maulid Nabi Muhammad SAW', 'libur_nasional'],
  '2025-12-25': ['Hari Raya Natal', 'libur_nasional'],
  '2025-12-26': ['Cuti Bersama Hari Raya Natal', 'cuti_bersama'],

  // 2026
  '2026-01-01': ['Tahun Baru 2026 Masehi', 'libur_nasional'],
  '2026-01-16': ['Isra Mikraj Nabi Muhammad SAW', 'libur_nasional'],
  '2026-02-16': ['Cuti Bersama Tahun Baru Imlek', 'cuti_bersama'],
  '2026-02-17': ['Tahun Baru Imlek 2577 Kongzili', 'libur_nasional'],
  '2026-03-18': ['Cuti Bersama Hari Suci Nyepi', 'cuti_bersama'],
  '2026-03-19': ['Hari Suci Nyepi Tahun Baru Saka 1948', 'libur_nasional'],
  '2026-03-20': ['Hari Raya Idul Fitri 1447 Hijriah', 'libur_nasional'],
  '2026-03-21': ['Hari Raya Idul Fitri 1447 Hijriah', 'libur_nasional'],
  '2026-03-23': ['Cuti Bersama Hari Raya Idul Fitri 1447 H', 'cuti_bersama'],
  '2026-03-24': ['Cuti Bersama Hari Raya Idul Fitri 1447 H', 'cuti_bersama'],
  '2026-04-03': ['Wafat Yesus Kristus', 'libur_nasional'],
  '2026-04-05': ['Hari Paskah', 'libur_nasional'],
  '2026-05-01': ['Hari Buruh Internasional', 'libur_nasional'],
  '2026-05-14': ['Kenaikan Yesus Kristus', 'libur_nasional'],
  '2026-05-15': ['Cuti Bersama Kenaikan Yesus Kristus', 'cuti_bersama'],
  '2026-05-27': ['Hari Raya Idul Adha 1447 Hijriah', 'libur_nasional'],
  '2026-05-28': ['Cuti Bersama Hari Raya Idul Adha 1447 H', 'cuti_bersama'],
  '2026-05-31': ['Hari Raya Waisak 2570 BE', 'libur_nasional'],
  '2026-06-01': ['Hari Lahir Pancasila', 'libur_nasional'],
  '2026-06-16': ['Tahun Baru Islam 1448 Hijriah', 'libur_nasional'],
  '2026-08-17': ['Hari Kemerdekaan Republik Indonesia', 'libur_nasional'],
  '2026-08-25': ['Maulid Nabi Muhammad SAW', 'libur_nasional'],
  '2026-12-25': ['Hari Raya Natal', 'libur_nasional'],
  '2026-12-26': ['Cuti Bersama Hari Raya Natal', 'cuti_bersama'],

  // 2027
  '2027-01-01': ['Tahun Baru 2027 Masehi', 'libur_nasional'],
  '2027-01-05': ['Isra Mikraj Nabi Muhammad SAW', 'libur_nasional'],
  '2027-02-06': ['Tahun Baru Imlek 2578 Kongzili', 'libur_nasional'],
  '2027-03-08': ['Hari Suci Nyepi Tahun Baru Saka 1949', 'libur_nasional'],
  '2027-03-10': ['Hari Raya Idul Fitri 1448 Hijriah', 'libur_nasional'],
  '2027-03-11': ['Hari Raya Idul Fitri 1448 Hijriah', 'libur_nasional'],
  '2027-03-26': ['Wafat Yesus Kristus', 'libur_nasional'],
  '2027-03-28': ['Hari Paskah', 'libur_nasional'],
  '2027-05-01': ['Hari Buruh Internasional', 'libur_nasional'],
  '2027-05-06': ['Kenaikan Yesus Kristus', 'libur_nasional'],
  '2027-05-16': ['Hari Raya Idul Adha 1448 Hijriah', 'libur_nasional'],
  '2027-05-20': ['Hari Raya Waisak 2571 BE', 'libur_nasional'],
  '2027-06-01': ['Hari Lahir Pancasila', 'libur_nasional'],
  '2027-06-06': ['Tahun Baru Islam 1449 Hijriah', 'libur_nasional'],
  '2027-08-17': ['Hari Kemerdekaan Republik Indonesia', 'libur_nasional'],
  '2027-08-14': ['Maulid Nabi Muhammad SAW', 'libur_nasional'],
  '2027-12-25': ['Hari Raya Natal', 'libur_nasional'],
};

@Injectable()
export class HariLiburService {
  constructor(private prisma: PrismaService) {}

  async index(query: any = {}) {
    const page = parseInt(query.page ?? '1');
    const perPage = parseInt(query.per_page ?? '15');
    const where: any = {};

    if (query.tahun) {
      const year = parseInt(query.tahun);
      where.tanggal = {
        gte: new Date(`${year}-01-01T00:00:00.000Z`),
        lte: new Date(`${year}-12-31T23:59:59.999Z`),
      };
    }

    if (query.kategori) {
      where.kategori = query.kategori;
    }

    const [total, items, allRecords] = await Promise.all([
      this.prisma.hari_libur.count({ where }),
      this.prisma.hari_libur.findMany({
        where,
        orderBy: { tanggal: 'asc' },
        skip: (page - 1) * perPage,
        take: perPage,
      }),
      this.prisma.hari_libur.findMany({
        select: { tanggal: true },
        orderBy: { tanggal: 'desc' },
      }),
    ]);

    const yearsSet = new Set<number>();
    allRecords.forEach((h) => {
      const yr = new Date(h.tanggal).getFullYear();
      if (!isNaN(yr)) yearsSet.add(yr);
    });

    const currentYear = new Date().getFullYear();
    yearsSet.add(currentYear);
    yearsSet.add(currentYear + 1);
    const available_years = Array.from(yearsSet).sort((a, b) => b - a);

    const from = total > 0 ? (page - 1) * perPage + 1 : 0;
    const to = Math.min(page * perPage, total);
    const last_page = Math.ceil(total / perPage) || 1;

    return {
      status: 'success',
      data: {
        items: {
          data: items.map((item) => ({
            ...item,
            id: Number(item.id),
            tanggal: dayjs(item.tanggal).format('YYYY-MM-DD'),
          })),
          total,
          current_page: page,
          per_page: perPage,
          last_page,
          from,
          to,
        },
        available_years,
      },
    };
  }

  async syncApi(year: number) {
    let holidaysList: Array<{ tanggal: string; keterangan: string; kategori: string }> = [];

    // Attempt 1: Try public Dayoff API
    try {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 6000);
      const res = await fetch(`https://dayoffapi.vercel.app/api?year=${year}`, {
        signal: controller.signal,
      });
      clearTimeout(timeoutId);

      if (res.ok) {
        const data = await res.json();
        if (Array.isArray(data) && data.length > 0) {
          for (const item of data) {
            if (item.is_holiday) {
              const name = item.holiday_name || '';
              const isCuti = name.toLowerCase().includes('cuti bersama');
              holidaysList.push({
                tanggal: item.holiday_date,
                keterangan: name,
                kategori: isCuti ? 'cuti_bersama' : 'libur_nasional',
              });
            }
          }
        }
      }
    } catch {
      // Ignore and fallback
    }

    // Attempt 2: If primary API failed, try secondary api-harilibur
    if (holidaysList.length === 0) {
      try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 6000);
        const res = await fetch(`https://api-harilibur.vercel.app/api?year=${year}`, {
          signal: controller.signal,
        });
        clearTimeout(timeoutId);

        if (res.ok) {
          const data = await res.json();
          if (Array.isArray(data) && data.length > 0) {
            for (const item of data) {
              if (item.is_holiday) {
                const name = item.holiday_name || '';
                const isCuti = name.toLowerCase().includes('cuti bersama');
                holidaysList.push({
                  tanggal: item.holiday_date,
                  keterangan: name,
                  kategori: isCuti ? 'cuti_bersama' : 'libur_nasional',
                });
              }
            }
          }
        }
      } catch {
        // Ignore and fallback
      }
    }

    // Attempt 3: Built-in reliable dictionary
    if (holidaysList.length === 0) {
      const yrStr = String(year);
      for (const [dateStr, info] of Object.entries(DEFAULT_HOLIDAYS)) {
        if (dateStr.startsWith(yrStr)) {
          holidaysList.push({
            tanggal: dateStr,
            keterangan: info[0],
            kategori: info[1],
          });
        }
      }
    }

    // Upsert into database
    let count = 0;
    for (const h of holidaysList) {
      const date = new Date(h.tanggal);
      await this.prisma.hari_libur.upsert({
        where: { tanggal: date },
        update: {
          keterangan: h.keterangan,
          kategori: h.kategori,
          is_nasional: true,
        },
        create: {
          tanggal: date,
          keterangan: h.keterangan,
          kategori: h.kategori,
          is_nasional: true,
        },
      });
      count++;
    }

    return {
      status: 'success',
      message: `Berhasil menyinkronkan ${count} data hari libur nasional & cuti bersama untuk tahun ${year}.`,
      data: { count, year },
    };
  }

  async store(body: any) {
    if (!body.tanggal) throw new BadRequestException('Tanggal wajib diisi.');
    if (!body.keterangan) throw new BadRequestException('Keterangan wajib diisi.');

    const date = new Date(body.tanggal);
    const existing = await this.prisma.hari_libur.findUnique({
      where: { tanggal: date },
    });
    if (existing) {
      throw new BadRequestException('Tanggal tersebut sudah terdaftar sebagai hari libur.');
    }

    const created = await this.prisma.hari_libur.create({
      data: {
        tanggal: date,
        keterangan: body.keterangan,
        kategori: body.kategori || 'libur_nasional',
        is_nasional: body.kategori !== 'internal',
      },
    });

    return {
      status: 'success',
      message: 'Hari libur / cuti bersama berhasil ditambahkan.',
      data: {
        ...created,
        id: Number(created.id),
        tanggal: dayjs(created.tanggal).format('YYYY-MM-DD'),
      },
    };
  }

  async remove(id: number) {
    await this.prisma.hari_libur.delete({
      where: { id: BigInt(id) },
    });
    return {
      status: 'success',
      message: 'Data hari libur berhasil dihapus.',
    };
  }

  async allDates() {
    const holidays = await this.prisma.hari_libur.findMany({
      orderBy: { tanggal: 'asc' },
    });
    return {
      status: 'success',
      data: holidays.map((h) => ({
        id: Number(h.id),
        tanggal: dayjs(h.tanggal).format('YYYY-MM-DD'),
        keterangan: h.keterangan,
        kategori: h.kategori,
        is_nasional: h.is_nasional,
      })),
    };
  }
}
