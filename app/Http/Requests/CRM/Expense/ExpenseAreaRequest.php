<?php

namespace App\Http\Requests\CRM\Expense;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Expense\ExpenseArea;

class ExpenseAreaRequest extends BaseRequest
{

    public function rules(): array
    {
        return $this->initRules( new ExpenseArea());
    }

}
