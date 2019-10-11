<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
Use App\Goal;

class GoalRequest extends FormRequest
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

            return[
                    'doing_id' => 'required',
                    'configuration_id' => 'required',
                    'description' => 'required|min:30|max:512',
            ];
    }

    public function messages()
    {
            return [
                    'doing_id.required' => 'Debe elegir una acción',

                    'configuration_id.required' => 'Debe elegir un período',

                    'description.required' => 'Se requiere de una descripción',
                    'description.min' => 'Mínimo 30 caracteres',
                    'description.max' => 'Máximo 512 caracteres',

                ];
    }

}
