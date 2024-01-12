<?php

namespace App\Models\CRM\Proposal\Traits;

use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\CRM\Contact\ContactType;
use App\Models\CRM\File\File;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Traits\OwnerRelationshipTrait;
use App\Models\CRM\Traits\TagRelationshipTrait;

trait ProposalRelationshipsTrait
{
    use CreatedByRelationship,
        StatusRelationship,
        OwnerRelationshipTrait,
        TagRelationshipTrait;

    /*public function person()
    {
        return $this->belongsTo(Person::class, 'deal_id');
    }*/

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

}
