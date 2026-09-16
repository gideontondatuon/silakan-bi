import { Controller, Get, Post, Delete, Param, Query, Req, UseGuards, HttpCode, HttpStatus } from '@nestjs/common';
import { NotificationService } from './notification.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';

@Controller('notifications')
@UseGuards(JwtAuthGuard)
export class NotificationController {
  constructor(private notificationService: NotificationService) {}

  @Get() index(@Req() req: any, @Query() query: any) { return this.notificationService.index(req.user.id, query); }
  @Get('live-sync') liveSync(@Req() req: any) { return this.notificationService.liveSync(req.user); }
  @Post('read-all') @HttpCode(HttpStatus.OK) readAll(@Req() req: any) { return this.notificationService.readAll(req.user.id); }
  @Post(':id/read') @HttpCode(HttpStatus.OK) markAsRead(@Param('id') id: string, @Req() req: any) { return this.notificationService.markAsRead(req.user.id, id); }
  @Delete(':id') destroy(@Param('id') id: string, @Req() req: any) { return this.notificationService.destroy(req.user.id, id); }
  @Delete() destroyAll(@Req() req: any) { return this.notificationService.destroyAll(req.user.id); }
}
