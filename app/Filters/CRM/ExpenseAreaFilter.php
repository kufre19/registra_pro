<?php

namespace App\Filters\CRM;

use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\StatusFilterTrait;
use App\Filters\FilterBuilder;

class ExpenseAreaFilter extends FilterBuilder
{
    use DateFilterTrait,
        StatusFilterTrait;


    public function search($search = null)
    {
        $this->builder
            ->where('name', 'LIKE', "%{$search}%");
    }

}
