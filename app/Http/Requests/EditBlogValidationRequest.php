<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBlogValidationRequest extends FormRequest
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
            'cover_img'=>'mimes:png,jpg,jpeg',
            'postcontent'=>'required',
            'post_img.*'=>'mimes:png,jpg,jpeg',
        ];
    }
    public function messages(){
        return [
            'title.required'=>'You Forgot Blog Title',
            'cover_img.mimes'=>'Choose only png,jpg,jpeg',
            'postcontent.required'=>'You Forgot Blog Detail',
            'post_img.mimes'=>'Choose only png,jpg,jpeg',
        ];
    }
}
