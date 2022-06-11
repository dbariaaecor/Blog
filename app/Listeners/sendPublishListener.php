<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\sendPublishEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\sendPublishNotification;
use Illuminate\Support\Facades\Notification;

class sendPublishListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\sendPublicEvent  $event
     * @return void
     */
    public function handle(sendPublishEvent $event)
    {
          $users = User::role('superadmin')->get();
          foreach($users as $user){
             Notification::send($user,new sendPublishNotification($event));
          }
    }
}

