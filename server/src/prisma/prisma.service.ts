import { Injectable, OnModuleInit, OnModuleDestroy } from '@nestjs/common';
import { PrismaClient } from '@prisma/client';

@Injectable()
export class PrismaService extends PrismaClient implements OnModuleInit, OnModuleDestroy {
  constructor() {
    super({
      log: process.env.NODE_ENV === 'development' ? ['query', 'error', 'warn'] : ['error'],
    });
  }

  async onModuleInit() {
    try {
      await this.$connect();
      console.log('✅ Database connected successfully via Prisma');
    } catch (error: any) {
      console.warn(`⚠️  Database connection warning: ${error.message}`);
      console.warn('⚠️  Make sure MySQL is running (e.g. XAMPP/Laragon) on 127.0.0.1:3306.');
    }
  }

  async onModuleDestroy() {
    await this.$disconnect();
  }
}
