<tr data-index="{{ $index }}">
    <td>{!! Form::text('questions['.$index.'][title]', old('questions['.$index.'][title]', isset($field) ? $field->title: ''), ['class' => 'form-control']) !!}</td>

    <td>
        <a href="#" class="remove btn btn-xs btn-danger">@lang('quickadmin.qa_delete')</a>
    </td>
</tr>