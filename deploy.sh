#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link || true
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

if systemctl is-active --quiet tzelcafe-worker; then
  systemctl restart tzelcafe-worker
fi

echo "Deploy complete."
