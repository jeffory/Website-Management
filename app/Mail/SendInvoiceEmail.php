<?php

namespace App\Mail;

use App\Helpers\PDFView;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Invoice;

class SendInvoiceEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice->load('client', 'items');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = PDFView::output('invoice.show', [
            'invoice' => $this->invoice
        ]);

        return $this->markdown('emails.invoice.send', [
            'invoice' => $this->invoice
        ])->attachData($pdf, "invoice_{$this->invoice->id}.pdf", [
            'mime' => 'application/pdf',
        ]);
    }
}
