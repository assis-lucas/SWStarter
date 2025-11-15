#!/bin/bash
set -e

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

setfacl -R -m u:1000:rwX /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
setfacl -R -d -m u:1000:rwX /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
