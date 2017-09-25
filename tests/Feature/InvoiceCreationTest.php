<?php

namespace Tests\Feature;

use App\Invoice;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class InvoiceCreationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_invoices()
    {
        $this->withExceptionHandling()
            ->get('/client-area/invoice/create')
            ->assertRedirect('/login');

        $this->post('/client-area/invoice')
            ->assertRedirect('/login');
    }

    /** @test */
    function normal_users_may_not_create_invoices()
    {
        $this->signIn()
            ->withExceptionHandling()
            ->get('/client-area/invoice/create')
            ->assertStatus(403);

        $this->post('/client-area/invoice')
            ->assertStatus(403);
    }

    /** @test */
    function normal_users_can_view_an_invoice_with_a_token()
    {
        $invoice = create('App\Invoice');
        create('App\InvoiceItem', ['invoice_id' => $invoice->id]);
        create('App\InvoicePayment', ['invoice_id' => $invoice->id]);

        $this->get(route('invoice.generate_pdf', [
            'invoice_id' => $invoice->id,
            'view_key' => $invoice->view_key
        ]));
    }

    /** @test */
    function admin_users_can_create_invoices()
    {
        $user = create('App\User', ['is_admin' => true]);

        $this->signIn($user)
            ->get('/client-area/invoice/create')
            ->assertSuccessful();

        $invoice = $this->get_fake_invoice_with_items();

        $this->post(route('invoice.store'), $invoice->toArray());

        $this->assertTrue(Invoice::where([
            'client_id' => $invoice['client_id'],
            'note' => $invoice['note']
        ])->exists());
    }

    /** @test */
    function check_new_invoice_has_necessary_details()
    {
        $user = create('App\User', [
            'is_verified' => true,
            'is_admin' => true
        ]);

        $invoice = $this->get_fake_invoice_with_items(3);
        $expected_total = $this->get_expected_total($invoice);

        $this->signIn($user)
            ->withExceptionHandling()
            ->post('/client-area/invoice', $invoice->toArray())
            ->assertSessionHas('flash.level', 'success');

        $this->assertDatabaseHas('invoice_items', [
            'description' => $invoice->description[0],
            'quantity' => $invoice->quantity[0],
            'cost' => $invoice->cost[0],
        ]);

        $this->assertDatabaseHas('invoices', [
            'client_id' => $invoice->client_id,
            'days_until_due' => $invoice->days_until_due,
            'note' => $invoice->note,
            'total' => $expected_total,
            'owing' => $expected_total
        ]);

        $invoice = Invoice::first();
        $this->assertNotNull($invoice->fresh()->view_key);
    }

    /** @test */
    function blank_lines_are_trimmed_from_invoices()
    {
        $invoice = $this->get_fake_invoice_with_items(3)->toArray();

        $invoice['description'][] = '';
        $invoice['description'][] = '';
        $invoice['quantity'][] = '';
        $invoice['cost'][] = '';
        $invoice['cost'][] = '';
        $invoice['cost'][] = '';

        $this->signIn(create('App\User', ['is_admin' => true]))
            ->post('/client-area/invoice', $invoice)
            ->assertSessionHas('flash.level', 'success');

        $invoice = Invoice::first();
        $this->assertEquals(3, $invoice->items->count());
    }

    /** @test */
    function ensure_invoice_pdf_is_generated_without_errors()
    {
        $invoice = create('App\Invoice');
        $item = create('App\InvoiceItem', ['invoice_id' => $invoice->id]);
        $payment = create('App\InvoicePayment', ['invoice_id' => $invoice->id]);

        $this->signIn(create('App\User'));
        $this->get(route('invoice.generate_pdf', ['invoice_id' => $invoice->id, 'view_key' => $invoice->view_key]));
    }

    /** Helper function */
    public function get_fake_invoice_with_items($itemCount = 1)
    {
        $invoice = make('App\Invoice');

        $invoice->description = [];
        $invoice->quantity = [];
        $invoice->cost = [];

        make('App\InvoiceItem', [], $itemCount)->each(function ($newItem) use (&$invoice) {
            // The magic methods on the model mess with array_push
            $invoice->description = array_merge($invoice->description, [$newItem->description]);
            $invoice->quantity = array_merge($invoice->quantity, [$newItem->quantity]);
            $invoice->cost = array_merge($invoice->cost, [$newItem->cost]);
        });

        return $invoice;
    }

    /** Helper function */
    public function get_expected_total($invoice)
    {
        $expected_total = 0.00;

        for ($i = 0; $i < count($invoice->cost); $i++) {
            $expected_total += $invoice->cost[$i] * $invoice->quantity[$i];
        }

        return $expected_total;
    }

}
