<?php

namespace App\Jobs;

use App\Mail\CRM\Activity\ActivityMail;
use App\Models\CRM\Activity\Activity;
use App\Notifications\CRM\Deal\ActivityNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ActivityReminderNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activities = Activity::where('reminder_on', '>', Carbon::now()->subMinute())
            ->where('reminder_on', '<=', Carbon::now())
            ->with('participants.email', 'collaborators', 'activityType', 'contextable', 'createdBy')
            ->get();

        foreach($activities as $activity) {
            $participants = $activity->participants->map(function ($participant) {
                if($participant->email->count() > 0) return [
                    'email' => $participant->email->first()->value,
                    'name' => $participant->name
                ];
            })->toArray();

            foreach($participants as $participant) {
                Mail::to($participant['email'])->send(new ActivityMail($activity->createdBy, $activity, 'activity_reminder', $participant['name']));
            }

            $notifiableParticipants = [];
            foreach ($activity->participants as $participant) {
                if($participant->attach_login_user_id) $notifiableParticipants[] = $participant->attach_login_user_id;
            }

            notify()
                ->on('activity_reminder')
                ->with($activity)
                ->audiences($activity->collaborators->pluck('id')->toArray())
                ->mergeAudiences($activity->created_by)
                ->mergeAudiences($notifiableParticipants)
                ->send(ActivityNotification::class);

        }
    }
}
