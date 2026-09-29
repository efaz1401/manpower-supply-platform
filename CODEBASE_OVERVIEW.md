# ASMC Manpower Platform — Codebase Overview

> **What this is:** A bilingual (Arabic-first RTL / English LTR) **Saudi workforce-management SaaS** built for **Adel Saad Mater Contracting (ASMC)**. It manages the full lifecycle of supplying manpower to client companies in Saudi Arabia: workers, clients, supply contracts, deployments, recruitment, timesheets, WPS payroll, ZATCA-compliant invoicing, and document/permit compliance — plus a public SEO marketing site and separate portals for clients and workers.
>
> Analyzed: 2026-09-20 · Location: `C:\Users\MSI\manpower-platform`

---

## 1. Executive Summary

The platform serves **5 roles** — `admin`, `manager`, `ops`, `client`, `worker` — each landing on a role-appropriate home after login. The back office is a set of 9 Livewire Volt modules (clients, workers, contracts, deployments, recruitment, timesheets, payroll, invoices, compliance) plus admin pages. The public-facing marketing site is locale-prefixed (`/ar/...`, `/en/...`) with hreflang, sitemap, and `llms.txt` for SEO. Business rules live in dedicated, unit-tested **domain services** (`app/Services/`), keeping Livewire components thin.

The codebase is **mature beyond skeleton stage**: all 9 CRUD modules are built as Volt components, a compliance scanner runs daily via the scheduler, and a full demo dataset is seeded. An ongoing UI/UX redesign codenamed **"Desert Signal"** (dark glass-morphism, semantic Tailwind tokens) is tracked in `REDESIGN_PROGRESS.md`.

---

## 2. Technology Stack

| Layer | Technology |
|---|---|
| Backend | **Laravel 12** (PHP ^8.2) |
| Frontend | **Livewire 3 + Volt** (single-file components), Blade, Alpine.js (bundled by Livewire) |
| Styling | **Tailwind CSS 3** + `@tailwindcss/forms`, custom "Desert Signal" design-token system |
| Build | Vite 7, PostCSS, Autoprefixer |
| Auth | Laravel Breeze (Livewire stack) |
| RBAC | `spatie/laravel-permission` ^8.3 |
| Audit trail | `spatie/laravel-activitylog` ^5.0 (v5 namespaces: `Models\Concerns\LogsActivity`) |
| Localization | `mcamara/laravel-localization` + custom `SetLocale` middleware + `lang/ar.json` |
| Mail | `resend/resend-php` (transactional email, e.g. quote requests) |
| Database | **MySQL** (local dev via XAMPP on Windows); tests use in-memory **SQLite** |
| Queue/Cache/Session | `database` driver in dev; `array`/`sync` in tests |
| Testing | **PHPUnit 11.5** (not Pest), Mockery, Faker |
| Tooling | Laravel Pint (formatting), Pail (logs), Tinker, Sail |

---

## 3. Repository Layout

```
app/
  Console/Commands/    ScanComplianceCommand (compliance:scan)
  Http/
    Controllers/       DashboardController, PublicSiteController, SitemapController,
                         LocaleController + Portal/{Client,Worker}PortalController
    Middleware/        SetLocale (global web middleware)
  Livewire/            Actions/Logout, Forms (Breeze scaffolding)
  Mail/                QuoteRequestMail
  Models/              18 Eloquent models (see section 5)
  Providers/           AppServiceProvider (registers @greg/@hijri/@dateboth/@money directives)
  Services/            ComplianceScanner, PayrollCalculator, TimesheetCalculator,
                       InvoiceBuilder, ZatcaQr   <- all business logic lives here
  Support/             DateFormatter (Hijri/Gregorian/SAR), Seo (meta/JSON-LD helpers)
  View/Components/     AppLayout, GuestLayout
bootstrap/app.php      Middleware registration + Spatie role aliases
config/app.php         supported_locales: ar(rtl, default) / en(ltr)
database/
  migrations/          13 migrations (~20 tables)
  seeders/             DatabaseSeeder -> RolePermissionSeeder + DemoSeeder
  factories/           17 model factories
docs/                  Marketing/creative production docs (briefs, prompts) - not app code
lang/ar.json           Arabic translations (English string = JSON key)
resources/
  css/app.css          Design tokens (:root dark + html.light), glass/aurora/skeleton utils
  js/                  app.js (Alpine stores via alpine:init), theme.js,
                       motion.js (x-reveal/x-parallax/x-countup/x-magnetic), network.js
  views/
    layouts/           app, guest, sidebar, topbar
    components/        x-icon, x-stat-card, x-status-pill, x-modal, buttons/inputs...
    livewire/          9 module Volt components + admin + auth + profile + quote form
    public/            Marketing pages (home, services, city, ajeer, about, faq, contact)
    dashboards/ portal/ mail/
routes/
  web.php              Public locale-prefixed site + authenticated role-gated routes
  auth.php             Breeze auth routes
  console.php          Schedules compliance:scan daily at 02:00
scripts/tokenize-views.php   Bulk-maps legacy color literals -> design tokens
tests/                 Feature (auth, roles, CRUD, SEO, quote) + Unit (services)
```

---

## 4. Routing & Access Control

### Public marketing site (`routes/web.php`)
- `/` 302-redirects to the session/default locale (Google-recommended for locale landing).
- All content URLs are **locale-prefixed**: `/{locale}/`, `services`, `services/{service}`, `manpower-supply/{city}` (programmatic city pages), `ajeer`, `about`, `faq`, `contact`, `thank-you` — via `PublicSiteController`.
- `sitemap.xml` and `llms.txt` via `SitemapController`.
- Quote requests: Volt component `livewire/public/quote-request.blade.php` -> `QuoteRequestMail` (Resend).

### Authenticated app (unprefixed, `auth` middleware)
- `/dashboard` is a **generic entry point** — `DashboardController::redirect()` sends each user to `User::homeRoute()` per role.
- Route groups gated by **Spatie `role:` middleware** (aliases in `bootstrap/app.php`):

| Group | Roles | Contents |
|---|---|---|
| Management | `admin`, `manager` | `executive` dashboard |
| Back office | `admin`, `manager`, `ops` | `operations` dashboard + 9 Volt modules (clients, workers, contracts, deployments, recruitment, timesheets, payroll, invoices, compliance) |
| Admin | `admin` | `admin/users`, `admin/activity` (Volt) |
| Client portal | `client` | `/portal` -> dashboard, workers, timesheets, invoices (scoped to `user->client_id`) |
| Worker portal | `worker` | `/me` -> dashboard, documents, payslips (scoped to `user->worker_id`) |

### Two kinds of pages (important convention)
1. **Data modules = Volt route components** — `Volt::route('workers', 'workers.index')` maps straight to `resources/views/livewire/workers/index.blade.php`, a single file containing the PHP class (`new #[Layout('layouts.app')] class extends Component`) *and* the Blade template. No separate class in `app/`.
2. **Read-only dashboards/portals = plain controllers** returning Blade views.

Volt conventions: `authorize('<permission>')` at top of `mount()`; `#[Url]` attributes for filters/search; `WithPagination` for lists; `wire:key` in loops; modal open/close listeners tolerate both Livewire-3 dispatch payloads (`{0:'name'}`) and bare strings.

---

## 5. Domain Model (~20 tables, 18 models)

Framework tables: `users` (soft-deleted, has `client_id`/`worker_id` links, `locale`), `cache`, `jobs`, `activity_log`, Spatie permission tables. Nearly every domain model carries `*_en` / `*_ar` bilingual fields.

| Domain | Models / Tables | Notes |
|---|---|---|
| Clients & contracts | `Client`, `SupplyContract`, `RateCard` | CR / establishment / VAT numbers, payment terms, status |
| Workforce | `Worker` | Iqama/passport numbers, nationality, profession, skills (JSON), sponsorship status, availability, Qiwa contract salary, basic salary, IBAN/payment method. Soft-deleted + activity-logged |
| Deployment | `Deployment`, `AjeerPermit` | Worker-to-client placement with billing rate; Ajeer permits carry expiry dates and statuses |
| Attendance | `Timesheet`, `Shift` | Clock in/out, geolocation + selfie (PII — handle carefully), status workflow (submitted -> verified), derived hours/overtime |
| Payroll | `PayrollRun`, `PayrollLine` | Period runs; lines capture basic/allowances/overtime/deductions/net, Qiwa salary-mismatch flag, IBAN snapshot |
| Billing | `Invoice`, `InvoiceLine`, `RateCard` | 15% KSA VAT, sequential `INV-YYYYMM-###` numbers, ZATCA QR payload, seller/buyer VAT numbers |
| Recruitment | `RecruitmentCandidate`, `OnboardingCase` | Pipeline: sourced -> offered -> visa -> medical -> travel -> arrived -> onboarded |
| Compliance | `Document` (polymorphic `documentable`), `AlertRule`, `AlertEvent` | Documents carry a denormalised `status` (valid -> expiring -> expired); alerts are polymorphic (`alertable`) |

Key model details:
- `User`: `HasRoles`, `SoftDeletes`, `belongsTo` `Client`/`Worker`; `homeRoute()` maps role -> landing route; `name()` returns the locale-appropriate name.
- `Worker`: `LogsActivity` (v5 API: `LogOptions::defaults()->logFillable()->logOnlyDirty()->dontLogEmptyChanges()`), morphMany `documents`, `currentDeployment()` hasOne active, plus `ajeerPermits`, `timesheets`, `payrollLines`.

---

## 6. Domain Services (`app/Services/`) — the business core

All non-trivial business logic is extracted here so it is unit-testable outside HTTP/Livewire.

- **`ComplianceScanner`** — walks every expiry-bearing `Document` and active `AjeerPermit` against `AlertRule` thresholds (default `[90,60,30,14,7,0]`). Maintains each document's denormalised status (`valid` -> `expiring` at 90 days or less -> `expired`), raises deduplicated `AlertEvent`s per record+threshold with severity (`critical` 7 days or less, `warning` 30 days or less, else `info`), and **auto-resolves** alerts when a record is renewed (due date pushed beyond the 90-day window). Exposed as `php artisan compliance:scan`, scheduled daily at 02:00.
- **`TimesheetCalculator`** — derives `hours` / `overtime_hours` from clock in/out (standard day = 8h; anything beyond is overtime). `recalculate()` persists to the timesheet.
- **`PayrollCalculator`** — generates `PayrollLine`s for all actively-deployed workers in a run: overtime from verified timesheets valued at 1.5x hourly rate (`basic / 208` standard monthly hours), net = basic + allowances + overtime - deductions, and flags `salary_mismatch` when basic differs from the Qiwa-registered contract salary (WPS compliance signal).
- **`InvoiceBuilder`** — builds an issued `Invoice` for a client from active deployments (one line per deployment at its monthly billing rate), computes subtotal + **15% KSA VAT** + total inside a DB transaction, generates the ZATCA QR payload, and assigns sequential monthly invoice numbers.
- **`ZatcaQr`** — ZATCA **Phase-1** simplified-tax-invoice QR: base64-encoded TLV (Tag-Length-Value) of 5 tags (seller name, VAT number, ISO-8601 timestamp, total, VAT). Phase-2 cryptographic stamping is intentionally out of scope.

---

## 7. KSA-Specific Domain & Localization

The app is deeply Saudi-market-specific:

- **Arabic is the default locale** (`APP_LOCALE=ar`, fallback `en`); `config/app.php` `supported_locales` defines `ar`=rtl / `en`=ltr, and layouts set `<html dir>` from it.
- **`SetLocale` middleware** (global web): locale resolution order = `{locale}` route segment (public site — must win so `/ar` and `/en` are stable, crawlable URLs) -> session -> user preference -> default. Sets `URL::defaults(['locale' => ...])` so `route()` calls stay prefixed.
- **Every user-facing string goes through `__()`**, with the Arabic value in `lang/ar.json` (English is the JSON key). Validate after edits: `php -r "json_decode(file_get_contents('lang/ar.json')); echo json_last_error_msg();"`.
- **Dates & money**: Blade directives `@greg`, `@hijri`, `@dateboth`, `@money` (registered in `AppServiceProvider`, backed by `App\Support\DateFormatter`). Hijri uses the **intl Umm al-Qura calendar** (the official Saudi calendar) — no external service. Currency formats as SAR (ر.س). Never hand-format dates/amounts.
- **Regulatory entities**: Iqama numbers, Qiwa contract salaries, Ajeer permits, WPS (Wage Protection System) 10-day payroll deadline, ZATCA e-invoicing (QR + VAT fields), Commercial Registration (CR) and MOL/Qiwa establishment IDs.

---

## 8. Design System — "Desert Signal"

A dark-default **glass-morphism** theme with semantic tokens, mid-redesign (see `REDESIGN_PROGRESS.md` for live status).

- **Never hardcode Tailwind colors** (`bg-white`, `text-gray-*`, `indigo-*`). Use semantic tokens: `canvas`, `surface`, `surface-raised`, `surface-sunken`, `hairline`, `ink` / `ink-muted` / `ink-faint`, `accent`, `teal`, `gold`, `success` / `warning` / `danger` / `info`. Tokens are CSS variables in `resources/css/app.css` (`:root` dark + `html.light` overrides) wired into `tailwind.config.js` (`darkMode: 'class'`).
- Theme persists in `localStorage['ds-theme']`; a pre-paint inline script in each layout sets `html.light` before first paint to avoid flash.
- `scripts/tokenize-views.php` bulk-maps legacy color literals to tokens (with a skip-list for hand-tokenized files).
- **Alpine is provided by Livewire — never import/start a second Alpine.** Register stores/directives on the `alpine:init` event in `resources/js/app.js`. Custom directives (`x-reveal`, `x-parallax`, `x-countup`, `x-magnetic`) live in `motion.js`; the canvas hero scene is `network.js`. All motion must be reduced-motion-safe and must not gate content visibility behind JS.
- Use **logical CSS properties** (`ms-`/`me-`, `ps-`/`pe-`, `start`/`end`) — never `left`/`right` — so RTL mirrors correctly.
- Reusable components in `resources/views/components/`: `x-icon` (dependency-free curated SVG set), `x-stat-card`, `x-status-pill`, `x-modal`, `x-auth-header`, button/input primitives. `<x-app-layout>` -> `layouts.app`, `<x-guest-layout>` -> `layouts.guest`.
- Native `<select>`/`<textarea>` need explicit `bg-surface-sunken text-ink` (the forms plugin defaults to white).

Regression-sensitive areas (per project notes): modal listener payload tolerance, in-body module "New" buttons (header slot renders outside the Livewire root, which kills `wire:click`), the Workers module **bulk-edit** feature (row checkboxes, select-page, floating toolbar, per-field opt-in modal, confirm step), and activitylog v5 namespaces.

---

## 9. Testing

**PHPUnit 11.5** (not Pest, despite the plugin allowance). `phpunit.xml` forces SQLite `:memory:`, `array` cache/session/mail, `sync` queue, `BCRYPT_ROUNDS=4` — **tests never touch the MySQL dev DB**.

- `tests/Unit` — service-level: `PayrollCalculatorTest`, `TimesheetCalculatorTest`, `ComplianceScannerTest`, `InvoiceBuilderTest`, `ZatcaQrTest`.
- `tests/Feature` — Breeze auth suite, `RoleAccessTest` (role-gated routes), `ClientCrudTest`, `ComplianceScanTest`, `InvoiceBuilderTest`, `PublicSiteSeoTest`, `QuoteRequestTest`, `ProfileTest`.
- 17 model factories support test/seed data.
- Convention: put new domain logic in a `Service` and unit-test it directly, mirroring the calculator tests.

---

## 10. Seed Data & Credentials

`DatabaseSeeder` -> `RolePermissionSeeder` (RBAC) + `DemoSeeder`:

- **Roles -> permissions**: `admin` = all 20 permissions; `manager` = executive/ops dashboards, view workers/clients, manage contracts/deployments/payroll/invoices, approve timesheets, view compliance/activity/reports; `ops` = full operational management (no executive dashboard, no user management); `client` = view invoices + approve timesheets; `worker` = portal only.
- **DemoSeeder** builds a realistic dataset: 3 staff users (`admin@asmc.com.sa` etc.), 3 clients with supply contracts + rate cards, 12 workers across professions/availability states, iqama/passport documents with a spread of expiry statuses, Ajeer permits + active deployments, client + worker portal users, 5 days of timesheets, one payroll run (with intentional Qiwa salary mismatches), VAT invoices, a recruitment pipeline, and default alert rules.
- **Default seeded login: `admin@asmc.com.sa` / `password`** (all demo users share `password`).

---

## 11. Commands & Workflows

```bash
composer setup        # install, .env, key:generate, migrate, npm install + build
composer dev          # concurrently: artisan serve + queue:listen + pail + vite (primary dev cmd)
composer test         # config:clear + full PHPUnit suite
php artisan test --filter=ComplianceScannerTest
php artisan db:seed --class=DemoSeeder
php artisan compliance:scan      # manual compliance sweep (scheduled daily 02:00)
npm run build         # REQUIRED after any CSS/JS/Tailwind change; then hard-refresh
./vendor/bin/pint     # PHP formatting
php artisan optimize:clear
```

Rebuild rule: Blade/PHP-only edits -> just hard-refresh; `resources/css`, `resources/js`, or `tailwind.config.js` edits -> `npm run build` + Ctrl+Shift+R. Local dev DB is MySQL via XAMPP (`manpower` on 127.0.0.1:3306). Node 20.16 triggers a Vite version warning but builds fine.

---

## 12. Observations, Gaps & Risks

**Current state:** All 9 back-office modules are implemented as Volt components (the older `CODEBASE_ANALYSIS.txt` from 2026-07-24 predates this — it described them as placeholders). The compliance engine that analysis flagged as missing now exists (`ComplianceScanner` + scheduled `compliance:scan`). The Desert Signal redesign is the main in-flight work (bespoke module UIs, ⌘K command palette, portal polish — Phases 5-7 in `REDESIGN_PROGRESS.md`).

**Not yet implemented / watch list:**
- ZATCA **Phase 2** (cryptographic invoice stamping) — only Phase-1 QR payloads exist; invoice PDF rendering is also not built.
- No notifications/broadcasting wiring for `AlertEvent`s yet (alerts are DB records surfaced in the compliance module).
- Timesheets store worker **geolocation + selfies** — PII/privacy and file-storage security must be addressed before production.
- `.env` is present in the working tree with `APP_DEBUG=true`, `APP_ENV=local`, DB root credentials, log mailer — expected for dev, must not ship.
- Seller identity constants in `InvoiceBuilder` (`SELLER_VAT_NUMBER`, `SELLER_NAME`) are hardcoded placeholders pending real company registration data.
- `docs/` holds marketing/creative production material (briefs, image/video prompts) — unrelated to app runtime.
- Stray root artifacts: `asmc_build.zip`, `_manpower_extracted/`, `bug_bounty_writeups_corpus.md`, `.env.backup-preseo` — not part of the app; cleanup candidates.

**Strengths:** clean normalized KSA-accurate schema with denormalised status fields; strong separation of concerns (RBAC via Spatie, audit via activitylog, business logic in unit-tested services); centralized bilingual + Hijri-aware formatting; SEO-conscious public site (locale URLs, hreflang, sitemap, llms.txt); realistic demo seed data making the app immediately demoable.

---

*Generated from direct codebase inspection. Key sources: `CLAUDE.md`, `REDESIGN_PROGRESS.md`, `routes/web.php`, `app/Services/*`, `app/Models/*`, `database/seeders/*`, `bootstrap/app.php`, `composer.json`, `phpunit.xml`.*
