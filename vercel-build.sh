#!/bin/bash

# Vercel Build Script untuk Laravel
echo "🚀 Memulai build untuk Vercel..."

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install NPM dependencies dan build assets
echo "🎨 Installing NPM dependencies dan building assets..."
npm ci --only=production
npm run build

# Clear dan cache Laravel
echo "🧹 Clearing dan caching Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize untuk production
echo "⚡ Optimizing untuk production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink jika diperlukan
echo "🔗 Creating storage symlink..."
php artisan storage:link

echo "✅ Build selesai!"
