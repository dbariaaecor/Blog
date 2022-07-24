<?php

namespace App\Http\Requests\author\professional;

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
            'tehnology.*'=>'required',
            'level.*'=>'required',
            'expreience.*'=>'required',
            'desc'=>'required',

        ];
    }
    public function messages(){
        return [

            'tehnology.required'=>'Must Enter Technology',
            'level.required'=>'Must Enter Level',
            'expreience.required'=>'Must Enter Expreience',
            'desc.required'=>'You Forgot Your Description',


        ];
    }
}
