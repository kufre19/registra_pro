<?php

namespace App\Models\CRM\Expense\Traits;

trait ExpenseRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required',
            'expense_area_id' => 'required',
            'amount' => 'required',
            'attachments' => 'nullable|array',
            'attachments.*' => 'required|max:2048',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'name' => 'required',
            'expense_area_id' => 'required',
            'amount' => 'required',
            'attachments' => 'nullable|array',
            'attachments.*' => 'required|max:2048',
            'remove_attachments' => 'nullable|array',
        ];
    }
}
