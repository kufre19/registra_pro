<?php

namespace App\Services\CRM\Expense;

use App\Models\CRM\Expense\ExpenseArea;
use App\Services\ApplicationBaseService;

class ExpenseAreaService extends ApplicationBaseService
{
    public function __construct(ExpenseArea $expenseArea)
    {
        $this->model = $expenseArea;
    }


}
