import { NestFactory } from '@nestjs/core';
import { ValidationPipe, BadRequestException } from '@nestjs/common';
import { AppModule } from './app.module';

// Enable BigInt serialization in JSON.stringify
(BigInt.prototype as any).toJSON = function () {
  return Number(this);
};

async function bootstrap() {
  const app = await NestFactory.create(AppModule);

  // Global prefix — matches Laravel's /api prefix
  app.setGlobalPrefix('api');

  // Global validation pipe — formatted to match frontend expectation: { message, errors: { [field]: string[] } }
  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      forbidNonWhitelisted: false,
      transform: true,
      transformOptions: { enableImplicitConversion: true },
      exceptionFactory: (validationErrors) => {
        const errors: Record<string, string[]> = {};
        const extractErrors = (errs: typeof validationErrors) => {
          for (const err of errs) {
            if (err.constraints) {
              errors[err.property] = Object.values(err.constraints);
            }
            if (err.children && err.children.length > 0) {
              extractErrors(err.children);
            }
          }
        };
        extractErrors(validationErrors);
        return new BadRequestException({
          status: 'error',
          message: 'Validasi gagal.',
          errors,
        });
      },
    }),
  );

  // CORS — support custom origins from env while allowing local development
  const envOrigins = (process.env.CORS_ORIGIN || process.env.APP_URL || '')
    .split(',')
    .map((o) => o.trim())
    .filter(Boolean);

  const defaultOrigins = [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'http://localhost:8000',
    'http://127.0.0.1:8000',
    'http://localhost',
    'http://127.0.0.1',
  ];

  const allowedOrigins = Array.from(new Set([...defaultOrigins, ...envOrigins]));

  app.enableCors({
    origin: (origin, callback) => {
      if (!origin || allowedOrigins.includes(origin) || process.env.NODE_ENV !== 'production') {
        callback(null, true);
      } else {
        callback(null, true); // Permissive or allow dynamic origin in proxy
      }
    },
    credentials: true,
    methods: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    allowedHeaders: ['Content-Type', 'Authorization', 'Accept', 'X-Requested-With', 'X-CSRF-TOKEN'],
  });

  const port = process.env.PORT ?? 3001;
  await app.listen(port);

  console.log(`🚀 SILAKAN NestJS API running on: http://localhost:${port}/api`);
}
bootstrap();
