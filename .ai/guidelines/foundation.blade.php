@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
# Laravel Boost Guidelines

Installed versions — abide by these exactly:

- php - {{ PHP_VERSION }}
@foreach (app(\Laravel\Roster\Roster::class)->packages()->unique(fn ($package) => $package->rawName()) as $package)
- {{ $package->rawName() }} ({{ $package->name() }}) - v{{ $package->majorVersion() }}
@endforeach
@if (! empty(config('boost.purpose')))

Application purpose: {!! config('boost.purpose') !!}
@endif

- Match sibling files for structure and naming; reuse before writing new.
- Don't add base folders or change dependencies without approval.
- Only create documentation files if explicitly asked.
- If a frontend change isn't visible, ask the user to run `{{ $assist->nodePackageManagerCommand('run build') }}` or `{{ $assist->nodePackageManagerCommand('run dev') }}`.
