<?php

namespace App\Mail;

use App\Mail\Tag\InvoiceTag;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceAttachmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $invoice;


    public $template;

    public function __construct($invoice)
    {
        $tag = new InvoiceTag($invoice);

        $this->invoice = $invoice;

        $template = $this->template();

        $this->template = optional($template)->parse(
            method_exists($tag, 'invoiceGenerate') ? $tag->invoiceGenerate() : ['{invoice_number}' => optional($this->invoice)->invoice_number]
        );

        $this->subject = optional($template)->parseSubject(
            method_exists($tag, 'invoiceGenerate') ? $tag->invoiceGenerate() : ['{invoice_number}' => optional($this->invoice)->invoice_number]
        );
    }

    public function build(): InvoiceAttachmentMail
    {
        return $this->view('notification.mail.user.template', [
            'template' => $this->template
        ])->subject($this->subject)
            ->attach(storage_path('app/public/pdf/invoice_' . $this->invoice->id . '.pdf'), [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);
    }

    public function template(): \App\Models\Core\Notification\NotificationTemplate
    {
        return NotificationTemplateHelper::new()
            ->on('invoice_sending_attachment')
            ->mail();
    }
}
