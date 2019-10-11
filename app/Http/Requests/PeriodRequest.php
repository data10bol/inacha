<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;

class PeriodRequest extends FormRequest
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
        'start' => 'required|date_format:Y',
        'finish' => 'required|date_format:Y|gte:start',
      ];
    }

    public function messages()
    {
    return [
            'start.required' => 'Debe definir el inicio de gestión',
            'start.date_format' => 'El formato es incorrecto Ej:2018',

            'finish.required' => 'Debe definir la finalización de la gestión',
            'finish.date_format' => 'El formato es incorrecto Ej:2018',
            'finish.gte' => 'La finalización debe ser posterior al inicio',

        ];
    }
}
