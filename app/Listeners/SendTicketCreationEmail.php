<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreationEmail;
use App\Mail\UserCreatedTicket;

class SendTicketCreationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TicketCreated  $event
     * @return void
     */
    public function handle(TicketCreated $event)
    {
        $ticket = $event->ticket;

        Mail::to($ticket->user->email)
            ->queue(new TicketCreationEmail($ticket));

        // Send email to any assigned staff to indicate a ticket was opened
        foreach ($ticket->usersToNotify() as $user) {
            Mail::to($user->email)
                ->queue(new UserCreatedTicket($ticket->user, $ticket));
        }
    }
}
