<?php

namespace App\Http\Requests\author\personal;

use Illuminate\Foundation\Http\FormRequest;

class EditValidation extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'phone'=>'required',
            'tehnology.*'=>'required',
            'level.*'=>'required',
            'expreience.*'=>'required',
            'description'=>'required',
        ];
    }
    public function messages(){
        return [
            'name.required'=>'You Forgot Your Name',
            'phone.required'=>'You Forgot Phone Number',
            'tehnology.required'=>'Must Enter Technology',
            'level.required'=>'Must Enter Level',
            'expreience.required'=>'Must Enter Expreience',
            'description.required'=>'You Forgot Your Description',

        ];
    }
}
