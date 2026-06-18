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
FRONTEND_URL=https://www.tzelcafe.com
CORS_ALLOWED_ORIGINS=*
```

`APP_URL` must use **https** or admin CSS/JS will be blocked (mixed content).

### CORS — allow all origins (live `.env`)

In **DigitalOcean → your backend app → Settings → App-Level Environment Variables**, set **either**:

```env
CORS_ALLOWED_ORIGINS=*
```

**or**

```env
CORS_ALLOW_ALL=true
```

Both allow requests from any frontend domain (Vercel preview URLs, `www.tzelcafe.com`, etc.).

**More secure (recommended once your domain is final):**

```env
CORS_ALLOWED_ORIGINS=https://www.tzelcafe.com,https://tzelcafe.com,https://your-vercel-app.vercel.app
```

After changing env vars, **Save** and **Redeploy** the app.

### Migrations & seeders

```bash
php artisan migrate --force
php artisan db:seed --force
```

Runs `AdminUserSeeder`, `InitialMenuSeeder`, and `PromotionSeeder`.

### Menu images not showing (important)

DigitalOcean **App Platform** uses an **ephemeral filesystem** — files saved to `storage/` on the server are **deleted every time you redeploy**. That is why Samosa and other uploaded photos break after a GitHub push.

**You must use persistent object storage.** Options:

| Option | Cost | Best for |
|--------|------|----------|
| **DigitalOcean Spaces** | ~$5/mo (250 GB) | Recommended — works with your app code |
| **Image URL in admin** | Free | Paste a link from Imgur/Cloudinary while setting up Spaces |
| **Droplet + disk** | Droplet only | If you move off App Platform later |

---

### How to create DigitalOcean Spaces (step by step)

Spaces is **not inside App Platform**. It is a separate product in the main DigitalOcean control panel.

1. Open **[cloud.digitalocean.com/spaces](https://cloud.digitalocean.com/spaces)**  
   Or: left sidebar → **Spaces Object Storage** (under *MANAGE*).  
   If you do not see it: click green **Create** (top right) → **Spaces Object Storage**.

2. **Create a Space**
   - Choose a region close to users (e.g. **Frankfurt `fra1`** or **Amsterdam `ams3`**)
   - **Enable CDN** (recommended — faster image loading)
   - Name: `tzelcafe-assets` (must be unique globally)
   - File listing: **Restrict** (files are public only via direct URL)
   - Click **Create a Space**

3. **Create API keys**
   - Go to **API** in the left sidebar (or [cloud.digitalocean.com/account/api/spaces](https://cloud.digitalocean.com/account/api/spaces))
   - **Spaces access keys** → **Generate New Key**
   - Name: `tzelcafe-uploads` → copy **Key** and **Secret** (secret shown once)

4. **Get your Space URL**
   - Open your Space → **Settings**
   - **CDN Endpoint** looks like: `https://tzelcafe-assets.fra1.cdn.digitaloceanspaces.com`
   - **Origin endpoint**: `https://fra1.digitaloceanspaces.com`

5. **Add to backend App Platform env vars** (Settings → Environment Variables):

```env
FILESYSTEM_UPLOADS_DISK=spaces
DO_SPACES_KEY=paste_key_here
DO_SPACES_SECRET=paste_secret_here
DO_SPACES_REGION=fra1
DO_SPACES_BUCKET=tzelcafe-assets
DO_SPACES_ENDPOINT=https://fra1.digitaloceanspaces.com
DO_SPACES_URL=https://tzelcafe-assets.fra1.cdn.digitaloceanspaces.com
```

6. **Redeploy** the backend app (Deployments → **Deploy** or push to GitHub).

7. **Re-upload menu images** in admin — old files on the app server are gone; new uploads go to Spaces and **survive redeploys**.

**Quick test without Spaces:** In admin → edit menu item → use **Image URL** field with a direct link (e.g. `https://images.unsplash.com/...`) until Spaces is ready.

---

### Deployments and photos — what happens

| Storage | What happens on `git push` / redeploy |
|---------|----------------------------------------|
| Default (`public` disk on App Platform) | Images **lost** — broken photos |
| **Spaces** (`FILESYSTEM_UPLOADS_DISK=spaces`) | Images **kept** — safe to redeploy anytime |

Your workflow after Spaces is set up:

1. Push code to GitHub → App Platform auto-redeploys  
2. No need to re-upload images unless you changed the image files  
3. New admin uploads go straight to Spaces  

6. Run `php artisan storage:link` only if using `FILESYSTEM_UPLOADS_DISK=public` on a **Droplet** (not App Platform)
