import { describe, it, expect, vi, beforeEach } from 'vitest';
import { BadRequestException, ForbiddenException } from '@nestjs/common';
import { PemesananService } from './pemesanan.service';
import dayjs from 'dayjs';

describe('PemesananService Unit Tests', () => {
  let service: PemesananService;
  let mockPrisma: any;
  let mockNotificationService: any;
  let mockWhatsapp: any;
  let mockAuditLog: any;

  beforeEach(() => {
    mockPrisma = {
      pemesanan: {
        findMany: vi.fn(),
        findUnique: vi.fn(),
        create: vi.fn(),
        update: vi.fn(),
        updateMany: vi.fn(),
        count: vi.fn(),
      },
      ruangan: {
        findUnique: vi.fn(),
        findMany: vi.fn(),
      },
      hari_libur: {
        findFirst: vi.fn(),
      },
      users: {
        findUnique: vi.fn(),
      },
      pemesanan_status_history: {
        create: vi.fn().mockResolvedValue({ id: 1n }),
      },
      audit_log: {
        create: vi.fn().mockResolvedValue({ id: 1n }),
      },
    };

    mockNotificationService = {
      notifyAdmins: vi.fn().mockResolvedValue(true),
      sendNotification: vi.fn().mockResolvedValue(true),
    };

    mockWhatsapp = {
      notifyAdmin: vi.fn().mockResolvedValue(true),
      send: vi.fn().mockResolvedValue(true),
    };

    mockAuditLog = {
      log: vi.fn().mockResolvedValue({ id: 1n }),
      index: vi.fn(),
    };

    service = new PemesananService(
      mockPrisma,
      mockNotificationService,
      mockWhatsapp,
      mockAuditLog as any,
    );
  });

  describe('Validation: Date & Operating Hours', () => {
    it('should reject booking in the past', async () => {
      const pastDate = dayjs().subtract(3, 'day').format('YYYY-MM-DD');
      const dto: any = {
        tanggal_kegiatan: pastDate,
        waktu_mulai: '09:00',
        waktu_selesai: '10:00',
        ruangan_id: 1,
        jumlah_tamu: 10,
        judul_kegiatan: 'Rapat Evaluasi',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dto, { id: 1, role: 'user' })).rejects.toThrow(
        BadRequestException,
      );
      await expect(service.store(dto, { id: 1, role: 'user' })).rejects.toThrow(
        'Tanggal kegiatan tidak boleh di masa lalu.',
      );
    });

    it('should reject booking on weekends (Saturday / Sunday)', async () => {
      // Find next Saturday
      let nextSat = dayjs().add(1, 'day');
      while (nextSat.day() !== 6) {
        nextSat = nextSat.add(1, 'day');
      }

      const dto: any = {
        tanggal_kegiatan: nextSat.format('YYYY-MM-DD'),
        waktu_mulai: '09:00',
        waktu_selesai: '10:00',
        ruangan_id: 1,
        jumlah_tamu: 10,
        judul_kegiatan: 'Rapat Weekend',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dto, { id: 1, role: 'user' })).rejects.toThrow(
        'Pemesanan ruangan hanya dapat dilakukan pada hari kerja (Senin - Jumat)',
      );
    });

    it('should reject booking on a registered national holiday', async () => {
      // Find next Monday
      let nextMon = dayjs().add(1, 'day');
      while (nextMon.day() !== 1) {
        nextMon = nextMon.add(1, 'day');
      }

      mockPrisma.hari_libur.findFirst.mockResolvedValue({
        id: 1n,
        tanggal: new Date(nextMon.format('YYYY-MM-DD')),
        keterangan: 'Tahun Baru Imlek',
      });

      const dto: any = {
        tanggal_kegiatan: nextMon.format('YYYY-MM-DD'),
        waktu_mulai: '09:00',
        waktu_selesai: '10:00',
        ruangan_id: 1,
        jumlah_tamu: 10,
        judul_kegiatan: 'Rapat Libur',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dto, { id: 1, role: 'user' })).rejects.toThrow(
        'merupakan hari libur (Tahun Baru Imlek)',
      );
    });

    it('should reject booking outside operating hours (< 08:00 or > 19:00 WITA)', async () => {
      let nextTue = dayjs().add(1, 'day');
      while (nextTue.day() !== 2) {
        nextTue = nextTue.add(1, 'day');
      }
      mockPrisma.hari_libur.findFirst.mockResolvedValue(null);

      // Start time before 08:00
      const dtoEarly: any = {
        tanggal_kegiatan: nextTue.format('YYYY-MM-DD'),
        waktu_mulai: '07:30',
        waktu_selesai: '09:00',
        ruangan_id: 1,
        jumlah_tamu: 10,
        judul_kegiatan: 'Rapat Subuh',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dtoEarly, { id: 1, role: 'user' })).rejects.toThrow(
        'Jam mulai rapat harus berada di antara pukul 08:00 WITA hingga 18:30 WITA.',
      );

      // End time after 19:00
      const dtoLate: any = {
        tanggal_kegiatan: nextTue.format('YYYY-MM-DD'),
        waktu_mulai: '18:00',
        waktu_selesai: '20:00',
        ruangan_id: 1,
        jumlah_tamu: 10,
        judul_kegiatan: 'Rapat Malam',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dtoLate, { id: 1, role: 'user' })).rejects.toThrow(
        'Jam selesai rapat tidak boleh melebihi pukul 19:00 WITA.',
      );
    });

    it('should reject when end time is earlier than or equal to start time', async () => {
      let nextWed = dayjs().add(1, 'day');
      while (nextWed.day() !== 3) {
        nextWed = nextWed.add(1, 'day');
      }
      mockPrisma.hari_libur.findFirst.mockResolvedValue(null);

      const dtoInvalidTime: any = {
        tanggal_kegiatan: nextWed.format('YYYY-MM-DD'),
        waktu_mulai: '14:00',
        waktu_selesai: '13:00',
        ruangan_id: 1,
        jumlah_tamu: 10,
        judul_kegiatan: 'Rapat Terbalik',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dtoInvalidTime, { id: 1, role: 'user' })).rejects.toThrow(
        'Jam selesai rapat harus lebih besar dari jam mulai.',
      );
    });
  });

  describe('Validation: Room Capacity & Conflict Checking', () => {
    it('should reject if guest count exceeds room capacity', async () => {
      let nextThu = dayjs().add(1, 'day');
      while (nextThu.day() !== 4) {
        nextThu = nextThu.add(1, 'day');
      }
      mockPrisma.hari_libur.findFirst.mockResolvedValue(null);
      mockPrisma.ruangan.findUnique.mockResolvedValue({
        id: 1n,
        nama_ruangan: 'Ruang Rapat VIP',
        kapasitas: 20,
      });

      const dto: any = {
        tanggal_kegiatan: nextThu.format('YYYY-MM-DD'),
        waktu_mulai: '09:00',
        waktu_selesai: '11:00',
        ruangan_id: 1,
        jumlah_tamu: 50, // exceeds 20
        judul_kegiatan: 'Seminar Akbar',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dto, { id: 1, role: 'user' })).rejects.toThrow(
        'Jumlah tamu (50) melebihi kapasitas maksimal ruangan Ruang Rapat VIP (20 orang).',
      );
    });

    it('should reject when there is a scheduling conflict', async () => {
      let nextFri = dayjs().add(1, 'day');
      while (nextFri.day() !== 5) {
        nextFri = nextFri.add(1, 'day');
      }
      mockPrisma.hari_libur.findFirst.mockResolvedValue(null);
      mockPrisma.ruangan.findUnique.mockResolvedValue({
        id: 1n,
        nama_ruangan: 'Ruang Rapat VIP',
        kapasitas: 50,
      });

      // Mock conflict found in checkConflict
      mockPrisma.pemesanan.findMany.mockResolvedValue([
        {
          id: 10n,
          judul_kegiatan: 'Rapat Direksi',
          waktu_mulai: new Date('1970-01-01T09:00:00Z'),
          waktu_selesai: new Date('1970-01-01T11:00:00Z'),
        },
      ]);

      const dto: any = {
        tanggal_kegiatan: nextFri.format('YYYY-MM-DD'),
        waktu_mulai: '10:00',
        waktu_selesai: '12:00',
        ruangan_id: 1,
        jumlah_tamu: 15,
        judul_kegiatan: 'Rapat Tim IT',
        pic_kegiatan: 'Budi',
        jenis_pic: 'Organik',
      };

      await expect(service.store(dto, { id: 1, role: 'user' })).rejects.toThrow(
        'Ruangan sudah memiliki agenda kegiatan pada tanggal dan jam tersebut.',
      );
    });
  });

  describe('Cancellation & Authorization', () => {
    it('should throw ForbiddenException if regular user tries to cancel someone else booking', async () => {
      mockPrisma.pemesanan.findUnique.mockResolvedValue({
        id: 10n,
        user_id: 99n, // owned by user 99
        status: 'Pending',
        kode_pemesanan: 'SIL-20260915-00001UK',
      });

      await expect(service.cancel(10, { id: 2, role: 'user' })).rejects.toThrow(
        ForbiddenException,
      );
    });

    it('should allow booking owner to cancel their own pending booking', async () => {
      mockPrisma.pemesanan.findUnique.mockResolvedValue({
        id: 10n,
        user_id: 2n,
        status: 'Pending',
        kode_pemesanan: 'SIL-20260915-00001UK',
        judul_kegiatan: 'Rapat Internal',
      });

      mockPrisma.pemesanan.update.mockResolvedValue({
        id: 10n,
        user_id: 2n,
        status: 'Cancel',
        kode_pemesanan: 'SIL-20260915-00001UK',
      });

      const res = await service.cancel(10, { id: 2, role: 'user' });
      expect(res.status).toBe('success');
      expect(mockPrisma.pemesanan.update).toHaveBeenCalled();
      expect(mockAuditLog.log).toHaveBeenCalledWith(
        expect.objectContaining({
          aksi: 'CANCEL_PEMESANAN',
        }),
      );
    });
  });
});
