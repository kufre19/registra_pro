<?php

namespace App\Services\CRM\Tax;

use App\Models\CRM\Tax\Tax;
use App\Services\ApplicationBaseService;

class TaxService extends ApplicationBaseService
{
    public function __construct(Tax $tax)
    {
        $this->model = $tax;
    }


}
