#!/bin/bash
set -e

if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "vendor/autoload.php not found. Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

echo "Run migrations"

echo "MySQL is ready. Running migrations..."
php artisan migrate --force

echo "Starting PHP built-in server..."
exec php artisan serve --host=0.0.0.0
