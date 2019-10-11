<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
use App\Target;

class TargetRequest extends FormRequest
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
          $target = Target::Select('code','policy_id')->Where('id',$id)->first();
          if(($target->code == $this->code)&&($target->policy_id == $this->policy_id))
            return [
                'code' => 'required',
                'policy_id' => 'required',
                'description' => 'required|min:30',
            ];
        else
          return [
              'code' => 'required|unique:targets,code,NULL,code,policy_id,'.$this->policy_id,
              'policy_id' => 'required',
              'description' => 'required|min:30',
          ];
      }
      else
          return [
            'code' => 'required|unique:targets,code,NULL,code,policy_id,',
            'policy_id' => 'required',
            'description' => 'required|min:30',
          ];
    }

    public function messages()
    {
    return [
            'code.required' => 'Se requiere definir un código',
            'code.unique' => 'El código ya está siendo utilizado',

            'policy_id.required' => 'Debe elegir una política',

            'description.required' => 'Se requiere de una descripción',
            'description.min' => 'Mínimo 30 caracteres',
        ];
    }

}
