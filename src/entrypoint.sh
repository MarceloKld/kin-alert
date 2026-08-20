#!/bin/sh

echo "🚀 Running migrations & seeders..."
php artisan migrate --force --seed

echo "🧹 Clearing config cache..."
php artisan config:clear

echo "🧹 Clearing route cache..."
php artisan route:clear

echo "✅ Starting server..."
php artisan serve --host=0.0.0.0 --port=8000