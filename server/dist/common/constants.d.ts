import { pemesanan_status, pemesanan_jenis_pic, ruangan_status, users_role } from '@prisma/client';
export declare const STATUS: {
    PENDING: "Pending";
    DISETUJUI: "Disetujui";
    DITOLAK: "Ditolak";
    DIBATALKAN: "Cancel";
    SELESAI: "Selesai";
};
export declare const RUANGAN_STATUS: {
    AKTIF: "aktif";
    NONAKTIF: "nonaktif";
    PERAWATAN: "perawatan";
};
export { pemesanan_status, pemesanan_jenis_pic, ruangan_status, users_role };
