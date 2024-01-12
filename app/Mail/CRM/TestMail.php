<?php

namespace App\Mail\CRM;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }


    public function build()
    {
        return $this->subject($this->subject)
            ->text('notification.template', ['template' => $this->message]);
    }
}
