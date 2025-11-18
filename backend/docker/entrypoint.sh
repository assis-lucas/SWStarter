#!/bin/bash
set -e

mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public

chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

if [ -f /var/www/html/composer.json ] && [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "Installing composer dependencies..."
    mkdir -p /var/www/html/vendor
    chown -R www-data:www-data /var/www/html/vendor
    composer install --no-interaction --prefer-dist --optimize-autoloader || {
        echo "Composer install failed, cleaning up..."
        rm -rf /var/www/html/vendor/*
        exit 1
    }
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
