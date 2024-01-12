<?php

namespace App\Mail;

use App\Mail\Tag\UserTag;
use App\Models\CRM\User\User;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;

    public $auth;

    protected $tempPass;

    public function __construct($user)
    {
        $this->user = $user;
        $this->tempPass = optional($this->user)->tempPass;
        $this->auth = auth()->user();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->user->tempPass = $this->tempPass;

        $template = $this->template();

        $tag = new UserTag($this->user, $this->auth, $this->user);

        return $this->view('crm.mail.template', [
            'template' => $template->parse(
                $tag->userCreate()
            )
        ])->subject($template->parseSubject(
            method_exists($tag, 'userCreateSubject') ? $tag->userCreateSubject() : ['{name}' => $this->user->full_name ?? '']
        ));
    }

    public function template()
    {
        return NotificationTemplateHelper::new()
            ->on('user_create')
            ->mail();
    }
}
