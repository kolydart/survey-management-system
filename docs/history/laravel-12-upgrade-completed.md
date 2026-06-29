# Laravel 12 Upgrade — Completion Record

**Completed:** 2026-06-29
**From:** Laravel 11.54.0 / PHP 8.2
**To:** Laravel 12.62.0 / PHP 8.2
**Result:** 346/346 PHPUnit tests passing, 5/5 Dusk smoke tests passing, 0 security advisories

## Package Changes

`composer.json` constraint changes (installed version in parentheses):

| Package | Before | After |
|---|---|---|
| `laravel/framework` | ^11.0 (v11.54.0) | ^12.0 (v12.62.0) |
| `phpunit/phpunit` | ^10.5 (10.5.63) | ^11.5.3 (11.5.55) |
| `nunomaduro/collision` | ^8.1 (v8.5.0) | ^8.6 (v8.9.4) |
| `brianium/paratest` | ^7.0 (v7.4.9) | ^7.4 (v7.8.5) |
| `yajra/laravel-datatables-oracle` | ^11.0 (v11.1.6) | ^12.0 (v12.7.2) |
| `askedio/laravel-soft-cascade` | ^11.0 (11.0.4) | ^12.0 (12.0.0) |
| `nesbot/carbon` (transitive) | 3.11.4 | 3.13.0 |

All other dependencies (Sanctum, Tinker, UI, Dusk, Sentry, Activitylog, Intervention,
query-detector, etc.) resolved to L12-compatible versions within their existing caret
ranges and needed no constraint changes. `kolydart/laravel` already declared
`^10.0|^11.0|^12.0` illuminate constraints; `kolydart/common` has no Laravel constraint.

## Structural Decisions

- **Kept the Laravel 10/11 application structure** (Kernel, RouteServiceProvider, etc.).
  Laravel 12 introduces **no structural changes** over Laravel 11, so nothing to migrate.

## Code Changes

Laravel 12 is a minimal release (official estimate: 5 minutes). Two behavioural changes
affected this application, both caught by the test suite:

- **`tests/Feature/app/Http/Controllers/Auth/LoginTest.php`** — the "remember me"
  recaller cookie assertion. In Laravel 12, `SessionGuard::queueRecallerCookie()` now
  stores `hash_hmac('sha256', $passwordHash, $hashKey)` (via the public
  `hashPasswordForCookie()` method) as the recaller's third segment, instead of the raw
  bcrypt password hash used in Laravel 11. The test now calls
  `Auth::guard()->hashPasswordForCookie($user->password)` to match — making it robust
  to future algorithm changes rather than hard-coding the format.

- **`resources/views/admin/surveys/index.blade.php`** and
  **`resources/views/admin/surveys/show.blade.php`** — guarded
  `route('frontend.create', $survey->alias)` with `@if ($survey->alias)`. The
  `frontend.create` route has a required `{alias}` parameter; Laravel 12 now throws
  `UrlGenerationException` when an **empty string** is passed for a required parameter,
  whereas Laravel 11 silently produced a (broken) URL. Surveys with an empty alias
  therefore caused a 500 on the admin surveys index/show pages. The link is now only
  rendered when an alias exists (it was meaningless without one). This regression was
  surfaced by the `admin_index_routes_smoke_pass` and `admin_show_routes_smoke_pass`
  Dusk smoke tests.

## Audit — No Action Needed

All documented Laravel 12 breaking changes were checked against the codebase:

- **Models / UUIDv7** (`HasUuids` now emits UUIDv7): no model uses `HasUuids`.
- **Image validation excludes SVG**: file uploads use `mimes:png,jpg,jpeg,gif`, not the
  `image` rule, and never allowed SVG.
- **Carbon 3**: already running Carbon 3.x before the upgrade; no `diffIn*` assumptions.
- **Local filesystem disk root** (now `storage/app/private`): the `local` disk
  explicitly defines `'root' => storage_path('app')` in `config/filesystems.php`, so the
  new default does not apply.
- **Concurrency result index mapping**, **Container default class-dependency resolution**,
  **Multi-schema `Schema::getTables()`**, **nested `mergeIfMissing()` dot notation**,
  **route precedence**, and the **DatabaseTokenRepository / Blueprint / Grammar
  constructor signature** changes: none of these APIs are used in application code.

## Known Follow-up (not part of this upgrade)

- PHPUnit emits deprecation **warnings** (not failures) for tests using the
  `/** @test */` doc-comment annotation; doc-comment metadata is removed in PHPUnit 12.
  Several test files still use it. These should be migrated to the `#[Test]` attribute
  before a future PHPUnit 12 upgrade.

## Commands Used

```bash
cphp /usr/local/bin/composer update laravel/boost --with-dependencies
cphp /usr/local/bin/composer update -W
cphp artisan optimize:clear
cphp artisan view:clear
cphp artisan test --compact
cphp /usr/local/bin/composer audit
cphp artisan dusk tests/Browser/SmokeTest.php
```

The previous major-version upgrade is recorded at
`docs/history/laravel-11-upgrade-completed.md`.
