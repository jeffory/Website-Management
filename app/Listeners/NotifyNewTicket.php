<?php

namespace App\Listeners;

use App\Events\TicketCreation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyNewTicket
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
     * @param  TicketCreation  $event
     * @return void
     */
    public function handle(TicketCreation $event)
    {
        //
    }
}
