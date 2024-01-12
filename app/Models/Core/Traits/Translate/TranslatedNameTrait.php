<?php


namespace App\Models\Core\Traits\Translate;

trait TranslatedNameTrait
{
    public function getTranslatedNameAttribute()
    {
        $value = \Str::lower($this->attributes['name']);
        return app_trans("default.{$value}");
    }

}
