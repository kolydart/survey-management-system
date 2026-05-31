# Laravel 11 Upgrade Plan (from Laravel 10.50.2)

**Created:** 2026-05-30  
**PHP target:** 8.2 (max, available at Homebrew)  
**Baseline:** 378 tests passing, PHP 8.1.34 in use

---

## Pre-Flight Checklist

- [x] Run `cphp artisan test` and confirm green baseline (378 passed)
- [x] Identify all package compatibility issues
- [x] Audit `laravelcollective/html` usage (65 blade files, ~600 occurrences)

---

## Phase 1: Dependency Updates

### 1.1 Update `composer.json`

Make the following changes to `composer.json`:

**`require` section:**

```json
"php": "^8.2",
"laravel/framework": "^11.0",
"laravel/sanctum": "^4.0",
"laravel/tinker": "^2.9",
"sentry/sentry-laravel": "^4.3",
"spatie/laravel-activitylog": "^4.8",
"yajra/laravel-datatables-oracle": "^11.0",
"askedio/laravel-soft-cascade": "^11.0",
"intervention/image": "^3.0",
"intervention/image-laravel": "^1.0"
```

**Remove from `require`:**
```
"laravelcollective/html"   ← replaced with plain HTML
"doctrine/dbal"            ← no longer needed in Laravel 11
```

**`require-dev` section:**
```json
"nunomaduro/collision": "^8.1",
"phpunit/phpunit": "^10.5",
"brianium/paratest": "^7.0",
"beyondcode/laravel-query-detector": "^2.3"
```

### 1.2 Run Composer Update

```bash
cphp composer update -W
```

> `cphp` will now resolve to PHP 8.2 (Homebrew) because `composer.json` requires `^8.2`.

---

## Phase 2: Laravel 11 Structural Changes

### 2.1 Do NOT Restructure the App

Laravel 11 still supports the Laravel 10 application structure (Kernel, RouteServiceProvider, etc.).
**Do not migrate** to the new slim structure — it would be unnecessary churn.

### 2.2 Publish Sanctum Migrations

Sanctum 4 no longer auto-loads its own migrations:

```bash
cphp artisan vendor:publish --tag=sanctum-migrations
```

Then review the published migration files in `database/migrations/` to ensure they don't conflict with existing migrations (the `personal_access_tokens` table likely already exists — delete duplicate migrations).

### 2.3 Update Sanctum Config

In `config/sanctum.php`, update the `middleware` array:

```php
'middleware' => [
    'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
    'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
    'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
],
```

---

## Phase 3: Replace `laravelcollective/html` with Plain HTML

**Scope:** 65 blade files, ~600 method calls.

**Command to find all files:**
```bash
grep -rl "Form::\|Html::" resources/views --include="*.blade.php"
```

### 3.1 Conversion Reference Table

| Old (laravelcollective) | New (plain HTML) |
|---|---|
| `{!! Form::open(['route' => 'foo', 'method' => 'POST']) !!}` | `<form action="{{ route('foo') }}" method="POST">@csrf` |
| `{!! Form::open(['route' => 'foo', 'method' => 'PUT']) !!}` | `<form action="{{ route('foo') }}" method="POST">@csrf @method('PUT')` |
| `{!! Form::model($model, ['route' => [...], 'method' => 'PUT']) !!}` | Same as above — values must be bound manually via `old('field', $model->field)` |
| `{!! Form::close() !!}` | `</form>` |
| `{!! Form::label('field', 'Label Text') !!}` | `<label for="field">Label Text</label>` |
| `{!! Form::text('field', $value, ['class'=>'form-control']) !!}` | `<input type="text" name="field" id="field" value="{{ old('field', $value) }}" class="form-control">` |
| `{!! Form::email('field', $value, [...]) !!}` | `<input type="email" name="field" id="field" value="{{ old('field', $value) }}" ...>` |
| `{!! Form::password('field', [...]) !!}` | `<input type="password" name="field" id="field" ...>` |
| `{!! Form::number('field', $value, [...]) !!}` | `<input type="number" name="field" id="field" value="{{ old('field', $value) }}" ...>` |
| `{!! Form::file('field', [...]) !!}` | `<input type="file" name="field" id="field" ...>` |
| `{!! Form::hidden('field', $value) !!}` | `<input type="hidden" name="field" value="{{ $value }}">` |
| `{!! Form::textarea('field', $value, [...]) !!}` | `<textarea name="field" id="field" ...>{{ old('field', $value) }}</textarea>` |
| `{!! Form::select('field', $options, $selected, [...]) !!}` | `<select name="field" id="field" ...>@foreach($options as $k => $v)<option value="{{ $k }}" {{ old('field', $selected) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>` |
| `{!! Form::checkbox('field', $value, $checked, [...]) !!}` | `<input type="checkbox" name="field" value="{{ $value }}" {{ old('field', $default) ? 'checked' : '' }} ...>` |
| `{!! Form::radio('field', $value, $checked, [...]) !!}` | `<input type="radio" name="field" value="{{ $value }}" {{ old('field', $default) == $value ? 'checked' : '' }} ...>` |
| `{!! Form::submit('Label', [...]) !!}` | `<button type="submit" ...>Label</button>` |

### 3.2 Priority Files (most usages first)

Work through these files in order:

1. `resources/views/admin/surveys/edit.blade.php` (29 usages)
2. `resources/views/admin/surveys/create.blade.php` (29 usages)
3. `resources/views/admin/loguseragents/edit.blade.php` (26 usages)
4. `resources/views/admin/answerlists/edit.blade.php` (23 usages)
5. `resources/views/admin/answerlists/create.blade.php` (23 usages)
6. `resources/views/admin/surveys/show.blade.php` (22 usages)
7. `resources/views/admin/answers/show.blade.php` (20 usages)
8. `resources/views/admin/questions/show.blade.php` (19 usages)
9. `resources/views/admin/content_pages/edit.blade.php` (18 usages)
10. `resources/views/admin/content_pages/create.blade.php` (18 usages)
11. (continue for remaining 55 files)

### 3.3 Remove `HtmlServiceProvider` Registration

After converting all views, remove the provider from `config/app.php` (if registered there):

```php
// Remove these lines:
Collective\Html\HtmlServiceProvider::class,
// And from aliases:
'Form' => Collective\Html\FormFacade::class,
'Html' => Collective\Html\HtmlFacade::class,
```

---

## Phase 4: Update `intervention/image` Usage

The upgrade from v2 to v3 has a **rewritten API**. Search for all usages:

```bash
grep -r "InterventionImage\|Image::make\|Image::canvas\|->resize\|->encode\|->fit\|->crop" app/ --include="*.php" -l
```

Key API changes (v2 → v3):
- `Image::make($path)` → `Image::read($path)` (via `use Intervention\Image\Laravel\Facades\Image`)
- `->resize($w, $h)` → `->resize($w, $h)` (similar but check callback-based resize)
- `->encode('jpg', 80)` → `->toJpeg(80)` or `->encodeByMediaType('image/jpeg', 80)`
- `->save($path)` → `->save($path)`
- `Image::canvas($w, $h)` → `Image::create($w, $h)`

The new ServiceProvider is provided by `intervention/image-laravel`. Update `config/image.php` if it exists (re-publish: `php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"`).

---

## Phase 5: Code-Level Breaking Changes

### 5.1 Check Migration Files Using `->change()`

Laravel 11 requires all column attributes to be re-stated when using `->change()`:

```bash
grep -r "->change()" database/migrations/ --include="*.php" -l
```

For each migration found, verify the full column definition is specified before `->change()`.

### 5.2 Check for `unsignedDouble`, `unsignedFloat`, `unsignedDecimal`

These methods were removed. Replace with `->unsigned()` chained:

```bash
grep -r "unsignedDouble\|unsignedFloat" database/migrations/ --include="*.php"
```

### 5.3 Check `doctrine/dbal` Direct Usages

```bash
grep -r "Doctrine\|DBAL\|getDoctrineConnection\|getDoctrineSchemaManager\|registerDoctrineType" app/ --include="*.php"
```

If found, replace with Laravel's native schema methods (`Schema::getTables()`, etc.).

### 5.4 Check Rate Limiting Code

```bash
grep -r "GlobalLimit\|new Limit\|decayMinutes\|ThrottlesExceptions" app/ --include="*.php"
```

`decayMinutes` property renamed to `decaySeconds` (and now stores seconds, not minutes).

---

## Phase 6: Cache Clear & Rebuild

```bash
cphp artisan optimize:clear
rm -f bootstrap/cache/*.php
cphp artisan config:cache
cphp artisan route:cache
```

---

## Phase 7: Run Tests

```bash
cphp artisan test
```

Target: all 378+ tests green. Debug any failures methodically before reporting complete.

---

## Phase 8: Security Audit

```bash
cphp composer audit
```

---

## Summary of Decisions

| Decision | Choice |
|---|---|
| `laravelcollective/html` replacement | **Plain HTML** (manual conversion of 65 files) |
| Laravel 11 application structure | **Keep existing L10 structure** (no restructure) |
| PHP target version | **8.2** (max, available at Homebrew) |
| PHPUnit version | **^10.5** |
| `intervention/image` | **Upgrade to v3** + add `intervention/image-laravel` |
| `doctrine/dbal` | **Remove** (not needed in L11) |

---

## Commands Quick Reference

```bash
# 1. Update dependencies
cphp composer update -W

# 2. Publish Sanctum migrations
cphp artisan vendor:publish --tag=sanctum-migrations

# 3. Publish Intervention Image config (after updating package)
cphp artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"

# 4. Clear caches after all changes
cphp artisan optimize:clear

# 5. Run test suite
cphp artisan test

# 6. Security audit
cphp composer audit
```
