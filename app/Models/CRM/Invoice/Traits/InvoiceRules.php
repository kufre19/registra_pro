<?php


namespace App\Models\CRM\Invoice\Traits;


trait InvoiceRules
{
    public function createdRules()
    {
        return [
            'quantity' => 'required',
            'price' => 'required',
            'status_id' => 'required',
            'total' => 'required',
            'issue_date' => 'required',
            'due_date' => 'required',
            'deal_id' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
