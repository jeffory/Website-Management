<?php

namespace tests\Feature;

use App\InvoiceClients;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class InvoiceClientsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_admins_can_access_invoice_client_management()
    {
        $admin = create('App\User', ['is_admin' => true]);

        $this->withExceptionHandling()
            ->get(route('clients.index'))
            ->assertRedirect(route('login'));

        $this->signIn()
            ->get(route('clients.index'))
            ->assertStatus(403);

        $this->signIn($admin)
            ->get(route('clients.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function admins_can_create_new_invoice_clients()
    {
        $this->signIn(create('App\User', ['is_admin' => true]));

        $client = create('App\InvoiceClient');

        $this->post(route('clients.store'), [
            'name' => $client->name,
            'address' => $client->address,
            'city' => $client->city,
            'state' => $client->state,
            'country' => $client->country,
            'postcode' => $client->postcode
        ]);

        $this->assertDatabaseHas('invoice_clients', $client->toArray());
    }
}