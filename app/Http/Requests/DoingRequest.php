<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
use App\Doing;

class DoingRequest extends FormRequest
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
          $doing = Doing::Select('code','result_id')->Where('id',$id)->first();
          if(($doing->code == $this->code)&&($doing->result_id == $this->result_id))
            return [
                'code' => 'required',
                'result_id' => 'required',
                'description' => 'required|min:30',
            ];
        else
          return [
              'code' => 'required|unique:doings,code,NULL,code,result_id,'.$this->result_id,
              'result_id' => 'required',
              'description' => 'required|min:30',
          ];
      }
      else
          return [
            'code' => 'required|unique:doings,code,NULL,code,result_id,',
            'result_id' => 'required',
            'description' => 'required|min:30',
          ];
    }

    public function messages()
    {
    return [
            'code.required' => 'Se requiere definir un código',
            'code.unique' => 'El código ya está siendo utilizado',

            'result_id.required' => 'Debe elegir un Resultado',

            'description.required' => 'Se requiere de una descripción',
            'description.min' => 'Mínimo 30 caracteres',
        ];
    }
}
