<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
Use App\Subtask;

class SubtaskRequest extends FormRequest
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
              'description' => 'required|min:30|max:256',
              'validation' => 'required|min:30|max:256',
              'start' => 'required',
              'finish' => 'required|gte:start',
            ];
    }

    public function messages()
    {
    return [

            'description.required' => 'Debe describir la subtarea',
            'description.min' => 'Mínimo 30 caracteres',
            'description.max' => 'Máximo 256 caracteres',

            'validation.required' => 'Debe describir el resultado',
            'validation.min' => 'Mínimo 30 caracteres',
            'validation.max' => 'Máximo 256 caracteres',

            'start.required' => 'Debe definir una fecha de inicio',

            'finish.required' => 'Debe definir una fecha de finalización',
            'finish.gte' => 'La finalización debe ser posterior a la de inicio',

        ];
    }

}
