<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Ticket;

class UserCreatedTicket extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $ticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Ticket $ticket)
    {
        $this->user = $user;
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ticket.user-created', [
            'user' => $this->user,
            'ticket' => $this->ticket
        ]);
    }
}
