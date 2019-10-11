<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;

class UserRequest extends FormRequest
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
        if(isset($this->id))
        {
            $id = Hashids::decode($this->id)[0];
            return [
                'employee' => 'required|min:5',
                'username' => 'required|min:5|unique:users,username,'.$id,
                'name' => 'required|min:12',
                'roles' => 'required',
            ];
        }
        else
            return [
                'employee' => 'required|min:5',
                'username' => 'required|min:5|unique:users,username',
                'name' => 'required|min:12',
                'roles' => 'required',
            ];
    }

    public function messages()
    {
    return [
            'employee.required' => 'Se requiere número de C.I. y extención',
            'employee.min' => 'Mínimo 5 caracteres',

            'username.required' => 'Debe definir un nombre de usuario',
            'username.unique' => 'Nombre repetido, elija otro',
            'username.min' => 'Mínimo 5 caracteres',

            'name.required' => 'Ingrese el nombre completo',
            'name.min' => 'Mínimo 12 caracteres',

            'roles.required' => 'Requiere permisos',
        ];
    }
}
