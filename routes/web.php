<?php
#region namespaces
use App\Models\Post;
use App\Models\User;
use App\Mail\RejectMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
#endregion namespaces
Route::get('/', [BlogController::class,'welcomepage'])->name('welcomepage');
Route::get('/tags',[TagController::class,'getTags'])->name('tags');
Route::get('/getposts/{slug}',[TagController::class,'getPosts'])->name('getposts');
Route::get('/edittags/{slug}',[TagController::class,'geteditTags'])->name('geteditTags');
Route::get('/download/{id}',[UserController::class,'download'])->name('download');
Route::get('/showblogs/{user}/posts/{slug?}',[BlogController::class,'userblogs'])->name('userblogs');
Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    //Blog
    Route::group(['as'=>'blog.'],function(){
        Route::get('/home', [BlogController::class,'index'])->name('home');
        Route::get('/showblog',[BlogController::class,'index'])->name('showallblog');
        Route::get('/draftshowblog',[BlogController::class,'draftindex'])->name('draftshowallblog');
        Route::get('/createblog',[BlogController::class,'create'])->name('createblog');
        Route::post('/store',[BlogController::class,'store'])->name('store');
        Route::get('/edit/{slug}',[BlogController::class,'edit'])->name('edit');
        Route::post('/update/{slug}',[BlogController::class,'update'])->name('update');
        Route::get('/delete/{slug}',[BlogController::class,'delete'])->name('delete');
        Route::get('/delete/{slug}/{id}',[BlogController::class,'deleteone'])->name('deleteone');
        Route::get('/publish/{slug}',[BlogController::class,'updateStatus'])->name('updateStatus');
        Route::get('/publishnow/{slug}',[BlogController::class,'updateStatusforpublishnow'])->name('publishnow');
        Route::get('/showbloag/post/{slug}',[BlogController::class,'viewblog'])->name('viewblog');
    });
    //User
    Route::group(['as'=>'user.'],function(){
        //USer Profile
        Route::get('/profile',[UserController::class,'index'])->name('profile');
        Route::get('/profile/{slug}',[UserController::class,'userindex'])->name('userindex');
        Route::get('profiledit/{slug}',[UserController::class,'edit'])->name('profileedit');
        Route::get('professionalprofiledit/{slug}',[UserController::class,'professionalprofiledit'])->name('professionalprofiledit');
        Route::post('profileupdate/{slug}',[UserController::class,'update'])->name('profileupdate');
        Route::post('professionalprofilupdate/{slug}',[UserController::class,'professionalprofileupdate'])->name('professionalprofileupdate');
    });
    //User Notification
    Route::group(['as'=>'notification.'],function(){
        //Notifications
        Route::get('/approve/{slug}/{id}',[UserController::class,'approve'])->name('approve');
        Route::get('/approveindex',[UserController::class,'approveindex'])->name('approveindex');
        Route::post('/cancel/{slug}/{id}',[UserController::class,'cancelApproval'])->name('cancelApproval');
        Route::get('/ap',function(){
            if(User::find(Auth::user()->id)->hasRole('admin')){
                return view('notification.adminapprove');
            }
            abort(403);
        });
    });
});
Route::fallback(function(){
    return view('error_pages.html.pagenotfound');
});

//tests


    /*
        Route::get('/post/{id}',function($id){
            $post = post::find($id);
            dd($post->getMedia('cover_image')[0]->getUrl());
        });

        Route::get('each',function(){
            $post = [];
            $post['postimage'] = post::where('id',6)->first()->getMedia();
            $post['cover_image']=post::where('id',6)->first()->getMedia('cover_image');
            dd($post);
        });

        Route::get('file', function () {
            return view('/file');
        });

        Route::post('/update',function(Request $request ){

            return response()->json([$request->all()]);
        })->name('update');

        Route::get('moveimages',function(){
            $post = post::where('user_id',3)->first();

        $temppost = new tempPost;
        $temppost->user_id = $post->user_id;
        $temppost->title = $post->title;
        $temppost->slug = $post->slug;
        $temppost->text_content = $post->text_content;
        $temppost->post_Status = $post->post_Status;
        $temppost->isApprove = $post->isApprove;

        $temppost->save();
        $temppost = tempPost::where('slug',$post->slug)->first();
        $covermediaobject = $post->getMedia('cover_image');
        foreach($covermediaobject as $covermedia){
            $covermedia->copy($temppost,'temp_cover_image','temp_my_files');
        }
        foreach($postmediaobject as $postimgmedia){
            $postimgmedia->copy($temppost,'temp_post_images','temp_my_post_files');
        }

        });
    */
 //posts
    /*
        Route::get('/createPostTitleView',[PostController::class,'createPostTitleView'])->name('createPostTitleView');
        Route::get('/showeblog',[PostController::class,'showone'])->name('showblog');
        Route::post('/CreatePostTitle',[PostController::class,'CreatePostTitle'])->name('CreatePostTitle');
        Route::post('ckeditor/image_upload', [PostController::class, 'postimages'])->name('upload');
        Route::get('/addPostContent/{slug}',[PostController::class,'addPostContent'])->name('addPostContent');
        Route::post('/addPost/{slug}',[PostController::class,'addPost'])->name('addPost');
        Route::post('/addPostImages/{slug}',[PostController::class,'postimages'])->name('appPostImages');
        Route::get('/showComplePost/{slug}',[PostController::class,'showComplePost'])->name('showComplePost');
        Route::get('/editPost/{slug}',[PostController::class,'edit'])->name('editpost');
        Route::post('/updatePost/{slug}',[PostController::class,'update'])->name('updatepost');
        Route::get('/deletePost/{slug}',[PostController::class,'deletepost'])->name('deletepost');

        Route::get('/role',function(){
            dd(Auth::user()->roles[0]['name']=='superadmin');
        });
        */

        Route::get('/send',function(){
            $post = Post::find(30);
            $from = 'superadmin@gmail.com';
            $superadmin = 'superadmin';
            $data = array('comment'=>"write more",'userpost'=>$post,'from'=>$from,'superadmin'=>$superadmin);
            Mail::to($post->user->email)->send(new RejectMail($data));
        });
   Route::get('temppost/{slug}',[UserController::class,'deleteTempPost']);
