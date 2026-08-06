@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
## PHPUnit
- Tests are PHPUnit classes (`{{ $assist->artisanCommand('make:test --phpunit {name}') }}`); convert any Pest test you find.
- Run the narrowest scope: `{{ $assist->artisanCommand('test --compact --filter=testName') }}`. Full suite: `{{ $assist->artisanCommand('test --compact') }}` — ask the user first.
- **Never delete tests or test files without approval.**
