<?php

namespace App\Http\Controllers\CRM\Invoices;

use App\Http\Controllers\Controller;
use App\Jobs\InvoiceAttachmentJob;
use App\Models\CRM\Invoice\Invoice;
use App\Services\CRM\Invoice\InvoiceService;
use Illuminate\Http\Request;

class InvoiceMailController extends Controller
{
    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }


    public function sendInvoice(Request $request, Invoice $invoice)
    {
        $this->service->setSendInvoiceValidation();
        $invoiceInfo = $this->service->getInvoiceInfo($request->invoice_id);

        $this->service
            ->setAttribute('file_path', 'public/pdf/invoice_' . $invoiceInfo->id . '.pdf')
            ->pdfGenerate($invoiceInfo);

        InvoiceAttachmentJob::dispatch($invoiceInfo,$request->email)->onQueue('high');

        return response()->json([
            'status' => true,
            'message' => trans('default.invoice_email_send'),
        ]);
    }


}
