<?php

namespace App\Http\Requests\blog;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class EditBlogValidation extends FormRequest
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
        $link = explode('/',$this->server('HTTP_REFERER'));
        $slug = end($link);
        $post = Post::where('slug',$slug)->first();

        $img = $post->getMedia('cover_image')->toArray();
        $postimg = $post->getMedia()->toArray();
        $rules = [
            'title'=>'required',
            'cover_img'=>['mimes:png,jpg,jpeg'],
            'postcontent'=>'required',
            'post_img.*'=>'mimes:png,jpg,jpeg',
        ];
        if($img==null){
            $rules['cover_img'] = ['mimes:png,jpg,jpeg','required'];
        }
        if($postimg==null){
            $rules['post_img'] = ['mimes:png,jpg,jpeg','required'];
        }
        if($this->has('schedule')){
            $rules['scheduletime'] = 'required';
        }

        return $rules;
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
