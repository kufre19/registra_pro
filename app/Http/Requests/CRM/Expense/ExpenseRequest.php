<?php

namespace App\Http\Requests\CRM\Expense;

use App\Http\Requests\BaseRequest;
use App\Models\CRM\Expense\Expense;

class ExpenseRequest extends BaseRequest
{
    public function rules(): array
    {
        return $this->initRules( new Expense());
    }
}
