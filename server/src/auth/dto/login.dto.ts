import { IsString, IsNotEmpty, MinLength, IsOptional } from 'class-validator';

export class LoginDto {
  @IsOptional()
  @IsString()
  username?: string;

  @IsOptional()
  @IsString()
  login_input?: string;

  @IsString()
  @IsNotEmpty({ message: 'Password wajib diisi.' })
  @MinLength(1)
  password: string;

  @IsOptional()
  remember?: boolean;
}

