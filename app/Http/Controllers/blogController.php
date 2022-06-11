<?php

namespace App\Http\Controllers;

use App\Events\sendPublishEvent;
use App\Models\post;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BlogValidationRequest;
use App\Http\Requests\EditBlogValidationRequest;
use App\Models\tag;
use App\Models\tempPost;

class blogController extends Controller
{

    public function index(){

        $post = post::where(['user_id'=>Auth::user()->id])->get();
        return view('blog.showblog',['posts'=>$post]);
    }
    public function create(){
        return view('blog.createBlog');
    }

    public function store(BlogValidationRequest $request){
        $post = new post();
        $posttag = new tag();

        $post_status = 0;
        if($request->has('draft')){
            $post_status = 0;
        }
        if($request->has('publish')){
            $post_status = 1;
        }
        $post->title = $request->title;
        $post->user_id = Auth::user()->id;
        if($request->hasFile('cover_img')){
            $post->addMedia($request->file('cover_img'))->toMediaCollection('cover_image','my_files');
        }
        $tags = explode(',',$request->tags);
        $ids = array("");
        $tempost = post::find(3);
        foreach($tags as $tag){
           $id =  $posttag::insertGetId(['name'=>$tag]);
           array_push($ids,$id);
        }
        array_shift($ids);
        $tempost->tags()->attach($ids);
        exit;
        $post->text_content = $request->postcontent;
        $post->post_Status = $post_status;
        if($request->hasFile('post_img')){
            foreach($request->file('post_img') as $file){
                $post->addMedia($file)->toMediaCollection();
           }
        }
        $post->isApprove = false;
        $email = Auth::user()->email;

        $post->save();
        if($post_status == 1){
            event(new sendPublishEvent($post,$email));
        }
        return redirect(route('showallblog'))->with('message','New Post Added');
    }

    public function edit(Request $request,$slug){
        $post = post::where(['slug'=>$slug,'user_id'=>Auth::user()->id])->first();
        if($post==null){
            return view('errorPages.pagenotfound');
        }
        return view('blog.editblog',['post'=>$post]);
    }

    public function update(EditBlogValidationRequest $request,$slug){
        $post = post::where('slug',$slug)->first();
        //Initialize statuscode
        $status_code = 1;
        if($post::where('slug',$slug)->first()->post_Status==0){
            $status_code = 0;
        }
        if($post::where('slug',$slug)->first()->post_Status==3){
            $status_code = 1;
        }
        //Create temp post if post is updating
        if(post::where('slug',$slug)->first()->post_Status==2){
            $temppost = new tempPost;
            $temppost->user_id = $post->user_id;
            $temppost->title = $post->title;
            $temppost->slug = $post->slug;
            $temppost->text_content = $post->text_content;
            $temppost->post_Status = $post->post_Status;
            $temppost->isApprove = $post->isApprove;
            $temppost->save();

            //move old model images to new model
            $temppost = tempPost::where('slug',$post->slug)->first();

            $covermediaobject = $post->getMedia('cover_image');
            $postmediaobject = $post->getMedia();
            foreach($covermediaobject as $covermedia){
                $covermedia->copy($temppost,'temp_cover_image','temp_my_files');
            }
            foreach($postmediaobject as $postimgmedia){
                $postimgmedia->copy($temppost,'temp_post_images','temp_my_post_files');
            }
            $status_code = 4;
        }
        //assign new updated values to post model
        $post->isApprove = false;
        $email = Auth::user()->email;
        $post->title = $request->title;
        $post->post_Status = $status_code;

        $post->text_content = $request->postcontent;

        //assign updated images to model and delete old images from post model

        if($request->hasFile('cover_img')){
            foreach($post->getMedia('cover_image') as $cover_image){
                $cover_image->delete();
            }
            $post->addMedia($request->file('cover_img'))->toMediaCollection('cover_image','my_files');
        }
        if($request->hasFile('post_img')){
            foreach($post->getMedia() as $postimg){
                $postimg->delete();
            }
            foreach($request->file('post_img') as $file){
                $post->addMedia($file)->toMediaCollection();
           }
        }
        //save updated post

        $post->save();


        //notificatons and session messages

            if(post::where('slug',$slug)->first()->post_Status==4 || post::where('slug',$slug)->first()->post_Status==3 || post::where('slug',$slug)->first()->post_Status==1){
                //post is updating after publised
                    $message = "Your blog is again in approval State";
                    event(new sendPublishEvent($post,$email));
            }else{
                $message = "Your drafted blog is Updated Sucessfully";
            }

        return redirect(route('showallblog'))->with('message',$message);
    }

    public function delete($slug){
        $post = post::where(['slug'=>$slug,'user_id'=>Auth::user()->id])->first();
        if($post==null){
            return view('errorPages.pagenotfound');
        }
        foreach($post->getMedia() as $postimg){
            $postimg->delete();
        }
        foreach($post->getMedia('cover_image') as $cover_image){
            $cover_image->delete();
        }
        $post->delete();
        return redirect(route('showallblog'))->with('delete',"Post Deleted Successfully");
    }

    public function userblogs($userslug,$postslug=null){

        $user = User::where(['username'=>$userslug])->first();

                if($postslug==null){
                    if(Auth::user()->username==$userslug){
                     return redirect('/home');
                    }
                    if($user==null){
                        return view('errorPages.pagenotfound');
                    }

                    $posts = post::where(['user_id'=>$user->id,'post_Status'=>2])->get();
                    $oldposts = tempPost::where(['user_id'=>$user->id,'post_status'=>2])->get();
                    if($posts==null){
                        return view('errorPages.pagenotfound');
                    }else{
                        return view('blog.userblogs',['posts'=>$posts,'userslug'=>$userslug,'oldposts'=>$oldposts]);
                    }

                }else{

                    if($user==null){
                        return view('errorPages.pagenotfound');
                    }
                    $post = post::where(['slug'=>$postslug,'post_Status'=>2])->first();
                    $oldpost = tempPost::where(['user_id'=>$user->id,'post_status'=>2,'slug'=>$postslug])->first();
                    if($post==null){
                        if($oldpost==null){
                            return view('errorPages.pagenotfound');
                        }
                        return view('blog.userblog',['post'=>$post,'userslug'=>$userslug,'oldpost'=>$oldpost]);
                    }
                    else{
                        return view('blog.userblog',['post'=>$post,'userslug'=>$userslug,'oldpost'=>$oldpost]);
                    }
               }


    }

}
