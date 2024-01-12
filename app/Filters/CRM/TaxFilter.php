<?php

namespace App\Filters\CRM;


use App\Filters\Core\BaseFilter;
use App\Filters\Core\traits\NameFilter;
use App\Filters\Core\traits\SearchFilterTrait;

class TaxFilter extends BaseFilter
{
    use NameFilter, SearchFilterTrait;

}
