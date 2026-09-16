import {
  IsString,
  IsOptional,
  IsEmail,
  MaxLength,
  IsNotEmpty,
  MinLength,
} from 'class-validator';

export class UpdateProfileDto {
  @IsString()
  @IsOptional()
  @MaxLength(255)
  name?: string;

  @IsEmail({}, { message: 'Format email tidak valid.' })
  @IsOptional()
  email?: string;

  @IsString()
  @IsOptional()
  @MaxLength(20)
  no_wa?: string;

  @IsString()
  @IsOptional()
  @MaxLength(255)
  nama_unit?: string;

  @IsString()
  @IsOptional()
  @MaxLength(50)
  kode_unit?: string;
}

export class UpdatePasswordDto {
  @IsString()
  @IsNotEmpty()
  current_password: string;

  @IsString()
  @MinLength(8, { message: 'Password baru minimal 8 karakter.' })
  password: string;

  @IsString()
  @IsNotEmpty()
  password_confirmation: string;
}
