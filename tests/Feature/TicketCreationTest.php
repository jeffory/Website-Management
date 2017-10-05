<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketCreationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_tickets()
    {
        $this->withExceptionHandling()
            ->get(route('tickets.create'))
            ->assertRedirect('/login');

        $this->post(route('tickets.store'))
            ->assertRedirect('/login');
    }

    /** @test */
    function unverified_users_may_not_create_tickets()
    {
        $ticket = make('App\Ticket')->toArray();

        $this->signIn(create('App\User', ['is_verified' => false]));

        $this->withExceptionHandling()
            ->get(route('tickets.create'))
            ->assertStatus(403);

        $this->post(route('tickets.store'), $ticket)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tickets', $ticket);
    }

    /** @test */
    function test_file_attachments_can_be_uploaded()
    {
        $this->signIn();

        Storage::fake('public');

        $response = $this->json('POST', route('tickets.file_upload'), [
            'upload_file' => UploadedFile::fake()->image('image.png', 600, 600)
        ])->json();

        Storage::disk('public')->assertExists($response['path']);
    }

    /** @test */
    function a_ticket_can_be_created_with_an_attachment()
    {
        $this->signIn();
        $ticket = make('App\Ticket')->toArray();

        $this->post(route('tickets.file_upload'), [
            'upload_file' => UploadedFile::fake()->image('image.png', 600, 600)
        ]);

        $this->post(route('tickets.store'), $ticket);
    }
}
