import { Module } from '@nestjs/common';
import { PemesananController } from './pemesanan.controller';
import { PemesananService } from './pemesanan.service';
import { NotificationModule } from '../notification/notification.module';
import { WhatsappModule } from '../whatsapp/whatsapp.module';

@Module({
  imports: [NotificationModule, WhatsappModule],
  controllers: [PemesananController],
  providers: [PemesananService],
  exports: [PemesananService],
})
export class PemesananModule {}
