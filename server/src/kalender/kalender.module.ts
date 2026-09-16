import { Module } from '@nestjs/common';
import { KalenderController } from './kalender.controller';
import { KalenderService } from './kalender.service';
@Module({ controllers: [KalenderController], providers: [KalenderService] })
export class KalenderModule {}
