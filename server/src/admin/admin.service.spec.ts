import { describe, it, expect, vi, beforeEach } from 'vitest';
import { BadRequestException, NotFoundException } from '@nestjs/common';
import { AdminService } from './admin.service';

describe('AdminService - approve and anti-double booking', () => {
  let adminService: AdminService;
  let mockPrisma: any;
  let mockPemesananService: any;
  let mockWhatsapp: any;
  let mockNotificationService: any;

  beforeEach(() => {
    mockPrisma = {
      pemesanan: {
        findUnique: vi.fn(),
        update: vi.fn(),
      },
      pemesanan_status_history: {
        create: vi.fn().mockResolvedValue({ id: 1n }),
      },
      users: {
        findMany: vi.fn(),
        findUnique: vi.fn(),
        create: vi.fn(),
        update: vi.fn(),
        delete: vi.fn(),
        count: vi.fn(),
      },
      departments: {
        findMany: vi.fn(),
      },
    };

    mockPemesananService = {
      markFinishedAgendas: vi.fn().mockResolvedValue(0),
      formatPemesanan: vi.fn((p) => p),
      formatTimeStr: vi.fn((val) => {
        if (!val) return '';
        if (typeof val === 'string') return val;
        return '09:00:00';
      }),
      checkConflict: vi.fn(),
    };

    mockWhatsapp = {
      send: vi.fn().mockResolvedValue(true),
    };

    mockNotificationService = {
      sendNotification: vi.fn().mockResolvedValue(true),
    };

    const mockAuditLog = {
      log: vi.fn().mockResolvedValue({ id: 1n }),
      index: vi.fn(),
    };

    adminService = new AdminService(
      mockPrisma,
      mockPemesananService,
      mockWhatsapp,
      mockNotificationService,
      mockAuditLog as any,
    );
  });

  it('should throw NotFoundException if pemesanan does not exist', async () => {
    mockPrisma.pemesanan.findUnique.mockResolvedValue(null);

    await expect(adminService.approve(999, { id: 1 })).rejects.toThrow(NotFoundException);
  });

  it('should throw BadRequestException if pemesanan is not Pending', async () => {
    mockPrisma.pemesanan.findUnique.mockResolvedValue({
      id: 1n,
      status: 'Disetujui',
    });

    await expect(adminService.approve(1, { id: 1 })).rejects.toThrow(BadRequestException);
  });

  it('should prevent double booking and throw BadRequestException when conflict exists', async () => {
    mockPrisma.pemesanan.findUnique.mockResolvedValue({
      id: 10n,
      status: 'Pending',
      ruangan_id: 2n,
      tanggal_kegiatan: new Date('2026-09-20'),
      waktu_mulai: '09:00',
      waktu_selesai: '11:00',
      judul_kegiatan: 'Rapat Koordinasi A',
    });

    // Simulate an existing approved booking in the same slot
    mockPemesananService.checkConflict.mockResolvedValue([
      {
        id: 5n,
        judul_kegiatan: 'Rapat Prioritas Pimpinan',
        waktu_mulai: '09:30',
        waktu_selesai: '11:30',
      },
    ]);

    await expect(adminService.approve(10, { id: 1 })).rejects.toThrow(
      /Tidak dapat menyetujui: Jadwal ruangan bentrok/,
    );

    // Verify status was NOT updated
    expect(mockPrisma.pemesanan.update).not.toHaveBeenCalled();
  });

  it('should approve booking and record status history when no conflict exists', async () => {
    mockPrisma.pemesanan.findUnique.mockResolvedValue({
      id: 10n,
      status: 'Pending',
      ruangan_id: 2n,
      tanggal_kegiatan: new Date('2026-09-20'),
      waktu_mulai: '09:00',
      waktu_selesai: '11:00',
      judul_kegiatan: 'Rapat Koordinasi A',
      kode_pemesanan: 'SIL-20260920-001BI',
      user_id: 3n,
      users: { no_wa: '08123456789' },
      ruangan: { nama_ruangan: 'Ruang Bunaken' },
    });

    mockPemesananService.checkConflict.mockResolvedValue([]);
    mockPrisma.pemesanan.update.mockResolvedValue({
      id: 10n,
      status: 'Disetujui',
      kode_pemesanan: 'SIL-20260920-001BI',
    });

    const result = await adminService.approve(10, { id: 1 }, 'Disetujui oleh Kepala Unit');

    expect(result.status).toBe('success');
    expect(mockPrisma.pemesanan.update).toHaveBeenCalled();
    expect(mockPrisma.pemesanan_status_history.create).toHaveBeenCalledWith(
      expect.objectContaining({
        data: expect.objectContaining({
          pemesanan_id: 10n,
          status_lama: 'Pending',
          status_baru: 'Disetujui',
        }),
      }),
    );
  });
});
