<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hashids;
use App\Policy;

class PolicyRequest extends FormRequest
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
          $policy = Policy::Select('code','period_id')->Where('id',$id)->first();
          if(($policy->code == $this->code)&&($policy->period_id == $this->period_id))
            return [
                'code' => 'required',
                'period_id' => 'required',
                'description' => 'required|min:30',
            ];
            else
            return [
                'code' => 'required|unique:policys,code,NULL,code,period_id,'.$this->period_id,
                'period_id' => 'required',
                'description' => 'required|min:30',
            ];
      }
      else
          return [
            'code' => 'required|unique:policys,code,NULL,code,period_id,',
            'period_id' => 'required',
            'description' => 'required|min:30',
          ];
    }

    public function messages()
    {
    return [
            'code.required' => 'Se requiere definir un código',
            'code.unique' => 'El código ya está siendo utilizado',

            'period_id.required' => 'Debe elegir un período',

            'description.required' => 'Se requiere de una descripción',
            'description.min' => 'Mínimo 30 caracteres',
        ];
    }
}
