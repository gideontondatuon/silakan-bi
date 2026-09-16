import {
  Controller, Get, Post, Put, Delete, Body,
  Param, ParseIntPipe, Query, UseGuards,
} from '@nestjs/common';
import { RuanganService } from './ruangan.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { RolesGuard } from '../auth/roles.guard';
import { Roles } from '../auth/roles.decorator';

@Controller('')
@UseGuards(JwtAuthGuard)
export class RuanganController {
  constructor(private ruanganService: RuanganService) {}

  /** GET /api/ruangan */
  @Get('ruangan')
  index(@Query() query: any) {
    return this.ruanganService.findAll(query);
  }

  /** GET /api/admin/ruangan */
  @Get('admin/ruangan')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminIndex(@Query() query: any) {
    return this.ruanganService.findAdmin(query);
  }

  /** GET /api/admin/ruangan/:id */
  @Get('admin/ruangan/:id')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminShow(@Param('id', ParseIntPipe) id: number) {
    return this.ruanganService.findOne(id);
  }

  /** GET /api/ruangan/:id */
  @Get('ruangan/:id')
  show(@Param('id', ParseIntPipe) id: number) {
    return this.ruanganService.findOne(id);
  }

  /** GET /api/ruangan/:id/layouts */
  @Get('ruangan/:id/layouts')
  layouts(@Param('id', ParseIntPipe) id: number) {
    return this.ruanganService.getLayouts(id);
  }

  /** GET /api/ruangan/:id/layouts-by-id */
  @Get('ruangan/:id/layouts-by-id')
  layoutsById(@Param('id', ParseIntPipe) id: number) {
    return this.ruanganService.getLayouts(id);
  }

  /** POST /api/admin/ruangan */
  @Post('admin/ruangan')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminCreate(@Body() body: any) {
    return this.ruanganService.create(body);
  }

  /** PUT /api/admin/ruangan/:id */
  @Put('admin/ruangan/:id')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminUpdate(@Param('id', ParseIntPipe) id: number, @Body() body: any) {
    return this.ruanganService.update(id, body);
  }

  /** DELETE /api/admin/ruangan/:id */
  @Delete('admin/ruangan/:id')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminRemove(@Param('id', ParseIntPipe) id: number) {
    return this.ruanganService.remove(id);
  }

  // ===== ADMIN LAYOUT CONTROLLERS =====

  /** GET /api/admin/layout */
  @Get('admin/layout')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminLayoutList(@Query() query: any) {
    return this.ruanganService.findAllLayouts(query);
  }

  /** GET /api/admin/layout/:id */
  @Get('admin/layout/:id')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminLayoutDetail(@Param('id', ParseIntPipe) id: number) {
    return this.ruanganService.findLayout(id);
  }

  /** POST /api/admin/layout */
  @Post('admin/layout')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminCreateLayout(@Body() body: any) {
    return this.ruanganService.createLayout(body);
  }

  /** PUT /api/admin/layout/:id */
  @Put('admin/layout/:id')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminUpdateLayout(@Param('id', ParseIntPipe) id: number, @Body() body: any) {
    return this.ruanganService.updateLayout(id, body);
  }

  /** DELETE /api/admin/layout/:id */
  @Delete('admin/layout/:id')
  @UseGuards(RolesGuard)
  @Roles('admin')
  adminDeleteLayout(@Param('id', ParseIntPipe) id: number) {
    return this.ruanganService.deleteLayout(id);
  }
}

