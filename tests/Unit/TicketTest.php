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

    /** @test */
    function a_ticket_message_can_change_ticket_status()
    {
        $ticket = create('App\Ticket');
        $ticket->addMessage('test', 2);

        $this->assertEquals(2, $ticket->fresh()->status);
    }

    /** @test */
    function ensure_staff_and_admin_listed_to_be_notified_when_a_new_ticket_is_made()
    {
        create('App\User', ['is_staff' => true]);
        $ticket = create('App\Ticket');

        $this->assertCount(1, $ticket->fresh()->usersToNotify());

        create('App\User', ['is_admin' => true]);
        $this->assertCount(2, $ticket->fresh()->usersToNotify());
    }

    /** @test */
    function test_ticket_statuses()
    {
        $ticket = create('App\Ticket')->fresh();

        $this->assertTrue($ticket->isOpen());
        $ticket->close();
        $this->assertTrue($ticket->isClosed());
    }
}
