#!/bin/sh
set -e

cd /var/www/html

# Wait isn't needed for FPM/Nginx since Supervisor starts them after this,
# but we DO need env vars to exist before caching config.

echo "Running Laravel startup optimizations..."

# Clear any stale caches baked into the image from build time
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Re-cache using the REAL runtime environment variables from Render
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations automatically on every container start
php artisan migrate --force

echo "Startup optimizations complete. Handing off to Supervisor..."

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf