# Deployment — Hostinger

Production deployment guide for the ASMC Manpower Platform (Laravel 12 + Livewire 3 + Vite).
Target: Hostinger shared/premium hosting with PHP 8.2+, MySQL, and SSH or File Manager access.

> Replace `u1234567` with your Hostinger username and `your-domain.com` with your real domain throughout.

---

## 0. Prerequisites (do these in hPanel first)

1. **PHP version** → hPanel → Advanced → PHP Configuration → set to **8.2 or 8.3**.
2. **PHP extensions** → same page → enable: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `gd` or `imagick`, `intl` (required for Hijri dates), `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`.
3. **Create MySQL database** → hPanel → Databases → MySQL Databases. Note the DB name, user, and password (Hostinger prefixes them with your account, e.g. `u1234567_manpower`).
4. **Create an email mailbox** → hPanel → Emails → Email Accounts → Add. e.g. `noreply@your-domain.com`. This is required for password-reset and email-verification emails (see **Email / SMTP** below).
5. **SSL** → hPanel → Security → SSL → install the free Let's Encrypt certificate for your domain. After it's active, uncomment the HTTPS-redirect and HSTS lines in `public/.htaccess`.

---

## 1. Upload the project

The web root on Hostinger is `public_html`. Laravel's entry point is `public/index.php`, so we keep the **whole project above `public_html`** and only expose the public folder.

### Option A — Git (recommended, reproducible)

```bash
ssh u1234567@your-domain.com
cd ~                                   # /home/u1234567
git clone <your-repo-url> manpower-platform
cd manpower-platform
composer install --no-dev --optimize-autoloader
npm ci && npm run build                # or build locally and commit public/build
```

### Option B — File Manager / ZIP

1. Zip the project **excluding** `node_modules`, `vendor`, `.env`, `storage/logs/*`, `storage/framework/*`.
2. Upload the zip to `/home/u1234567/` (one level above `public_html`) via hPanel File Manager.
3. Extract → you get `/home/u1234567/manpower-platform/`.
4. Run `composer install --no-dev --optimize-autoloader` via SSH, or upload a locally-built `vendor/` if you have no SSH.

---

## 2. Point `public_html` at Laravel's public folder

**Empty `public_html` first** (back up anything inside it). Then choose one:

### Method 1 — Symlink (cleanest, needs SSH)

```bash
cd /home/u1234567
rm -rf public_html
ln -s manpower-platform/public public_html
```

### Method 2 — Adapter `index.php` (no SSH / symlink not allowed)

Copy the contents of `manpower-platform/public/` into `public_html/`, then **edit `public_html/index.php`** so its paths point up one level:

```php
<?php
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../manpower-platform/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../manpower-platform/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../manpower-platform/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

Also copy `manpower-platform/public/.htaccess` and `manpower-platform/public/build/` into `public_html/` (the `.htaccess` and built assets must sit next to `index.php`).

> With Method 2, keep `public_html/build/` in sync after every `npm run build` — re-copy the `build/` folder on each deploy.

---

## 3. Configure the environment

```bash
cd ~/manpower-platform
cp .env.production.example .env
php artisan key:generate
```

Edit `.env` and fill in:
- `APP_URL=https://your-domain.com`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (from step 0.3)
- `MAIL_*` (from step 0.4 — see **Email / SMTP** below)
- `SESSION_DOMAIN=your-domain.com`

Then:

```bash
php artisan migrate --force          # create tables
php artisan db:seed --class=DemoSeeder   # optional: load demo roles/users/data
php artisan storage:link             # symlink public/storage → storage/app/public
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> Default seeded login: `admin@asmc.com.sa` / `password`. **Change this password immediately** after first login, or skip the seeder and create your own admin.

---

## 4. Set the folder permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R u1234567:u1234567 storage bootstrap/cache
```

Hostinger usually runs PHP as your user, so ownership is typically fine. If you get a 500 / "permission denied" on logs or sessions, widen `storage/` to `775` recursively.

---

## 5. Scheduler (cron) — required for compliance alerts

`routes/console.php` schedules `compliance:scan` daily at 02:00. Add this cron entry in **hPanel → Advanced → Cron Jobs** (or via SSH `crontab -e`):

```
* * * * * cd /home/u1234567/manpower-platform && php artisan schedule:run >> /dev/null 2>&1
```

This runs Laravel's scheduler every minute; Laravel internally decides when `compliance:scan` fires.

---

## 6. Queue worker (optional but recommended)

The app uses the `database` queue. If you send password-reset emails synchronously you can skip this, but for email verification, future notifications, and the compliance scanner's alert dispatching, run a worker.

### Shared hosting (no daemon access) — use the scheduler

Hostinger shared plans don't allow long-running daemons. Add this to `routes/console.php` so the scheduler processes queued jobs each minute:

```php
Schedule::command('queue:work --stop-when-empty --tries=1 --timeout=60')->everyMinute();
```

### VPS / Premium hosting with SSH

```bash
php artisan queue:work --tries=3 --timeout=60 &
```
Or use Supervisor — see the Laravel [queue docs](https://laravel.com/docs/queues#supervisor-configuration).

Alternatively set `QUEUE_CONNECTION=sync` in `.env` to send emails inline (simplest, no worker needed, but the request waits on SMTP).

---

## 7. Email / SMTP — **required for password reset & email verification**

The password-reset flow already exists (`resources/views/livewire/pages/auth/forgot-password.blade.php` → `Password::sendResetLink`). It only works once `MAIL_MAILER=smtp` is configured with working SMTP credentials.

### What you need to set up (in hPanel)

1. **Create a mailbox** → hPanel → Emails → Email Accounts → **Add account**.
   - Address: `noreply@your-domain.com` (or `admin@your-domain.com`)
   - Password: choose a strong one — save it, this becomes `MAIL_PASSWORD`
2. **Confirm the SMTP settings** (Hostinger standard):
   - Host: `smtp.hostinger.com`
   - Port: `587` with `MAIL_SCHEME=tls` (recommended), **or** port `465` with implicit SSL (leave `MAIL_SCHEME` empty)
   - Username: the full email address, e.g. `noreply@your-domain.com`
   - Password: the mailbox password
3. **Set these in `.env`**:
   ```
   MAIL_MAILER=smtp
   MAIL_SCHEME=tls
   MAIL_HOST=smtp.hostinger.com
   MAIL_PORT=587
   MAIL_USERNAME=noreply@your-domain.com
   MAIL_PASSWORD=your-mailbox-password
   MAIL_FROM_ADDRESS="noreply@your-domain.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```
4. **DNS records** (help deliverability — hPanel → DNS Zone Editor):
   - Add an **SPF** TXT record on your domain: `v=spf1 include:_spf.mail.hostinger.com ~all`
   - Add a **DKIM** record if Hostinger exposes one in the email account settings (copy the provided TXT value).
   - Add a **DMARC** TXT record: `v=DMARC1; p=none; rua=mailto:admin@your-domain.com`
5. **Test it** after deploy:
   ```bash
   php artisan tinker
   >>> Illuminate\Support\Facades\Mail::raw('test from ASMC', fn($m) => $m->to('your-personal@email.com')->subject('SMTP test'));
   ```
   If it sends, password reset and email verification will work. If it fails, check `storage/logs/laravel.log` for the SMTP error (wrong port, wrong password, or the mailbox not yet provisioned).

### Why this matters for the app

- **Forgot password** — `/forgot-password` emails a reset link via `Password::sendResetLink`. With `MAIL_MAILER=log` (the current default) the link is only written to the log file and never emailed. Switching to `smtp` makes it reach the user's inbox.
- **Email verification** — `/verify-email` sends a signed verification link on registration. Same dependency on SMTP.

---

## 8. Post-deploy checklist

- [ ] Visit `https://your-domain.com` — the welcome page loads.
- [ ] `/login` works and `admin@asmc.com.sa` / `password` logs in (if seeded).
- [ ] `https://` is enforced (HTTPS redirect active).
- [ ] `/forgot-password` sends a real email (check the inbox).
- [ ] `storage/logs/laravel.log` is writable (no permission errors).
- [ ] Cron is running — check `php artisan schedule:list` shows the compliance task.
- [ ] `php artisan about` reports the correct env, DB, and cache config.
- [ ] Assets load — open DevTools → Network, confirm `app-*.css` and `app-*.js` return 200 with long cache headers.
- [ ] Change the default admin password and remove the seeder from production deploys if you don't want demo data.

---

## 9. Updating on future deploys

```bash
cd ~/manpower-platform
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build               # if assets changed
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache
php artisan queue:restart             # so workers pick up new code
php artisan optimize:clear            # if anything looks stale, then re-cache
```

If using Method 2 (adapter `index.php`), also re-copy `public/build/` into `public_html/build/`.

---

## 10. Troubleshooting

| Symptom | Fix |
|---|---|
| 500 error on every page | Check `storage/logs/laravel.log`. Usually a missing `.env` key, bad DB creds, or `storage/` not writable. Run `php artisan key:generate`. |
| Assets 404 | `public_html/build/` missing or out of sync. Re-copy from `manpower-platform/public/build/`. |
| "No application encryption key" | `php artisan key:generate` then `php artisan config:cache`. |
| Password reset email never arrives | `MAIL_MAILER` still `log`, or wrong SMTP port/password. Test with the `tinker` command in section 7. |
| Cron not firing | Confirm the cron job path points at `~/manpower-platform/artisan` and PHP is on PATH. Use the full path to PHP if needed: `/usr/local/bin/php`. |
| Session/login loops | `SESSION_DOMAIN` wrong, or `storage/framework/sessions` not writable. |
| Hijri dates show garbage | `intl` extension missing — enable it in hPanel PHP settings. |
