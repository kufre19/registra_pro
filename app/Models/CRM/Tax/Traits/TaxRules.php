<?php

namespace App\Models\CRM\Tax\Traits;

trait TaxRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|min:2|max:255|unique:taxes,name',
            'value' => 'required|numeric|min:0'
        ];
    }

    public function updatedRules(): array
    {
        return [
            'name' => 'required|min:2|max:255|unique:taxes,name,'. request()->tax->id,
            'value' => 'required|numeric|min:0'
        ];
    }

}
