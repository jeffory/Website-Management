<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketMessage;
use Auth;

class TicketMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new ticket Message.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Ticket $ticket, Request $request)
    {
        // TODO: Check user has permission to ticket thread.

        $ticketMessage = new TicketMessage();

        $ticketMessage->ticket_id = $ticket->id;
        $ticketMessage->user_id = Auth::user()->id;
        $ticketMessage->message = $request->input('message');
        $ticketMessage->save();

        // Set the Ticket updated_at time to now.
        $ticket->touch();

        $ticketMessage->load(['user' => function ($q) {
            $q->select('id', 'name');
        }]);

        broadcast(new \App\Events\TicketAddMessage($ticketMessage))->toOthers();

        if ($request->wantsJson()) {
            return $ticketMessage;
        }

        return redirect()->back();
    }

    /**
     * Update an existing ticket message.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Ticket $ticket, TicketMessage $ticketmessage, Request $request)
    {
        
    }

    /**
     * Delete a ticket message.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket, TicketMessage $ticketmessage, Request $request)
    {
        broadcast(new \App\Events\TicketDeleteMessage($ticketmessage))->toOthers();
        $ticketmessage->delete();

        if (! $request->wantsJson()) {
            return redirect()->back();
        }
    }
}
