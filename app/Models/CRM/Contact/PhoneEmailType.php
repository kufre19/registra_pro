<?php

namespace App\Models\CRM\Contact;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\Core\Traits\Translate\TranslatedNameTrait;

class PhoneEmailType extends BaseModel
{
    use DescriptionGeneratorTrait, TranslatedNameTrait;

    protected $table = 'phone_email_types';

    protected $fillable = ['name', 'class'];

    protected static $logAttributes = [
        'name', 'class'
    ];
}
