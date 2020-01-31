<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
Use App\Operation;

class OperationRequest extends FormRequest
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
            'action_id' => 'required',
            'description' => 'required|min:30|max:512',
            'ponderation' => 'required|numeric|max:100',
            'base' => 'required|numeric',
            'aim' => 'required|numeric|max:100|gte:base',
            'describe' => 'required|min:15|max:500',
            'pointer' => 'required|min:15|max:200',
            'validation' => 'required|min:15|max:512',
            'department_id' => 'required',
            'dep_ponderation' => 'required|numeric|min:1|max:100'
//            'start' => 'required',
//            'finish' => 'required|gte:start',
        ];

/*        if(isset($this->id))
        {
            $id = Hashids::decode($this->id)[0];
            $operation = Operation::Select('code','action_id')->Where('id',$id)->first();
            if(($operation->code == $this->code)&&($operation->action_id == $this->action_id))
              return [
                  'code' => 'required',
                  'action_id' => 'required',
                  'description' => 'required|min:30',
//                  'measure' => 'required|min:15',
                  'ponderation' => 'required|numeric|max:100',
                  'base' => 'required|numeric',
                  'aim' => 'required|numeric|max:100|gte:base',
                  'describe' => 'required|min:15',
                  'pointer' => 'required|min:15|max:128',
                  'validation' => 'required',
                  'start' => 'required',
                  'finish' => 'required|gte:start',
              ];
          else
            return [
                'code' => 'required|unique:operations,code,NULL,code,action_id,'.$this->action_id,
                'action_id' => 'required',
                'description' => 'required|min:30',
//                'measure' => 'required|min:15',
                'ponderation' => 'required|numeric|max:100',
                'base' => 'required|numeric',
                'aim' => 'required|numeric|max:100|gte:base',
                'describe' => 'required|min:15',
                'pointer' => 'required|min:15|max:128',
                'validation' => 'required',
                'start' => 'required',
                'finish' => 'required|gte:start',
            ];
        }
        else
            return [
              'code' => 'required|unique:operations,code,NULL,code,action_id,',
              'action_id' => 'required',
              'description' => 'required|min:30',
//              'measure' => 'required|min:15',
              'ponderation' => 'required|numeric|max:100',
              'base' => 'required|numeric',
              'aim' => 'required|numeric|max:100|gte:base',
              'describe' => 'required|min:15',
              'pointer' => 'required|min:15|max:128',
              'validation' => 'required',
              'start' => 'required',
              'finish' => 'required|gte:start',
            ]; */
    }

    public function messages()
    {

        return [

            'action_id.required' => 'Debe elegir una Acción de Corto Plazo',

            'description.required' => 'Debe describir la operación',
            'description.min' => 'Mínimo 30 caracteres',
            'description.max' => 'Máximo 512 caracteres',

            'ponderation.required' => 'Debe ingresar una ponderación',
            'ponderation.numeric' => 'Debe ser un número',
            'ponderation.max' => 'No debe superar el 100%',

            'base.required' => 'Debe ingresar un valor inicial',
            'base.numeric' => 'Debe ser un valor numérico',

            'aim.required' => 'Debe definir una meta',
            'aim.numeric' => 'Debe ser un número',
            'aim.max' => 'No debe superar el 100%',
            'aim.gte' => 'La meta debe ser superior a la base',

            'describe.required' => 'Fórmula empleada en el indicador',
            'describe.min' => 'Mínimo 15 caracteres',
            'describe.max' => 'Máximo 160 caracteres',

            'pointer.required' => 'Debe definir un indicador',
            'pointer.min' => 'Mínimo de 15 caracteres',
            'pointer.max' => 'Máximo de 160 caracteres',

            'validation.required' => 'Se requiere una forma de validación',
            'validation.min' => 'Mínimo 15 caracteres',
            'validation.max' => 'Máximo 512 caracteres',

            'department_id.required' => 'El Campo departamento es requerído',

            'dep_ponderation.required' => 'Debe definir una ponderación',
            'dep_ponderation.numeric' => 'Debe introducir un carácter numérico entero',
            'dep_ponderation.min' => 'Debe tener un valor mínimo de 1',
            'dep_ponderation.max' => 'Debe poner un valor máximo de 100',

//            'start.required' => 'Debe definir un mes de inicio',

//            'finish.required' => 'Debe definir un mes de finalización',
//            'finish.gte' => 'El mes debe ser posterior a la de inicio',

        ];


/*    return [
            'code.required' => 'Se requiere definir un código',
            'code.unique' => 'El código ya está siendo utilizado',

            'action_id.required' => 'Debe elegir una Acción de Corto Plazo',

            'description.required' => 'Debe describir la operación',
            'description.min' => 'Mínimo 30 caracteres',

//            'measure.required' => 'Se requiere de una unidad de medida',
//            'measure.min' => 'Mínimo 15 caracteres',

            'ponderation.required' => 'Debe ingresar una ponderación',
            'ponderation.numeric' => 'Debe ser un número',
            'ponderation.max' => 'No debe superar el 100%',

            'base.required' => 'Debe ingresar un valor inicial',
            'base.numeric' => 'Debe ser un valor numérico',

            'aim.required' => 'Debe definir una meta',
            'aim.numeric' => 'Debe ser un número',
            'aim.max' => 'No debe superar el 100%',
            'aim.gte' => 'La meta debe ser superior a la base',

            'describe.required' => 'Fórmula empleada en el indicador',
            'describe.min' => 'Mínimo 15 caracteres',

            'pointer.required' => 'Debe definir un indicador',
            'pointer.min' => 'Mínimo de 15 caracteres',
            'pointer.max' => 'Máximo de 128 caracteres',

            'validation.required' => 'Se requiere una forma de validación',

            'start.required' => 'Debe definir un mes de inicio',

            'finish.required' => 'Debe definir un mes de finalización',
            'finish.gte' => 'El mes debe ser posterior a la de inicio',

        ];*/
    }

}
