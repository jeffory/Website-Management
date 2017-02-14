<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketCreationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create a new ticket, assert its existence.
     *
     * @return void
     */
    public function testBasicTicketCreation()
    {
        $user = App\User::where('email', 'keef05@gmail.com')->first();

        $title = 'This is my title';
        $message = 'This is my message';

        $this->actingAs($user)
             ->visit('tickets/create')
             ->see('Create a new Ticket')
             ->type($title, 'title')
             ->type($message, 'message')
             ->press('Create');

        $new_ticket = App\Ticket::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->with('messages')
                        ->first();

        $this->assertEquals($title, $new_ticket->title);
        $this->assertEquals($message, $new_ticket->messages[0]->message);
    }
}
