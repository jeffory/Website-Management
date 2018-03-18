<?php

namespace App\Http\Controllers;

use App\Events\TicketCreated;
use App\Facades\Flash;
use App\Ticket;
use App\TicketFile;
use Auth;
use Illuminate\Http\Request;

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
        $this->authorize('index', Ticket::class);

        $user = Auth::user();

        $sort_status = $request->input('status', 0);

        // Other than an asterisk, we only want to allow integers
        if ($sort_status === '*') {
            $sort_status = null;
        } else {
            $sort_status = intval($sort_status) or 0;
        }

        $tickets = $user->myTickets($sort_status)
            ->paginate(15);

        foreach ($tickets as &$ticket) {
            $ticket['user_name'] = $ticket['user']['name'];
            $ticket['_link'] = route('tickets.show', $ticket->id);
            unset($ticket['user']);
        }

        return view('ticket.index', [
            'tickets' => $tickets,
            'user' => $user,
            'ticket_status_sort' => $sort_status
        ]);
    }

    /**
     * Show ticket.
     *
     * @return \Illuminate\Http\Response|String
     */
    public function show(Ticket $ticket, Request $request)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['messages', 'messages.user' => function ($q) {
            $q->select('id', 'name');
        }, 'messages.file' => function ($q) {
            $q->select('id', 'name', 'path', 'url', 'user_id', 'ticket_message_id', 'file_size', 'created_at');
        }]);

        if ($request->wantsJson()) {
            return ['ticket' => $ticket];
        }

        foreach ($ticket->messages as &$message) {
            $message->message = clean($message->message);
        }

        return view('ticket.show', [
            'ticket' => $ticket,
            'user' => Auth::user()
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
     * @return \Illuminate\Http\Response|\App\Ticket
     */
    public function store(Request $request)
    {
        if ($request->has('ticket_user_id') && !empty($request->input('ticket_user_id'))) {
            $this->authorize('createForUser', Ticket::class);
            $user_id = $request->input('ticket_user_id');
        }

        $this->authorize('create', Ticket::class);

        $ticket = new Ticket();

        $ticket->title = $request->input('title');
        $ticket->user_id = isset($user_id) ? $user_id : Auth::user()->id;

        $ticket->message = clean($request->input('message'));
        $ticket->save();

        if ($request->has('ticket_file')) {
            foreach ($request->input('ticket_file') as $index => $token) {
                $ticket_file = TicketFile::where('token', $token)->first();

                $ticket_file->ticket_id = $ticket->id;
                $ticket_file->ticket_message_id = $ticket->messages->first()->id;

                $ticket_file->save();
            }
        }

        event(new TicketCreated($ticket));

        if ($request->wantsJson()) {
            return $ticket->fresh()->load('files', 'messages');
        }

        return redirect()->route('tickets.show', ['id' => $ticket->id]);
    }

    /**
     * Update an existing ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Ticket $ticket, Request $request)
    {
        $this->authorize('update', $ticket);

        if ($request->has('title')) {
            $ticket->title = $request->input('title');
        }

        if ($request->has('status')) {
            $ticket->status = $request->input('status');
            $ticket->save();

            return redirect()->route('tickets.show', $ticket->id);
        }

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
        $this->authorize('delete', $ticket);

        if ($ticket->delete()) {
            Flash::set('Ticket deleted', 'success');
        }

        if (!$request->wantsJson()) {
            return redirect()->route('tickets.index');
        }
    }
}
