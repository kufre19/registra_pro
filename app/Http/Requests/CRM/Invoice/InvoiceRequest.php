<?php

namespace App\Http\Requests\CRM\Invoice;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Invoice\Invoice;

class InvoiceRequest extends BaseRequest
{
    public function rules()
    {
        return $this->initRules(new Invoice());
    }
}
