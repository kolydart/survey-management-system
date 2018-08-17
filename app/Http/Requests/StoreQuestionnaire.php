<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use gateweb\common\Router;

class StoreQuestionnaire extends FormRequest
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
            'survey_id' => 'numeric|required',
            '*_id*' => 'numeric|filled',
            '*_content*' => 'filled|regex:'.Router::PREG_VALIDATE_TEXT,          
        ];
    }

    public function messages()
    {
        return [
            'survey_id.required' => __('Could not determine Survey.')." ".__('Please contact administrator.'),
            '*_id*.numeric' => __('Error validating data.')." ".__('Please contact administrator.'),
            '*_id*.filled' => __('Empty field during data validation.')." ".__('Please contact administrator.'),
            '*_content*.filled' => __('Text fields are not allowed to be empty.'),
            '*_content*.regex' => __('Special characters ($, ^, $ etc.) are not allowed in text fields. Please remove special characters and try again.'),
        ];
    }

}
