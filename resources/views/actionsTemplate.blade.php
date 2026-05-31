@can($gateKey.'view')
    <a href="{{ route($routeKey.'.show', $row->id) }}"
       class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
@endcan
@can($gateKey.'edit')
    <a href="{{ route($routeKey.'.edit', $row->id) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
@endcan
@can($gateKey.'delete')
    <form action="{{ route($routeKey.'.destroy', $row->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('{{ trans('quickadmin.qa_are_you_sure') }}');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-xs btn-danger">{{ trans('quickadmin.qa_delete') }}</button>
    </form>
@endcan
