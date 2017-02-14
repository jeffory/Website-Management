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

        $this->visit('/login')
             ->submitForm('form', [
                'email' => $user->email,
                'password' => $user_password
                ])
             ->visit('tickets/'. $ticket->id)
             ->see('Ticket: '. $ticket->title)
             ->type($message->message, 'message')
             ->press('Add new message')
             ->visit('tickets/'. $ticket->id)
             ->see($message->message);
    }
}
