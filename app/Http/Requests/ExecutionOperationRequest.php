<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
Use App\Task;

class ExecutionOperationRequest extends FormRequest
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

        if(!isset($this->success) && !isset($this->failure))
              return [
                  'success' => 'required|min:15',
              ];
        else
        {
            if(isset($this->failure))
                return [
                    'failure' => 'min:15',
                    'solution' => 'required|min:15',
                ];
            elseif(isset($this->success))
                return [
                    'success' => 'min:15',
                ];
        }
    }

    public function messages()
    {
    return [
            'success.required' => 'Se requiere definir los logros',
            'success.min' => 'Mínimo 15 caracteres',

            'failure.min' => 'Mínimo 15 caracteres',

            'solution.required' => 'Debe describir la o las soluciones',
            'solution.min' => 'Mínimo 15 caracteres',

            'success.min' => 'Mínimo 15 caracteres',
        ];
    }

}
