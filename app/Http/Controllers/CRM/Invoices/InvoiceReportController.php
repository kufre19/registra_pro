<?php

namespace App\Http\Controllers\CRM\Invoices;

use App\Http\Controllers\Controller;
use App\Models\CRM\Invoice\Invoice;
use App\Services\CRM\Invoice\InvoiceService;
use App\Http\Requests\CRM\Invoice\InvoiceRequest as Request;
use App\Filters\CRM\InvoiceFilter;
use Illuminate\Support\Str;

class InvoiceReportController extends Controller
{
    public function __construct(InvoiceService $invoiceService, InvoiceFilter $invoiceFilter)
    {
        $this->service = $invoiceService;
        $this->filter = $invoiceFilter;
    }

    public function index()
    {
        $order = Str::contains(\Request::get('orderBy'), ['asc', 'desc'])
            ? \Request::get('orderBy')
            : 'desc';

        return $this->service
            ->showAllInvoices()
            ->orderBy('created_at', $order)
            ->filters($this->filter)
            ->paginate(
                request(
                    'per_page',
                    \Request::get('per_page') ?? 15
                )
            );
    }

    public function destroy(Invoice $invoice)
    {
        return $invoice->delete() ? deleted_responses('invoice') : failed_responses();
    }

    public function invoiceBulkDelete()
    {
        if(request()->is_all_selected) Invoice::query()->delete();
        else Invoice::whereIn('id', request()->deletable_ids)->delete();
        return deleted_responses('invoice');
    }

    public function store(Request $request)
    {
        $request['invoice_number'] = $this->service->getNewInvoiceNumber();
        $request['created_by'] = auth()->id();
        return $this->service->saveInvoice($request);
    }

    public function show($id)
    {
         return $this->service->getInvoiceData($id);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($request->all());
        return updated_responses('invoice');
    }

    public function generateInvoicePdf($id)
    {
        return $this->service->downloadPdfInvoice($id);
    }

    public function getInvoiceDealContactPerson($id)
    {
        return $this->service->getContactPersons($id);
    }



}
