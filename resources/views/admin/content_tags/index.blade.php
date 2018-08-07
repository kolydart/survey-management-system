@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.content-tags.title')</h3>
    @can('content_tag_create')
    <p>
        <a href="{{ route('admin.content_tags.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($content_tags) > 0 ? 'datatable' : '' }} @can('content_tag_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('content_tag_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.content-tags.fields.title')</th>
                        <th>@lang('quickadmin.content-tags.fields.slug')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($content_tags) > 0)
                        @foreach ($content_tags as $content_tag)
                            <tr data-entry-id="{{ $content_tag->id }}">
                                @can('content_tag_delete')
                                    <td></td>
                                @endcan

                                <td field-key='title'>{{ $content_tag->title }}</td>
                                <td field-key='slug'>{{ $content_tag->slug }}</td>
                                                                <td>
                                    @can('content_tag_view')
                                    <a href="{{ route('admin.content_tags.show',[$content_tag->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('content_tag_edit')
                                    <a href="{{ route('admin.content_tags.edit',[$content_tag->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('content_tag_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.content_tags.destroy', $content_tag->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('content_tag_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.content_tags.mass_destroy') }}';
        @endcan

    </script>
@endsection