<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticket_count = Auth::user()->myTicketCount();

        return view('home', [
            'ticket_count' => $ticket_count,
            'user' => Auth::user()
        ]);
    }
}
