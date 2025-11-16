#!/bin/bash
set -e

mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

find /var/www/html/storage -type d -exec chmod g+s {} \; 2>/dev/null || true

chown -R www-data:www-data /run/php 2>/dev/null || true

if [ -f /var/www/html/composer.json ]; then
    if [ ! -d /var/www/html/vendor ]; then
        echo "Installing composer dependencies..."
        su -s /bin/bash www-data -c "composer install --no-interaction --prefer-dist --optimize-autoloader"
    fi
    chown -R www-data:www-data /var/www/html/vendor 2>/dev/null || true
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
