#!/bin/bash
set -e

echo "Run migrations"

echo "MySQL is ready. Running migrations..."
php artisan migrate --force

echo "Starting PHP built-in server..."
exec php artisan serve --host=0.0.0.0
