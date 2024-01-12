<?php

namespace App\Models\CRM\Expense\Traits;

trait ExpenseAreaRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|max:255',
        ];
    }

    public function updatedRules(): array
    {
        return $this->createdRules();
    }
}
