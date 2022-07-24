<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Temppost;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getTags(){
        $tags = Tag::all();
        $out = "";
        foreach($tags as $tag){
            $out.= "<option value='".$tag['slug']."'>".$tag['slug']."</option>";
        }
        return $out;
    }
    public function geteditTags($slug){
          $post = Post::where('slug',$slug)->first();

          //all tags
          $tabletags = Tag::get('name');
          $ttags = array("");
          foreach($tabletags as $tag){
            array_push($ttags,$tag->name);
           }
            array_shift($ttags);
           //end all tags


          $tags = array("");
          if($post->post_Status==3){
            $usertemppost = json_decode($post->temppost->tags);
            foreach($usertemppost as $tag){

                array_push($tags,$tag->name);
            }
            array_shift($tags);
          }
          else{

            $usertags = $post->tags;
            foreach($usertags as $tag){
                array_push($tags,$tag->name);
            }
            array_shift($tags);
          }
          $out = "";
          foreach($ttags as $tag){
             if(in_array($tag,$tags)){
                $out.= "<option value='".$tag."' selected>".$tag."</option>";
             }else{
                $out.= "<option value='".$tag."'>".$tag."</option>";
             }
          }

          return $out;
    }
    public function getPosts($slug){
        $tag = Tag::where('slug',$slug)->first();
        $posts = $tag->post->where('post_Status',2);
        $oldposts = Temppost::all();
        $oldpostarray = array();
        $texts = array("");
        $oldtexts = array("");

        if(count($oldposts)>0)
        {
            $oldpostarray = array("");
            foreach($oldposts as $oldpost){
                $tags = json_decode($oldpost->tags);
                foreach($tags as  $tag){
                    if($tag->slug==$slug){
                        array_push($oldpostarray,$oldpost);
                    }
                }
                array_shift($oldpostarray);
            }
            foreach($oldposts as $text){
                array_push($oldtexts,substr($text->text_content,0,160));
            }
            array_shift($oldtexts);

        }else{
            $oldposts = array();
            $oldtexts = array();
        }

        if(count($posts)>0){
             foreach($posts as $text){
                 array_push($texts,substr($text->text_content,0,160));
             }
             array_shift($texts);

        }
        // dd([$posts,$texts,$oldtexts,$oldpostarray]);
        return view('blog.tagbase',['posts'=>$posts,'text'=>$texts,'oldposts'=>$oldpostarray,'oldtext'=>$oldtexts]);

    }
}
