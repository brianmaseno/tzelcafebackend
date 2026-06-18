# Deploy TZEL CAFÉ Backend on DigitalOcean (low-cost)

This guide uses a **single Droplet** with **MySQL installed on the same server**. You pay only for the Droplet (~$6–12/month), not a separate managed database.

Repository: [github.com/brianmaseno/tzelcafebackend](https://github.com/brianmaseno/tzelcafebackend)

---

## Overview

| Piece | Choice | Why |
|-------|--------|-----|
| Server | DigitalOcean Droplet (Ubuntu 24.04) | Full control, cheap |
| Database | MySQL on same Droplet | **$0 extra** vs managed DB |
| Web server | Nginx + PHP-FPM | Standard Laravel stack |
| SSL | Let's Encrypt (Certbot) | Free HTTPS |
| Deploy | Git pull on server | Simple after GitHub link |

**Cheaper DB alternatives** (if you outgrow the Droplet): PlanetScale free tier, Aiven free MySQL trial, or TiDB Cloud free tier. For TZEL CAFÉ starting out, **same-server MySQL is the best cost cut**.

---

## Part 1 — Create the Droplet

1. Log in to [DigitalOcean](https://cloud.digitalocean.com).
2. **Create → Droplets**
3. **Image:** Ubuntu 24.04 LTS
4. **Plan:** Basic → Regular → **$6/mo** (1 GB RAM) or **$12/mo** (2 GB RAM recommended)
5. **Region:** Frankfurt or closest to Kenya
6. **Authentication:** SSH key (recommended)
7. **Hostname:** `tzelcafe-api`
8. Note the **public IP**

Optional firewall: allow 22, 80, 443 — block 3306 from the internet.

---

## Part 2 — DNS (recommended)

| Type | Name | Value |
|------|------|--------|
| A | `api` | Your Droplet IP |

Example: `api.tzelcafe.co.ke`

---

## Part 3 — Server setup

```bash
ssh root@YOUR_DROPLET_IP
apt update && apt upgrade -y
apt install -y nginx mysql-server php8.3-fpm php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl \
  unzip git curl software-properties-common
```

### MySQL (on same server — no extra monthly fee)

```bash
mysql_secure_installation
mysql -u root -p
```

```sql
CREATE DATABASE tzelcafe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tzelcafe'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON tzelcafe.* TO 'tzelcafe'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Composer & Node

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

### Clone from GitHub

```bash
mkdir -p /var/www && cd /var/www
git clone https://github.com/brianmaseno/tzelcafebackend.git tzelcafe
cd tzelcafe
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
nano .env
```

Production `.env` essentials:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.tzelcafe.co.ke
DB_HOST=127.0.0.1
DB_DATABASE=tzelcafe
DB_USERNAME=tzelcafe
DB_PASSWORD=STRONG_PASSWORD_HERE
FRONTEND_URL=https://your-frontend-domain.com
CORS_ALLOWED_ORIGINS=https://your-frontend-domain.com
```

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
npm ci && npm run build
php artisan config:cache && php artisan route:cache && php artisan view:cache
chown -R www-data:www-data /var/www/tzelcafe
chmod -R 775 storage bootstrap/cache
```

---

## Part 4 — Nginx + SSL

Create `/etc/nginx/sites-available/tzelcafe` pointing `root` to `/var/www/tzelcafe/public`, enable site, then:

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d api.tzelcafe.co.ke
```

---

## Part 5 — Queue worker

```bash
systemctl enable --now tzelcafe-worker
```

See full systemd unit example in repo docs or use:

`php artisan queue:work database --daemon`

---

## Part 6 — Paystack webhook

```
https://api.tzelcafe.co.ke/api/paystack/webhook
```

---

## Deploy updates after GitHub push

```bash
cd /var/www/tzelcafe
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci && npm run build
php artisan config:cache && php artisan route:cache && php artisan view:cache
systemctl restart tzelcafe-worker
```

---

## Estimated monthly cost

| Item | Cost |
|------|------|
| Droplet 2 GB | ~$12/mo |
| MySQL on Droplet | $0 |
| SSL | $0 |
| **Total** | **~$12/mo** |

---

## DigitalOcean App Platform (backend)

Live URL example: `https://tzelcafebackend-gixrp.ondigitalocean.app`

Set in **Settings → App-Level Environment Variables**:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tzelcafebackend-gixrp.ondigitalocean.app
APP_FORCE_HTTPS=true
ASSET_URL=https://tzelcafebackend-gixrp.ondigitalocean.app
FRONTEND_URL=https://YOUR-FRONTEND-URL
CORS_ALLOWED_ORIGINS=https://YOUR-FRONTEND-URL
```

`APP_URL` must use **https** or admin CSS/JS will be blocked (mixed content).

### Migrations & seeders

```bash
php artisan migrate --force
php artisan db:seed --force
```

Runs `AdminUserSeeder`, `InitialMenuSeeder`, and `PromotionSeeder`.

### Menu images not showing (important)

DigitalOcean App Platform uses an **ephemeral filesystem** — uploaded images in `storage/` are **lost on every redeploy** unless you use **DigitalOcean Spaces**.

1. Create a **Space** in DigitalOcean (e.g. `tzelcafe-assets`, region `fra1`)
2. Set Space to **Public** or use CDN endpoint
3. Create API keys (Spaces access key + secret)
4. Set backend environment variables:

```env
FILESYSTEM_UPLOADS_DISK=spaces
DO_SPACES_KEY=your_key
DO_SPACES_SECRET=your_secret
DO_SPACES_REGION=fra1
DO_SPACES_BUCKET=tzelcafe-assets
DO_SPACES_ENDPOINT=https://fra1.digitaloceanspaces.com
DO_SPACES_URL=https://tzelcafe-assets.fra1.cdn.digitaloceanspaces.com
```

5. Redeploy, then **re-upload menu images** in admin (old files on ephemeral disk are gone)
6. Run `php artisan storage:link` only if using `FILESYSTEM_UPLOADS_DISK=public` on a Droplet (not App Platform)
