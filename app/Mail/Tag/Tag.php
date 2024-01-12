<?php


namespace App\Mail\Tag;


abstract class Tag
{
    protected $notifier;

    protected $receiver;

    protected $resourceurl;

    abstract function notification();


    public function appNameAndLogo()
    {
        $logo = config()->get('settings.application.company_logo');
        return [
            '{app_name}' => config('app.name'),
            '{app_logo}' => asset(empty($logo) ? '/images/logo.png' : $logo),
        ];
    }
}
