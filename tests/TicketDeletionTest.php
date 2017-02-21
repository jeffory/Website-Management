<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ticket;
use App\TicketMessage;
use App\TicketFile;

class TicketDeletionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that messsages are deleted when their parent ticket is deleted.
     *
     * @return void
     */
    public function testTicketDeletionCleanup()
    {
        $ticket = factory(Ticket::class)->create([
            'user_id' => App\User::first()->id
        ]);

        $message_1 = factory(TicketMessage::class)->create([
            'ticket_id' => $ticket->id,
            'user_id' => App\User::first()->id
        ]);

        $message_2 = factory(TicketMessage::class)->create([
            'ticket_id' => $ticket->id,
            'user_id' => App\User::first()->id
        ]);

        $file = factory(TicketFile::class)->create([
            'ticket_id' => $ticket->id,
            'ticket_message_id' => $message_1->id,
            'user_id' => App\User::first()->id
        ]);

        $ticket->delete();

        $this->assertEquals(0, TicketMessage::where('id', $message_1->id)->count());
        $this->assertEquals(0, TicketMessage::where('id', $message_2->id)->count());
        $this->assertEquals(0, TicketFile::where('id', $file->id)->count());
    }
}
