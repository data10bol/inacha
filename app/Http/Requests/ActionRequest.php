<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
Use App\Action;

class ActionRequest extends FormRequest
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
            'goal_id' => 'required',
            'description' => 'required|min:30|max:512',
            'ponderation' => 'required|numeric|max:100',
            'base' => 'required|numeric',
            'aim' => 'required|numeric|max:100|gte:base',
            'describe' => 'required|min:15|max:500',
            'pointer' => 'required|min:15|max:200',
            'measure' => 'required|min:15|max:500',
            'validation' => 'required|min:15|max:512',
           // 'scode' => 'required',
          //  'name' => 'required',

          ];

 /*       if(isset($this->id))
        {
            $id = Hashids::decode($this->id)[0];
            $action = Action::Select('code','goal_id')->Where('id',$id)->first();
            if(($action->code == $this->code)&&($action->goal_id == $this->goal_id))
              return [
                  'code' => 'required',
                  'department_id' => 'required',
                  'goal_id' => 'required',
                  'description' => 'required|min:30',
//                  'measure' => 'required|min:15',
                  'ponderation' => 'required|numeric|max:100',
                  'base' => 'required|numeric',
                  'aim' => 'required|numeric|max:100|gte:base',
                  'describe' => 'required|min:15',
                  'pointer' => 'required|min:15',
                  'validation' => 'required',
                  'scode' => 'required',
                  'name' => 'required',

              ];
          else
            return [
                'code' => 'required|unique:actions,code,NULL,code,goal_id,'.$this->goal_id,
                'department_id' => 'required',
                'goal_id' => 'required',
                'description' => 'required|min:30',
//                'measure' => 'required|min:15',
                'ponderation' => 'required|numeric|max:100',
                'base' => 'required|numeric',
                'aim' => 'required|numeric|max:100|gte:base',
                'describe' => 'required|min:15',
                'pointer' => 'required|min:15',
                'validation' => 'required',
                'scode' => 'required',
                'name' => 'required',

            ];
        }
        else
            return [
              'code' => 'required|unique:actions,code,NULL,code,goal_id,',
              'department_id' => 'required',
              'goal_id' => 'required',
              'description' => 'required|min:30',
//              'measure' => 'required|min:15',
              'ponderation' => 'required|numeric|max:100',
              'base' => 'required|numeric',
              'aim' => 'required|numeric|max:100|gte:base',
              'describe' => 'required|min:15',
              'pointer' => 'required|min:15',
              'validation' => 'required',
              'scode' => 'required',
              'name' => 'required',

            ]; */
    }

    public function messages()
    {
    return [
            //            'code.required' => 'Se requiere definir un código',
            //            'code.unique' => 'El código ya está siendo utilizado',

            'department_id.required' => 'Debe elegir un departamento responsable',

            'goal_id.required' => 'Debe elegir una Acción a Mediano Plazo',

            'description.required' => 'Debe describir la acción',
            'description.min' => 'Mínimo 30 caracteres',
            'description.max' => 'Máximo 512 caracteres',

            'measure.required' => 'Debe indicar la fórmula empleada',
            'measure.min' => 'Mínimo 15 caracteres',
            'measure.max' => 'Máximo 128 caracteres',

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
            'describe.max' => 'Máximo 128 caracteres',

            'pointer.required' => 'Debe definir un indicador',
            'pointer.min' => 'Mínimo de 15 caracteres',
            'pointer.max' => 'Máximo de 128 caracteres',

            'validation.required' => 'Se requiere una forma de validación',
            'validation.min' => 'Mínimo de 15 caracteres',
            'validation.max' => 'Máximo de 128 caracteres',

            //'scode.required' => 'Debe definir un código',

            //'name.required' => 'Debe ingresar una denominación',
    ];
    }

}
