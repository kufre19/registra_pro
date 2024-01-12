<?php

namespace App\Jobs;

use App\Mail\InvoiceAttachmentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice;
    protected $email;

    public function __construct($invoice, $email)
    {
        $this->invoice = $invoice;
        $this->email = $email;
    }


    public function handle()
    {
        Mail::to($this->email)
            ->send(
                (new InvoiceAttachmentMail($this->invoice)));

        Storage::delete('public/pdf/invoice_' . $this->invoice->id . '.pdf');
    }
}
