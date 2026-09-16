"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.users_role = exports.ruangan_status = exports.pemesanan_jenis_pic = exports.pemesanan_status = exports.RUANGAN_STATUS = exports.STATUS = void 0;
const client_1 = require("@prisma/client");
Object.defineProperty(exports, "pemesanan_status", { enumerable: true, get: function () { return client_1.pemesanan_status; } });
Object.defineProperty(exports, "pemesanan_jenis_pic", { enumerable: true, get: function () { return client_1.pemesanan_jenis_pic; } });
Object.defineProperty(exports, "ruangan_status", { enumerable: true, get: function () { return client_1.ruangan_status; } });
Object.defineProperty(exports, "users_role", { enumerable: true, get: function () { return client_1.users_role; } });
exports.STATUS = {
    PENDING: client_1.pemesanan_status.Pending,
    DISETUJUI: client_1.pemesanan_status.Disetujui,
    DITOLAK: client_1.pemesanan_status.Ditolak,
    DIBATALKAN: client_1.pemesanan_status.Cancel,
    SELESAI: client_1.pemesanan_status.Selesai,
};
exports.RUANGAN_STATUS = {
    AKTIF: client_1.ruangan_status.aktif,
    NONAKTIF: client_1.ruangan_status.nonaktif,
    PERAWATAN: client_1.ruangan_status.perawatan,
};
//# sourceMappingURL=constants.js.map