<?php

namespace App\Http\Controllers;

#region namespaces
    use App\Models\Tag;
    use App\Models\Post;
    use App\Models\User;
    use App\Models\Temppost;
    use App\Http\Classes\Blog;
    use Illuminate\Http\Request;
    use App\Events\sendPublishEvent;
use App\Http\Classes\Tags;
use App\Http\Classes\User as ClassesUser;
    use App\Http\interfaces\constant;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Requests\blog\StoreValidation;
    use App\Http\Requests\blog\EditBlogValidation;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
#endregion namespaces

class BlogController extends Controller implements constant{
    public $blog;
    public $classuser;
    public $classtag;
    public function __construct(Blog $blog,ClassesUser $classuser,Tags $classtag){
        $this->blog = $blog;
        $this->classuser = $classuser;
        $this->classtag = $classtag;
    }
    //welcome page
    public function welcomepage(){
        $posts = $this->blog->Fetch();
        $topfiveposts = $this->blog->topfive();
        return view('welcome',['posts'=>$posts,'topfiveposts'=>$topfiveposts]);
    }
    //user index page
    public function index(){
        $posts = $this->blog->userPosts(Auth::user()->id);
        return view('blog.show',['posts'=>$posts]);
    }
    public function draftindex(){
        $posts = $this->blog->userDraftPosts(Auth()->user()->id);
        return view('blog.draftindex',['posts'=>$posts]);
    }
    //create blog page
    public function create(){
        return view('blog.create');
    }
    public function resentBlogs($userslug){
        return $this->blog->userResentBlogs($userslug);
    }
    //store blog
    public function store(StoreValidation $request){
        $post_status = 0;
        if($request->has('draft')){
            $post_status = 0;
        }
        if($request->has('publish')){
            $post_status = 1;
            if(Auth::user()->roles[0]['name']=='superadmin'){

                $post_status = constant::PUBLISHED;
            }
        }

        $this->blog->store($request,$post_status);
        if($post_status==0){
            return redirect(route('blog.draftshowallblog'))->with('message','New Post Added');
        }
        return redirect(route('blog.showallblog'))->with('message','New Post Added');
    }
    //update blog status for schedule
    public function updateStatus(Request $request,$slug){
        $post = $this->blog->findPost($slug);
        if(Auth::user()->roles[0]['name']=='superadmin'){
            $this->blog->updatepostforsuperadmins($post,$request->datetime);
        }else{
            $post = $this->blog->findPost($slug);
            $temppost = $this->blog->Temppost_For_Publish_And_Schedule($post,$request->datetime);
            $email = Auth::user()->email;
            $post->post_Status = constant::APPROVAL_PENDING;
            $post->actual_post_time = $request->datetime;
            $post->save();
            $message = "Your blog is again in approval State";
            event(new sendPublishEvent($temppost,$email));
        }

        return redirect(route('blog.showallblog'))->with('message',$message);
    }
    //update blog status for publish now
    public function updateStatusforpublishnow($slug){
        $post = $this->blog->findPost($slug);
        if(Auth::user()->roles[0]['name']=='superadmin'){
            $this->blog->updatepostforsuperadmins($post);
        }else{
            $temppost = $this->blog->Temppost_For_Publish_And_Schedule($post);
            $email = Auth::user()->email;
            $post->post_Status = constant::PENDING;
            $post->save();
            $message = "Your Draft blog is  in approval State";
            event(new sendPublishEvent($temppost,$email));
        }

        return redirect(route('blog.showallblog'))->with('message',$message);
    }
    //edit blog
    public function edit(Request $request,$slug){
        $edpost = $this->blog->edit($slug);
        if($edpost==null){
            return view('error_pages.html.pagenotfound');
        }
        return view('blog.edit',['post'=>$edpost]);
    }
    //update blog
    public function update(EditBlogValidation $request,$slug){
        $post = $this->blog->findPost($slug);
        if($post==null){
            return view('error_pages.html.pagenotfound');
        }else{
            $temppost = $this->blog->findTempPost($post->id);
            if($temppost==null){
                if($request->has('saveasdraft')){
                    $this->blog->updateoriginalpost($request,$post);
                    return redirect(route('blog.draftshowallblog'))->with('message','Your drafted blog is updated.');
                }
                if($request->has('publish')){
                    if(Auth::user()->roles[0]['name']=='superadmin'){
                         $this->blog->updateoriginalpost($request,$post,false,true);
                    }else{
                        $this->blog->publisdrafthpost($request,$post);
                    }
                    return redirect(route('blog.showallblog'))->with('message','Your blog is  in approval State');
                }
                if($request->has('schedule')){
                    if(Auth::user()->roles[0]['name']=='superadmin'){
                        $this->blog->updateoriginalpost($request,$post,true,true);
                    }else{
                        $this->blog->scheduledraftpost($request,$post);
                        $post->actual_post_time = $request->scheduletime;
                        $post->post_Status = constant::APPROVAL_PENDING;
                        $post->save();
                    }
                    $message = "Your blog is  in approval State";
                    return redirect(route('blog.showallblog'))->with('message',$message);
                }
                if($request->has('update')){
                    if(Auth::user()->roles[0]['name']=='superadmin'){
                        $this->blog->updateoriginalpost($request,$post,false,true);
                    }else{
                        $this->blog->updatePublishedPost($request,$post);
                    }
                    $message = "Your blog is  in approval State";

                    return redirect(route('blog.showallblog'))->with('message',$message);
                }
            }else{
                if($post->temppost->post_Status==constant::REJECTED){

                    $this->blog->updateRejectedPosts($request,$post);
                    return redirect(route('blog.showallblog'))->with('message','Your blog is again in approval State');
                }
                if($post->temppost->post_Status==constant::SCHEDULE_REJECTED){
                    $this->blog->updateRejectedPosts($request,$post,true);
                    return redirect(route('blog.showallblog'))->with('message','Your blog is again in approval State');
                }
            }
        }
    }
    //delete blog
    public function delete($slug){
        if($this->blog->delete($slug)){
            return redirect(route('blog.showallblog'))->with('delete',"Post Deleted Successfully");
        }else{
            return view('error_pages.html.pagenotfound');
        }
    }
    //delete image in edit blog page
    public function deleteone($slug,$uuid){
        $post = $this->blog->findPost($slug);
        $mediaid = Media::where('uuid',$uuid)->first();
       if($mediaid!=null){
            if($post->temppost!=null){
                $mediaid->forceDelete();
            }else{
                $mediaid->delete();
            }
            return true;
       }else{
            return false;
       }
    }
    //view blog for superadmin
    public function viewblog($slug){
        $post = Post::where('slug',$slug)->first();
        $temppost = Temppost::where('post_id',$post->id)->first();
        $post = $post;
        if($temppost!=null){
            $post = $temppost;
        }else{
            if($post->post_Status==1){
                $post = $post;
            }else{
                return view('error_pages.html.pagenotfound');
            }
        }
        return view('notification.blog.view',['post'=>$post]);
    }
    //user blog and blogs
    public function userblogs($userslug,$postslug=null){
        $user = $this->classuser->findUser($userslug);

        if($user==null){
            return view('error_pages.html.pagenotfound');
        }else{
            if($postslug==null){

                $posts = $this->blog->getUserPosts($user->id);
                if($posts==null){
                    return view('error_pages.html.pagenotfound');
                }else{
                    return view('blog.index',['posts'=>$posts,'userslug'=>$userslug]);
                }
            }else{
                $post = $this->blog->getPublishPost($postslug);
                if($post==null){
                    return view('error_pages.html.pagenotfound');
                }else{
                    $this->blog->unDelete($post);
                    $tags = $this->classtag->getAllTags();
                    $topfivepost = $this->blog->userresentBlogs($userslug);
                    return view('blog.detail',['post'=>$post,'userslug'=>$userslug,'newposts'=>$topfivepost,'tags'=>$tags]);
                }

            }
        }
    }
}
