<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Jobs\taskjob;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Events\sendPublishEvent;
class task extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'first:task';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send somthing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $posts = Post::where(function ($query){
        //     $query->where('post_Status',6)->orWhere(function($query){
        //         $query->where('post_Status',2)->where('actual_post_time','!=',null);
        //     });
        // })->where(function ($query) {
        //     $query->where('actual_post_time',Carbon::now())
        //            ->orWhere('actual_post_time', '<',Carbon::now());
        // })->get();

        $posts = Post::where('post_Status',5)->where(function ($query) {
             $query->where('actual_post_time',Carbon::now())
                    ->orWhere('actual_post_time', '<',Carbon::now());
         })->get();

        if(count($posts)>0){
            foreach($posts as $post){
                $post->post_Status = 2;
                $post->isApprove = 1;
                $post->save();
            }
            $this->info('published post');
        }
        return "Opertion Done";
    }
}
