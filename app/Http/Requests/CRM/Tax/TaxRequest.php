<?php

namespace App\Http\Requests\CRM\Tax;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Tax\Tax;
use function Symfony\Component\Translation\t;

class TaxRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new Tax());
    }
}
