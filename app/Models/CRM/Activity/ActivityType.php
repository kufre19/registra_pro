<?php

namespace App\Models\CRM\Activity;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Activity\Traits\ActivityTypeRules;
use App\Models\Core\Traits\Translate\TranslatedNameTrait;

class ActivityType extends BaseModel
{
    use ActivityTypeRules, DescriptionGeneratorTrait,TranslatedNameTrait;

    protected $fillable = ['name'];

    protected static $logAttributes = [
        'name'
    ];
    protected $appends = ['translated_name'];

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
