<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LimitRequest extends FormRequest
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
            'date' => 'required',
            'year_id' => 'required',
            'month' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Ingrese una fecha',
            'year_id.required' => 'Ingrese una gestión',
            'month.required' => 'Ingrese un mes',
        ];
    }
}
