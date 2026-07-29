#!/bin/sh

echo "🚀 Running migrations & seeders..."
php artisan migrate --force --seed

echo "⚡ Caching config..."
php artisan config:cache

echo "⚡ Caching routes..."
php artisan route:cache

echo "✅ Starting server..."
php artisan serve --host=0.0.0.0 --port=8000