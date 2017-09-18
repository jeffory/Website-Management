<?php

namespace Tests\Unit;

use App\Invoice;
use App\InvoiceItem;

use App\InvoicePayment;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InvoiceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_calculates_totals_when_invoice_items_are_added_or_removed()
    {
        $invoice = create('App\Invoice');

        $item_1 = create('App\InvoiceItem', ['invoice_id' => $invoice->id]);
        $item_2 = create('App\InvoiceItem', ['invoice_id' => $invoice->id]);

        $this->assertEquals($item_1->total() + $item_2->total(), $invoice->fresh()->total);
        $item_2->delete();

        $this->assertEquals($item_1->total(), $invoice->fresh()->total);
    }

    /** @test */
    function it_calculates_amounts_owing_correctly()
    {
        $invoice = create('App\Invoice');
        $items[] = create('App\InvoiceItem', ['invoice_id' => $invoice->id]);
        $items[] = create('App\InvoiceItem', ['invoice_id' => $invoice->id]);
        $items[] = create('App\InvoiceItem', ['invoice_id' => $invoice->id]);

        $expected_total = 0.00;

        collect($items)->each(function($item) use (&$expected_total) {
            $expected_total += $item->total();
        });

        $payment_1 = InvoicePayment::forceCreate([
            'invoice_id' => $invoice->id,
            'date_paid' => Carbon::now(),
            'amount_paid' => mt_rand(1, $expected_total)
        ]);

        $this->assertEquals($payment_1->amount_paid, $invoice->paymentTotal());
        $this->assertEquals($invoice->fresh()->balanceDue(), $expected_total - $payment_1->amount_paid);
    }


}
