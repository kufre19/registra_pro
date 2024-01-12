<?php

namespace App\Filters\CRM;

use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\StatusFilterTrait;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class ExpenseFilter extends FilterBuilder
{
    use StatusFilterTrait, CreatedByFilter, DateFilterTrait;

    public function search($search = null)
    {
        $this->builder
            ->where('name', 'LIKE', "%{$search}%");
    }

    public function expenseArea($expense_area_id = null)
    {
        $this->builder->where('expense_area_id', $expense_area_id);
    }

    public function createdBy($id = null)
    {
        $this->builder->where('created_by', $id);
    }

    public function expenseDate($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        $this->builder->when($date, function (Builder $builder) use ($date){
            $builder->whereBetween(\DB::raw('DATE(expense_date)'), array_values($date));
        });
    }

}
