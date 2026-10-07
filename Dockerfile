# syntax=docker/dockerfile:1

# ---------- Stage 1: build frontend assets ----------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---------- Stage 2: PHP runtime ----------
FROM php:8.2-fpm-bookworm

# nginx serves HTTP (php-fpm alone is not a web server),
# ffmpeg is required by the video processing jobs,
# supervisor runs nginx + php-fpm in one container.
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        ffmpeg \
        supervisor \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libpq-dev \
        libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# The stack runs on PostgreSQL (pdo_pgsql), not MySQL.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql mbstring exif pcntl bcmath gd zip opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Dependencies first so this layer is cached independently of app code.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Application code, then frontend assets and the PSPDFKit runtime built in stage 1.
COPY . .
COPY --from=assets /app/public/build ./public/build
COPY --from=assets /app/public/assets/dist ./public/assets/dist

# Runtime configuration.
COPY docker/nginx/app.conf.template /etc/nginx/conf.d/app.conf.template
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/wait-for-db.php /usr/local/bin/wait-for-db.php
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN rm -f /etc/nginx/sites-enabled/default \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/framework/testing storage/logs bootstrap/cache \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
