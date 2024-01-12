<?php

namespace App\Mail\CRM\Activity;

use App\Mail\Tag\ActivityTag;
use App\Models\Core\Auth\User;
use App\Models\Core\Notification\NotificationTemplate;
use App\Models\CRM\Activity\Activity;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;
    protected $notifier;

    protected $activity;

    protected $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Activity $activity, $event, $notifier)
    {
        $this->user = $user;
        $this->notifier = $notifier;
        $this->activity = $activity;
        $this->event = $event;
    }

    public function build(): ActivityMail
    {
        $template = $this->template();

        $tag = new ActivityTag($this->activity, $this->user, $this->notifier);

        return $this->view('crm.mail.template', [
            'template' => $template->parse(
                $tag->notification()
            )
        ])->subject($template->parseSubject(
            method_exists($tag, 'activityCreateSubject') ? $tag->activityCreateSubject() :
                ['{activity_title}' => $this->activity->title]
        ));
    }


    public function template(): NotificationTemplate
    {
        return NotificationTemplateHelper::new()
            ->on($this->event)
            ->mail();
    }
}
