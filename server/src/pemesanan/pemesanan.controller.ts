import {
  Controller,
  Get,
  Post,
  Body,
  Param,
  Query,
  Req,
  UseGuards,
  UseInterceptors,
  UploadedFile,
  ParseIntPipe,
  HttpCode,
  HttpStatus,
  BadRequestException,
} from '@nestjs/common';
import { FileInterceptor } from '@nestjs/platform-express';
import { diskStorage } from 'multer';
import { extname, join } from 'path';
import { PemesananService } from './pemesanan.service';
import { JwtAuthGuard } from '../auth/jwt-auth.guard';
import { CreatePemesananDto } from './dto/create-pemesanan.dto';

import * as fs from 'fs';

export const disposisiStorage = diskStorage({
  destination: (_req, _file, cb) => {
    const dest = fs.existsSync(join(process.cwd(), 'storage', 'app', 'public', 'disposisi'))
      ? join(process.cwd(), 'storage', 'app', 'public', 'disposisi')
      : join(process.cwd(), '..', 'storage', 'app', 'public', 'disposisi');
    if (!fs.existsSync(dest)) {
      fs.mkdirSync(dest, { recursive: true });
    }
    cb(null, dest);
  },
  filename: (_req, file, cb) => {
    const uniqueSuffix = `${Date.now()}-${Math.round(Math.random() * 1e9)}`;
    cb(null, `disposisi-${uniqueSuffix}${extname(file.originalname)}`);
  },
});

export const disposisiFileFilter = (
  _req: any,
  file: Express.Multer.File,
  cb: (error: Error | null, acceptFile: boolean) => void,
) => {
  const allowedMimeTypes = [
    'application/pdf',
    'image/jpeg',
    'image/png',
    'image/webp',
  ];
  if (!allowedMimeTypes.includes(file.mimetype)) {
    return cb(
      new BadRequestException(
        'Format berkas disposisi tidak didukung. Harap unggah berkas PDF, JPG, atau PNG.',
      ),
      false,
    );
  }
  cb(null, true);
};

export const disposisiUploadOptions = {
  storage: disposisiStorage,
  fileFilter: disposisiFileFilter,
  limits: {
    fileSize: 10 * 1024 * 1024, // Maksimal 10MB
  },
};

@Controller('pemesanan')
@UseGuards(JwtAuthGuard)
export class PemesananController {
  constructor(private pemesananService: PemesananService) {}

  /** GET /api/pemesanan */
  @Get()
  index(@Req() req: any, @Query() query: any) {
    return this.pemesananService.index(req.user.id, query);
  }

  /** GET /api/pemesanan/check-conflict */
  @Get('check-conflict')
  checkConflict(@Query() query: any) {
    return this.pemesananService.checkConflictApi(query);
  }

  /** GET /api/pemesanan/units */
  @Get('units')
  getUnits() {
    return this.pemesananService.getUnits();
  }

  /** GET /api/pemesanan/:id */
  @Get(':id')
  show(@Param('id', ParseIntPipe) id: number, @Req() req: any) {
    return this.pemesananService.show(id, req.user);
  }

  /** POST /api/pemesanan */
  @Post()
  @UseInterceptors(FileInterceptor('file_disposisi', disposisiUploadOptions))
  store(
    @Body() dto: CreatePemesananDto,
    @Req() req: any,
    @UploadedFile() file?: Express.Multer.File,
  ) {
    const filePath = file ? `disposisi/${file.filename}` : undefined;
    return this.pemesananService.store(dto, req.user, filePath);
  }

  /** POST /api/pemesanan/:id/cancel */
  @Post(':id/cancel')
  @HttpCode(HttpStatus.OK)
  cancel(@Param('id', ParseIntPipe) id: number, @Req() req: any) {
    return this.pemesananService.cancel(id, req.user);
  }

  /** POST /api/pemesanan/:id/selesai-awal */
  @Post(':id/selesai-awal')
  @HttpCode(HttpStatus.OK)
  selesaiAwal(@Param('id', ParseIntPipe) id: number, @Req() req: any) {
    return this.pemesananService.selesaiAwal(id, req.user);
  }
}
