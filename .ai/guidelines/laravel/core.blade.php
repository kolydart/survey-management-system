@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
## Laravel
- Generate files with `{{ $assist->artisanCommand('make:') }}` commands, always with `--no-interaction`.
- `env()` only inside `config/` files; everywhere else `config()`.
- Validation goes in Form Request classes, never inline in controllers.
- New models get factories and seeders; tests use those factories and their custom states.
