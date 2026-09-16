# Stage 1: Build Frontend (React + Vite)
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Build Backend (NestJS + Prisma)
FROM node:20-alpine AS backend-builder
WORKDIR /app/server
COPY server/package*.json ./
RUN npm ci
COPY server/ ./
RUN npx prisma generate
RUN npm run build

# Stage 3: Production Runner
FROM node:20-alpine AS runner
WORKDIR /app
ENV NODE_ENV=production

# Install openssl for Prisma on Alpine Linux
RUN apk add --no-cache openssl libc6-compat

# Copy built frontend assets to dist/
COPY --from=frontend-builder /app/dist ./dist
COPY package*.json ./

# Setup server folder and production dependencies
WORKDIR /app/server
COPY --from=backend-builder /app/server/package*.json ./
RUN npm ci --omit=dev
COPY --from=backend-builder /app/server/dist ./dist
COPY --from=backend-builder /app/server/prisma ./prisma
COPY --from=backend-builder /app/server/node_modules/.prisma ./node_modules/.prisma

# Create persistent upload storage directory
WORKDIR /app
RUN mkdir -p storage/app/public/disposisi && chmod -R 777 storage

EXPOSE 3001
ENV PORT=3001

CMD ["sh", "-c", "npx --prefix server prisma migrate deploy || true; node server/dist/main"]
