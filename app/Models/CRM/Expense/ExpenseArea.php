<?php

namespace App\Models\CRM\Expense;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\CRM\Expense\Traits\ExpenseAreaRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseArea extends BaseModel
{
    use HasFactory, CreatedByRelationship,ExpenseAreaRules;

    protected $fillable = [
        'name', 'description', 'created_by'
    ];

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
