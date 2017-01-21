<?php

namespace App\Listeners;

use App\Events\TicketMessageCreation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyNewTicketMessage
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
     * @param  TicketMessageCreation  $event
     * @return void
     */
    public function handle(TicketMessageCreation $event)
    {
        //
    }
}
