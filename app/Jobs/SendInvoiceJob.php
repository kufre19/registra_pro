<?php

namespace App\Jobs;

use App\Mail\SendInvoiceMail;
use App\Models\CRM\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $invoice;
    protected $email;
    public $message;

    public function __construct($invoice,$message, $email)
    {
        $this->invoice = $invoice;
        $this->email = $email;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)
            ->send(
                (new SendInvoiceMail($this->invoice, $this->message))
                    ->onQueue('high')
            );
        Storage::delete('public/pdf/send_invoice_' . $this->invoice->id . '.pdf');

    }
}
