#!/bin/sh

echo "🚀 Running migrations & seeders..."
php artisan migrate --force --seed

echo "⚡ Caching config..."
php artisan config:cache

echo "⚡ Caching routes..."
php artisan route:cache

echo "✅ Starting PHP-FPM..."

exec php-fpm