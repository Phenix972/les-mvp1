# MVP-1

Distribution Operator for indie SaaS founders — scaffold.

**Stack:** Laravel 11 · Blade · Livewire 3 · Horizon · Upstash Redis · PostgreSQL (Railway) · Sentry · PostHog

[![CI](https://github.com/Phenix972/les-mvp1/actions/workflows/ci.yml/badge.svg)](https://github.com/Phenix972/les-mvp1/actions/workflows/ci.yml)

---

## Prerequisites

- PHP 8.4+
- Composer 2
- Node.js 22+
- (Optional) Redis for local queue/cache testing

## Local setup

```bash
git clone git@github.com:Phenix972/les-mvp1.git
cd les-mvp1
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev          # Vite dev server
php artisan serve    # Laravel dev server → http://localhost:8000
```

### Run queues locally

```bash
php artisan queue:listen   # simple (no Redis required locally)
# or
php artisan horizon        # full Horizon (requires Redis)
```

### Run the scheduler locally

```bash
php artisan schedule:work
# Logs a heartbeat every 5 minutes: storage/logs/laravel.log
```

## Environment variables

See `.env.example` for the full list. Key variables to set in production:

| Variable | Purpose |
|---|---|
| `APP_KEY` | Laravel encryption key (auto-generated) |
| `DB_*` | PostgreSQL connection (Railway plugin) |
| `REDIS_HOST` / `REDIS_PASSWORD` | Upstash Redis (Railway plugin) |
| `SENTRY_LARAVEL_DSN` | Error tracking — get from sentry.io |
| `POSTHOG_KEY` | Analytics — get from eu.posthog.com |
| `RESEND_API_KEY` | Transactional email — get from resend.com |

## Deployment (Railway)

The repo connects directly to Railway via GitHub. On push to `main`:

1. Railway detects `nixpacks.toml` → builds PHP 8.4 + Node 22 image
2. Runs `composer install --no-dev` + `npm run build`
3. Runs `php artisan migrate --force`
4. Starts `php artisan serve`

**Services to configure in Railway:**

| Service | Config |
|---|---|
| `web` | main process (Procfile `web`) |
| `worker` | `php artisan horizon` |
| `scheduler` | `php artisan schedule:work` |

Add **PostgreSQL** and **Redis (Upstash)** plugins in the Railway project dashboard.

## CI

GitHub Actions runs on every push:
- PHP syntax lint (`php -l`)
- `php artisan test --parallel`

Green badge required before merge to `main`.

## Folder layout

```
app/
├── app/
│   ├── Console/Commands/PingHealth.php   ← scheduler proof-of-life
│   ├── Http/Controllers/
│   ├── Jobs/
│   └── Models/
├── config/
│   ├── horizon.php
│   ├── sentry.php
│   └── services.php   ← PostHog key lives here
├── resources/views/
│   ├── layouts/app.blade.php   ← PostHog snippet + Livewire
│   └── welcome.blade.php       ← hello-world
├── routes/
│   ├── console.php   ← scheduler (ping every 5 min)
│   └── web.php
├── .github/workflows/ci.yml
├── nixpacks.toml
├── Procfile
└── railway.toml
```
