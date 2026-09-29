# ASMC Manpower — UI/UX Redesign Progress & Plan ("Desert Signal")

Last updated: 2026-07-25

## HOW TO RUN (Git Bash — use forward slashes, no `cd /d`)
```
# MySQL: start via XAMPP Control Panel (or):
! C:/xampp/mysql/bin/mysqld.exe --defaults-file=C:/xampp/mysql/bin/my.ini
! cd /c/Users/MSI/manpower-platform && npm run build
! cd /c/Users/MSI/manpower-platform && php artisan serve
# App: http://127.0.0.1:8000   Login: admin@asmc.com.sa / password
# After CSS/JS/Tailwind changes: npm run build + hard-refresh (Ctrl+Shift+R)
# After Blade/PHP only: just hard-refresh
# If stale: php artisan optimize:clear
```

## IMMEDIATE NEXT STEP (do this first when resuming)
Run the SECOND tokenizer pass + rebuild (fixes native <select>/<textarea> dark bg + leftover literals):
```
! cd /c/Users/MSI/manpower-platform && php scripts/tokenize-views.php
! cd /c/Users/MSI/manpower-platform && npm run build
```
Then hard-refresh and check dark+light / AR+EN on: Workers → New Worker (select/textarea dark?),
Executive & Operations dashboards, a portal page. Report anything still "off".

---

## DECISIONS LOCKED (do not re-ask)
- Rollout: token-first, incremental.
- Theme: DARK default + light toggle (persist in localStorage 'ds-theme').
- First delivery: foundation + hero (DONE).
- Motion: FULL cinematic (WebGL-free canvas Network + radar + parallax) with static + reduced-motion fallbacks.
- Icons: dependency-free <x-icon> (curated SVG set). No composer installs.
- modules/placeholder.blade.php: repurpose as empty-state motif (not deleted yet).

## ENV / STACK FACTS
- Laravel 12, Livewire 3.8 (Volt), Alpine bundled by Livewire (register via `alpine:init`, NEVER start a 2nd Alpine).
- Tailwind 3 (tailwind.config.js). Node 20.16 (Vite warns, still builds fine).
- RTL from config/app.php supported_locales (ar=rtl default, en=ltr). Layouts set <html dir>.
- app/Support/DateFormatter.php: gregorian/hijri/both/money(SAR) — reuse everywhere.
- i18n JSON via __(); strings live in lang/ar.json (ADD new UI strings there for AR parity — NOT done yet).
- <x-app-layout> -> layouts.app ; <x-guest-layout> -> layouts.guest (classes in app/View/Components/).

---

## DONE SO FAR

### Session bug fixes (pre-redesign) — keep intact, do not regress
- activitylog v5 namespace fix in 8 models (Traits\LogsActivity -> Models\Concerns\LogsActivity,
  LogOptions -> Support\LogOptions, dontSubmitEmptyLogs() -> dontLogEmptyChanges()).
- modal.blade.php open/close listeners tolerant of Livewire-3 payload {0:'name'} AND bare string.
- Module "New" buttons moved OUT of <x-slot name="header"> into in-body headers (9 modules + admin)
  because header slot rendered outside Livewire root -> wire:click dead.
- Workers module: full bulk-edit (row checkboxes, select-page, floating toolbar, per-field opt-in
  modal 'worker-bulk-form', confirm step, bulkSave). Unique fields excluded. Guarded by authorize('manage workers').

### Phase 0 — tokens & infrastructure (DONE, built OK)
- tailwind.config.js: darkMode:'class'; semantic colors (canvas/surface/surface-raised/surface-sunken/
  hairline/ink/ink-muted/ink-faint/accent/accent-soft/accent-contrast/teal/gold/success/warning/danger/info);
  shadows (glass, glow-accent, glow-gold); keyframes+animation (fade-in-up, fade-in, shimmer, pulse-ring,
  radar-sweep, aurora-drift, drawer-in, scale-in).
- resources/css/app.css: :root dark vars + html.light vars; utilities .glass/.glass-panel/.aurora/
  .mashrabiya/.skeleton/.grain; [x-reveal] + .is-revealed; prefers-reduced-motion kill-switch.
- resources/js/theme.js: Alpine store 'theme' (current, toggle, apply) + localStorage.
- resources/js/motion.js: Alpine directives x-reveal, x-parallax (RTL-aware), x-countup, x-magnetic;
  all reduced-motion-safe. Registered in app.js on 'alpine:init'.
- resources/js/network.js: dependency-free canvas "Network" scene (nodes/edges/gold radar sweep);
  guards on [data-network], reduced-motion, visibility, IntersectionObserver. Called on DOMContentLoaded + livewire:navigated.
- resources/js/app.js: imports bootstrap + registers theme/motion on alpine:init + initNetwork.

### Phase 1 — brand, components, shell, hero (DONE, built OK, confirmed on screen)
- components/application-logo.blade.php: NEW mark (mashrabiya octagon + signal-node network), :animate prop.
- components/icon.blade.php: NEW curated icon set (dashboard, executive, operations, workers, clients,
  contracts, deployments, timesheets, payroll, invoices, recruitment, compliance, users, activity,
  documents, search, bell, sun, moon, logout, plus, chevron, command). Usage: <x-icon name=".." class=".."/>.
- Restyled shared components to tokens+motion (props/API preserved):
  stat-card (glass + x-countup + icon prop + edge-glow), status-pill (dot + pulse-ring on critical, token tones),
  brand-button (x-magnetic), primary/secondary/danger-button, text-input, input-label, input-error, modal (glass).
- layouts/app.blade.php: pre-paint theme <script>, aurora bg, glass header slot, tokens.
- layouts/partials/sidebar.blade.php: glass rail, x-icon per nav item, active indicator bar, role-gated groups preserved.
- layouts/partials/topbar.blade.php: glass, ⌘K launcher (dispatches 'open-command-palette' — PALETTE NOT BUILT YET),
  theme toggle (sun/moon), Hijri+Greg date chip (DateFormatter::both), notifications bell (pulse), locale switch, user dropdown.
- welcome.blade.php: cinematic hero (data-network scene, aurora+mashrabiya+grain, x-reveal, x-countup KPI strip, magnetic CTAs).
- layouts/guest.blade.php: split-screen (Network scene | glass form card), locale switch, pre-paint theme.

### Phase 2 — nav dedup + headers (DONE)
- Deleted orphan resources/views/livewire/layout/navigation.blade.php (unreferenced Breeze scaffold) via rm.
- Header slot now themed in app layout; dashboards/portals keep <x-slot name="header"> (works, themed).

### Phase 3 — dashboards (DONE)
- dashboards/executive.blade.php + dashboards/operations.blade.php: fully tokenized, glass panels,
  stat-cards with icons, status-pill in tables. (This fixed the dark-mode "text off" issue.)

### Tokenizer sweep (PARTIAL)
- scripts/tokenize-views.php: maps hardcoded gray/white -> tokens across views (skips hand-tokenized files).
- FIRST pass RAN: 34 files updated, build OK (css 47.6kB). 
- SECOND pass NOT YET RUN: added mappings for native select/textarea dark bg + residual literals
  (text-blue-600->text-info, bg-red-50->bg-danger/10, bg-emerald-50->bg-accent/10, hover:text-red-800->hover:text-danger,
  border-emerald-200, text-emerald-700, and the two long form-control class strings). nav-link + responsive-nav-link
  hand-fixed (indigo->accent) and ADDED TO SKIP LIST. >>> RUN THIS NEXT (see IMMEDIATE NEXT STEP). <<<

---

## REMAINING PLAN

### Phase 3b — finish theming sweep (after 2nd tokenizer pass)
- Verify no white cards/inputs remain in dark. Spot-fix any file the script missed (grep for
  bg-white|text-gray-|border-gray-|bg-gray-|text-indigo|bg-emerald-600 in resources/views).
- Verify light theme still looks right (toggle). Verify AR RTL mirrors.

### Phase 4 — Auth suite polish (6 screens share layouts/guest split-screen)
- livewire/pages/auth/{login,register,forgot-password,reset-password,confirm-password,verify-email}.blade.php
- Tokenized by script already; add motion (x-reveal), ensure inputs use x-text-input, consistent spacing.

### Phase 5 — Data modules: calm/fast mode + bespoke
Shared: glass sticky table header, skeleton loading, row-expand, bottom floating bulk bar,
side-DRAWER for create/edit (convert x-modal -> drawer variant), 120-180ms transitions only.
Bespoke per module:
- compliance/index  => SECOND HERO: animated radar sweep over document field; "Run Scan" triggers sweep;
  blips amber/rose by expiry; results table below. (Seed data already has valid/expiring/expired spread.)
- deployments/index => stylized KSA map with animated route lines (worker->client) + table toggle.
- recruitment/index => animated kanban (sourced->offered->visa->medical->travel->arrived->onboarded), draggable.
- payroll/index     => WPS 10-day deadline countdown ring; Qiwa salary_mismatch rows pulse amber.
- invoices/index    => ZATCA VAT breakdown + QR block; SAR totals count-up.
- workers/clients/contracts/timesheets => glass tables; PRESERVE workers bulk-edit exactly.

### Phase 6 — admin, account, portals
- admin/activity => animated vertical timeline (actor avatar, verb, subject chip, before/after diff chips, relative time + Hijri/Greg on hover).
- admin/users => themed table + invite drawer.
- profile + livewire/profile/* => console settings cards; delete-user-form = red-glow DANGER ZONE (danger-button + confirm).
- portal/client/* => trust/invoice focus; outstanding balance hero; ZATCA invoice + QR download.
- portal/worker/* (mobile-first) => document WALLET (iqama/passport cards w/ expiry ring green/amber/red,
  Hijri+Greg via DateFormatter), payslips (SAR count-up), big tap tiles. Handle "no worker linked" empty state.

### Phase 7 — command palette + polish + QA
- [DONE] ⌘K COMMAND PALETTE built: `resources/views/components/command-palette.blade.php` +
  `resources/js/palette.js` (registered via `alpine:init`, no 2nd Alpine). Opens from the topbar
  dispatch, ⌘K/Ctrl+K, or "/" (when not typing). Arrow nav, Enter to run, Esc to close, blurred
  backdrop, scroll-locked body, translated group headings, RTL-safe. Items are built server-side
  with the SAME role checks the routes use, so it can never offer a 403 link. Client sees 7 items,
  worker 6, admin 21. Action "Run compliance scan" deep-links to `/compliance?scan=1` (new `#[Url]`
  `$scan` prop on the compliance component auto-runs the scan from any page).
- [DONE] Mobile navigation: off-canvas sidebar drawer (new `sidebar` Alpine store) + topbar
  hamburger + drawer footer (language switcher, theme toggle, profile). Was previously unreachable
  on phones (aside was `hidden md:flex` with no toggle).
- [DONE] Added all new UI strings to `lang/ar.json` (Menu, Command palette, Search or jump to…,
  No results found., Navigate, Open, Toggle theme, Run compliance scan, Add worker, Add client,
  New invoice).
- [TODO] modules/placeholder.blade.php => convert to shared empty-state (idle radar motif).
- [TODO] QA: every screen in dark+light AND ar-RTL+en-LTR; WCAG AA contrast; reduced-motion;
  60fps on tables; smoke test all 5 roles (admin/manager/ops/client/worker); confirm CRUD +
  workers bulk-edit still work.

## FIXED THIS SESSION (do not regress)
- **`layouts/app.blade.php` was missing `@livewireScripts`** — Livewire (and therefore Alpine)
  only auto-injected on pages containing a Livewire component, so on all plain-controller pages
  (executive, operations, both portals) Alpine was DEAD: theme toggle, user dropdown, notification
  bell and /-shortcuts did nothing, with no console error.
- **`x-stat-card` rendered `<span x-countup="N">0</span>`** — the literal fallback was `0`, so with
  Alpine dead the executive/operations dashboards showed 0 for every metric. Now the real value is
  the fallback (matches the public home-page pattern).
- **Mobile topbar overflowed**: brand text was squeezed to 65x120px and overlapped the header.
  Brand text is now desktop-only and the language switcher moved into the drawer.
- **`public/home.blade.php` services grid was broken**: `:name="config(\"…\")"` — escaped quotes
  inside a `:name` attribute broke HTML parsing so `<x-icon>` swallowed its sibling `<h3>/<p>/<span>`
  (cards collapsed to 78px with text overflowing). Replaced with `@php($icon = config(...))`.


## RISKS / GOTCHAS
- Classifier outages block Bash for the assistant -> user runs `!` commands (build/tokenizer/artisan).
- Git Bash: use /c/Users/... forward slashes; `cd /d` and `del` fail; use `rm`.
- Livewire DOM morph vs Alpine: keep wire:key on loops; x-data on stable roots.
- Node 20.16 < Vite's preferred 20.19 -> warning only, builds fine.
- Don't regress: modal listeners, in-body module headers, workers bulk-edit, activitylog v5 namespaces.
- Native selects/textareas need explicit bg-surface-sunken text-ink (forms plugin defaults to white).

## FILE INVENTORY (56 views / 13 dirs) — coverage
Components(17): logo✔ icon✔(new) stat-card✔ status-pill✔ modal✔ brand/primary/secondary/danger-button✔
  text-input✔ input-label✔ input-error✔ nav-link✔ responsive-nav-link✔ dropdown~(tokenized by script)
  dropdown-link~ auth-session-status~ action-message~
Layouts(4): app✔ guest✔ sidebar✔ topbar✔
Entry(2): welcome✔ livewire/welcome/navigation(TODO check)
Auth(6): all tokenized by script; motion polish TODO (Phase 4)
Dashboards(3): dashboard(redirect)~ executive✔ operations✔
Modules(9): tokenized by script; BESPOKE work TODO (Phase 5)
Admin(2): users~ activity~ (timeline TODO Phase 6)
Account(4): profile~ update-profile~ update-password~ delete-user~ (danger zone TODO Phase 6)
Client portal(4): tokenized; polish TODO Phase 6
Worker portal(3): tokenized; document wallet TODO Phase 6
Legacy(1): modules/placeholder -> empty-state TODO Phase 7
(✔=redesigned, ~=tokenized only/needs polish, TODO=bespoke pending)
