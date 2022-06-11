<?php

namespace App\Http\Controllers;

use App\Models\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function createPostTitleView(){
        return view('addblog');
    }

    public function CreatePostTitle(Request $request){

        $post = new post();
        $post->user_id = Auth::user()->id;
        $post->title=$request->title;
        if($request->hasFile('cover_img')){
            $post->addMedia($request->file('cover_img'))->toMediaCollection('cover_image','my_files');
        }
        $post->text_content = "";
        $post->post_status = 0;
        $post->isApprove = 0;
        $post->save();
        return redirect()->back();
    }

    public function showone(){

        $posts = post::select(['id','title','slug','post_Status'])->where('user_id',Auth::user()->id)->get();

        return view('showblogList',['posts'=>$posts]);
    }



    public function showComplePost($slug){
        $post = post::where('slug',$slug)->first();
        return view('showComplePost',['post'=>$post]);
    }


    public function addPostContent(Request $request,$slug){
        $post =  post::where('slug',$slug)->first();
        return view('addPostContent',['post'=>$post]);
    }

    public function addPost(Request $request,$slug){
            $post = post::where('slug',$slug)->first();
            $postcontent = $request->postcontent;
            $message = "";
            if($request->has('draft')){
                $post->text_content = $postcontent;
                $post->post_Status = 1;
                $message = "Post Is in Draft Mode.";
            }
            if($request->has('publish')){
                $post->text_content = $postcontent;
                $post->post_Status = 2;
                $message = "Post Is in Publishing Phase.";
            }

            $post->save();
            return redirect(route('showblog'))->with('message',$message);



    }

    public function postimages(Request $request,$slug){


         $post = post::where('slug',$slug)->first();

         if($request->hasFile('upload')){
            $img = $post->addMedia($request->file('upload'))->toMediaCollection();
         }

        return response()->json([

            'url'=>$img->getUrl(),
       ]);
    }



    public function edit($slug){
        $post = post::where('slug',$slug)->first();
        return view('editpost',['post'=>$post]);
    }


    public function update(Request $request,$slug){
        $post = post::where('slug',$slug)->first();
        $post->title = $request->title;
        $post->text_content =  $request->postcontent;
        $post->save();
        return redirect('/showComplePost/',$slug)->with('message','Post is Updated');
    }


    public function deletepost($slug){
        $post = post::where('slug',$slug)->first();
        foreach($post->getMedia() as $postimg){
            $postimg->delete();
        }

        foreach($post->getMedia('cover_image') as $cover_image){
            $cover_image->delete();
        }

        $post->delete();
        return redirect(route('showblog'))->with('message',"Post Deleted Successfully");
    }

}
