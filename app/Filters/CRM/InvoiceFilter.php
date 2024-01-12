<?php

namespace App\Filters\CRM;

use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\StatusFilterTrait;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class InvoiceFilter extends FilterBuilder
{

    use DateFilterTrait,
        StatusFilterTrait;

    //This owner filter is from chaining with deal so should call from OwnerFilterTrait
    public function ownerIs($ids = null)
    {
        if (!empty($ids)) {
            $owner_id = explode(',', $ids);
            return $this->builder->when($ids, function (Builder $builder) use ($owner_id) {
                $builder->whereHas('deal',function ($query) use ($owner_id) {
                    $query->whereIn('owner_id', $owner_id);
                });
            });
        }
        return $this->builder;
    }


    //person filter chain with deal
    public function person($ids = null)
    {
        $persons = explode(',', $ids);

        $this->builder->when($ids, function (Builder $query) use ($persons) {
            $query->whereHas('deal',function ($query) use ($persons) {
                $query->whereHas('contactPerson', function (Builder $query) use ($persons) {
                    $query->whereIn('person_id', $persons);
                });
            });
        });
    }

    public function search($search = null)
    {
        //remove string except int number from search keyword
        $inv_number_only = preg_replace("/[^\d]/", "", $search);
        return $this->builder->when($inv_number_only, function (Builder $builder) use ($inv_number_only) {
            $builder->where('invoice_number', 'LIKE', "%$inv_number_only%");
        });
    }

}
