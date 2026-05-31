# Laravel 11 Upgrade — Completion Record

**Completed:** 2026-05-31
**From:** Laravel 10.50.2 / PHP 8.1
**To:** Laravel 11.54.0 / PHP 8.2
**Result:** 378/378 tests passing, 0 security advisories

## Package Changes

| Package | Before | After |
|---|---|---|
| `laravel/framework` | ^10.0 | ^11.0 |
| `laravel/sanctum` | ^3.2 | ^4.0 |
| `laravel/tinker` | ^2.5 | ^2.9 |
| `sentry/sentry-laravel` | ^3.0 | ^4.3 |
| `spatie/laravel-activitylog` | ^4.0.0 | ^4.8 |
| `yajra/laravel-datatables-oracle` | ^10.0 | ^11.0 |
| `askedio/laravel-soft-cascade` | >0.1 | ^11.0 |
| `intervention/image` | ^2.5 | ^3.0 |
| `intervention/image-laravel` | — | ^1.0 (new) |
| `nunomaduro/collision` | ^6.1 | ^8.1 |
| `phpunit/phpunit` | ^9.3 | ^10.5 |
| `brianium/paratest` | ^6.3 | ^7.0 |
| `beyondcode/laravel-query-detector` | ^2.1 | ^2.3 |
| `laravelcollective/html` | ^6.2 | **removed** |
| `doctrine/dbal` | ^3.1 | **removed** |

## Structural Decisions

- **Kept Laravel 10 application structure** (Kernel, RouteServiceProvider, etc.) — no migration to the slim Laravel 11 layout.
- **Plain HTML replaced `laravelcollective/html`** across 65 Blade files (~600 method calls).
- **Sanctum migration** was already applied in the DB; the published file was kept and matches the existing migrations-table entry.

## Code Changes

- `config/app.php`: removed `Intervention\Image\ImageServiceProvider`, `Collective\Html\HtmlServiceProvider`, and the `Image`/`Form`/`Html` aliases (auto-discovery handles the new Intervention Image provider).
- `config/sanctum.php`: published fresh with new middleware keys (`authenticate_session`, `encrypt_cookies`, `validate_csrf_token`).
- `app/Http/Controllers/Traits/FileUploadTrait.php`: migrated to Intervention Image v3 API (`Image::read`, `scale()` with named args replacing the v2 resize-with-callback aspect-ratio pattern).
- `tests/Feature/app/Http/Controllers/Admin/SurveysControllerTest.php`: Content-Type charset assertions changed from `UTF-8` to lowercase `utf-8` (Symfony 7 behaviour).
- 65 Blade files in `resources/views/`: every `Form::open`/`Form::text`/`Form::select`/`Form::checkbox`/`Form::radio`/`Form::label`/`Form::submit`/`Form::close`/etc. call converted to plain HTML. `Form::model` was treated as `Form::open` with `old('field', $model->field ?? '')` patterns where pre-fill was needed. Several `{{ Form::checkbox(...) }}` (double-curly) usages in show views were fixed in passing — they had been silently HTML-escaped and rendered as text rather than checkboxes.

## Audit — No Action Needed

- No migrations used `->change()` requiring full column re-declaration.
- No `unsignedDouble` / `unsignedFloat` / `unsignedDecimal` usage.
- No direct `Doctrine\DBAL` API calls in app code (Laravel 11 native schema methods cover everything).
- No rate-limiting code using deprecated `decayMinutes` / `GlobalLimit` / `ThrottlesExceptions`.
- No `protected $dates = [...]` in models.

## Commands Used

```bash
cphp composer update -W
cphp artisan vendor:publish --tag=sanctum-migrations
cphp artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
cphp artisan optimize:clear
cphp artisan test
cphp composer audit
```

The original upgrade plan is archived at `docs/history/laravel-11-upgrade-plan.md`.
