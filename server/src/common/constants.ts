import { pemesanan_status, pemesanan_jenis_pic, ruangan_status, users_role } from '@prisma/client';

export const STATUS = {
  PENDING: pemesanan_status.Pending,
  DISETUJUI: pemesanan_status.Disetujui,
  DITOLAK: pemesanan_status.Ditolak,
  DIBATALKAN: pemesanan_status.Cancel,
  SELESAI: pemesanan_status.Selesai,
};

export const RUANGAN_STATUS = {
  AKTIF: ruangan_status.aktif,
  NONAKTIF: ruangan_status.nonaktif,
  PERAWATAN: ruangan_status.perawatan,
};

export { pemesanan_status, pemesanan_jenis_pic, ruangan_status, users_role };
