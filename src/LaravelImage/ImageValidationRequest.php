<?php

namespace LaravelImage;

use App\Http\Requests\Request;

class ImageValidationRequest extends Request
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
        $imageFields = config('laravelimage.imageFields');
        $validationRule = config('laravelimage.validationRules');

        $rules = [];
        foreach ($imageFields as $field) {
            $rules[$field] = $validationRule;
        }

        return $rules;
    }

    /**
     * Get validation message text to display.
     *
     * @return array
     */
    public function messages()
    {
        return config('laravelimage.validationMessages');
    }
}
