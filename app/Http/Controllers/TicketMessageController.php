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
    public function destroy(Ticket $ticket, TicketMessage $ticketmessage)
    {
        $ticketmessage->delete();
        return redirect()->back();
    }
}
