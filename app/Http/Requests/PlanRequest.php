<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;

class PlanRequest extends FormRequest
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
            //'operation_id' => 'required',
            'name' => 'required|min:15',
        ];
    }

    public function messages()
    {
    return [
            //'operation_id.required' => 'Se requiere definir el cargo',

            'name.required' => 'Debe describir el POAI',
            'name.min' => 'Mínimo 15 caracteres',

        ];
    }
}
