<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;

class PositionRequest extends FormRequest
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
                'department_id' => 'required',
                'name' => 'required|min:12',
            ];
    }

    public function messages()
    {
    return [
            'department_id.required' => 'Se requiere definir un departamento',

            'name.required' => 'Ingrese el nombre del cargo',
            'name.min' => 'Mínimo 12 caracteres',

        ];
    }
}
