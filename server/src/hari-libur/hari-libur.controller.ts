import {
  Controller, Get, Post, Delete, Param, Body, Query,
  ParseIntPipe, UseGuards, HttpCode, HttpStatus,
} from '@nestjs/common';
import { HariLiburService } from './hari-libur.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { RolesGuard } from '../auth/roles.guard';
import { Roles } from '../auth/roles.decorator';

@Controller('')
@UseGuards(JwtAuthGuard)
export class HariLiburController {
  constructor(private service: HariLiburService) {}

  // Endpoint to retrieve all active holidays for calendar & booking forms
  @Get('hari-libur/dates')
  allDates() {
    return this.service.allDates();
  }

  // ─── ADMIN HARI LIBUR MASTER ──────────────────────────────────────────────

  @Get('admin/hari-libur')
  @UseGuards(RolesGuard)
  @Roles('admin')
  index(@Query() query: any) {
    return this.service.index(query);
  }

  @Post('admin/hari-libur/sync')
  @UseGuards(RolesGuard)
  @Roles('admin')
  @HttpCode(HttpStatus.OK)
  sync(@Body() body: any) {
    const year = body.tahun ? parseInt(body.tahun) : new Date().getFullYear();
    return this.service.syncApi(year);
  }

  @Post('admin/hari-libur')
  @UseGuards(RolesGuard)
  @Roles('admin')
  @HttpCode(HttpStatus.CREATED)
  store(@Body() body: any) {
    return this.service.store(body);
  }

  @Delete('admin/hari-libur/:id')
  @UseGuards(RolesGuard)
  @Roles('admin')
  @HttpCode(HttpStatus.OK)
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.service.remove(id);
  }
}
