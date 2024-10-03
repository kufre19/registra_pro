<?php


namespace App\Filters\CRM;


use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\CRM\Traits\ContactTypeFilterTrait;
use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\NameFilterTrait;
use App\Filters\CRM\Traits\OwnerFilterTrait;
use App\Filters\CRM\Traits\PhoneFilterTrait;
use App\Filters\CRM\Traits\PublicAccessFilterTrait;
use App\Filters\CRM\Traits\TagsFilterTrait;
use App\Filters\CRM\Traits\NoteFilterTrait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PersonFilter extends UserActivityFilter
{
    use PublicAccessFilterTrait,
        CreatedByFilter,
        ContactTypeFilterTrait,
        OwnerFilterTrait,
        TagsFilterTrait,
        DateFilterTrait,
        PhoneFilterTrait,
        NameFilterTrait,
        NoteFilterTrait;



  

    public function organization($ids = null)
    {
        $organizations = explode(',', $ids);

        $this->builder->when($ids, function (Builder $query) use ($organizations) {
            $query->whereHas('organizations', function (Builder $query) use ($organizations) {
                $query->whereIn('organization_id', $organizations);
            });
        });
    }

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            // Find matching people IDs from the people table
            $peopleIds = DB::table('people')
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('address', 'LIKE', "%$search%")
                ->pluck('id');

            // Find matching people IDs from the phones table
            $phoneIds = DB::table('phones')
                ->where('value', 'LIKE', "%$search%")
                ->pluck('contextable_id');

            // Find matching people IDs from the emails table
            $emailIds = DB::table('emails')
                ->where('value', 'LIKE', "%$search%")
                ->pluck('contextable_id');

            // Combine all matching IDs
            $matchingIds = $peopleIds->concat($phoneIds)->concat($emailIds)->unique();

            // Apply the filter to the main query
            $builder->whereIn('id', $matchingIds);
        });
    }
}
