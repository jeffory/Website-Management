<?php

namespace tests\Feature;

use App\Invoice;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class InvoicePaymentsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function check_payments_update_invoice_total()
    {
        $user = create('App\User', [
            'is_verified' => true,
            'is_admin' => true
        ]);

        $items = [];

        $invoice = create('App\Invoice');
        $item = create('App\InvoiceItem', ['invoice_id' => $invoice->id]);

        $this->assertEquals($item->total(), $invoice->fresh()->total);

    }
}