import { Controller, Get, Query, Res, UseGuards } from '@nestjs/common';
import { Response } from 'express';
import { LaporanService } from './laporan.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { RolesGuard } from '../auth/roles.guard';
import { Roles } from '../auth/roles.decorator';

@Controller('admin/laporan')
@UseGuards(JwtAuthGuard, RolesGuard)
@Roles('admin')
export class LaporanController {
  constructor(private laporanService: LaporanService) {}

  @Get()
  index(@Query() query: any) { return this.laporanService.index(query); }

  @Get('export-excel')
  async exportExcel(@Query() query: any, @Res() res: Response) {
    const buffer = await this.laporanService.exportExcel(query);
    const filename = `laporan-silakan-${Date.now()}.xlsx`;
    res.set({
      'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'Content-Disposition': `attachment; filename="${filename}"`,
      'Content-Length': buffer.length,
    });
    res.end(buffer);
  }
}
