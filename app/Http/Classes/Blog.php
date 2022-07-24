<?php


namespace App\Http\Classes;

#region namespaces
    use App\Models\Tag;
    use App\Models\Post;
    use App\Models\User;
    use App\Models\Temppost;
    use App\Events\sendPublishEvent;
    use App\Http\interfaces\constant;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Contracts\Pagination\Paginator;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
#endregion namespaces

class Blog implements constant{
    public function Fetch():Paginator{
        return Post::where('post_Status',constant::PUBLISHED)->orderBy('id','desc')->simplePaginate(10);
    }
    public function topfive():Collection{
        return Post::where('post_Status',constant::PUBLISHED)->latest()->limit(5)->get();
    }
    public function userPosts($userId):Collection{
        return Post::where('user_id',$userId)->orderBy('id','desc')->get();
    }
    public function userDraftPosts($userId):Collection{
        return Post::where(['user_id'=>$userId,'post_Status'=>constant::DRAFT])->orderBy('id','desc')->get();
    }
    public function userResentBlogs($userSlug):Collection{
        $userId = User::where('username',$userSlug)->first()->id;
        return Post::where(['user_id'=>$userId,'post_Status'=>constant::PUBLISHED])->latest()->limit(5)->get();
    }
    public function storeTags($posttags,$post):void{
        $tags = Tag::whereIn('name',$posttags)->get('id');
        $ids = array("");
        foreach($tags as $tag){
            array_push($ids,$tag->id);
        }
        array_shift($ids);
        $post->tags()->sync($ids);
    }
    public function createTempTags($posttags):String{
        $tags = Tag::whereIn('name',$posttags)->get();
        $temppost = array("");
        foreach($tags as $tag){
            array_push($temppost,array('name'=>$tag->name,'slug'=>$tag->slug));
        }
        array_shift($temppost);
        $jsontemptags = json_encode($temppost);
        return $jsontemptags;
    }
    public function store($request,$postStatus):void{
        //create post
            $post = new Post;
            $post->title = $request->title;
            $post->user_id = Auth::user()->id;
            if($request->hasFile('cover_img')){
                $post->addMedia($request->file('cover_img'))->toMediaCollection('cover_image','my_files');
            }
            $post->preview_content = strip_tags(substr($request->postcontent,0,160));
            $post->text_content = $request->postcontent;
            $post->post_Status = $postStatus;
            if($request->hasFile('post_img')){
                foreach($request->file('post_img') as $file){
                    $post->addMedia($file)->toMediaCollection();
                }
            }
            $post->isApprove = false;
            if(Auth::user()->roles[0]['name']=='superadmin'){
                $post->isApprove = true;
            }
            $post->save();
         //end post

         //store post tags
            $posttags = $request->tags;
            $this->storeTags($posttags,$post);
         //end tags

         //send conformation
            if(Auth::user()->roles[0]['name']=='admin'){
                $email = Auth::user()->email;
                if($postStatus==1){
                    event(new sendPublishEvent($post,$email));
                }
            }
         //end Conformation
    }
    public function findUser($userSlug){
        return User::where('username',$userSlug)->first();
    }
    public function findPost($slug){
        return Post::where('slug',$slug)->first();
    }
    public function findTempPost($postId){
        return Temppost::where('post_id',$postId)->first();
    }
    public function updateoriginalpost($request,$post,$isschedule = false,$isAdmin=false):void{
        $post->title = $request->title;
        $posttags = $request->tags;
        $this->storeTags($posttags,$post);
        $post->preview_content = strip_tags(substr($request->postcontent,0,160));
        $post->text_content = $request->postcontent;
        $post->post_Status = constant::DRAFT;
        if($isschedule && $isAdmin){
            $post->post_Status = constant::SCHEDULE;
            $post->actual_post_time = $request->scheduletime;
            $post->isApprove = constant::APPROVE;
        }elseif($isschedule==false && $isAdmin){

            $post->post_Status = constant::PUBLISHED;
            $post->isApprove = constant::APPROVE;
        }
        if($request->hasFile('cover_img')){
            foreach($post->getMedia('cover_image') as $cover_image){
                $cover_image->forceDelete();
            }
            $post->addMedia($request->file('cover_img'))->toMediaCollection('cover_image','my_files');
        }
        if($request->hasFile('post_img')){
            foreach($request->file('post_img') as $file){
                $post->addMedia($file)->toMediaCollection();
            }
        }
        $post->save();
    }
    public function copy_from_post_to_tempppost($post,$temppost):void{
        $coverimages = $post->getMedia('cover_image')->where('deleted_at',null);
        $postimages = $post->getMedia()->where('deleted_at',null);

        //copy cover images
        foreach($coverimages as $coverimage){
            $coverimage->copy($temppost,'temp_cover_image','temp_my_files');
        }

        foreach($postimages as $postimage){
            $postimage->copy($temppost,'temp_post_images','temp_my_post_files');
        }
    }
    public function CreateTempPost($request,$post,$actualPostTime=false,$isSperadmin=false):Temppost{
        //New Temppost
        $temppost = new Temppost();
        $temppost->user_id = $post->user->id;
        $temppost->post_id = $post->id;
        $temppost->title = $request->title;
        $temppost->slug = $request->slug;
        $posttags = $request->tags;
        $temppost->tags = $this->createTempTags($posttags);
        $temppost->preview_content = strip_tags(substr($request->postcontent,0,160));

        $temppost->text_content = $request->postcontent;
        $temppost->post_Status = constant::PENDING;
        $temppost->isApprove = constant::NOT_APPROVE;
        if($actualPostTime){
            $temppost->post_Status = constant::APPROVAL_PENDING;

        }
        if($actualPostTime){
            $temppost->actual_post_time = $request->scheduletime;
        }
        $temppost->save();

        //save images
            //copy images
                $this->copy_from_post_to_tempppost($post,$temppost);
            //store images from request
                if($request->hasFile('cover_img')){
                    foreach($temppost->getMedia('temp_cover_image') as $cover_image){
                        $cover_image->delete();
                    }
                    $temppost->addMedia($request->file('cover_img'))->toMediaCollection('temp_cover_image','temp_my_files');
                }
                if($request->hasFile('post_img')){
                    foreach($request->file('post_img') as $file){
                        $temppost->addMedia($file)->toMediaCollection('temp_post_images
                        ','temp_my_post_files');
                    }
                }
        //end save images
        return $temppost;
    }
    public function publisdrafthpost($request,$post):void{

            //create temppost
            $temppost = $this->CreateTempPost($request,$post);
            //send notification
                $email = Auth::guard()->user()->email;
                event(new sendPublishEvent($temppost,$email));
            //end notification


    }
    public function scheduledraftpost($request,$post):void{

            //create Temppost
                $temppost = $this->CreateTempPost($request,$post,true);
            //send notification
                $email = Auth::guard()->user()->email;
                event(new sendPublishEvent($temppost,$email));
            //end notification

    }
    public function updatePublishedPost($request,$post):void{


            //create Temppost
                $temppost = $this->CreateTempPost($request,$post);
            //send notification
                $email = Auth::guard()->user()->email;
                event(new sendPublishEvent($temppost,$email));
            //end notification


    }
    public function updateRejectedPosts($request,$post,$actualPostTime=false):void{
        $temppost = $this->findTempPost($post->id);
        $temppost->title = $request->title;
        $posttags = $request->tags;
        $temppost->tags = $this->createTempTags($posttags);
        $temppost->post_Status = constant::PENDING;
        if($actualPostTime){
            $temppost->post_Status = constant::APPROVAL_PENDING;
        }
        $temppost->preview_content = strip_tags(substr($request->postcontent,0,200));
        $temppost->text_content = $request->postcontent;
        $temppost->isApprove = constant::NOT_APPROVE;

        $temppost->save();
        if($request->hasFile('cover_img')){
            foreach($temppost->getMedia('temp_cover_image') as $cover_image){
                $cover_image->forceDelete();
            }
            $temppost->addMedia($request->file('cover_img'))->toMediaCollection('temp_cover_image','temp_my_files');
        }
        if($request->hasFile('post_img')){

            foreach($request->file('post_img') as $file){
                $temppost->addMedia($file)->toMediaCollection('temp_post_images
                ','temp_my_post_files');
            }
        }

        $email = Auth::user()->email;
        event(new sendPublishEvent($temppost,$email));

    }
    public function edit($slug){
        $post = $this->findPost($slug);
        $edpost = null;
        if($post==null){
            return $edpost;
        }else{
            if($post->temppost==null){
                $edpost = $post;
            }else{
                $edpost = $this->findTempPost($post->id);
            }
        }
        //will return either Post Object or Temppost Object.
        return $edpost;
    }
    public function update($request,$slug){
        $post = $this->findPost($slug);
        if($post==null){
            return null;
        }else{
            $temppost = $this->findTempPost($post->id);
            if($temppost==null){
                if($request->has('saveasdraft')){
                     $this->updateoriginalpost($request,$post);
                }
                if($request->has('publish')){
                     $this->publisdrafthpost($request,$post);
                }

                if($request->has('schedule')){
                     $this->scheduledraftpost($request,$post);
                }

                if($request->has('update')){
                   $this->updatePublishedPost($request,$post);
                }
            }else{
                if($post->temppost->post_Status==constant::REJECTED){
                    $this->updateRejectedPosts($request,$post);
                }
                if($post->temppost->post_Status==constant::SCHEDULE_REJECTED){
                    $this->updateRejectedPosts($request,$post,true);
                }
            }
        }
    }
    public function delete($slug):bool{
        $post = $this->findPost($slug);
        if($post==null){
            return false;
        }else{
            $temppost = $this->findTempPost($post->id);
            //delete Temppost
                if($temppost!=null){
                    foreach($post->getMedia('temp_post_images') as $postimg){
                        $postimg->forceDelete();
                    }
                    foreach($post->getMedia('temp_cover_image') as $cover_image){
                        $cover_image->forceDelete();
                    }
                    $temppost->delete();
                }
            //end

            //delete original post
                foreach($post->getMedia() as $postimg){
                    $postimg->forceDelete();
                }
                foreach($post->getMedia('cover_image') as $cover_image){
                    $cover_image->forceDelete();
                }
                $post->delete();
            //end
            return true;
        }
    }
    public function viewBlog($slug){
        $post = $this->findPost($slug);
        $temppost = $this->findTempPost($post->id);
        if($temppost==null){
            if($post->post_Status==constant::PENDING){
                return $post;
            }else{
                return null;
            }
        }else{
            if($temppost->post_Status==constant::PENDING || $temppost->post_Status==constant::APPROVAL_PENDING){
                return $temppost;
            }else{
                return null;
            }
        }
    }
    public function Temppost_For_Publish_And_Schedule($post,$actualPostTime=null):Temppost{
        $temppost = new Temppost();
        $temppost->user_id = $post->user->id;
        $temppost->post_id = $post->id;
        $temppost->title = $post->title;
        $temppost->slug = $post->slug;
        $tags = $post->tags;
        $temptags = array("");
        foreach($tags as $tag){
            array_push($temptags,array('name'=>$tag->name,'slug'=>$tag->slug));
        }
        array_shift($temptags);
        $jsontemptags = json_encode($temptags);
        $temppost->tags = $jsontemptags;
        $temppost->preview_content = $post->preview_content;
        $temppost->text_content = $post->text_content;
        $temppost->post_Status = constant::PENDING;
        if($actualPostTime!=null){
            $temppost->actual_post_time = $actualPostTime;
            $temppost->post_Status = constant::APPROVAL_PENDING;
        }
        $temppost->isApprove = 0;
        $temppost->save();

        //copy images from post
            $this->copy_from_post_to_tempppost($post,$temppost);
        return $temppost;
    }
    public function updatepostforsuperadmins($post,$actualPostTime=null){
         $post->post_Status = constant::PUBLISHED;
         if($actualPostTime!=null){
            $post->post_Status = constant::SCHEDULE;
            $post->actual_post_time = $actualPostTime;
         }
         $post->isApprove = constant::APPROVE;
         $post->save();
    }
    public function getUserPosts($id){
        return Post::where(['user_id'=>$id,'post_Status'=>constant::PUBLISHED])->get();
    }
    public function getPublishPost($slug){
        return Post::where(['slug'=>$slug,'post_Status'=>constant::PUBLISHED])->first();
    }
    public function unDelete($post){
        $medias = Media::onlyTrashed()->get();
        foreach($medias as $media){
            if($media->model_id==$post->id && $media->deleted_at!=null){
                $uuid = $media->uuid;
                Media::onlyTrashed()->restore();
            }
        }
    }
}
