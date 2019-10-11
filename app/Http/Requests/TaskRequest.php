<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
Use App\Task;

class TaskRequest extends FormRequest
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
      if(isset($this->edit))
        return[
          'operation_id' => 'required',
//            'user_id' => 'required',
          'plan_id' => 'required',
          'description' => 'required|min:30',
//            'base' => 'required|numeric',
//            'aim' => 'required|numeric|max:100|gte:base',
//            'describe' => 'required|min:15|max:128',
//            'pointer' => 'required|min:15|max:128',
          'validation' => 'required|min:15',
          'reason' => 'required|min:15|max:512',
//            'start' => 'required',
//            'finish' => 'required|gte:start',
        ];
      else
        return[
            'operation_id' => 'required',
//            'user_id' => 'required',
            'plan_id' => 'required',
            'description' => 'required|min:30|max:512',
//            'base' => 'required|numeric',
//            'aim' => 'required|numeric|max:100|gte:base',
//            'describe' => 'required|min:15|max:128',
//            'pointer' => 'required|min:15|max:128',
            'validation' => 'required|min:15|max:512',
//            'start' => 'required',
//            'finish' => 'required|gte:start',
        ];

/*        if(isset($this->id))
        {
            $id = Hashids::decode($this->id)[0];
            $task = Task::Select('code','operation_id')->Where('id',$id)->first();
            if(($task->code == $this->code)&&($task->operation_id == $this->operation_id))
              return [
                  'code' => 'required',
                  'operation_id' => 'required',
                  'user_id' => 'required',
                  'description' => 'required|min:30',
//                  'measure' => 'required|min:15',
                  'base' => 'required|numeric',
                  'aim' => 'required|numeric|max:100|gte:base',
                  'describe' => 'required|min:15',
                  'pointer' => 'required|min:15',
                  'validation' => 'required',
                  'start' => 'required',
                  'finish' => 'required|gte:start',
              ];
          else
            return [
                'code' => 'required|unique:tasks,code,NULL,code,operation_id,'.$this->operation_id,
                'operation_id' => 'required',
                'user_id' => 'required',
                'description' => 'required|min:30',
//                'measure' => 'required|min:15',
                'base' => 'required|numeric',
                'aim' => 'required|numeric|max:100|gte:base',
                'describe' => 'required|min:15',
                'pointer' => 'required|min:15',
                'validation' => 'required',
                'start' => 'required',
                'finish' => 'required|gte:start',
            ];
        }
        else
            return [
              'code' => 'required|unique:tasks,code,NULL,code,operation_id,',
              'operation_id' => 'required',
              'user_id' => 'required',
              'description' => 'required|min:30',
//              'measure' => 'required|min:15',
              'base' => 'required|numeric',
              'aim' => 'required|numeric|max:100|gte:base',
              'describe' => 'required|min:15',
              'pointer' => 'required|min:15',
              'validation' => 'required',
              'start' => 'required',
              'finish' => 'required|gte:start',
            ];*/
    }

    public function messages()
    {
/*    return [
            'code.required' => 'Se requiere definir un código',
            'code.unique' => 'El código ya está siendo utilizado',

            'operation_id.required' => 'Debe elegir una Operación',

            'user_id.required' => 'Debe elegir un responsable',

            'description.required' => 'Debe describir la tarea',
            'description.min' => 'Mínimo 30 caracteres',

//            'measure.required' => 'Se requiere de una unidad de medida',
//            'measure.min' => 'Mínimo 15 caracteres',

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

            'validation.required' => 'Se requiere una forma de validación',

            'start.required' => 'Debe definir un mes de inicio',

            'finish.required' => 'Debe definir un mes de finalización',
            'finish.gte' => 'El mes debe ser posterior a la de inicio',

        ];*/
        return [
            'operation_id.required' => 'Debe elegir una Operación',

//            'user_id.required' => 'Debe elegir un responsable',

            'plan_id.required' => 'Debe elegir una actividad del POAI',

            'description.required' => 'Debe describir la tarea',
            'description.min' => 'Mínimo 30 caracteres',
//            'description.max' => 'Máximo 512 caracteres',

//            'base.required' => 'Debe ingresar un valor inicial',
//            'base.numeric' => 'Debe ser un valor numérico',

//            'aim.required' => 'Debe definir una meta',
//            'aim.numeric' => 'Debe ser un número',
//            'aim.max' => 'No debe superar el 100%',
//            'aim.gte' => 'La meta debe ser superior a la base',

            'describe.required' => 'Fórmula empleada en el indicador',
            'describe.min' => 'Mínimo 15 caracteres',
            'describe.max' => 'Máximo 128 caracteres',

//            'pointer.required' => 'Debe definir un indicador',
//            'pointer.min' => 'Mínimo de 15 caracteres',
//            'pointer.max' => 'Máximo de 128 caracteres',

            'validation.required' => 'Se requiere una forma de validación',
            'validation.min' => 'Mínimo 15 caracteres',
//            'validation.max' => 'Máximo 512 caracteres',

            'reason.required' => 'Se requiere un justificativo de la actualización',
            'reason.min' => 'Mínimo 15 caracteres',
//            'reason.max' => 'Máximo 512 caracteres',

//            'start.required' => 'Debe definir un mes de inicio',

//            'finish.required' => 'Debe definir un mes de finalización',
//            'finish.gte' => 'El mes debe ser posterior a la de inicio',

        ];
    }

}
