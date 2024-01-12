<?php

use App\Http\Controllers\CRM\Tax\TaxController;
use Illuminate\Support\Facades\Route;

Route::resource('tax', TaxController::class);
