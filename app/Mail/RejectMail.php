<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data = array("");
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)

    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->data['from'])->view('email_pages.html.RejectedBlog')->with('data',$this->data);
    }
}
