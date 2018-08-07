<tr data-index="{{ $index }}">
    <td>{!! Form::text('surveys['.$index.'][title]', old('surveys['.$index.'][title]', isset($field) ? $field->title: ''), ['class' => 'form-control']) !!}</td>

    <td>
        <a href="#" class="remove btn btn-xs btn-danger">@lang('quickadmin.qa_delete')</a>
    </td>
</tr>