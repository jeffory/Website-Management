<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\Browser\Pages\TicketPage;

use App\User;
use App\Ticket;

class TicketCreationTest extends DuskTestCase
{
    /**
     * Test that a user can create a ticket with a file attachment.
     *
     * @return void
     */
    public function testTicketCreationWithFile()
    {
        $user = factory(User::class)->create();
        $ticket = factory(Ticket::class)->make();

        $this->browse(function (Browser $browser) use ($user, $ticket) {
            $browser->loginAs($user)
                    ->visit(new TicketPage)
                    ->create(
                        $ticket->title,
                        $ticket->message,
                        '/home/seluser/cheese-01.jpg'
                        // app_path(). 'tests/testfiles/cheese-01.jpg'
                    );

            $ticket = Ticket::where('title', $ticket->title)
                            ->where('user_id', $user->id)
                            ->first();

            $browser->delete($ticket->id);
        });

        $user->forceDelete();
    }

    /**
     * Test that a ticket can be closed and reopened.
     *
     * @return void
     */
    public function testTicketStatusChanges()
    {
        $user = factory(User::class)->create();

        $ticket = factory(Ticket::class)->create([
            'user_id' => $user->id
        ]);

        $ticket = Ticket::where('title', $ticket->title)
                        ->where('user_id', $user->id)
                        ->first();

        // Close the ticket...
        $this->browse(function (Browser $browser) use ($user, $ticket) {
            $browser->loginAs($user)
                    ->visit(new TicketPage)
                    ->addMessage($ticket->id, 'Ticket is complete, thank-you!', true);
        });

        $ticket = $ticket->fresh();
        $this->assertEquals(1, intval($ticket->status));

        // Reopen the ticket...
        $this->browse(function (Browser $browser) use ($user, $ticket) {
            $browser->loginAs($user)
                    ->visit(new TicketPage)
                    ->addMessage($ticket->id, 'Nope, ticket isn\'t complete!', true);
        });

        $ticket = $ticket->fresh();
        $this->assertEquals(0, intval($ticket->status));

        $ticket->forceDelete();
        $user->forceDelete();
    }
}
