<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
use App\Result;

class ResultRequest extends FormRequest
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
          $result = Result::Select('code','target_id')->Where('id',$id)->first();
          if(($result->code == $this->code)&&($result->target_id == $this->target_id))
            return [
                'code' => 'required',
                'target_id' => 'required',
                'description' => 'required|min:30',
            ];
        else
          return [
              'code' => 'required|unique:doings,code,NULL,code,target_id,'.$this->target_id,
              'target_id' => 'required',
              'description' => 'required|min:30',
          ];
      }
      else
          return [
            'code' => 'required|unique:doings,code,NULL,code,target_id,',
            'target_id' => 'required',
            'description' => 'required|min:30',
          ];
    }

    public function messages()
    {
    return [
            'code.required' => 'Se requiere definir un código',
            'code.unique' => 'El código ya está siendo utilizado',

            'target_id.required' => 'Debe elegir una meta',

            'description.required' => 'Se requiere de una descripción',
            'description.min' => 'Mínimo 30 caracteres',
        ];
    }
}
