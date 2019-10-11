<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;

class ConfigurationRequest extends FormRequest
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
            'period_id' => 'required',
            'mission' => 'required|min:50|max:512',
            'vision' => 'required|min:50|max:512',
          ];
    }

    public function messages()
    {
    return [
            'period_id.required' => 'Se requiere definir el periodo',

            'mission.required' => 'Debe definir una misión',
            'mission.min' => 'La misión es demasiado corta',
            'mission.max' => 'La misión supera el límite de caracteres',

            'vision.required' => 'Debe definir una visión',
            'vision.min' => 'La visión es demasiado corta',
            'vision.max' => 'La visión supera el límite de caracteres',

        ];
    }
}
