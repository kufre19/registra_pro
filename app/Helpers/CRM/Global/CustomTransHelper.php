<?php
if (!function_exists('app_trans')) {
    function app_trans($key, $replace=[])
    {
        return trans($key, $replace, app_get_locale());
    }
}
if(!function_exists('app_get_locale')) {
    function app_get_locale()
    {
        return \Cookie::has('user_lang') ? \Cookie::get('user_lang') : config('settings.application.language');
    }
}
