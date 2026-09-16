"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.AppModule = void 0;
const common_1 = require("@nestjs/common");
const config_1 = require("@nestjs/config");
const prisma_module_1 = require("./prisma/prisma.module");
const auth_module_1 = require("./auth/auth.module");
const pemesanan_module_1 = require("./pemesanan/pemesanan.module");
const ruangan_module_1 = require("./ruangan/ruangan.module");
const dashboard_module_1 = require("./dashboard/dashboard.module");
const display_module_1 = require("./display/display.module");
const whatsapp_module_1 = require("./whatsapp/whatsapp.module");
const notification_module_1 = require("./notification/notification.module");
const admin_module_1 = require("./admin/admin.module");
const kalender_module_1 = require("./kalender/kalender.module");
const laporan_module_1 = require("./laporan/laporan.module");
const hari_libur_module_1 = require("./hari-libur/hari-libur.module");
const audit_log_module_1 = require("./audit-log/audit-log.module");
let AppModule = class AppModule {
};
exports.AppModule = AppModule;
exports.AppModule = AppModule = __decorate([
    (0, common_1.Module)({
        imports: [
            config_1.ConfigModule.forRoot({ isGlobal: true }),
            prisma_module_1.PrismaModule,
            whatsapp_module_1.WhatsappModule,
            auth_module_1.AuthModule,
            pemesanan_module_1.PemesananModule,
            ruangan_module_1.RuanganModule,
            dashboard_module_1.DashboardModule,
            display_module_1.DisplayModule,
            notification_module_1.NotificationModule,
            admin_module_1.AdminModule,
            kalender_module_1.KalenderModule,
            laporan_module_1.LaporanModule,
            hari_libur_module_1.HariLiburModule,
            audit_log_module_1.AuditLogModule,
        ],
    })
], AppModule);
//# sourceMappingURL=app.module.js.map