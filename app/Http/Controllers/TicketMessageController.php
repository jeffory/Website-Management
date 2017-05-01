<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketMessage;
use App\TicketFile;
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
        // $this->authorize('update', Ticket::class);

        $ticketMessage = new TicketMessage();

        if ($request->input('status_change') !== null) {
            $ticket->status = intval($request->input('status_change'));
            $ticket->save();

            $ticketMessage->status_change = intval($request->input('status_change'));
        }

        $ticketMessage->ticket_id = $ticket->id;
        $ticketMessage->user_id = Auth::user()->id;
        $ticketMessage->message = $request->input('message');
        $ticketMessage->save();

        foreach ($request->input('ticket_files') as $index => $file) {
            $TicketFile = TicketFile::where('token', $file['token'])->first();

            $TicketFile->ticket_id = $ticket->id;
            $TicketFile->ticket_message_id = $ticketMessage->id;

            $TicketFile->save();
        }

        // Set the Ticket updated_at time to now.
        $ticket->touch();

        $ticketMessage->load([
            'user' => function ($q) {
                $q->select('id', 'name');
            },
            'file'
        ]);

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
