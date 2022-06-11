<?php

namespace App\Http\Controllers;

use App\Models\post;
use App\Models\User;
use App\Mail\RejectMail;
use App\Mail\approvedmail;
use App\Mail\cancelBlogMail;
use Illuminate\Http\Request;
use App\Events\approvedPostEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\okayNotification;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\tempPost;
use App\Notifications\cancelBlogNotiication;

class userController extends Controller
{
    public function index(){

        return view('authorprofile',['post'=>Auth::user()]);
    }

    public function userindex($slug){
        $user = User::where('username',$slug)->first();

        return view('authorprofile',['post'=>$user]);
    }
    public function edit($slug){
        $author = User::where(['username'=>$slug])->first();
        return view('editprofile',['author'=>$author]);
    }
    public function update(ProfileUpdateRequest $request,$slug){

        $user = User::where('username',$slug)->first();

        $user->name = $request->name;
        $user->phone = $request->phone;
        $exp = array();
        $tehnology = $request->tehnology;
        $level = $request->level;
        $expreience = $request->expreience;
        $size = count($tehnology);
        for($i=0;$i<$size;$i++){
            array_push($exp,array('technology'=>$tehnology[$i],"level"=>$level[$i],"expreience"=>$expreience[$i]));
        }
        $exp = json_encode($exp);
        $user->experiance = $exp;
        $user->Auth_Description = $request->description;
        $user->save();
        return redirect(route('userindex',Auth::user()->username))->with('message',"Your Profile has been updated!");
    }

    public function approve($slug,$id){

        if(User::find(Auth::user()->id)->hasRole('superadmin')){
            $post = post::where('slug',$slug)->first();
            if(post::where('slug',$slug)->first()->post_Status==4){
                $this->deleteTempPost($slug);
            }
            if(post::where('slug',$slug)->first()->post_Status==1 && tempPost::where('slug',$slug)->first()!=null){
                    $this->deleteTempPost($slug);
            }
            $title = $post->title;
            $post->post_Status = 2;
            $post->isApprove = 1;
            $email = $post->user->email;
            $user = $post->user;
            $post->save();

            $data = array('title'=>$title,'user'=>$user,'from'=>Auth::user()->email);
            $user->notify(new okayNotification($title,$user->username));
            Mail::to($email)->send(new approvedmail($data));
            Auth::user()->notifications->where('id',$id)->markAsRead();
            return redirect(route('approveindex'));
        }
        abort(403);
    }
    public function approveindex(){
        if(User::find(Auth::user()->id)->hasRole('superadmin')){
            return view('approve');
        }
        abort(403);
    }

    public function cancelApproval(Request $request,$slug,$id){
        if(User::find(Auth::user()->id)->hasRole('superadmin')){

            $comment = $request->comment;
            $post = post::where('slug',$slug)->first();
            $temppost = tempPost::where('slug',$slug)->first();
            $post_Status = 3;
            $post->post_Status = $post_Status;
            $post->save();
            $userpost = post::where('slug',$slug)->first();
            $from = Auth::user()->email;
            $data = array('comment'=>$comment,'userpost'=>$userpost,'from'=>$from);
            $userpost->user->notify(new cancelBlogNotiication($comment,$userpost,$from));
            Mail::to($userpost->user->email)->send(new RejectMail($data));
            Auth::user()->notifications->where('id',$id)->markAsRead();
            return true;
        }
        abort(403);
    }
    public function deleteTempPost($slug){
        $temppost = tempPost::where('slug',$slug)->first();

            foreach($temppost->getMedia('temp_cover_image') as $cover_image){
                $cover_image->delete();
            }
            foreach($temppost->getMedia('temp_post_images') as $postimg){
                $postimg->delete();
            }
        $temppost->delete();
    }
}

