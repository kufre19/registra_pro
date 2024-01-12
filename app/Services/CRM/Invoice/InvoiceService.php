<?php


namespace App\Services\CRM\Invoice;

use App\Models\CRM\Invoice\Invoice;
use App\Services\ApplicationBaseService;
use PDF;
use App\Models\Core\Setting\Setting;
use Illuminate\Support\Facades\Storage;

class InvoiceService extends ApplicationBaseService
{
    public function __construct(Invoice $invoice)
    {
        $this->model = $invoice;
    }

    public function showAllInvoices()
    {
        $data =  $this->model;

        //Check if agent
        if (auth()->user()->hasRole('Agent')){
            $data = $data->where('owner_id', auth()->id());

        //Check if Client
        }elseif(auth()->user()->hasRole('Client')){
            $data = $data->whereHas('deal',function ($query){
                  $query->whereHas('contactPerson',function ($q){
                    $q->whereHas('email',function($que){
                        $que->where('value', auth()->user()->email);
                    });
                });
            });
        }

        $data = $data->select(
                'id','total', 'invoice_number', 'quantity', 'price','tax_id',
                'issue_date', 'due_date', 'status_id', 'deal_id'
            )
            ->with([
                'tax:id,name,value',
                'status:id,name,class,type',
                'deal' => function ($q) {
                    $q->select('id', 'title', 'status_id', 'owner_id', 'pipeline_id', 'created_at')
                        ->with([
                            'owner:id,first_name,last_name,email',
                            'contactPerson:id,name'
                        ]);
                }
            ]);

        return $data;
    }

    public function getInvoiceData($id)
    {
        return $this->model->where('id',$id)
            ->select(
                'id','total', 'invoice_number', 'quantity', 'price', 'discount_type','tax_id',
                'discount_amount','note','issue_date', 'due_date', 'status_id', 'deal_id','created_by'
            )
            ->with([
                'tax:id,name,value',
                'status:id,name,class,type',
                'deal' => function ($q) {
                    $q->select('id', 'title','value', 'status_id', 'owner_id', 'pipeline_id',
                        'status_id', 'created_at')
                        ->with([
                            'owner:id,first_name,last_name,email',
                            'contactPerson:id,name'
                        ]);
                },
            ])
            ->first();
    }

    public function saveInvoice($request){
        $this->save();
        return created_responses('invoice');
    }

    public function getNewInvoiceNumber()
    {
        $last_invoice = $this->model::latest()->first();

        if (!empty($last_invoice)){
            $invoice_number = $last_invoice->invoice_number + 1;
        }else{
            $invoice_setting_data = Setting::query()->where('name','invoice_starting_number')->first();
            $invoice_number = $invoice_setting_data->value;
        }

        return $invoice_number;
    }

    public function invoicePrefix()
    {
        $prefix = Setting::query()->where('name','invoice_prefix')->first();
        return $prefix ? $prefix->value : '';

    }

    public function getInvoiceInfo($invoice_id)
    {
        $invoiceInfo = $this->model
            ->where('id', $invoice_id)
            ->with([
                'createdBy:id,first_name,last_name,email',
                'createdBy.profile:id,user_id,gender,address,contact',
                'deal' => function ($q) {
                    $q->select('id', 'title', 'status_id', 'owner_id', 'pipeline_id', 'created_at')
                        ->with([
                            'owner:id,first_name,last_name,email',
                            'contactPerson:id,name,country_id,address',
                            'contactPerson.phone:id,value,contextable_id',
                            'contactPerson.email:id,value,type_id,contextable_id',
                            'contactPerson.country:id,code,name',
                            'contactPerson.organizations:id,name,address,area,state,city,zip_code',
                        ]);
                }
            ])->first();
            $invoiceInfo['invoice_number'] = $this->invoicePrefix().''.$invoiceInfo['invoice_number'];

        return $invoiceInfo;
    }

    // to download pdf invoice instantly
    public function downloadPdfInvoice($id)
    {
        $invoiceInfo = $this->getInvoiceInfo($id);
        $pdf = $this->loadInvoicePdfTemplate($invoiceInfo);
        return $pdf->download('' . $invoiceInfo->invoice_number . '.pdf');
    }

    public function getContactPersons($id)
    {
        $contact_persons = $this->model
            ->where('id', $id)
            ->select('id', 'deal_id')
            ->with([
                'deal' => function ($q) {
                    $q->select('id','created_at')
                        ->with([
                            'contactPerson.email:id,value,contextable_id',
                        ]);
                }
            ])
            ->first();

        return $contact_persons;
    }

    public function setSendInvoiceValidation(): self
    {
        validator(request()->all(),[
           'email'=>'required|email',
           'invoice_id'=>'required'
        ])->validate();

        return $this;
    }

    public function loadInvoicePdfTemplate($invoiceInfo)
    {
        $invoiceInfo->load('tax');
        $pdf = PDF::loadView('invoice.invoice-generate', [
            'invoice' => $invoiceInfo
        ]);

        return $pdf;
    }


    // For sending mail with attachment pdf
    public function pdfGenerate($invoiceInfo): self
    {
        $pdf = $this->loadInvoicePdfTemplate($invoiceInfo);

        $output = $pdf->output();
        $filePath = $this->getAttribute('file_path');
        Storage::put($filePath, $output);
        return $this;
    }

}
