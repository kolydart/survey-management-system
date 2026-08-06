@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
- Every change must be covered by a new or updated test, then run it: `{{ $assist->artisanCommand('test --compact') }}` with a filter.
