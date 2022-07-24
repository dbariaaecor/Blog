<?php

namespace App\Http\Requests\blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreValidation extends FormRequest
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
            'title'=>'required',
            'cover_img'=>'required|mimes:png,jpg,jpeg',
            'tags'=>'required',
            'postcontent'=>'required',
            'post_img.*'=>'required|mimes:png,jpg,jpeg',
        ];
    }
    public function messages(){
        return [
            'title.required'=>'You Forgot Blog Title',
            'cover_img.required'=>'You Forgot Blog Cover Image',
            'cover_img.mimes'=>'Choose only png,jpg,jpeg',
            'tags.required'=>'Must insert one Tag',
            'postcontent.required'=>'You Forgot Blog Detail',
            'post_img.required'=>'You Forgot Blog Images',
            'post_img.mimes'=>'Choose only png,jpg,jpeg',
        ];
    }
}
