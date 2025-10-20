<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

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
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Build detailed error information
        $failedFields = [];
        foreach ($validator->failed() as $field => $rules) {
            $failedFields[$field] = [
                'rules_failed' => array_keys($rules),
                'value' => $this->input($field),
            ];
        }

        // Log validation errors with details about which fields failed
        Log::error('Survey submission validation failed', [
            'failed_fields' => $failedFields,
            'all_errors' => $validator->errors()->toArray(),
            'request_data_keys' => array_keys($this->all()),
        ]);

        // Also log just the field names for easy viewing
        Log::warning('Failed field names: ' . implode(', ', array_keys($failedFields)));

        parent::failedValidation($validator);
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
            '*_content*' => 'nullable|string|max:65535|regex:/^[^$^]*$/',
        ];
    }

    public function messages()
    {
        return [
            'survey_id.required' => __('Could not determine Survey.').' '.__('Please contact administrator.'),
            '*_id*.numeric' => __('Error validating data.').' '.__('Please contact administrator.'),
            '*_id*.filled' => __('Empty field during data validation.').' '.__('Please contact administrator.'),
            '*_content*.filled' => __('Text fields are not allowed to be empty.'),
            '*_content*.regex' => __('Special characters ($, ^, $ etc.) are not allowed in text fields. Please remove special characters and try again.'),
        ];
    }
}
