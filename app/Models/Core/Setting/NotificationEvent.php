<?php

namespace App\Models\Core\Setting;

use App\Models\Core\BaseModel;
use App\Models\Core\Setting\Traits\NotificationEventRelationship;
use App\Models\Core\Setting\Traits\NotificationEventRules;
use App\Models\Core\Traits\Translate\TranslatedNameTrait;
use Illuminate\Database\Eloquent\Builder;
class NotificationEvent extends BaseModel
{
    protected $appends = ['translated_name'];

    use NotificationEventRelationship, NotificationEventRules,TranslatedNameTrait;


}
