<?php

namespace tests\Feature;

use App\Invoice;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class InvoicePaymentsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function normal_users_cannot_add_payments()
    {
        $this->signIn()
            ->withExceptionHandling()
            ->post(route('invoice.payment_store', 1), [])
            ->assertStatus(403);
    }
}