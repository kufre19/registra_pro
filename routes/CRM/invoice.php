<?php

/**
 * This route file contains all
 * related routes
 */

use App\Http\Controllers\CRM\Invoices\InvoiceMailController;
use App\Http\Controllers\CRM\Invoices\InvoiceReportController;
use App\Http\Controllers\CRM\Setting\InvoiceSettingController;
use Illuminate\Support\Facades\Route;

// Bulk Actions
Route::get('invoice/{id}/generate-pdf', [InvoiceReportController::class, 'generateInvoicePdf'])->name('invoice.download');
Route::patch('invoice-status-update', [InvoiceReportController::class, 'change_invoice_status'])->name('invoice.change-status');
Route::post('invoice-bulk-delete', [InvoiceReportController::class, 'invoiceBulkDelete'])->name('invoice.bulk-delete');
Route::resource('invoice', InvoiceReportController::class);
Route::get('invoice/{id}/contact_persons', [InvoiceReportController::class, 'getInvoiceDealContactPerson'])->name('get_deal_contact_person.invoice');


Route::get('get-invoice-setting-data',[InvoiceSettingController::class,'getInvoiceSetting'])->name('setting.getInvoiceData');
Route::post('update-invoice-setting-data',[InvoiceSettingController::class,'update'])->name('setting.invoice_update');

Route::post('invoice/{id}/send_email', [InvoiceMailController::class, 'sendInvoice'])->name('sending_attachment_mail.invoice');
