import { Controller, Get, Query, Req, UseGuards } from '@nestjs/common';
import { KalenderService } from './kalender.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';

@Controller('kalender')
@UseGuards(JwtAuthGuard)
export class KalenderController {
  constructor(private kalenderService: KalenderService) {}
  @Get() index(@Req() req: any, @Query() query: any) { return this.kalenderService.index(req.user.id, query); }
  @Get('events') events(@Req() req: any, @Query() query: any) { return this.kalenderService.events(req.user.id, query); }
}
