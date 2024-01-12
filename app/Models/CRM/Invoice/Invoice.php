<?php

namespace App\Models\CRM\Invoice;

use App\Models\Core\Auth\User;
use App\Models\Core\BaseModel;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Invoice\Traits\InvoiceRules;
use App\Models\CRM\Tax\Tax;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends BaseModel
{
    use HasFactory,
        StatusRelationship,
        CreatedByRelationship,
        InvoiceRules;

    protected $dates = ['issue_date','due_date'];

    protected $fillable = [
        'invoice_number',
        'quantity',
        'price',
        'total',
        'tax_id',
        'issue_date',
        'due_date',
        'status_id',
        'owner_id',
        'deal_id',
        'created_by',
        'discount_type',
        'discount_amount',
        'note',
    ];

    protected $casts = ['deal_id' => 'int'];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function tax(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

}
