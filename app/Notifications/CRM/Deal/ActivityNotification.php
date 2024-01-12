<?php

namespace App\Notifications\CRM\Deal;

use App\Mail\Tag\ActivityTag;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct($templates, $via, $activity)
    {
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $activity;
        $this->auth = auth()->user();
        $this->tag = ActivityTag::class;
        parent::__construct();
    }

    public function parseNotification()
    {
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('activities.lists');

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{activity_title}' => $this->model->title,
            '{app_name}' => config('settings.application.company_name')
        ]);

        $this->databaseNotificationContent = optional($this->template()->database())->parse([
            '{activity_title}' => $this->model->title,
            '{activity_type}' => optional($this->model->activityType)->translated_name,
            '{schedule_start}' => 'schedule_start',
            '{schedule_end}' => 'schedule_end',
        ]);
    }
}
