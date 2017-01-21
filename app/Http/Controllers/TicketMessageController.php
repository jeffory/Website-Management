<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketMessage;
use Auth;

use App\Events\TicketMessageCreation;

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

        $ticketMessage->load(['user' => function ($q) {
            $q->select('id', 'name');
        }]);

        event(new TicketMessageCreation($ticketMessage));

        if ($request->wantsJson()) {
            return $ticketMessage;
        }

        return redirect()->back();
    }

    /**
     * Update an existing ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Ticket $ticket, TicketMessage $ticketmessage, Request $request)
    {
        
    }

    /**
     * Delete a ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket, TicketMessage $ticketmessage, Request $request)
    {
        $ticketmessage->delete();

        if (! $request->wantsJson()) {
            return redirect()->back();
        }
    }
}
