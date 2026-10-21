#!/bin/sh
set -e

cd /var/www/html

# Cache with the runtime env (Coolify env vars aren't known at build time)
su -s /bin/sh www-data -c "php artisan optimize"

exec supervisord -c /etc/supervisor/supervisord.conf
