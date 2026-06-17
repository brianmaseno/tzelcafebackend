# TZEL CAFÉ — Laravel API & Admin

Laravel 12 backend for TZEL CAFÉ: REST API (Sanctum), admin panel, Paystack checkout, Brevo email, Groq chatbot.

## Requirements

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js 20+ (admin asset build)

## Local setup

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm ci && npm run build
php artisan storage:link
php artisan serve --port=9000
```

Default admin (after seed): `admin@tzelcafe.local` / `Admin@8498`

## Production deployment

See [DEPLOYMENT.md](./DEPLOYMENT.md) for DigitalOcean Droplet setup with MySQL on the same server (lowest cost).

## API

- Base URL: `{APP_URL}/api`
- Auth: Bearer token (Sanctum)
- Paystack webhook: `POST /api/paystack/webhook`
