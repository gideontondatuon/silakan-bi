import { Module } from '@nestjs/common';
import { DashboardController } from './dashboard.controller';
import { DashboardService } from './dashboard.service';
import { PemesananModule } from '../pemesanan/pemesanan.module';

@Module({
  imports: [PemesananModule],
  controllers: [DashboardController],
  providers: [DashboardService],
})
export class DashboardModule {}
