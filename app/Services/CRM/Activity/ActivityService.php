<?php


namespace App\Services\CRM\Activity;


use App\Mail\CRM\Activity\ActivityMail;
use App\Models\CRM\Activity\Activity;
use App\Notifications\CRM\Deal\ActivityNotification;
use App\Services\ApplicationBaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ActivityService extends ApplicationBaseService
{
    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    public function showAllActivities()
    {
        return $this->model->where('status_id','<',5)
            ->with([
                'contextable',
                'activityType:id,name',
                'CreatedBy:id,first_name,last_name',
                'tags:id,name,color_code,taggable_id'
            ])
            ->orderByDesc('created_at')
            ->paginate(
                request('per_page', '15')
            );
    }

    public function getReminderOn()
    {
        $reminder_type = request('reminder_type', '');
        if($reminder_type == 'custom') {
            return request('reminder_on', '');
        } else if($reminder_type == '') {
            return null;
        } else if($reminder_type == '15mins') {
            $started_at = Carbon::createFromFormat('Y-m-d H:i', request('started_at').' '.request('start_time'));
            $started_at->subMinutes(15);
            return $started_at->format('Y-m-d H:i:s');
        } else if($reminder_type == '30mins') {
            $started_at = Carbon::createFromFormat('Y-m-d H:i', request('started_at').' '.request('start_time'));
            $started_at->subMinutes(30);
            return $started_at->format('Y-m-d H:i:s');
        } else if($reminder_type == '1hr') {
            $started_at = Carbon::createFromFormat('Y-m-d H:i', request('started_at').' '.request('start_time'));
            $started_at->subMinutes(60);
            return $started_at->format('Y-m-d H:i:s');
        } else if($reminder_type == '3hrs') {
            $started_at = Carbon::createFromFormat('Y-m-d H:i', request('started_at').' '.request('start_time'));
            $started_at->subMinutes(180);
            return $started_at->format('Y-m-d H:i:s');
        } else if($reminder_type == '1day') {
            $started_at = Carbon::createFromFormat('Y-m-d H:i', request('started_at').' '.request('start_time'));
            $started_at->subDays(1);
            return $started_at->format('Y-m-d H:i:s');
        }
    }

    public function notifyToRecipients() {
        $activity = $this->model;
        $activity->load('participants.email', 'collaborators', 'activityType', 'contextable');

        $participants = $activity->participants->map(function ($participant) {
            if($participant->email->count() > 0) return [
                'email' => $participant->email->first()->value,
                'name' => $participant->name
            ];
        })->toArray();

        foreach($participants as $participant) {
            Mail::to($participant['email'])->send(new ActivityMail(auth()->user(), $activity, 'activity_created', $participant['name']));
        }

        $owner_ids = $activity->collaborators->pluck('id')->toArray();

        $notifiableParticipants = [];
        foreach ($activity->participants as $participant) {
            if($participant->attach_login_user_id) $notifiableParticipants[] = $participant->attach_login_user_id;
        }

        notify()
            ->on('activity_created')
            ->with($activity)
            ->mergeAudiences($owner_ids)
            ->mergeAudiences($notifiableParticipants)
            ->send(ActivityNotification::class);
    }
}
