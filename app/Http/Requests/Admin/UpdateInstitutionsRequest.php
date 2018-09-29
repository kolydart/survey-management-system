<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstitutionsRequest extends FormRequest
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
        return [
            
            'title' => 'required|unique:institutions,title,'.$this->route('institution'),
            'surveys.*.title' => 'required',
            'surveys.*.alias' => 'required|unique:surveys,alias,'.$this->route('survey'),
        ];
    }
}
