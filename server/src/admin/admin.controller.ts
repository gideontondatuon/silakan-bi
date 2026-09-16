import {
  Controller, Get, Post, Put, Delete, Body,
  Param, Query, Req, ParseIntPipe, UseGuards, HttpCode, HttpStatus,
  UseInterceptors, UploadedFile,
} from '@nestjs/common';
import { FileInterceptor } from '@nestjs/platform-express';
import { disposisiUploadOptions } from '../pemesanan/pemesanan.controller';
import { AdminService } from './admin.service';
import { PemesananService } from '../pemesanan/pemesanan.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { RolesGuard } from '../auth/roles.guard';
import { Roles } from '../auth/roles.decorator';

@Controller('')
@UseGuards(JwtAuthGuard, RolesGuard)
@Roles('admin')
export class AdminController {
  constructor(
    private adminService: AdminService,
    private pemesananService: PemesananService,
  ) {}

  // ─── Approval ──────────────────────────────────────────────────────────────
  @Get('admin/approval') approvalIndex(@Query() q: any) { return this.adminService.approvalIndex(q); }
  @Post('admin/approval')
  @HttpCode(HttpStatus.CREATED)
  @UseInterceptors(FileInterceptor('file_disposisi', disposisiUploadOptions))
  approvalStore(
    @Body() body: any,
    @Req() req: any,
    @UploadedFile() file?: Express.Multer.File,
  ) {
    const filePath = file ? `disposisi/${file.filename}` : undefined;
    return this.adminService.adminStorePemesanan(body, req.user, filePath);
  }
  @Get('admin/approval/:id') approvalShow(@Param('id', ParseIntPipe) id: number) { return this.adminService.approvalShow(id); }
  @Post('admin/approval/:id/approve') @HttpCode(HttpStatus.OK) approve(@Param('id', ParseIntPipe) id: number, @Body() body: any, @Req() req: any) { return this.adminService.approve(id, req.user, body.catatan_admin); }
  @Post('admin/approval/:id/reject') @HttpCode(HttpStatus.OK) reject(@Param('id', ParseIntPipe) id: number, @Body() body: any, @Req() req: any) { return this.adminService.reject(id, req.user, body.alasan_penolakan); }
  @Post('admin/approval/:id/selesai-awal') @HttpCode(HttpStatus.OK) approvalSelesaiAwal(@Param('id', ParseIntPipe) id: number, @Req() req: any) {
    return this.pemesananService.selesaiAwal(id, req.user);
  }
  @Delete('admin/approval/:id') @HttpCode(HttpStatus.OK) approvalDestroy(@Param('id', ParseIntPipe) id: number) { return this.adminService.approvalDestroy(id); }

  // ─── Users ─────────────────────────────────────────────────────────────────
  @Get('admin/users') userIndex(@Query() q: any) { return this.adminService.userIndex(q); }
  @Get('admin/users/:id') userShow(@Param('id', ParseIntPipe) id: number) { return this.adminService.userShow(id); }
  @Post('admin/users') @HttpCode(HttpStatus.CREATED) userStore(@Body() body: any) { return this.adminService.userStore(body); }
  @Put('admin/users/:id') userUpdate(@Param('id', ParseIntPipe) id: number, @Body() body: any) { return this.adminService.userUpdate(id, body); }
  @Delete('admin/users/:id') @HttpCode(HttpStatus.OK) userDestroy(@Param('id', ParseIntPipe) id: number) { return this.adminService.userDestroy(id); }
  @Get('admin/units') getUnits() { return this.adminService.getUnits(); }

  // ─── WhatsApp Gateway ────────────────────────────────────────────────────────
  @Get('admin/whatsapp/status') getWhatsappStatus() { return this.adminService.getWhatsappStatus(); }
  @Post('admin/whatsapp/test') @HttpCode(HttpStatus.OK) testWhatsapp(@Body() body: any) { return this.adminService.testWhatsapp(body?.phone); }
}
