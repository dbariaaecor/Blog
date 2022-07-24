<?php

namespace App\Http\Controllers;

                          #region namespaces
    use App\Http\Classes\Blog;
    use App\Models\Tag;
    use App\Models\Post;
    use App\Models\User;
    use App\Http\Classes\User as Classuser;
    use App\Http\interfaces\constant;
    use App\Mail\RejectMail;
    use App\Models\Temppost;
    use App\Mail\approvedmail;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    use App\Notifications\okayNotification;
    use App\Notifications\cancelBlogNotiication;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
    use App\Http\Requests\author\personal\EditValidation as personal;
    use App\Http\Requests\author\professional\EditValidation as professional;
#endregion namespaces
class UserController extends Controller implements constant{
    public $classuser;
    public $blog;
    public function __construct(Classuser $classuser,Blog $blog){
        $this->classuser = $classuser;
        $this->blog = $blog;
    }
    //logged user profile page
    public function index(){
        return view('author_profile.profile',['user'=>Auth::user()]);
    }
    //users profile
    public function userindex($slug){
        $user = $this->classuser->finduser($slug);
        return view('author_profile.profile',['user'=>$user]);
    }
    //user personal detail edit page
    public function edit($slug){
        $author = $this->classuser->finduser($slug);
        return view('author_profile.editprofile',['author'=>$author]);
    }
    //user personal detail update
    public function update(personal $request,$slug){
        $this->classuser->updatepersonal($request,$slug);
        return redirect(route('user.userindex',Auth::user()->username))->with('message',"Your Profile has been updated!");
    }
    //user professional detail edit page
    public function professionalprofiledit($slug){
        $author =$this->classuser->finduser($slug);
        return view('author_profile.editProfessionalProfile',['author'=>$author]);
    }
    //user professional detail update
    public function professionalprofileupdate(professional $request,$slug){
        $this->classuser->updateProfessional($request,$slug);
        return redirect(route('user.userindex',Auth::user()->username))->with('message',"Your Profile has been updated!");
    }
    //User blog post approve
    public function approve($slug,$id){
        if(User::find(Auth::user()->id)->hasRole('superadmin')){
            $post = $this->blog->findPost($slug);
            if($post==null){
                return view('error_pages.html.pagenotfound');
            }else{

                $temppost = $this->blog->findTempPost($post->id);
                if($temppost==null){

                    //Approve newly created post
                        $post->post_Status = constant::PUBLISHED;
                        $post->save();
                }else{

                    if($temppost->post_Status==constant::PENDING){

                        $this->classuser->updatePost($post,$temppost);
                    }
                    if($temppost->post_Status==constant::APPROVAL_PENDING){
                        $this->classuser->updatePost($post,$temppost,true);
                    }
                }

                  //send notification to user
                  $this->classuser->approvedNotification($post);
                  //End
                  Auth::user()->notifications->where('id',$id)->markAsRead();

                  return redirect(route('notification.approveindex'));
            }
        }
        abort(403);
    }
    //Blog publish notification index
    public function approveindex(){
        if(User::find(Auth::user()->id)->hasRole('superadmin')){
            return view('notification.approve');
        }
        abort(403);
    }
    //Blog cancel notifiction
    public function cancelApproval(Request $request,$slug,$id){
        if(User::find(Auth::user()->id)->hasRole
        ('superadmin')){
            $comment = $request->comment;
            $post = $this->blog->findPost($slug);
            if($post==null){
                return view('error_pages.html.pagenotfound');
            }else{

                $temppost = $this->blog->findTempPost($post->id);
                $this->classuser->updateTemppost($post,$temppost,$comment);
                Auth::user()->notifications->where('id',$id)->markAsRead();
                return true;
            }
        }
        abort(403);
    }
    //Delete Temp post
    public function deleteTempPost($slug){
        $temppost = Temppost::where('slug',$slug)->first();
            foreach($temppost->getMedia('temp_cover_image') as $cover_image){
                $cover_image->forceDelete();
            }
            foreach($temppost->getMedia('temp_post_images') as $postimg){
                $postimg->forceDelete();
            }
        $temppost->delete();
    }
    //download users cv
    public function download($id){
        $mediaid =$this->classuser->getMedia($id);
        $url = $mediaid->getPath();
        $name =  $mediaid->file_name.time();
        return response()->download($url,$name);
    }
}
