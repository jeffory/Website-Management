<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;
use App\Ticket;

class TicketPermissionsTest extends DuskTestCase
{
    /**
     * Test that a user that is not logged in cannot create a ticket.
     *
     * Browser should be redirected to login page.
     *
     * @return void
     */
    public function testTicketCreationWithoutLoggingIn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('tickets.create'))
                    ->assertPathIs('/login');
        });
    }

    /**
     * Test that a user cannot see another users' ticket.
     *
     * @return void
     */
    public function testUserCannotViewAnotherUsersTicket()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $ticket = factory(Ticket::class)->create([
            'user_id' => $user1
        ]);

        $this->browse(function (Browser $browser) use ($user2, $ticket) {
            $browser->loginAs($user2)
                    ->visit('/client-area/tickets/'. $ticket->id)
                    ->assertSee("This action is unauthorized");
        });

        $ticket->forceDelete();
        $user1->forceDelete();
        $user2->forceDelete();
    }

    /**
     * Test that a admin can see a users ticket.
     *
     * @return void
     */
    public function testAdminCanSeeUsersTicket()
    {
        $admin = factory(User::class)->create([
            'is_admin' => true
        ]);

        $user = factory(User::class)->create();

        $ticket = factory(Ticket::class)->create([
            'user_id' => $user
        ]);

        $this->browse(function (Browser $browser) use ($admin, $ticket) {
            $browser->loginAs($admin)
                    ->visit('/client-area/tickets/'. $ticket->id)
                    ->assertSee($ticket->title);
        });

        $ticket->forceDelete();
        $admin->forceDelete();
        $user->forceDelete();
    }

     /**
     * Test that a unverified user cannot create a ticket.
     *
     * @return void
     */
    public function testUnverifiedUserCannotCreateTicket()
    {
        $user = factory(User::class)->create([
            'is_verified' => false
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/client-area/tickets/create')
                    ->assertSee("This action is unauthorized");
        });

        $user->forceDelete();
    }
}
