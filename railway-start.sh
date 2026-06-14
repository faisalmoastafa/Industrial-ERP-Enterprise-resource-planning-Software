#!/bin/bash
set -e

echo "=== Industrial ERP System Railway Startup ==="

# ─── 1. ENVIRONMENT ─────────────────────────────────────────────────────────

# Force SQLite connection
export DB_CONNECTION=sqlite

# Set DB_DATABASE to an absolute path inside the writable storage folder
export DB_DATABASE="$(pwd)/storage/database.sqlite"

# Disable wkhtmltopdf on Railway (Linux binary not available via composer)
export WKHTML_PDF_BINARY=""

# Railway provides APP_URL via env var — must be the full HTTPS URL
if [ -z "$APP_URL" ]; then
  export APP_URL="https://industrial-erp-enterprise-resource-planning-soft-production.up.railway.app"
fi

# Fix asset URL to match APP_URL
export ASSET_URL="${APP_URL}"

# Session must use same-site=lax and secure=true for Railway HTTPS
export SESSION_SECURE_COOKIE=true
export SESSION_SAME_SITE=lax
export SESSION_DOMAIN=".railway.app"

# ─── 2. DIRECTORY SETUP ─────────────────────────────────────────────────────

echo "Creating required directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# ─── 3. DATABASE FILE ────────────────────────────────────────────────────────

if [ ! -f storage/database.sqlite ]; then
  echo "Creating SQLite database file..."
  touch storage/database.sqlite
fi

# ─── 4. APP KEY ──────────────────────────────────────────────────────────────

if [ -z "$APP_KEY" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
fi

# ─── 5. CLEAR STALE CACHE ────────────────────────────────────────────────────

echo "Clearing stale bootstrap cache..."
rm -f bootstrap/cache/*.php

# ─── 6. RUN MIGRATIONS ───────────────────────────────────────────────────────

echo "Running migrations..."
php artisan migrate --force

# ─── 7. SEED DATA ────────────────────────────────────────────────────────────

echo "Seeding currency..."
php artisan db:seed --class="Modules\Currency\Database\Seeders\CurrencyDatabaseSeeder" --force 2>/dev/null || echo "CurrencySeeder skipped (already seeded)"

echo "Seeding settings..."
php artisan db:seed --class="Modules\Setting\Database\Seeders\SettingDatabaseSeeder" --force 2>/dev/null || echo "SettingSeeder skipped (already seeded)"

echo "Seeding permissions and roles..."
php artisan db:seed --class="Modules\User\Database\Seeders\UserDatabaseSeeder" --force 2>/dev/null || echo "UserDatabaseSeeder skipped (already seeded)"

echo "Seeding superadmin user..."
php artisan db:seed --class=Database\\Seeders\\SuperUserSeeder --force 2>/dev/null || echo "SuperUserSeeder skipped (already seeded)"

echo "Seeding demo user (if APP_DEMO=true)..."
php artisan db:seed --class=Database\\Seeders\\DatabaseSeeder --force 2>/dev/null || echo "DatabaseSeeder skipped"

# ─── 8. STORAGE LINK ─────────────────────────────────────────────────────────

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || echo "Storage link skipped"

# ─── 9. PRODUCTION CACHE ─────────────────────────────────────────────────────

echo "Caching config, routes, views..."
php artisan config:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ─── 10. START SERVER ────────────────────────────────────────────────────────

echo "=== Starting server on 0.0.0.0:${PORT:-8000} ==="
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
