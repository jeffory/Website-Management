<?php

namespace Tests\Feature;

use App\Invoice;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\TestCase;

class InvoiceCreationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_invoices()
    {
        $this->withExceptionHandling()
            ->get('/client-area/invoice/create')
            ->assertRedirect('/login');

        $this->post('/client-area/invoice')
            ->assertRedirect('/login');
    }

    /** @test */
    public function normal_users_may_not_create_invoices()
    {
        $this->signIn()
            ->withExceptionHandling()
            ->get('/client-area/invoice/create')
            ->assertStatus(403);

        $this->post('/client-area/invoice')
            ->assertStatus(403);
    }

    /** @test */
    public function admin_users_can_create_invoices()
    {
        $user = create('App\User', ['is_admin' => true]);

        $this->signIn($user)
            ->withExceptionHandling()
            ->get('/client-area/invoice/create')
            ->assertSuccessful();

        $invoice = make('App\Invoice')->toArray();

        // For the form request the fields need to be arrays even if single valued.
        $item = array_map(function($key) {
                return Array($key);
            }, make('App\InvoiceItem')->toArray());

        $this->withExceptionHandling()
            ->post('/client-area/invoice', array_merge($invoice, $item))
            ->assertSessionHas('flash.level', 'success');
    }

    /** @test */
    public function check_new_invoice_has_details()
    {
        $user = create('App\User', [
            'is_verified' => true,
            'is_admin' => true
        ]);

        // For the form request the fields need to be arrays even if single valued.
        $item = array_map(function($key) {
            return Array($key);
        }, make('App\InvoiceItem')->toArray());

        $invoice = array_merge(make('App\Invoice')->toArray(), $item);

        $expectedTotal = 0.00;

        for ($i = 0; $i < count($invoice['quantity']); $i++) {
            $expectedTotal = $expectedTotal + floatval($invoice['quantity'][$i]) * floatval($invoice['cost'][$i]);
        }

        $expectedTotal = round($expectedTotal, 2);

        $this->signIn($user)
            ->withExceptionHandling()
            ->post('/client-area/invoice', $invoice)
            ->assertSessionHas('flash.level', 'success');

        $invoice = Invoice::latest()
            ->first()
            ->load('items');

        $this->assertCount(1, $invoice->fresh()->items);
        $this->assertEquals($expectedTotal, $invoice->fresh()->calculateTotal());
    }
}
