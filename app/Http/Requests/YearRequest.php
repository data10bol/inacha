<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;

class YearRequest extends FormRequest
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
        'name' => 'required|numeric|min:4',
      ];
    }

    public function messages()
    {
    return [
            'name.required' => 'Debe definir una gestión',
            'name.numeric' => 'Debe ser un valor numérico',
            'name.min' => 'Debe ser mayo a 2017',

        ];
    }
}
