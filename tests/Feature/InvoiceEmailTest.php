<?php

namespace Tests\Feature;

use App\Mail\SendInvoiceEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvoiceEmailTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function invoices_can_be_emailed_to_clients_with_attached_pdf()
    {
        $this->signIn(create('App\User', ['is_admin' => true]));
        $invoice = create('App\Invoice');

        Mail::fake();

        $this->get(route('invoice.send', $invoice));

        Mail::assertSent(SendInvoiceEmail::class, function ($mail) use ($invoice) {
            return $mail->hasTo($invoice->client->email) &&
                $mail->invoice->id === $invoice->id;
        });
    }
}