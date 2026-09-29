# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

ASMC Manpower Platform — a bilingual (Arabic-default RTL / English LTR) Saudi workforce-management SaaS for Adel Saad Mater Contracting. Covers workers, clients, supply contracts, deployments, recruitment, timesheets, payroll, invoicing (ZATCA), and document/permit compliance. A UI/UX redesign ("Desert Signal") is in progress — see `REDESIGN_PROGRESS.md` for its live status, locked decisions, and next steps.

Stack: Laravel 12 · Livewire 3 + Volt (single-file components) · Alpine (bundled by Livewire) · Tailwind 3 · Vite. PHP 8.2+. Local dev DB is MySQL via XAMPP on Windows; tests use in-memory SQLite.

## Commands

```bash
composer dev          # Runs server + queue + pail logs + vite concurrently (primary dev command)
composer test         # Clears config then runs the full PHPUnit suite
php artisan test                                  # Run all tests
php artisan test --filter=ComplianceScannerTest   # Run one test class
php artisan test tests/Unit/PayrollCalculatorTest.php   # Run one file
npm run build         # Build assets — REQUIRED after any CSS/JS/Tailwind change
npm run dev           # Vite dev server (HMR) if not using `composer dev`
./vendor/bin/pint     # Format PHP (Laravel Pint)
php artisan db:seed --class=DemoSeeder    # Load demo data (roles, users, workers, etc.)
php artisan optimize:clear                # Clear caches if views/config look stale
```

Rebuild rule: after Blade/PHP-only edits just hard-refresh; after `resources/css`, `resources/js`, or `tailwind.config.js` edits you must `npm run build` and hard-refresh (Ctrl+Shift+R). Default seeded login: `admin@asmc.com.sa` / `password`.

## Architecture

**Role-driven routing.** All authenticated routes live in `routes/web.php`, gated by Spatie `role:` middleware (aliases registered in `bootstrap/app.php`). The five roles are `admin`, `manager`, `ops`, `client`, `worker`. `/dashboard` is a generic entry point: `DashboardController::redirect()` sends each user to `User::homeRoute()` based on their role (executive dashboard for admin/manager, portals for client/worker). When adding a feature, decide which role group it belongs to and place its route inside the matching middleware group.

**Two kinds of pages.** Data modules are **Volt route components** — `Volt::route('workers', 'workers.index')` maps directly to `resources/views/livewire/workers/index.blade.php`, a single file containing both the PHP component class (`new #[Layout('layouts.app')] class extends Component`) and its Blade template. There is no separate PHP class in `app/`. Read-only dashboards and portals instead use plain controllers (`DashboardController`, `Portal\*Controller`) returning Blade views. Follow the existing kind when extending a section.

**Domain services** in `app/Services/` hold business logic, kept out of components/controllers so they're unit-testable:
- `ComplianceScanner` — walks expiry-bearing `Document`/`AjeerPermit` records against `AlertRule` thresholds, maintains each document's denormalised `status` (valid→expiring→expired), and raises/resolves `AlertEvent`s. `EXPIRING_WINDOW_DAYS = 90`.
- `PayrollCalculator` / `TimesheetCalculator` — derive payroll and timesheet totals.
- `InvoiceBuilder` — assembles `Invoice` + `InvoiceLine`s from approved timesheets against a `RateCard`.
- `ZatcaQr` — builds ZATCA-compliant TLV/base64 QR payloads for invoices.

**Models** in `app/Models/` are the domain: `Worker`, `Client`, `SupplyContract`, `Deployment`, `RecruitmentCandidate`/`OnboardingCase`, `Timesheet`/`Shift`, `PayrollRun`/`PayrollLine`, `Invoice`/`InvoiceLine`/`RateCard`, `Document`/`AjeerPermit`/`AlertRule`/`AlertEvent`. `User` links to `client_id`/`worker_id` for portal accounts. Most domain models carry bilingual `name_en`/`name_ar` columns and expose a `name()` helper that returns the right one for the active locale.

**Activity logging (Spatie v5).** Auditable models use `Spatie\Activitylog\Models\Concerns\LogsActivity` with `getActivitylogOptions()` returning `LogOptions::defaults()->logFillable()->logOnlyDirty()->dontLogEmptyChanges()`. Note the v5 namespaces (`Models\Concerns\LogsActivity`, `Support\LogOptions`) and `dontLogEmptyChanges()` — the older Traits/`dontSubmitEmptyLogs()` forms are wrong here.

## i18n / RTL (pervasive — every UI change touches this)

- `SetLocale` middleware resolves the active locale (session → user preference → default `ar`) on every web request. `LocaleController::switch` persists it. Supported locales and their text direction are defined in `config/app.php` `supported_locales` (`ar`=rtl default, `en`=ltr).
- Layouts set `<html dir>` from that config. Use logical CSS/Tailwind properties (`ms-`/`me-`, `ps-`/`pe-`, `start`/`end`) — never left/right — so RTL mirrors correctly.
- **Every user-facing string must go through `__()`**, with the Arabic value added to `lang/ar.json`. English is the JSON key. Adding UI text without an `ar.json` entry breaks Arabic parity. Validate `ar.json` after editing: `php -r "json_decode(file_get_contents('lang/ar.json')); echo json_last_error_msg();"`.
- **Dates & money:** use the Blade directives `@greg`, `@hijri`, `@dateboth`, `@money` (registered in `AppServiceProvider`, backed by `App\Support\DateFormatter`). Hijri uses the intl Umm al-Qura calendar — the official Saudi calendar — with no external service. Never hand-format dates or SAR amounts.

## Design system (Desert Signal)

Dark-default glass-morphism theme with semantic tokens. **Never hardcode Tailwind colors** (`bg-white`, `text-gray-*`, `indigo-*`) — use semantic tokens: `canvas`, `surface`, `surface-raised`, `surface-sunken`, `hairline`, `ink`/`ink-muted`/`ink-faint`, `accent`, `teal`, `gold`, `success`/`warning`/`danger`/`info`. Tokens are CSS vars in `resources/css/app.css` (`:root` dark + `html.light` overrides) wired into `tailwind.config.js`. `scripts/tokenize-views.php` bulk-maps legacy color literals to tokens (has a skip-list for hand-tokenized files).

- Theme persists in `localStorage['ds-theme']`; a pre-paint inline script in each layout sets `html.light` before first paint to avoid flash.
- **Alpine is provided by Livewire — never import or start a second Alpine.** Register stores/directives in `resources/js/app.js` on the `alpine:init` event. Custom directives (`x-reveal`, `x-parallax`, `x-countup`, `x-magnetic`) live in `resources/js/motion.js`; the canvas hero scene is `resources/js/network.js`. All motion must be reduced-motion-safe and must not gate content visibility behind JS.
- Reusable Blade components are in `resources/views/components/` (`x-icon` is a dependency-free curated SVG set; also `x-stat-card`, `x-status-pill`, `x-modal`, `x-auth-header`, button/input primitives). `<x-app-layout>`→`layouts.app`, `<x-guest-layout>`→`layouts.guest` (classes in `app/View/Components/`).

## Volt component conventions

- Guard access at the top of `mount()` with `$this->authorize('<permission>')` (Spatie permissions, seeded in `RolePermissionSeeder`).
- Use `#[Url]` attributes for filter/search state so it's shareable and back-button-safe; `use WithPagination` for lists.
- In loops set `wire:key`; keep `x-data` on stable roots.
- `x-modal` open/close listeners tolerate both the Livewire-3 dispatch payload shape (`{0:'name'}`) and a bare string — preserve that when touching modals.

## Testing

PHPUnit (not Pest, despite the plugin allowance). `tests/Feature` covers auth, role access, and CRUD/service integration; `tests/Unit` covers the calculators and `ComplianceScanner`. Config forces SQLite `:memory:`, `array`/`sync` drivers, and `BCRYPT_ROUNDS=4` — tests never touch the MySQL dev DB. When adding domain logic, prefer putting it in a `Service` and unit-testing it directly, mirroring the existing calculator tests.
