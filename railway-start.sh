#!/bin/bash
set -e

echo "=== NECI-ERP Railway Startup ==="

# Ensure storage directories exist
mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Create SQLite database file if it doesn't exist
if [ ! -f storage/database.sqlite ]; then
  echo "Creating SQLite database..."
  touch storage/database.sqlite
fi

# Set DB_DATABASE to absolute path
export DB_DATABASE="$(pwd)/storage/database.sqlite"

# Generate app key if APP_KEY not set
if [ -z "$APP_KEY" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
fi

# Clear stale bootstrap cache
rm -f bootstrap/cache/*.php

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed demo data
echo "Seeding demo data..."
php artisan db:seed --force 2>/dev/null || echo "Seeding skipped"

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Starting server on port ${PORT:-8000} ==="
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
