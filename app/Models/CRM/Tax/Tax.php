<?php

namespace App\Models\CRM\Tax;

use App\Models\Core\BaseModel;
use App\Models\CRM\Tax\Traits\TaxRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends BaseModel
{
    use HasFactory, TaxRules;

    protected $fillable = ['name', 'value'];
}
