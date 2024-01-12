<?php


namespace App\Mail\Tag;


use Illuminate\Support\Facades\URL;

class UserTag extends Tag
{
    protected $user;

    public function __construct($user, $notifier = null, $receiver = null)
    {
        $this->user = $user;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl = config('notification.user_front_end_route_name');
    }

    public function passwordReset($token): array
    {
        return array_merge([
            '{receiver_name}' => $this->user->full_name,
            '{reset_password_url}' => URL::signedRoute('reset-password.index', [
                'token' => $token,
                'email' => $this->user->email
            ])
        ], $this->appNameAndLogo());
    }

    public function passwordResetSubject(): array
    {
        return array_merge([
            '{name}' => optional($this->user)->full_name
        ], $this->appNameAndLogo());
    }

    public function userCreate()
    {
        return array_merge([
            '{name}' => optional($this->user)->first_name,
            '{email}' => optional($this->user)->email,
            '{password}' => optional($this->user)->tempPass,
            '{resource_url}' => route('users.login.index'),
            '{action_by}' => $this->notifier->full_name ?? '',
            '{receiver_name}' => $this->receiver->full_name,
        ], $this->appNameAndLogo());
    }

    public function invitation(): array
    {
        return array_merge([
            '{action_by}' => $this->notifier->full_name,
            '{receiver_name}' => $this->user->full_name,
            '{invitation_url}' => URL::signedRoute('user-invite.index', [
                'invitation_token' => $this->user->invitation_token
            ]),
        ], $this->appNameAndLogo());
    }

    public function invitationSubject(): array
    {
        return array_merge([
            '{name}' => optional($this->user)->full_name
        ], $this->appNameAndLogo());
    }

    public function notification(): array
    {
        return array_merge([
            '{name}' => $this->user->full_name,
        ], $this->common());
    }

    public function common()
    {
        return array_merge([
            '{action_by}' => $this->notifier->full_name ?? '',
            '{receiver_name}' => $this->receiver->full_name,
            '{resource_url}' => $this->resourceurl,
        ], $this->appNameAndLogo());
    }

    public function appNameAndLogo(): array
    {
        $logo = config()->get('settings.application.company_logo');
        return [
            '{app_name}' => config('app.name'),
            '{app_logo}' => asset(empty($logo) ? '/images/logo.png' : $logo),
        ];
    }

    public function userCreateSubject()
    {
        return array_merge([
            '{name}' => optional($this->user)->full_name
        ], $this->appNameAndLogo());
    }

}
