<?php

namespace App\Console;

use App\Models\Post;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //  $posts = Post::where('post_Status')->get    ('actual_post_time');
        //  foreach($posts as $post){
        //     $actualtime = $post->actual_post_time;
        //     $year = (int)explode("-",explode(" ",$post->actual_post_time)[0])[0];
        //     $month = (int)explode("-",explode(" ",$post->actual_post_time)[0])[1];
        //     $date = (int)explode("-",explode(" ",$post->actual_post_time)[0])[2];
        //     $hour = (int)explode(":",explode(" ",$post->actual_post_time)[1])[0];
        //     $minute = (int)explode(":",explode(" ",$post->actual_post_time)[1])[1];
        //  }
         $schedule->command('first:task')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
