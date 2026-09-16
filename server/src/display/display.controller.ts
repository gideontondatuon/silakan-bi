import { Controller, Get, Query } from '@nestjs/common';
import { DisplayService } from './display.service';

/** Public — no auth needed, used by kiosk TV lobby display */
@Controller('display-data')
export class DisplayController {
  constructor(private displayService: DisplayService) {}

  @Get()
  apiData(@Query('jenis') jenis?: string, @Query('type') type?: string) {
    return this.displayService.apiData(jenis || type);
  }
}
