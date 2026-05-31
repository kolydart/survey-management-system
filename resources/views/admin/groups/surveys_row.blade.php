<tr data-index="{{ $index }}">
    <td><input type="text" name="surveys[{{ $index }}][title]" id="surveys[{{ $index }}][title]" value="{{ old('surveys['.$index.'][title]', isset($field) ? $field->title: '') }}" class="form-control"></td>

    <td>
        <a href="#" class="remove btn btn-xs btn-danger">@lang('quickadmin.qa_delete')</a>
    </td>
</tr>