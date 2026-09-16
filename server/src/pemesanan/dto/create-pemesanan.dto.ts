import {
  IsString,
  IsNotEmpty,
  IsOptional,
  IsInt,
  IsDateString,
  IsIn,
  Min,
  MaxLength,
} from 'class-validator';
import { Type } from 'class-transformer';

export class CreatePemesananDto {
  @IsOptional()
  @IsInt()
  @Type(() => Number)
  user_id?: number;

  @IsOptional()
  @IsString()
  kode_unit?: string;
  @IsInt()
  @Type(() => Number)
  ruangan_id: number;

  @IsOptional()
  @IsInt()
  @Type(() => Number)
  layout_ruangan_id?: number;

  @IsDateString()
  tanggal_kegiatan: string;

  @IsString()
  @IsNotEmpty()
  waktu_mulai: string;

  @IsString()
  @IsNotEmpty()
  waktu_selesai: string;

  @IsString()
  @IsNotEmpty()
  @MaxLength(150)
  judul_kegiatan: string;

  @IsOptional()
  @IsIn(['Internal', 'Eksternal'])
  jenis_kegiatan?: string;

  @IsString()
  @IsNotEmpty()
  @MaxLength(255)
  pic_kegiatan: string;

  @IsIn(['Organik', 'Non Organik'])
  jenis_pic: string;

  @IsOptional()
  @IsString()
  @MaxLength(20)
  no_wa_pic?: string;

  @IsInt()
  @Min(1)
  @Type(() => Number)
  jumlah_tamu: number;

  @IsOptional()
  @IsString()
  keterangan_layout?: string;

  @IsOptional()
  @IsString()
  catatan_user?: string;
}
