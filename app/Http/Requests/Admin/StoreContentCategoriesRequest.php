<?php
namespace App\Http\Requests\Admin;

use App\ContentCategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreContentCategoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ContentCategory::storeValidation($this);
    }
}
