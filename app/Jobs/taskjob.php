<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Events\sendPublishEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class taskjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;
    public $email;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($post,$email)
    {
        $this->post= $post;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new sendPublishEvent($this->post,$this->email));
    }
}
