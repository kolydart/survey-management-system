@can($gateKey.'delete')
    <form action="{{ route($routeKey.'.restore', $row->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">
        @csrf
        <button type="submit" class="btn btn-xs btn-success">{{ trans('quickadmin.qa_restore') }}</button>
    </form>
@endcan
@can($gateKey.'delete')
    <form action="{{ route($routeKey.'.perma_del', $row->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-xs btn-danger">{{ trans('quickadmin.qa_permadel') }}</button>
    </form>
@endcan
