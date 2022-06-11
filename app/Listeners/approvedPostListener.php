<?php

namespace App\Listeners;

use App\Models\post;
use App\Models\User;
use App\Events\approvedPostEvent;
use Illuminate\Support\Facades\Auth;
use App\Notifications\okayNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\approvedPostNotification;
use Illuminate\Notifications\Notifiable;

class approvedPostListener implements ShouldQueue
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
     * @param  \App\Events\approvePostEvent  $event
     * @return void
     */
    public function handle(approvedPostEvent $event)
    {
        $fuser = User::where('email',$event->email)->first();

        $event->user->notify(new okayNotification($fuser));
    }
}

