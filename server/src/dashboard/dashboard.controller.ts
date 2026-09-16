import { Controller, Get, Req, UseGuards } from '@nestjs/common';
import { DashboardService } from './dashboard.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { RolesGuard } from '../auth/roles.guard';
import { Roles } from '../auth/roles.decorator';

@Controller('')
@UseGuards(JwtAuthGuard)
export class DashboardController {
  constructor(private dashboardService: DashboardService) {}

  /** GET /api/dashboard/user */
  @Get('dashboard/user')
  userDashboard(@Req() req: any) {
    return this.dashboardService.user(req.user.id);
  }

  /** GET /api/dashboard/admin  */
  @Get('dashboard/admin')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminDashboard() {
    return this.dashboardService.admin();
  }

  /** GET /api/admin/dashboard (alias) */
  @Get('admin/dashboard')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminDashboardAlias() {
    return this.dashboardService.admin();
  }

  /** GET /api/kegiatan-berlangsung */
  @Get('kegiatan-berlangsung')
  kegiatanBerlangsung() {
    return this.dashboardService.kegiatanBerlangsung();
  }
}
