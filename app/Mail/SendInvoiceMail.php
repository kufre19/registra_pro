<?php

namespace App\Mail;

use App\Mail\Tag\InvoiceTag;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $invoice;
    protected $message;

    public function __construct($invoice,$message)
    {
        $this->invoice = $invoice;
        $this->message = $message;
    }

    public function build(): SendInvoiceMail
    {
        return $this->subject($this->subject)
            ->view('crm.invoices.send-invoice-mail', ['template' => $this->message])
            ->attach(storage_path('app/public/pdf/send_invoice_' . $this->invoice->id . '.pdf'), [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);

    }


}
