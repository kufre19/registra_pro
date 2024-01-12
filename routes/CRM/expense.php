<?php

/**
 * This route file contains all
 * related routes
 */

use App\Http\Controllers\CRM\Expense\AreaOfExpenseController;
use App\Http\Controllers\CRM\Expense\ExpenseController;
use Illuminate\Support\Facades\Route;

// Bulk Actions
Route::resource('expense', ExpenseController::class);
Route::resource('expense-area', AreaOfExpenseController::class);

