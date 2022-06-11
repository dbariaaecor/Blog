<?php

use App\Models\post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\blogController;
use App\Http\Controllers\userController;
use App\Models\tempPost;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {


    //Blog

    Route::get('/home', [blogController::class,'index'])->name('home');
    Route::get('/showblog',[blogController::class,'index'])->name('showallblog');
    Route::get('/createblog',[blogController::class,'create'])->name('createblog');
    Route::post('/store',[blogController::class,'store'])->name('store');
    Route::get('/edit/{slug}',[blogController::class,'edit'])->name('edit');
    Route::post('/update/{slug}',[blogController::class,'update'])->name('update');
    Route::get('/delete/{slug}',[blogController::class,'delete'])->name('delete');
    Route::get('/showblogs/{user}/posts/{slug?}',[blogController::class,'userblogs'])->name('userblogs');

    //User (admin)

    Route::get('/profile',[userController::class,'index'])->name('profile');
    Route::get('/profile/{slug}',[userController::class,'userindex'])->name('userindex');
    Route::get('profiledit/{slug}',[userController::class,'edit'])->name('profileedit');
    Route::post('profileupdate/{slug}',[userController::class,'update'])->name('profileupdate');

    //User (superadmin)

    Route::get('/approve/{slug}/{id}',[userController::class,'approve'])->name('approve');
    Route::get('/approveindex',[userController::class,'approveindex'])->name('approveindex');
    Route::post('/cancel/{slug}/{id}',[userController::class,'cancelApproval'])->name('cancelApproval');
    Route::get('/ap',function(){
        if(User::find(Auth::user()->id)->hasRole('admin')){
            return view('adminapprove');
        }
        abort(403);
    });
});



Route::fallback(function(){
    return view('errorPages.pagenotfound');
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

    */


