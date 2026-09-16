import { Module } from '@nestjs/common';
import { HariLiburController } from './hari-libur.controller';
import { HariLiburService } from './hari-libur.service';
@Module({ controllers: [HariLiburController], providers: [HariLiburService] })
export class HariLiburModule {}
