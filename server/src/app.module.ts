import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { PrismaModule } from './prisma/prisma.module';
import { AuthModule } from './auth/auth.module';
import { PemesananModule } from './pemesanan/pemesanan.module';
import { RuanganModule } from './ruangan/ruangan.module';
import { DashboardModule } from './dashboard/dashboard.module';
import { DisplayModule } from './display/display.module';
import { WhatsappModule } from './whatsapp/whatsapp.module';
import { NotificationModule } from './notification/notification.module';
import { AdminModule } from './admin/admin.module';
import { KalenderModule } from './kalender/kalender.module';
import { LaporanModule } from './laporan/laporan.module';
import { HariLiburModule } from './hari-libur/hari-libur.module';
import { AuditLogModule } from './audit-log/audit-log.module';

@Module({
  imports: [
    // Load .env automatically
    ConfigModule.forRoot({ isGlobal: true }),

    // Database — global, no need to import in each module
    PrismaModule,

    // WhatsApp gateway — global
    WhatsappModule,

    // Feature modules
    AuthModule,
    PemesananModule,
    RuanganModule,
    DashboardModule,
    DisplayModule,
    NotificationModule,
    AdminModule,
    KalenderModule,
    LaporanModule,
    HariLiburModule,
    AuditLogModule,
  ],
})
export class AppModule {}
