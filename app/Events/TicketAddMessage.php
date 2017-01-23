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

class TicketAddMessage implements ShouldBroadcast
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
        // If it's sent as an object and not an Array, it won't send user object.
        $this->ticketMessage = $ticketMessage->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('ticket.'. $this->ticketMessage['ticket_id']);
    }
}
