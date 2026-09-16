import { Injectable } from '@nestjs/common';
import { AuthGuard } from '@nestjs/passport';

/**
 * JwtAuthGuard — equivalent to Laravel's auth:sanctum middleware.
 * Apply with @UseGuards(JwtAuthGuard) on controllers or routes.
 */
@Injectable()
export class JwtAuthGuard extends AuthGuard('jwt') {
  constructor() {
    super();
  }
}
