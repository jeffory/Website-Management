<?php

use Modelizer\Selenium\SeleniumTestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Auth;

class TicketMessageTest extends SeleniumTestCase
{
    use DatabaseTransactions;

    /**
     * Create a new ticket, assert its existence.
     *
     * @return void
     */
    public function testBasicMessageCreation()
    {
        $user_password = 'password';

        $user = factory(App\User::class)->create([
                'password' => bcrypt($user_password)
            ]);
        $ticket = factory(App\Ticket::class)->create([
                'user_id' => $user->id
            ]);

        $message = factory(App\TicketMessage::class)->make();

        $this->visit(route('login'))
             ->submitForm('form', [
                'email' => $user->email,
                'password' => $user_password
                ])
             ->wait(2)
             ->visit(route('tickets.show', ['id' => $ticket->id]))
             ->see('Ticket: '. $ticket->title)
             ->type($message->message, 'message')
             ->press('Add new message')
             ->waitForElementsWithClass('ticket-message', 5000)
             ->see($message->message)
             ->visit(route('tickets.show', ['id' => $ticket->id]))
             ->see($message->message);

        $ticket->forceDelete();
    }
}
