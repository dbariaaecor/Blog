<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;

use Illuminate\Support\Facades\Auth;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class okayNotification extends Notification
{
    use Queueable;
    public $user;
    public $title;
    public $slug;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title,$user,$slug)
    {
        $this->user = $user;
        $this->title = $title;
        $this->slug = $slug;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // $message = new MailMessage;
        // // $message = $message->from($this->event->useremail);
        // $message = $message->markdown('emailpages.approvedPost');
        // return $message;
    }



    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title'=>$this->title,
            'slug'=>$this->slug,
            'user'=>Auth::user()->username,
        ];
    }
}
