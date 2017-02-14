<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketMessage;
use Auth;
use App\Events\TicketCreated;

class TicketController extends Controller
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
     * Show ticket index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!isset($request->trashed)) {
            if (Auth::user()->isStaff()) {
                $tickets = Ticket::paginate(15);
            } else {
                $tickets = Ticket::where('user_id', Auth::user()->id)
                    ->get();
            }
        } else {
            // Show trashed tickets too
            if (Auth::user()->isStaff()) {
                $tickets = Ticket::withTrashed()
                    ->get();
            } else {
                $tickets = Ticket::withTrashed()
                    ->where('user_id', Auth::user()->id)
                    ->get();
            }
        }
        
        $user = Auth::user();

        return view('ticket.index', [
            'tickets' => $tickets,
            'user' => $user
        ]);
    }

    /**
     * Show ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket, Request $request)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['messages', 'messages.user' => function ($q) {
            $q->select('id', 'name');
        }]);
        
        if ($request->wantsJson()) {
            return [ 'ticket' => $ticket ];
        }

        return view('ticket.show', [
            'ticket' => $ticket
            ]);
    }

    /**
     * Show ticket creation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Ticket::class);

        return view('ticket.create');
    }

    /**
     * Store a new ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);

        $ticket = new Ticket();
        $ticketMessage = new TicketMessage();

        $ticket->title = $request->input('title');
        $ticket->user_id = Auth::user()->id;
        $ticket->save();

        $ticketMessage->message = $request->input('message');
        $ticketMessage->user_id = $ticket->user_id;
        $ticketMessage->ticket_id = $ticket->id;
        $ticketMessage->save();

        event(new TicketCreated($ticket));

        if (! $request->wantsJson()) {
            return redirect('tickets/' . $ticket->id);
        } else {
            return compact($ticket);
        }
    }

    /**
     * Update an existing ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Ticket $ticket, Request $request)
    {
        $ticket->title = $request->input('title');
        $ticket->save();

        return redirect()->back();
    }

    /**
     * Delete a ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket, Request $request)
    {
        $ticket->delete();

        if (! $request->wantsJson()) {
            return redirect('/tickets');
        }
    }
}
