<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use App\TicketMessage;

class TicketMessageCreation implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $ticketMessage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TicketMessage $ticketMessage)
    {
        $this->ticketMessage = $ticketMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ticket.'. $this->ticketMessage->ticket_id);
    }
}
