<?php

namespace App\Http\Classes;
#region namespaces
    use App\Models\Tag;
    use App\Models\Post;
    use App\Mail\RejectMail;
    use App\Models\Temppost;
    use App\Mail\approvedmail;
    use App\Http\interfaces\constant;
    use App\Models\User as Modeluser;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    use App\Notifications\okayNotification;
    use App\Notifications\cancelBlogNotiication;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
#endregion namespaces
class User implements constant{
    public $blog;
    public function __construct(Blog $blog){
        $this->blog = $blog;
    }
    public function findUser($userSlug){
        return Modeluser::where('username',$userSlug)->first();
    }
    public function islogin():bool{
        return Auth::check();
    }
    public function isUser($userSlug):bool{
        return Auth::user()->username == $userSlug;
    }
    public function updatepersonal($request,$slug){
        $user =$this->finduser($slug);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->Auth_Description = $request->description;
        $user->save();
    }
    public function expreience($request){
        $exp = array();
        $tehnology = $request->tehnology;
        $level = $request->level;
        $expreience = $request->expreience;
        $des = $request->desc;
        $size = count($tehnology);
        for($i=0;$i<$size;$i++){
            array_push($exp,array('technology'=>$tehnology[$i],"level"=>$level[$i],"expreience"=>$expreience[$i],'des'=>$des));
        }
        $exp = json_encode($exp);
        return $exp;
    }
    public function updateProfessional($request,$slug){
        $user = $this->findUser($slug);
        $user->experiance = $this->expreience($request);
        if($request->hasFile('cvfile')){
            if($user->cv!=null){
                $mediaid = $user->getMedia('cv')[0]->uuid;
                $media = Media::where('uuid',$mediaid)->first();
                $user->deleteMedia($media->id);
                $user->addMedia($request->file('cvfile'))->toMediaCollection('cv','file');
             }else{
                $user->addMedia($request->file('cvfile'))->toMediaCollection('cv','file');
             }
        }
        $user->save();
    }
    public function jsonTagsToPost($jsonTags){
        $tempTags = json_decode($jsonTags);
        $slugtags = array(" ");
        foreach($tempTags as $tempTag){
            array_push($slugtags,$tempTag->slug);
        }
        array_shift($slugtags);
        $tags = Tag::whereIn('slug',$slugtags)->get('id');
        $ids = array(" ");
        foreach($tags as $tag){
            array_push($ids,$tag->id);
        }
        array_shift($ids);
        return $ids;
    }
    public function copy_from_Temppost_images_to_Post(Post $post,Temppost $temppost ){
        //get Temp images
        $covermediaobject = $temppost->getMedia('temp_cover_image');
        $postmediaobject = Media::where([ "conversions_disk" => "temp_my_post_files","model_id" => $temppost->id])->get();
        //copy images
        foreach($covermediaobject as $covermedia){
            $covermedia->copy($post,'cover_image','my_files');
        }
        foreach($postmediaobject as $postimgmedia){
            $postimgmedia->copy($post);
        }

    }
    public function deleteTempImages(Temppost $temppost){
        $covermediaobject = $temppost->getMedia('temp_cover_image');
        $postmediaobject = Media::where([ "conversions_disk" => "temp_my_post_files","model_id" => $temppost->id])->get();
        foreach($covermediaobject as $coverimages){
            $coverimages->forceDelete();
        }

        foreach($postmediaobject as $postImages){
            $postImages->forceDelete();
        }
    }
    public function updatePost(Post $post,Temppost $temppost,bool $isSchedule=false){
        //copy temppost to post
            $post->title = $temppost->title;
            $post->preview_content = $temppost->preview_content;
            $post->text_content = $temppost->text_content;
            $jsontemptags = $temppost->tags;
            $post->tags()->sync($this->jsonTagsToPost($jsontemptags));
            $post->post_Status = constant::PUBLISHED;
            if($isSchedule){
                $post->post_Status = constant::SCHEDULE;
            }
            $post->isApprove = constant::APPROVE;
            foreach($post->getMedia('cover_image') as $cov){
                $cov->forceDelete();
            }
            foreach($post->getMedia() as $img){
                $img->forceDelete();
            }

            //copy temp images to post
                $this->copy_from_Temppost_images_to_Post($post,$temppost);
            //end copy images
            $post->save();
        //end copy temppost

        //delete temppost
            //delete temp images
                $this->deleteTempImages($temppost);
            //end delete temp images
            $temppost->delete();
        //end delete post
    }
    public function approvedNotification($post){
        $title = $post->title;
        $slug = $post->slug;
        $user = $post->user;
        $email = $post->user->email;
        $data = array('title'=>$title,'slug'=>$slug,'user'=>$user,'from'=>Auth::user()->email,'superadmin'=>Auth::user()->username);
        $user->notify(new okayNotification($title,$user->username,$slug));
        Mail::to($email)->send(new approvedmail($data));
    }
    public function cancelNotification(Temppost $temppost,$comment){
        $userpost = $temppost;
        $from = Auth::user()->email;
        $superadmin = Auth::user()->username;
        $data = array('comment'=>$comment,'userpost'=>$userpost,'from'=>$from,'superadmin'=>$superadmin);
        $userpost->user->notify(new cancelBlogNotiication($comment,$userpost,$superadmin));
        Mail::to($userpost->user->email)->send(new RejectMail($data));

    }
    public function updateTemppost(Post $post,$temppost,$comment){
        $temppost = $temppost;

        if($temppost==null){

            $post_Status = constant::REJECTED;
            $post->reson =  $comment;
            $post->post_Status = $post_Status;
            $post->save();
            $temppost = new Temppost();
            $temppost->user_id = $post->user->id;
            $temppost->post_id = $post->id;
            $temppost->title = $post->title;
            $temppost->slug = $post->slug;
            $tags = array("");
            foreach($post->tags as $tag){
                array_push($tags,array("name"=>$tag->name,'slug'=>$tag->slug));
            }
            array_shift($tags);
            $temppost->tags = json_encode($tags);
            $temppost->preview_content = $post->preview_content;
            $temppost->text_content = $post->text_content;
            $temppost->post_Status = $post->post_Status;
            $temppost->isApprove = $post->isApprove;
            $temppost->save();
             //copy images
             if($temppost){
                $this->blog->copy_from_post_to_tempppost($post,$temppost);
            }
        //end copy images
        $this->cancelNotification($temppost,$comment);
        }else{

            if($temppost->post_status==constant::APPROVAL_PENDING){
                $temppost->post_Status = constant::SCHEDULE_REJECTED;
            }else{
                $temppost->post_Status = constant::REJECTED;
            }
            $temppost->save();
            $this->cancelNotification($temppost,$comment);
        }

    }
    public function getMedia($id){
        return Media::where('uuid',$id)->first();
    }
}
