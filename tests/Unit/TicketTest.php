<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function no_invoice_message_created_if_given_null_or_blank_string()
    {
        $ticket = make('App\Ticket');
        $ticket->message = null;
        $ticket->save();

        $this->assertCount(0, $ticket->fresh()->messages);
    }

    /** @test */
    function a_new_ticket_will_also_create_first_message_if_data_is_included()
    {
        $ticket = make('App\Ticket');
        $ticket->message = 'Hello world';
        $ticket->save();

        $this->assertCount(1, $ticket->fresh()->messages);
        $this->assertEquals('Hello world', $ticket->fresh()->messages[0]->message);
    }



}
