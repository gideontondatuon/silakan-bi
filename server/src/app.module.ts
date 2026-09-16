import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { ServeStaticModule } from '@nestjs/serve-static';
import { join } from 'path';
import * as fs from 'fs';
import { ScheduleModule } from '@nestjs/schedule';
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

const storagePath = fs.existsSync(join(process.cwd(), 'storage', 'app', 'public'))
  ? join(process.cwd(), 'storage', 'app', 'public')
  : join(process.cwd(), '..', 'storage', 'app', 'public');

@Module({
  imports: [
    // Task Scheduling engine
    ScheduleModule.forRoot(),

    // Static file serving for user uploads (e.g. lembar disposisi)
    ServeStaticModule.forRoot({
      rootPath: storagePath,
      serveRoot: '/storage',
      serveStaticOptions: {
        index: false,
      },
    }),

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
