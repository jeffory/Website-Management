<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use Auth;

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
    public function index()
    {
        return view('ticket.index', [
            'tickets' => Ticket::all()
            ]);
    }

    /**
     * Show ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
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
        return view('ticket.create');
    }

    /**
     * Store a new ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ticket = new Ticket();

        $ticket->title = $request->input('title');
        $ticket->user_id = Auth::user()->id;
        $ticket->save();

        return redirect('/tickets');
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
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect('/tickets');
    }
}
