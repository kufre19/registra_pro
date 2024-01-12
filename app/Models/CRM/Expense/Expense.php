<?php

namespace App\Models\CRM\Expense;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\CRM\Expense\Traits\ExpenseRules;
use App\Models\CRM\File\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends BaseModel
{
    use HasFactory, CreatedByRelationship, ExpenseRules;

    protected $fillable = [
        'expense_area_id', 'name', 'amount', 'description', 'expense_date', 'created_by'
    ];

    public function expenseArea(): BelongsTo
    {
        return $this->belongsTo(ExpenseArea::class);
    }

    public function attachments()
    {
        return $this->morphMany(File::class, 'fileable')
            ->whereType('expense');
    }
}
