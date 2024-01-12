<?php


namespace App\Mail\Tag;

use App\Repositories\Core\Setting\SettingRepository;
use Carbon\Carbon;

class ActivityTag extends Tag
{
    protected $activity;

    public function __construct($activity, $notifier = null, $receiver = null)
    {
        $this->activity = $activity;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl = route('activities.lists');
    }
    public function notification()
    {
        $settings = resolve(SettingRepository::class)
            ->getFormattedSettings('app');

        $contextable_type = $this->activity->contextable_type;
        $activity_description = $this->activity->description;
        $type_array = explode('\\', $contextable_type);
        $contextable_type = end($type_array);

        $started_at = Carbon::createFromFormat('Y-m-d', $this->activity->started_at);
        $start_time = Carbon::createFromFormat('H:i:s', $this->activity->start_time);

        $ended_at = Carbon::createFromFormat('Y-m-d', $this->activity->ended_at);
        $end_time = Carbon::createFromFormat('H:i:s', $this->activity->end_time);

        return [
            '{receiver_name}' => $this->receiver->full_name ?? ($this->receiver ?? ''),
            '{app_logo}' => asset(config('settings.application.company_logo')),
            '{app_name}' => config('settings.application.company_name'),
            '{activity_title}' => $this->activity->title,
            '{action_by}' => $this->notifier->full_name ?? '',
            '{resource_url}' => $this->resourceurl,
            '{contextable_type}' => $contextable_type,
            '{activity_description}' => $activity_description,
            '{contextable_title}' => $this->activity->contextable->title ?? ($this->activity->contextable->name ?? ''),
            '{activity_type}' => $this->activity->activityType->translated_name ?? '',
            '{start_timeanddate_query}' => "day=".$started_at->format('d')."&month=".$started_at->format('m')."&year=".$started_at->format('Y')."&hour=".$start_time->format('H')."&min=".$start_time->format('i')."&sec=".$start_time->format('s')."&p1=0",
            '{end_timeanddate_query}' => "day=".$ended_at->format('d')."&month=".$ended_at->format('m')."&year=".$ended_at->format('Y')."&hour=".$end_time->format('H')."&min=".$end_time->format('i')."&sec=".$end_time->format('s')."&p1=0",
            '{schedule_start}' =>
                $started_at->format($settings['date_format'])
                .' '.
                $start_time->format($settings['time_format']=='h' ? 'h:i:s A' : 'H:i:s'),
            '{schedule_end}' =>
                $ended_at->format($settings['date_format'])
                .' '.
                $end_time->format($settings['time_format']=='h' ? 'h:i:s A' : 'H:i:s'),
        ];
    }
}
