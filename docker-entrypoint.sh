#!/bin/bash
set -e

echo "Checking if MySQL is running and socket exists..."
until mysqladmin ping -h "mysqldb" -u "root" -p"$DB_PASSWORD" --silent; do
  echo 'Waiting for MySQL to become available...'
  sleep 1
done

echo "MySQL is ready. Running migrations..."
php artisan migrate --force

echo "Starting PHP built-in server..."
exec php artisan serve --host=0.0.0.0
