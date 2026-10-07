#!/bin/sh
set -e

# Local/demo convenience: create .env from the example when none is baked in.
# Real environment variables (docker compose, Render) always take precedence
# over .env values, so production configuration is never overridden.
[ -f .env ] || cp .env.example .env

# Ensure an APP_KEY exists. On Render the env var is provided instead.
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Rebuild the package manifest (skipped during the image build's composer install).
php artisan package:discover --ansi

php /usr/local/bin/wait-for-db.php

php artisan migrate --force
php artisan storage:link >/dev/null 2>&1 || true

# nginx must listen on $PORT (Render) — default 8080 for local docker compose.
PORT="${PORT:-8080}"
sed "s/__PORT__/${PORT}/g" /etc/nginx/conf.d/app.conf.template > /etc/nginx/conf.d/app.conf

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
